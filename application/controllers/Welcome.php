<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller{

  public function __construct(){
    parent::__construct();
    // if ($this->session->userdata('status') !== 'loggedin') {
    //   redirect(base_url("login"));
    // }
    $this->load->model(array());
    $this->load->helper(array('url', 'language', 'app_helper'));
  }

  function index(){
    echo "test";  
  }




}
