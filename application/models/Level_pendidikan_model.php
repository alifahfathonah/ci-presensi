<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_pendidikan_model extends CI_Model{

  var $table = 'tbl_level_pendidikan';
  var $view = 'tbl_level_pendidikan';

  public function __construct()
  {
    parent::__construct();
  }

  public function get_data(){
    return $this->db->get($this->view);
  }

  public function get_by($parameter){
    $this->db->where($where);
    $this->db->where($parameter);
    return $this->db->get($this->view);
  }

}
