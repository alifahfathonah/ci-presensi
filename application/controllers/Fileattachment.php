<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fileattachment extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Berkas_karyawan_model'));
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
    redirect('karyawan');
  }

  public function upload_ktp(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Berkas_karyawan_model->_uploadImage("ktp_" . id());
      if ($file_name['status']) {
          $object = array(
              'id_karyawan'       => $this->input->post('id_karyawan_file', true),
              'file_path'         => $file_name['original_image'],
              'id_jenis_berkas'   => $this->input->post('is_attachment', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
          );

          $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 2); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();

          if(empty($existing_data)){ //jika tidak ada maka insert
            $inserted_id = $this->Berkas_karyawan_model->save($object);
          } else { //jika ada maka update
            unlink('uploads/attachment/'.$existing_data['file_path']); //hapus existing file
            $where = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 2);
            $inserted_id = $this->Berkas_karyawan_model->update($object, $where);
          }

          if ($inserted_id != null) {
              $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 2); //cek apakah existing data ktp sudah ada
              $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();
              $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['file_path'], 'id_container' => "img_ktp",
                        'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_ktp" . '\')"><i class="material-icons">clear</i></button>');
              echo json_encode($result);
          } else {
              $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
              echo json_encode($result);
          }

      } else {
          $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
          echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  public function upload_kk(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Berkas_karyawan_model->_uploadImage("kk_" . id());
      if ($file_name['status']) {
          $object = array(
              'id_karyawan'       => $this->input->post('id_karyawan_file', true),
              'file_path'         => $file_name['original_image'],
              'id_jenis_berkas'   => $this->input->post('is_attachment', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
          );

          $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 3); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();

          if(empty($existing_data)){ //jika tidak ada maka insert
            $inserted_id = $this->Berkas_karyawan_model->save($object);
          } else { //jika ada maka update
            unlink('uploads/attachment/'.$existing_data['file_path']); //hapus existing file
            $where = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 3);
            $inserted_id = $this->Berkas_karyawan_model->update($object, $where);
          }

          if ($inserted_id != null) {
            $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 3); //cek apakah existing data ktp sudah ada
            $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();
            $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['file_path'], 'id_container' => "img_kk",
              'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_kk" . '\')"><i class="material-icons">clear</i></button>');
              echo json_encode($result);
          } else {
              $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
              echo json_encode($result);
          }

      } else {
          $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
          echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  public function upload_ijazah(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Berkas_karyawan_model->_uploadImage("ijazah_" . id());
      if ($file_name['status']) {
          $object = array(
              'id_karyawan'       => $this->input->post('id_karyawan_file', true),
              'file_path'         => $file_name['original_image'],
              'id_jenis_berkas'   => $this->input->post('is_attachment', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
          );

          $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 4); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();

          if(empty($existing_data)){ //jika tidak ada maka insert
            $inserted_id = $this->Berkas_karyawan_model->save($object);
          } else { //jika ada maka update
            unlink('uploads/attachment/'.$existing_data['file_path']); //hapus existing file
            $where = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 4);
            $inserted_id = $this->Berkas_karyawan_model->update($object, $where);
          }

          if ($inserted_id != null) {
              $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 4); //cek apakah existing data ktp sudah ada
              $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();
              $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['file_path'], 'id_container' => "img_ijazah",
              'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_jazah" . '\')"><i class="material-icons">clear</i></button>');
              echo json_encode($result);
          } else {
              $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
              echo json_encode($result);
          }

      } else {
          $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
          echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  public function upload_transkrip(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Berkas_karyawan_model->_uploadImage("transkrip_" . id());
      if ($file_name['status']) {
          $object = array(
              'id_karyawan'       => $this->input->post('id_karyawan_file', true),
              'file_path'         => $file_name['original_image'],
              'id_jenis_berkas'   => $this->input->post('is_attachment', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
          );

          $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 5); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();

          if(empty($existing_data)){ //jika tidak ada maka insert
            $inserted_id = $this->Berkas_karyawan_model->save($object);
          } else { //jika ada maka update
            unlink('uploads/attachment/'.$existing_data['file_path']); //hapus existing file
            $where = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 5);
            $inserted_id = $this->Berkas_karyawan_model->update($object, $where);
          }

          if ($inserted_id != null) {
              $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 5); //cek apakah existing data ktp sudah ada
              $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();
              $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['file_path'], 'id_container' => "img_transkrip",
              'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_transkrip" . '\')"><i class="material-icons">clear</i></button>');
              echo json_encode($result);
          } else {
              $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
              echo json_encode($result);
          }

      } else {
          $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
          echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  public function upload_cv(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Berkas_karyawan_model->_uploadImage("cv_" . id());
      if ($file_name['status']) {
          $object = array(
              'id_karyawan'       => $this->input->post('id_karyawan_file', true),
              'file_path'         => $file_name['original_image'],
              'id_jenis_berkas'   => $this->input->post('is_attachment', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
          );

          $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 6); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();

          if(empty($existing_data)){ //jika tidak ada maka insert
            $inserted_id = $this->Berkas_karyawan_model->save($object);
          } else { //jika ada maka update
            unlink('uploads/attachment/'.$existing_data['file_path']); //hapus existing file
            $where = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 6);
            $inserted_id = $this->Berkas_karyawan_model->update($object, $where);
          }

          if ($inserted_id != null) {
              $parameter = array('id_karyawan' => $this->input->post('id_karyawan_file', true), 'id_jenis_berkas' => 6); //cek apakah existing data ktp sudah ada
              $existing_data = $this->Berkas_karyawan_model->get_by($parameter)->row_array();
              $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['file_path'], 'id_container' => "img_cv",
              'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_cv" . '\')"><i class="material-icons">clear</i></button>');
              echo json_encode($result);
          } else {
              $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
              echo json_encode($result);
          }

      } else {
          $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
          echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  function delete($file_name, $img_container){
    $affected_row = $this->Berkas_karyawan_model->delete_by_filename($file_name);
    if($affected_row > 0){
      unlink('uploads/attachment/'.$file_name); //hapus existing file
      $result = array('status' => TRUE, "message" => "", 'id_container' => $img_container);
      echo json_encode($result);
    } else {
      $result = array('status' => TRUE, "message" => $this->db->error(), 'id_container' => $img_container);
      echo json_encode($result);
    }
  }

}
