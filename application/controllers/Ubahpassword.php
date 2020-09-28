<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubahpassword extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Departemen_model','Karyawan_model','User_model', 'Username_model',
    'Shift_karyawan_model', 'Kode_shift_model', 'Toleransitelat_model', 'Time_dim_model', 'Jam_kerja_model',
    'Log_presensi_model', 'Cuti_model', 'Data_izin_model', 'Jenis_izin_model', 'Departemen_model', 'Log_presensi_model', 'Logo_model'));
    $this->load->helper(array('app_helper'));

    if($this->session->userdata('id_hak_akses') != '3'){
        redirect(base_url());
    }
  }

  function index(){
    //   print_r($this->session->userdata());
    //   die();
    // $data['karyawan'] = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])
    $this->template->load('Template', 'ubahpassword/view_ubah_password');
  }
  
  public function hash_string($string)
  {
      $hashed_string = password_hash($string, PASSWORD_BCRYPT);
      return $hashed_string;
  }

  public function hash_verify($plain_text, $hashed_string)
  {
      $hashed_string = password_verify($plain_text, $hashed_string);
      return $hashed_string;
  }
  
  function update_password(){
    $object = array();

      if($this->input->post('input_password', true) !== ""){
        $hashed_string = $this->hash_string($this->input->post('input_password', true));
        $object['password'] = $hashed_string;
        
          $where = array('id_karyawan' => $this->session->userdata('id_karyawan') );
          $affected_row = $this->Username_model->update($object, $where);
          $result = array('status' => true, 'message' => "Update berhasil", 'username' => null);
          
          $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-thumbs-up"></i></b> Update password sukses!
          </div>');
          
      } else {
          
          $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-thumbs-up"></i></b> Update password Gagal!
          </div>');
          
      }
    redirect(base_url("user"));  
  }
 

}
