<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Username extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array( 'Indonesia_model', 'Username_model', 'Karyawan_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }

  }

  function index(){
    redirect('karyawan');
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
    $object = array(
      'id_hak_akses' => $this->input->post('input_hak_akses')
    );

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

        $where = array('id' => $this->input->post('id_username', true) );
        $affected_row = $this->Username_model->update($object, $where);
        $result = array('status' => true, 'message' => "Update berhasil", 'username' => $object['username']);
        echo json_encode($result);
      }
    } else {
      if($this->input->post('input_password', true) !== ""){
        $hashed_string = $this->hash_string($this->input->post('input_password', true));
        $object['password'] = $hashed_string;
      }

      $where = array('id' => $this->input->post('id_username', true) );
      $affected_row = $this->Username_model->update($object, $where);
      $result = array('status' => true, 'message' => "Update berhasil", 'username' => null);
      echo json_encode($result);
    }
  }

  function reset_password(){
    $data = $this->Karyawan_model->get_by(array('id' => $this->input->post('input_id_karyawan', true)))->row_array();
    $hashed_string = $this->hash_string($data['tanggal_lahir']);

    $object = array(
      'username'     => $data['nip'],
      'password'     => $hashed_string,
    );

    $where = array('id' => $this->input->post('id_username', true) );
    $affected_row = $this->Username_model->update($object, $where);
    $result = array('status' => true, 'message' => "Reset password berhasil", 'username' => $object['username']);
    echo json_encode($result);

  }

}
