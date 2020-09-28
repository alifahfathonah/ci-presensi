<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jeniskantor_model extends CI_Model{

  var $table = 'tbl_jenis_kantor';
  var $view = 'tbl_jenis_kantor';
  var $column_order = array('id', 'nama_jenis_kantor', 'input_by', 'input_datetime', 'is_del', 'client_id');
  var $column_search = array('id', 'nama_jenis_kantor', 'input_by', 'input_datetime', 'is_del', 'client_id');
  var $order = array('nama_jenis_kantor', 'asc');

  public function __construct(){
    parent::__construct();
  }

  public function query($sql){
    return $this->db->query($sql);
  }

  private function _get_datatables_query(){
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

}
