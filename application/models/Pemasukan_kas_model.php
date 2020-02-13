<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasukan_kas_model extends CI_Model{

  var $table = 'view_transaksi_pemasukan_kas';
  var $column_order = array('id', 'kd_trans', 'tgl_catat', 'tgl_catat_asli', 'tgl_catat_search', 'keterangan', 'jns_trans', 'dari_akun', 'untuk_kas_id', 'untuk_kas', 'jumlah', 'user_name');
  var $column_search = array('id', 'kd_trans', 'tgl_catat', 'tgl_catat_asli', 'tgl_catat_search', 'keterangan', 'jns_trans', 'dari_akun', 'untuk_kas_id', 'untuk_kas', 'jumlah', 'user_name');
  var $order = array('tgl_catat_asli' => 'desc');

  public function __construct(){
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  private function _get_datatables_query(){
      if($this->input->post('start_date') and $this->input->post('end_date')){
       $start_date = str_replace("+", " ", $_POST["start_date"]);
       $end_date   = str_replace("+", " ", $_POST["end_date"]);

       $this->db->where('DATE(tgl_catat_asli) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
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

  function get_all_data(){
    $data = $this->db->get($this->table);
    return $data;
  }

  function get_all_data_by_time($period1, $period2){
    $this->db->where('tgl_catat_asli >=', $period1);
    $this->db->where('tgl_catat_asli <=', $period2);
    $this->db->order_by('tgl_catat_asli', 'asc');
    $data = $this->db->get($this->table);
    return $data;
  }

  public function insert($object){
    $this->db->insert('tbl_trans_kas', $object);
    return $this->db->insert_id();
  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update('tbl_trans_kas', $object);
    return $this->db->affected_rows();
  }

  public function get_by($field, $parameter){
    $this->db->where($field, $parameter);
    return $this->db->get($this->table)->row_array();
  }

  public function get_all_by($parameter){
    // $parameter = "23 nov";
    $this->db->from($this->table);
    $i = 0;
    foreach ($this->column_search as $item){
      if($parameter){
        if($i===0){
          $this->db->group_start();
          $this->db->like($item, $this->db->escape_like_str($parameter));
        } else {
          $this->db->or_like($item, $this->db->escape_like_str($parameter));
        }

        if(count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }
    // $query = $this->db->get_compiled_select();
    $query = $this->db->get();
    return $query;
  }

  public function get_all_by_date($start_date, $end_date){
    $this->db->where('DATE(tgl_catat_asli) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
    $this->db->from($this->table);
    $query = $this->db->get();
    return $query;
  }

  public function get_data(){
    return $this->db->get($this->table);
  }

  public function delete_id($id){
    $this->db->where('id', $id);
    $this->db->delete($this->table);
    return $this->db->affected_rows();
  }

}
