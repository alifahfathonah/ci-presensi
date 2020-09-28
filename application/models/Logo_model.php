<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logo_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_data($client_id){
    $this->db->where('client_id', $client_id);
    return $this->db->get('tbl_logo');
  }

  public function insert($object){
    $this->db->insert('tbl_logo', $object);
    return $this->db->insert_id();
  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update('tbl_logo', $object);
    return $this->db->affected_rows();
  }

  public function delete($where){
    $this->db->where($where);
    $this->db->delete('tbl_logo');
  }

  public function _uploadImage($file_name){
    if( empty($_FILES["file"]["name"]) ) {
      $result = array(
        'status'		      => false,
        'message'		      => "Tidak ada gambar yg di upload"
      );
      return $result;
    } else {
      $config['upload_path']   = './uploads/logo';
  		$config['allowed_types'] = 'jpg|png|gif|jpeg';
  		$config['file_name']     = $file_name;
  		$config['overwrite']     = 'true';
  		$config['max_size']      = '1024';

  		$this->load->library('upload', $config);

  		if ($this->upload->do_upload('file')) {
  			$result = array(
  				'status'		      => true,
  				'message'		      => "Upload Berhasil",
  				'original_image'  => $this->upload->data("file_name"),
  			);
  			return $result;
  		} else {
  			$error = $this->upload->display_errors('<b class="fa fa-warning">', '</b> ');
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
