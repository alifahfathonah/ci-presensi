<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pendidikan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Riwayat_pendidikan_model'));
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

  public function validation(){
      $this->form_validation->set_rules('input_sekolah' , 'Sekolah / Universitas' , 'required', array('required' => 'Sekolah / Universitas Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_kota_pendidikan' , 'Kota Pendidkan' , 'required', array('required' => 'Kota Pendidkan Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_gelar_pendidikan' , 'Gelar' , 'required', array('required' => 'Gelar Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_fakultas' , 'Fakultas' , 'required', array('required' => 'Fakultas Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_jurusan' , 'Jurusan' , 'required', array('required' => 'Jurusan Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_tahun_masuk' , 'Tahun Masuk' , 'required', array('required' => 'Tahun Masuk Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_tahun_lulus' , 'Tahun Lulus' , 'required', array('required' => 'Tahun Lulus Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_nilai' , 'Nilai / IPK' , 'required', array('required' => 'Nilai / IPK Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_tingkat_pendidikan' , 'Tingkat Pendidikan' , 'callback_validasi_pilih');

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_sekolah_error_detail'            => form_error('input_sekolah', '<b class="fa fa-warning"></b> ', ' '),
              'input_kota_pendidikan_error_detail'    => form_error('input_kota_pendidikan', '<b class="fa fa-warning"></b> ', ' '),
              'input_gelar_pendidikan_error_detail'   => form_error('input_gelar_pendidikan', '<b class="fa fa-warning"></b> ', ' '),
              'input_fakultas_error_detail'           => form_error('input_fakultas', '<b class="fa fa-warning"></b> ', ' '),
              'input_jurusan_error_detail'            => form_error('input_jurusan', '<b class="fa fa-warning"></b> ', ' '),
              'input_tahun_masuk_error_detail'        => form_error('input_tahun_masuk', '<b class="fa fa-warning"></b> ', ' '),
              'input_tahun_lulus_error_detail'        => form_error('input_tahun_lulus', '<b class="fa fa-warning"></b> ', ' '),
              'input_nilai_error_detail'              => form_error('input_nilai', '<b class="fa fa-warning"></b> ', ' '),
              'input_tingkat_pendidikan_error_icon'   => form_error('input_tingkat_pendidikan', '', '')

          );
      }
      echo json_encode($array);
  }

  public function validasi_pilih($str){
      if ($str == 'x') {
          $this->form_validation->set_message('validasi_pilih', 'Silahkan Pilih Salah Satu');
          return FALSE;
      } else {
          return TRUE;
      }
  }

  public function insert(){
    $object = array(
      'id_karyawan'           => $this->input->post('id_karyawan_pendidikan', true),
      'id_level_pendidikan'   => $this->input->post('input_tingkat_pendidikan', true),
      'asal_sekolah_univ'     => $this->input->post('input_sekolah', true),
      'gelar'                 => $this->input->post('input_gelar_pendidikan', true),
      'fakultas'              => $this->input->post('input_fakultas', true),
      'jurusan'               => $this->input->post('input_jurusan', true),
      'tahun_masuk'           => $this->input->post('input_tahun_masuk', true),
      'tahun_lulus'           => $this->input->post('input_tahun_lulus', true),
      'kota'                  => $this->input->post('input_kota_pendidikan', true),
      'ipk_nilai'             => $this->input->post('input_nilai', true),
      'input_by'              => $this->session->userdata('username'),
      'input_datetime'        => date('Y-m-d H:i:s'),
      'is_del'                => 0,
      'client_id'             => $this->session->userdata('client_id')
    );

    $inserted = $this->Riwayat_pendidikan_model->save($object);
    if($inserted){
      $result = array('status' => true, "id" => $inserted);
    } else {
      $result = array('status' => false, "id" => $inserted);
    }
    echo json_encode($result);
  }

  public function update(){
    $object = array(
      'id_level_pendidikan'   => $this->input->post('input_tingkat_pendidikan', true),
      'asal_sekolah_univ'     => $this->input->post('input_sekolah', true),
      'gelar'                 => $this->input->post('input_gelar_pendidikan', true),
      'fakultas'              => $this->input->post('input_fakultas', true),
      'jurusan'               => $this->input->post('input_jurusan', true),
      'tahun_masuk'           => $this->input->post('input_tahun_masuk', true),
      'tahun_lulus'           => $this->input->post('input_tahun_lulus', true),
      'kota'                  => $this->input->post('input_kota_pendidikan', true),
      'ipk_nilai'             => $this->input->post('input_nilai', true)
    );

    $where = array('id_karyawan' => $this->input->post('id_karyawan_pendidikan', true));

    $affected_row = $this->Riwayat_pendidikan_model->update($object, $where);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

  public function detail($id_karyawan=""){
    if($id_karyawan == ""){
      redirect('karyawan');
    } else {
      $data = $this->Riwayat_pendidikan_model->get_by(array('id_karyawan'=>$id_karyawan))->row_array();
      echo json_encode($data);
    }
  }

}
