<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sukubunga_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  public function get_data(){
    // $this->db->where('is_del', 0);
    return $this->db->get('suku_bunga_n');
  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update('suku_bunga_n', $object);
    return $this->db->affected_rows();
  }

}
