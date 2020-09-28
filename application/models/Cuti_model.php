<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti_model extends CI_Model{

  var $table = 'tbl_hak_cuti';
  var $view = 'tbl_hak_cuti';

  public function __construct(){
    parent::__construct();
  }

  public function query($sql){
    return $this->db->query($sql);
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

}
