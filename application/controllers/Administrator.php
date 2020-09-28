<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));
    $this->load->model(array('Username_model'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }
  }

  function index(){
    $userdata = $this->session->userdata();
    // print_r($userdata);
    $data['username'] = $userdata['username'];
    $data['id']       = $userdata['id'];
    $data['client_id']= $userdata['client_id'];
    // die();
    $this->template->load('Template', 'admin/view_admin', $data);
  }

  public function hash_string($string)
  {
      $hashed_string = password_hash($string, PASSWORD_BCRYPT);
      return $hashed_string;
  }

  public function hash_verify($plain_text, $hashed_string)
  {
      $hashed_string = password_verify($plain_text, $hashed_string);
      return $hashed_string;
  }

  function update_password(){
    $object = array();

    $username_old = $this->input->post('input_username_old', true);
    $username_new = $this->input->post('input_username', true);

    if( $username_new !== $username_old ){ //jika ada perubahan username
      //cek existing username new
      $parameter = array('username' => $username_new);
      $data = $this->Username_model->get_by($parameter)->row_array();

      if(!empty($data)){ //jika ada username yang sama
        $result = array('status' => false, 'message' => "Username ".$this->input->post('input_username', true)." sudah digunakan. " );
        echo json_encode($result);
      } else {

        $object['username'] = $username_new;

        if($this->input->post('input_password', true) !== ""){
          $hashed_string = $this->hash_string($this->input->post('input_password', true));
          $object['password'] = $hashed_string;
        }

        $where = array('id' => $this->input->post('id', true) );
        $affected_row = $this->Username_model->update($object, $where);
        $result = array('status' => true, 'message' => "Update berhasil 1", 'username' => $object['username']);
        echo json_encode($result);
      }
    } else {
      if($this->input->post('input_password', true) !== ""){
        $hashed_string = $this->hash_string($this->input->post('input_password', true));
        $object['password'] = $hashed_string;
      }

      $where = array('id' => $this->input->post('id', true) );
      $affected_row = $this->Username_model->update($object, $where);
      $result = array('status' => true, 'message' => "Update berhasil 2", 'username' => null);
      echo json_encode($result);
    }
  }

}
