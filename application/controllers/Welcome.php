<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Klien_korporasi_model', 'Welcome_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    if($this->session->userdata('id_korporasi') !== null){
      $data['detail_korporasi'] = $this->Klien_korporasi_model->get_by('id', $this->session->userdata('id_korporasi'));
      $this->template->load('Template_admin_korporasi', 'back/view_welcome_korporasi', $data);
    } else {
      $data['anggota_all']    = $this->Welcome_model->get_anggota_all();
      $data['anggota_aktif']  = $this->Welcome_model->get_anggota_aktif();
      $data['anggota_non']    = $this->Welcome_model->get_anggota_non();

      $data['jml_simpanan']   = $this->Welcome_model->get_jml_simpanan();
      $data['jml_penarikan']  = $this->Welcome_model->get_jml_penarikan();

      $data['jml_pinjaman']   = $this->Welcome_model->get_jml_pinjaman();
      $data['jml_angsuran']   = $this->Welcome_model->get_jml_angsuran();
      $data['jml_denda']      = $this->Welcome_model->get_jml_denda();
      $data['peminjam']       = $this->Welcome_model->get_peminjam_bln_ini();

      $data['peminjam_aktif'] = $this->Welcome_model->get_peminjam_aktif();
      $data['peminjam_lunas'] = $this->Welcome_model->get_peminjam_lunas();
      $data['peminjam_belum'] = $this->Welcome_model->get_peminjam_belum();

      $data['kas_debet']      = $this->Welcome_model->get_jml_debet();
      $data['kas_kredit']     = $this->Welcome_model->get_jml_kredit();

      $data['user_aktif']     = $this->Welcome_model->get_user_aktif();
      $data['user_non']       = $this->Welcome_model->get_user_non();

      $this->template->load('Template', 'back/view_welcome', $data);
    }

  }

}
