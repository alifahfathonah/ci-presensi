<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Profile_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $data['record'] = $this->Profile_model->get_data()->row_array();
    if($data['record'] == null){
      $data['record']['url'] = base_url()."profile/save";
    } else {
      $data['record']['url'] = base_url()."profile/update";
    }
    $this->template->load('Template', 'back/view_profile', $data);
  }

  public function update(){
    $object = array(
      'nama_lembaga'  => $this->input->post('input_nama_koperasi', true),
      'nama_ketua'    => $this->input->post('input_nama_pimpinan', true),
      'hp_ketua'      => $this->input->post('input_hp', true),
      'alamat'        => $this->input->post('input_alamat', true),
      'telepon'       => $this->input->post('input_telepon', true),
      'kota'          => $this->input->post('input_kotakabupaten', true),
      'email'         => $this->input->post('input_email', true),
      'web'           => $this->input->post('input_website', true)
    );
    $where = array('id' => $this->input->post('id', true));
    $affected = $this->Profile_model->update($object, $where);
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Sukses!</strong> Data profil berhasil disimpan.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>');
    $this->index();
  }


}
