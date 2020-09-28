<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logo extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Logo_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }
  }

  function tes(){
    $existing_logo = $this->Logo_model->get_data($this->session->userdata('client_id'))->result();
    echo "<pre>";
    print_r($existing_logo);
    echo "<br>";
    echo sizeof($existing_logo);
    echo "<br>";
    echo count($existing_logo);
  }

  public function upload_photo(){

      $file_name = $this->Logo_model->_uploadImage(id()); //PROSES UPLOAD
      $existing_logo = $this->Logo_model->get_data($this->session->userdata('client_id'))->row_array();

      if ($file_name['status']) {
          $object = array(
              'img_attachment'    => $file_name['original_image']
          );

          if(empty($existing_logo)){ //JIKA BELUM ADA LOGO SAMA SEKALI
            $object = array(
                        'image_path'      => $file_name['original_image'],
                        'input_by'        => $this->session->userdata('username'),
                        'input_datetime'  => date('Y-m-d H:i:s'),
                        'is_del'          => 0,
                        'client_id'       => $this->session->userdata('client_id')
                      );

            $affected_row = $this->Logo_model->insert($object);
            if($affected_row){ echo 1; } else { echo 0; }

          } else { //JIKA SUDAH ADA LOGO SEBELUMNY DAN AKAN MENGGANTI LOGO

            unlink("uploads/logo/".$existing_logo['image_path']); //HAPUS LOGO

            $where = array('id' => $existing_logo['id'] );
            $object = array(
                        'image_path'      => $file_name['original_image'],
                        'input_by'        => $this->session->userdata('username')
                      );
            $affected_row = $this->Logo_model->update($object, $where);
            if($affected_row){ echo 1; } else { echo 0; }
          }
      } else {
          echo 2;
      }
  }

}
