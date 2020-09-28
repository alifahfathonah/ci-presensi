<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_izin_model extends CI_Model{

  var $table = 'tbl_data_izin';
  var $view = 'tbl_data_izin';
  var $column_order = array('id', 'id_karyawan', 'id_departemen', 'id_jenis_izin', 'tanggal_awal', 'tanggal_akhir', 'keterangan', 'status_approval', 'input_by', 'input_datetime', 'is_del', 'client_id');
  var $column_search = array('id', 'id_karyawan', 'id_departemen', 'id_jenis_izin', 'tanggal_awal', 'tanggal_akhir', 'keterangan', 'status_approval', 'input_by', 'input_datetime', 'is_del', 'client_id');
  var $order = array('id', 'desc');

  public function __construct(){
    parent::__construct();
  }

  public function query($sql){
    return $this->db->query($sql);
  }

  private function _get_datatables_query(){

    if($this->input->post('start_date') and $this->input->post('end_date')){
      $originalDate = $this->input->post('start_date');
      $tgl_awal = date("Y-m-d", strtotime($originalDate));

      $originalDate = $this->input->post('end_date');
      $tgl_akhir = date("Y-m-d", strtotime($originalDate));

      $this->db->where('tanggal_awal >= "'.$tgl_awal.'" and tanggal_akhir <= "'.$tgl_akhir);
    }

    // if($this->input->post('departemen')){
    //   $this->db->where('tanggal_awal >= "'.$tgl_awal.'" and tanggal_akhir <= "'.$tgl_akhir);
    // }

    $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
    $this->db->where($where);
    $this->db->from($this->view);
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
    $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
    $this->db->where($where);
    $this->db->from($this->view);
    return $this->db->count_all_results();
  }

  public function get_data(){
    $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
    $this->db->where($where);
    return $this->db->get($this->view);
  }

  public function save($object){
    $this->db->insert($this->table, $object);
    return $this->db->insert_id();
  }

  public function detail($id){
    $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
    $this->db->where($where);
    $this->db->where('id', $id);
    return $this->db->get($this->table);
  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update($this->table, $object);
    return $this->db->affected_rows();
  }

  public function delete($id){
    $this->db->where('id', $id);
    $this->db->delete($this->table);
    return $this->db->affected_rows();
  }

  public function get_by($parameter){
    $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
    $this->db->where($where);
    $this->db->where($parameter);
    return $this->db->get($this->view);
  }

  public function _uploadImage($file_name){
    if( empty($_FILES["imgInp"]["name"]) ) {
      $result = array(
        'status'		   => true,
        'message'		    => "Tidak ada gambar yg di upload",
        'original_image'=> null,
      );
      return $result;
    } else {
      $config['upload_path']   = './uploads/attachment/';
      $config['allowed_types'] = 'jpg|png|jpeg';
      $config['file_name']     = $file_name;
      $config['overwrite']     = 'true';
      $config['max_size']      = '1024';

      $this->load->library('upload', $config);

      if ($this->upload->do_upload('imgInp')) {
        $result = array(
          'status'		  => true,
          'message'		  => "Upload Berhasil",
          'original_image'  => $this->upload->data("file_name"),
        );
        return $result;
      } else {
        $error = $this->upload->display_errors('', '');
        $result = array(
          'status'		  => false,
          'message'		  => $error,
          'original_image'  => null
        );
        return $result;
      }
    }
  }
}
