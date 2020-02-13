<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    if($this->session->userdata('group_id') == 4){
      $this->template->load('Template_admin_korporasi', 'back/view_welcome');
    } else {
      $this->template->load('Template', 'back/view_welcome');
    }

  }

}
