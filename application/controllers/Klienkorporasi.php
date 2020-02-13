<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Klienkorporasi extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('General_model','Klien_korporasi_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/korporasi/view_klien_korporasi');
  }

  function ajax_list(){
    $list = $this->Klien_korporasi_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->nama_klien;
      $row[] = $r->alamat; //$r->tgl_pinjam_display ;
      $row[] = $r->kota;
      $row[] = $r->no_telpon ;
      $row[] = $r->username;
      $row[] = "=";

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Klien_korporasi_model->count_all(),
      "recordsFiltered" => $this->Klien_korporasi_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

}
