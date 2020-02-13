<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelunasan_model extends CI_Model{

  var $table = 'v_hitung_pinjaman';
  var $column_order = array('kode', 'id', 'tgl_pinjam', 'tgl_pinjam_display', 'tgl_pinjam_search', 'nama', 'lama_angsuran', 'jumlah', 'bunga', 'biaya_adm', 'lunas', 'dk', 'kas_id', 'user_name', 'pokok_angsuran', 'bunga_pinjaman', 'ags_per_bulan', 'tempo', 'tagihan', 'keterangan', 'barang_id', 'bln_sudah_angsur');
  var $column_search = array('kode', 'id', 'tgl_pinjam', 'tgl_pinjam_display', 'tgl_pinjam_search', 'nama', 'lama_angsuran', 'jumlah', 'bunga', 'biaya_adm', 'lunas', 'dk',
  'kas_id', 'user_name', 'pokok_angsuran', 'bunga_pinjaman', 'ags_per_bulan', 'tempo', 'tagihan', 'keterangan', 'barang_id', 'bln_sudah_angsur');
  var $order = array('tgl_pinjam' => 'desc');

  public function __construct(){
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  private function _get_datatables_query(){

    if($this->input->post('filter_status_pinjam')){
      if($_POST["filter_status_pinjam"] !== 'x'){
        $status_pinjam = $_POST["filter_status_pinjam"];
        $this->db->where('lunas = "'.$status_pinjam.'"');
      }
    }

    if($this->input->post('start_date') and $this->input->post('end_date')){
      $start_date = str_replace("+", " ", $_POST["start_date"]);
      $end_date   = str_replace("+", " ", $_POST["end_date"]);

      $this->db->where('DATE(tgl_pinjam) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
    }

    $this->db->where('lunas = "Lunas"');

    $this->db->from($this->table);
    $i = 0;

    foreach ($this->column_search as $item){
      if($_POST['search']['value']){
        if($i===0){
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if(isset($_POST['order'])){
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if(isset($this->order)){
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }

  }

  function get_datatables(){
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered(){
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all(){
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

}
