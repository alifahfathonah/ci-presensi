<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_trans_kas_model extends CI_Model{

    var $table = 'view_transaksi_v2';
    var $column_order = array('id', 'tgl', 'transaksi', 'jns_trans', 'dari_kas', 'untuk_kas',  'debet', 'kredit', 'ket');
    var $column_search = array('id', 'tbl', 'tgl', 'debet', 'kredit', 'dari_kas', 'untuk_kas', 'transaksi', 'jns_trans', 'ket');
    var $order = array('tgl' => 'asc');

    public function __construct(){
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        if ($this->input->post('start_date') and $this->input->post('end_date')) {
            $start_date = str_replace("+", " ", $_POST["start_date"]);
            $end_date   = str_replace("+", " ", $_POST["end_date"]);

            $this->db->where('DATE(tgl) BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
        } else {
            $this->db->where('DATE(tgl) like "%'.Date('Y').'%"');
        }

        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_cetak($start_date, $end_date){
        $this->db->where('DATE(tgl) BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
        $this->db->order_by('tgl', 'asc');
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    //panggil data simpanan
    function get_data_simpanan($limit, $start)
    {
        if (isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
            $tgl_samp = date('Y') . '-12-31';
        }
        $this->db->select('*');
        $this->db->from('view_transaksi_v2');
        $this->db->where('DATE(tgl) >= ', '' . $tgl_dari . '');
        $this->db->where('DATE(tgl) <= ', '' . $tgl_samp . '');
        $this->db->order_by('tgl', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $out = $query->result();
            return $out;
        } else {
            return array();
        }
    }


    function get_jml_data_kas()
    {
        if (isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
            $tgl_samp = date('Y') . '-12-31';
        }
        $this->db->where('DATE(tgl) >= ', '' . $tgl_dari . '');
        $this->db->where('DATE(tgl) <= ', '' . $tgl_samp . '');
        return $this->db->count_all_results('view_transaksi_v2');
    }

    function get_saldo_sblm()
    {
        // SALDO SEBELUM NYA
        if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $tgl_dari = $_POST['start_date'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
        }
        $this->db->select('SUM(debet) AS jum_debet, SUM(kredit) AS jum_kredit');
        $this->db->from('view_transaksi_v2');

        $this->db->where('DATE(tgl) < ', '' . $tgl_dari . '');
        $query_sblm = $this->db->get();
        $saldo_sblm = 0;
        if ($query_sblm->num_rows() > 0) {
            $row_sblm = $query_sblm->row();
            $saldo_sblm = ($row_sblm->jum_debet - $row_sblm->jum_kredit);
        }
        return $saldo_sblm;
    }

    function get_saldo_awal($limit, $start)
    {
        $this->db->select('debet, kredit');
        $this->db->from('view_transaksi_v2');

        if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $tgl_dari = $_POST['start_date'];
            $tgl_samp = $_POST['end_date'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
            $tgl_samp = date('Y') . '-12-31';
        }
        $this->db->where('DATE(tgl) >= ', '' . $tgl_dari . '');
        $this->db->where('DATE(tgl) <= ', '' . $tgl_samp . '');

        $this->db->order_by('tgl', 'ASC');
        $this->db->limit($start, 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res = $query->result();
            $saldo = 0;
            foreach ($res as $row) {
                $saldo += ($row->debet - $row->kredit);
            }
            return $saldo;
        } else {
            return 0;
        }
    }

    //panggil nama kas
    function get_nama_kas_id($id)
    {
        $this->db->select('*');
        $this->db->from('nama_kas_tbl');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $out = $query->row();
            return $out;
        } else {
            $out = (object) array('nama' => '');
            return $out;
        }
    }

    //panggil transaksi kas  untuk laporan
    function lap_trans_kas()
    {

        if (isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
            $tgl_samp = date('Y') . '-12-31';
        }

        $this->db->select('*');
        $this->db->from('v_transaksi');
        $this->db->where('DATE(tgl) >= ', '' . $tgl_dari . '');
        $this->db->where('DATE(tgl) <= ', '' . $tgl_samp . '');
        $this->db->order_by('tgl', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $out = $query->result();
            return $out;
        } else {
            return array();
        }
    }

    function get_nama_akun_id($id)
    {
        $this->db->select('*');
        $this->db->from('jns_akun');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $out = $query->row();
            return $out;
        } else {
            $out = (object) array('nama' => '');
            return $out;
        }
    }

}