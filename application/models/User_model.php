<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    var $table = 'view_user_login';
    var $column_order = array('id', 'name', 'username', 'password', 'user_role_name');
    var $column_search = array('id', 'name', 'username', 'password', 'user_role_name');
    var $order = array('name' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_role()
    {
        return $this->db->get('user_role');
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from('user');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update('user', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }

    public function check_username($username)
    {
        $query = $this->db->query("select COUNT(*) as num from user where username = '$username'");
        $result = $query->row();
        if (isset($result)) return $result->num;
        return 0;
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
        $config['upload_path']   = './uploads/foto_presensi/';
    		$config['allowed_types'] = 'jpg|png|jpeg';
    		$config['file_name']     = $file_name;
    		$config['overwrite']     = 'true';
    // 		$config['max_size']      = '512';

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
