<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_data_belumabsen(){
    $today = date("Y-m-d");
    $sql = "select a.id FROM ( select id from tbl_karyawan ) AS a left JOIN (SELECT id_karyawan FROM tbl_log_presensi where tanggal='".$today."') AS b ON a.id = b.id_karyawan where b.id_karyawan is NULL";
    return $this->db->query($sql);
  }

}
