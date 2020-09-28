<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keluarga_karyawan extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Keluarga_karyawan_model', 'Indonesia_model'));
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
      $this->form_validation->set_rules('input_nama_lengkap_keluarga' , 'Nama Lengkap' , 'required', array('required' => 'Nama Lengkap Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_tanggal_lahir_keluarga' , 'Tanggal Lahir' , 'required', array('required' => 'Tanggal Lahir Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_no_telp_keluarga' , 'No. Telp' , 'required', array('required' => 'No. Telp Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_pekerjaan_keluarga' , 'Pekerjaan' , 'required', array('required' => 'Pekerjaan Tidak Boleh Kosong'));

      $this->form_validation->set_rules('input_tempat_lahir_keluarga' , 'Tempat Lahir' , 'callback_validasi_pilih');
      $this->form_validation->set_rules('input_hubungan_keluarga' , 'Status Kawin' , 'callback_validasi_pilih');

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_nama_lengkap_keluarga_error_detail'  => form_error('input_nama_lengkap_keluarga', '<b class="fa fa-warning"></b> ', ' '),
              'input_tanggal_lahir_keluarga_error_detail' => form_error('input_tanggal_lahir_keluarga', '<b class="fa fa-warning"></b> ', ' '),
              'input_pekerjaan_keluarga_error_detail'     => form_error('input_pekerjaan_keluarga', '<b class="fa fa-warning"></b> ', ' '),
              'input_no_telp_keluarga_error_detail'       => form_error('input_no_telp_keluarga', '<b class="fa fa-warning"></b> ', ' '),

              'input_hubungan_keluarga_error_icon'        => form_error('input_hubungan_keluarga', '', ''),
              'input_tempat_lahir_keluarga_error_icon'    => form_error('input_tempat_lahir_keluarga', '', '')

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

  function load_data($id_karyawan){
    $parameter = array('id_karyawan' => $id_karyawan);
    $data = $this->Keluarga_karyawan_model->get_by($parameter)->result();
    $object = array(
      1 => 'Bapak',
      2 => 'Ibu',
      3 => 'Suami',
      4 => 'Istri',
      5 => 'Anak',
      6 => 'Saudara/i'
    );
    if(!empty($data)){
      $no = 1;
      echo "<tr>";
      foreach ($data as $r) {

        $tempat_lahir = $this->Indonesia_model->get_nama_kabupaten($r->tempat_lahir)->row_array();

        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$r->nama_lengkap."</td>";
        echo "<td>".$object[$r->id_hubungan_keluarga]."</td>";
        echo "<td>".$tempat_lahir['name'].", ".formatTglIndo($r->tanggal_lahir)."</td>";
        echo "<td>".$r->pekerjaan."</td>";
        echo "<td>".$r->no_telp."</td>";
        echo "<td>".'<div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-warning btn-link" onclick="modal_edit_keluarga('.$r->id.')"><b class="fa fa-edit"></b></button>
                      <button type="button" class="btn btn-sm btn-danger btn-link" onclick="hapus_data_keluarga('.$r->id.')"><b class="fa fa-trash"></b></button>
                    </div>
          '."</td>";
        echo "</tr>";
        $no++;
      }
      echo "</tr>";
    } else {
      echo '<tr>
        <td colspan="8" class="text-center"><i>Oups! Belum ada data</i></td>
      </tr>';
    }
  }

  public function insert(){
    $originalDate = $this->input->post('input_tanggal_lahir_keluarga', true);
    $tgl_lahir = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'id_karyawan'           => $this->input->post('id_karyawan', true),
      'nama_lengkap'          => $this->input->post('input_nama_lengkap_keluarga', true),
      'tanggal_lahir'         => $tgl_lahir,
      'pekerjaan'             => $this->input->post('input_pekerjaan_keluarga', true),
      'no_telp'               => $this->input->post('input_no_telp_keluarga', true),
      'id_hubungan_keluarga'  => $this->input->post('input_hubungan_keluarga', true),
      'tempat_lahir'          => $this->input->post('input_tempat_lahir_keluarga', true),
      'input_by'              => $this->session->userdata('username'),
      'input_datetime'        => date('Y-m-d H:i:s'),
      'is_del'                => 0,
      'client_id'             => $this->session->userdata('client_id')
    );

    $inserted = $this->Keluarga_karyawan_model->save($object);
    if($inserted){
      $result = array('status' => true, "id" => $inserted);
    } else {
      $result = array('status' => false, "id" => $inserted);
    }
    echo json_encode($result);
  }

  public function detail($id=""){
    if($id == ""){
      redirect('karyawan');
    } else {
      $data = $this->Keluarga_karyawan_model->get_by(array('id'=>$id))->row_array();

      $originalDate = $data['tanggal_lahir'];
      $data['tanggal_lahir'] = date("d-m-Y", strtotime($originalDate));

      echo json_encode($data);
    }
  }

  public function update(){
    $originalDate = $this->input->post('input_tanggal_lahir_keluarga', true);
    $tgl_lahir = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'id_karyawan'           => $this->input->post('id_karyawan', true),
      'nama_lengkap'          => $this->input->post('input_nama_lengkap_keluarga', true),
      'tanggal_lahir'         => $tgl_lahir,
      'pekerjaan'             => $this->input->post('input_pekerjaan_keluarga', true),
      'no_telp'               => $this->input->post('input_no_telp_keluarga', true),
      'id_hubungan_keluarga'  => $this->input->post('input_hubungan_keluarga', true),
      'tempat_lahir'          => $this->input->post('input_tempat_lahir_keluarga', true),
      'input_by'              => $this->session->userdata('username')
    );

    $where = array('id' => $this->input->post('id', true) );

    $affected_row = $this->Keluarga_karyawan_model->update($object, $where);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

  public function delete($id){
    $affected_row = $this->Keluarga_karyawan_model->delete($id);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

}
