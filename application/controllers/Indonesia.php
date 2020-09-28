<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indonesia extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array( 'Indonesia_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    // if($this->session->userdata('status') !== 'loggedin'){
    //     redirect(base_url("login"));
    // }

    // if($this->session->userdata('id_hak_akses') == '3'){
    //     redirect(base_url("user"));
    // }

  }

  function index(){
    redirect(base_url());
  }

  function ajax_kota($id){
    if($id != "X"){
      $data = $this->Indonesia_model->get_data_kabupaten($id)->result();
      echo "<option value='x' selected>-- Silahkan Pilih Kota / Kabupaten --</option>";
      foreach ($data as $r) {
        echo '<option value="'.$r->id.'">'.$r->name.'</option>';
      }
    } else {
      echo "";
    }
  }

}
