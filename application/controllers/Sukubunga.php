<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sukubunga extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Sukubunga_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $data['record'] = $this->Sukubunga_model->get_data()->row_array();
    if($data['record'] == null){
      $data['record']['url'] = base_url()."Sukubunga/save";
    } else {
      $data['record']['url'] = base_url()."Sukubunga/update";
    }
    $this->template->load('Template', 'back/view_sukubunga', $data);
  }

  public function update(){
    $object = array(
      'bg_pinjam'           => $this->input->post('input_bg_pinjam', true),
      'biaya_adm'           => $this->input->post('input_biaya_adm', true),
      'denda'               => $this->input->post('input_denda', true),
      'denda_hari'          => $this->input->post('input_denda_hari', true),
      'dana_cadangan'       => $this->input->post('input_dana_cadangan', true),
      'jasa_anggota'        => $this->input->post('input_jasa_anggota', true),
      'dana_pengurus'       => $this->input->post('input_dana_pengurus', true),
      'dana_karyawan'       => $this->input->post('input_dana_karyawan', true),
      'dana_pend'           => $this->input->post('input_dana_pend', true),
      'dana_sosial'         => $this->input->post('input_dana_sosial', true),
      'jasa_usaha'          => $this->input->post('input_jasa_usaha', true),
      'jasa_modal'          => $this->input->post('input_jasa_modal', true),
      'pjk_pph'             => $this->input->post('input_pjk_pph', true),
      'pinjaman_bunga_tipe' => $this->input->post('input_pinjaman_bunga_tipe', true)
    );
    $where = array('id' => $this->input->post('id', true));
    $affected = $this->Sukubunga_model->update($object, $where);
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Sukses!</strong> Data profil berhasil disimpan.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>');
    $this->index();
  }

}
