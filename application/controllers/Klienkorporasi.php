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

}
