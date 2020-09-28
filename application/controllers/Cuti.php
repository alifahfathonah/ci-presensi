<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));
    $this->load->model(array('cuti_model'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }

  }

  function index(){
    $data['cuti'] = $this->cuti_model->get_data()->row_array();
    $this->template->load('Template', 'cuti/view_cuti', $data);
  }

  function insert(){
    $object = [
      'hak_cuti'        => $this->input->post('input_hak_cuti', true),
      'input_by'        => $this->session->userdata('username'),
      'input_datetime'  => date('Y-m-d H:i:s'),
      'is_del'          => 0,
      'client_id'       => $this->session->userdata('client_id')
    ];

    $existing = $this->cuti_model->get_data()->row_array();
    if(!empty($existing)){
      $where = ['id' =>$existing['id']];
      $update = $this->cuti_model->update($object, $where);
      $result = ['status' => true];
    } else {
      $inserted = $this->cuti_model->save($object);
      $result = ['status' => true];
    }
    echo json_encode($result);
  }

}
