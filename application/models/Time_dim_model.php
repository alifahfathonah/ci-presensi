<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_dim_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_day_month($month="", $year=""){
    $this->db->where('month', $month);
    $this->db->where('year', $year);
    return $this->db->get('time_dimension');
  }

  function get_current_day_month(){
    // $this->db->where("day_name != 'Sunday' or  ");
    $this->db->where('month', Date('m'));
    $this->db->where('year', Date('Y'));
    return $this->db->get('time_dimension');
  }

  function get_date($start, $end){
    $where = " db_date >= '".$start."' and db_date <= '".$end."'";
    $this->db->where($where);
    return $this->db->get('time_dimension');
  }

  

}
