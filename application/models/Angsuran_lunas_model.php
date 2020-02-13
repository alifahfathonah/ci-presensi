<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_lunas_model extends CI_Model{

  var $table = 'view_t_pinjaman_d';
  var $column_order = array('id', 'kode', 'tgl_bayar_asli', 'tgl_bayar', 'tgl_bayar_search', 'pinjam_id', 'angsuran_ke', 'jumlah_bayar', 'denda_rp', 'terlambat', 'ket_bayar', 'dk', 'kas_id', 'jns_trans', 'update_data',
  'user_name', 'keterangan', 'kd_post', 'sisa_pelunasan');
  var $column_search = array('id', 'kode', 'tgl_bayar_asli', 'tgl_bayar', 'tgl_bayar_search', 'pinjam_id', 'angsuran_ke', 'jumlah_bayar', 'denda_rp', 'terlambat', 'ket_bayar', 'dk', 'kas_id', 'jns_trans', 'update_data',
  'user_name', 'keterangan', 'kd_post', 'sisa_pelunasan');
  var $order = array('tgl_bayar_asli' => 'desc');

  public function __construct()
  {
    parent::__construct();
  }

  private function _get_datatables_query(){
    if($this->input->post('start_date') and $this->input->post('end_date')){
      $start_date = str_replace("+", " ", $_POST["start_date"]);
      $end_date   = str_replace("+", " ", $_POST["end_date"]);

      $this->db->where('DATE(tgl_bayar_asli) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
    }

    $this->db->where('pinjam_id  = "'.$_POST["id"].'"');
    // $this->db->where('pinjam_id  = "75"');

    if( $this->input->post('pelunasan') === null){
      $this->db->where("(ket_bayar = 'Pelunasan' OR ket_bayar = 'Bayar Denda')");
    } else {
      $this->db->where("(ket_bayar = 'Angsuran')");
    }

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

  public function insert(){
    $total_tagihan = str_replace(',', '', $this->input->post('input_sisa_tagihan')) * 1;
    $jumlah_bayar = str_replace(',', '', $this->input->post('input_jumlah_bayar')) * 1;
    $denda= str_replace(',', '', $this->input->post('denda_val'))*1;
    $jml_tagihan = $total_tagihan + $denda;

    $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_trans'));
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);

    $object = array(
      'tgl_bayar'		=>	$tgl_trans,
      'pinjam_id'		=>	$this->input->post('input_nomor_pinjaman_id'),
      'angsuran_ke'	=>	$this->General_model->get_record_bayar($this->input->post('input_nomor_pinjaman_id')) + 1,
      'jumlah_bayar'=>	$jumlah_bayar,
      'ket_bayar'		=>	'Pelunasan',
      'kas_id'	    =>	$this->input->post('simpan_ke_kas'),
      'jns_trans'		=>	'48',
      'keterangan'	=>	$this->input->post('input_keterangan'),
      'user_name'		=>  $this->session->userdata('username'),
      'is_del'      =>  0
    );

    $this->db->trans_start();
    $this->db->insert('tbl_pinjaman_d', $object);

    if($jumlah_bayar >= $jml_tagihan) {
      $status = 'Lunas';
    } else {
      $status = 'Belum';
    }

    $object = array('lunas' => $status);
    $this->db->where('id', $this->input->post('input_nomor_pinjaman_id'));
    $this->db->update('tbl_pinjaman_h', $object);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_complete();
      return TRUE;
    }
  }

  public function delete($id, $master_id, $reason) {
    // $this->db->delete('tbl_pinjaman_d', array('id' => $id));
    $object = array(
      'del_datetime' => date('Y-m-d H:i:s'),
      'del_by'       => $this->session->userdata('username'),
      'del_reason'   => $_POST['param'][2],
      'is_del'       => 1,
    );
    $this->db->where('id', $id);
    $this->db->update('tbl_pinjaman_d', $object);
    if($this->auto_status_lunas($master_id)) {
      return TRUE;
    }
  }

  function auto_status_lunas($master_id) {
    $pinjam = $this->general_m->get_data_pinjam($master_id);
    $tagihan = $pinjam->lama_angsuran * $pinjam->ags_per_bulan;
    $denda = $this->general_m->get_semua_denda_by_pinjaman($master_id);
    $total_tagihan = $tagihan + $denda;
    if($total_tagihan <= 0) {
      $status = 'Lunas';
    } else {
      $status = 'Belum';
    }
      $data = array('lunas' => $status);
      $this->db->where('id', $master_id);
      $this->db->update('tbl_pinjaman_h', $data);
      return TRUE;
  }

}
