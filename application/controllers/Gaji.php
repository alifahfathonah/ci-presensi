<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Departemen_model'));
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
    $this->template->load('Template', 'gaji/view_gaji');
  }

}
