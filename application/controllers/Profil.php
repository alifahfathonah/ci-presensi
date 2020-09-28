<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Profil_model', 'Logo_model'));
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
      $data['profil'] = $this->Profil_model->get_data()->row_array();
      $data['logo']   = $this->Logo_model->get_data($this->session->userdata('client_id'))->row_array();
      $this->template->load('Template', 'profil/view_profil', $data);
  }

  function update_profil(){
    $existing_profil_data = $this->Profil_model->get_data()->row_array();
    if(empty($existing_profil_data)){ //JIKA BELUM ADA PROFIL DATA
      //INSERT NEW PROFIL DATA
      $object = array(
                  'nama_perusahaan'   => $this->input->post('input_nama_perusahaan', true),
                  'alamat_perusahaan' => $this->input->post('input_alamat_perusahaan', true),
                  'deksripsi'       => $this->input->post('input_deskripsi_perusahaan', true),
                  'logo'            => '-',
                  'input_by'        => $this->session->userdata('username'),
                  'input_datetime'  => date('Y-m-d H:i:s'),
                  'is_del'          => 0,
                  'client_id'       => $this->session->userdata('client_id')
                );
      $inserted_id = $this->Profil_model->save($object);
      if($inserted_id > 0){
        $result = array('status' => true , 'message' => "Insert profil berhasil");
      } else {
        $result = array('status' => false , 'message' => "Insert profil gagal");
      }
      echo json_encode($result);
    } else {
      //INSERT NEW PROFIL DATA
      $object = array(
                  'nama_perusahaan'   => $this->input->post('input_nama_perusahaan', true),
                  'alamat_perusahaan' => $this->input->post('input_alamat_perusahaan', true),
                  'deksripsi'       => $this->input->post('input_deskripsi_perusahaan', true),
                  'logo'            => '-',
                  'input_by'        => $this->session->userdata('username'),
                  'input_datetime'  => date('Y-m-d H:i:s'),
                  'is_del'          => 0,
                  'client_id'       => $this->session->userdata('client_id')
                );
      $where = array('id' => $existing_profil_data['id'] );
      $affected_row = $this->Profil_model->update($object, $where);
      if($affected_row > 0){
        $result = array('status' => true , 'message' => "Update profil berhasil");
      } else {
        $result = array('status' => false , 'message' => "Update profil gagal");
      }
      echo json_encode($result);
    }

  }

}
