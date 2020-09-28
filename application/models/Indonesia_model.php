<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indonesia_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_data_provinsi(){
    $this->db->order_by('name');
    return $this->db->get('provinces');
  }

  function get_data_kabupaten($province_id=""){
    $this->db->order_by('name');
    if($province_id != ""){
      $this->db->where('province_id', $province_id);
    }
    return $this->db->get('regencies');
  }

  function get_data_kecamatan($regency_id){
    $this->db->order_by('name');
    $this->db->where('regency_id', $regency_id);
    return $this->db->get('districts');
  }

  function get_nama_provinsi($province_id){
    $this->db->where('id', $province_id);
    return $this->db->get('provinces');
  }

  function get_nama_kabupaten($kabupaten_id){
    $this->db->where('id', $kabupaten_id);
    return $this->db->get('regencies');
  }

  function get_by($table, $where){
    $this->db->where($where);
    return $this->db->get($table);
  }

}
