<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));
    $this->load->model(array('Log_presensi_model', 'Data_izin_model', 'Dashboard_model'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }

  }

  function index(){
    $data['presensi_hari_ini'] = $this->Log_presensi_model->get_by(['tanggal' => date("Y-m-d")])->result();
    $data['izin']              = $this->Data_izin_model->get_by(['tanggal_awal' => date("Y-m-d"), 'status_approval ' => '1'])->result();
    $data['belum_absen']       = $this->Dashboard_model->get_data_belumabsen()->result();
    // echo "<pre>";
    // print_r($data['presensi_hari_ini']);
    // die();
    $this->template->load('Template', 'dashboard/view_dashboard', $data);
    // print_r($this->session->userdata());
  }

  function tes(){
    echo date("Y-m-d");
  }

}
