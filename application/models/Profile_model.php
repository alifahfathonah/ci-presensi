<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function get_data(){
    // $this->db->where('is_del', 0);
    return $this->db->get('tbl_setting_n');
  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update('tbl_setting_n', $object);
    return $this->db->affected_rows();
  }

}
