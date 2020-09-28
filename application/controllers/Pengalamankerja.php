<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengalamankerja extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Pengalaman_kerja_model', 'Indonesia_model'));
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
      $this->form_validation->set_rules('input_nama_perusahaan' , 'Nama Perusahaan' , 'required', array('required' => 'Nama Perusahaan Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_bidang' , 'Bidang' , 'required', array('required' => 'Bidang Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_jabatan' , 'Jabatan Terakhir' , 'required', array('required' => 'Jabatan Terakhir Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_kota_kerja' , 'Kota' , 'required', array('required' => 'Kota Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_masa_kerja' , 'Masa Kerja' , 'required', array('required' => 'Masa Kerja Tidak Boleh Kosong'));

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_nama_perusahaan_error_detail'  => form_error('input_nama_perusahaan', '<b class="fa fa-warning"></b> ', ' '),
              'input_bidang_error_detail'           => form_error('input_bidang', '<b class="fa fa-warning"></b> ', ' '),
              'input_jabatan_error_detail'          => form_error('input_jabatan', '<b class="fa fa-warning"></b> ', ' '),
              'input_kota_kerja_error_detail'       => form_error('input_kota_kerja', '<b class="fa fa-warning"></b> ', ' '),
              'input_masa_kerja_error_detail'       => form_error('input_masa_kerja', '<b class="fa fa-warning"></b> ', ' ')
          );
      }
      echo json_encode($array);
  }

  function load_data($id_karyawan){
    $parameter = array('id_karyawan' => $id_karyawan);
    $data = $this->Pengalaman_kerja_model->get_by($parameter)->result();
    if(!empty($data)){
      $no = 1;
      echo "<tr>";
      foreach ($data as $r) {

        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$r->nama_perusahaan."</td>";
        echo "<td>".$r->bidang."</td>";
        echo "<td>".$r->jabatan."</td>";
        echo "<td>".$r->kota."</td>";
        echo "<td>".$r->masa_kerja."</td>";
        echo "<td>".'<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-warning btn-link" onclick="detail_kerja('.$r->id.')"><b class="fa fa-edit"></b></button>
                      <button type="button" class="btn btn-sm btn-danger btn-link" onclick="hapus_data_kerja('.$r->id.')"><b class="fa fa-trash"></b></button>
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
    $object = array(
      'id_karyawan'           => $this->input->post('id_karyawan_kerja', true),
      'nama_perusahaan'       => $this->input->post('input_nama_perusahaan', true),
      'bidang'                => $this->input->post('input_bidang', true),
      'jabatan'               => $this->input->post('input_jabatan', true),
      'kota'                  => $this->input->post('input_kota_kerja', true),
      'masa_kerja'            => $this->input->post('input_masa_kerja', true),
      'input_by'              => $this->session->userdata('username'),
      'input_datetime'        => date('Y-m-d H:i:s'),
      'is_del'                => 0,
      'client_id'             => $this->session->userdata('client_id')
    );

    $inserted = $this->Pengalaman_kerja_model->save($object);
    if($inserted){
      $result = array('status' => true, "id" => $inserted);
    } else {
      $result = array('status' => false, "id" => $inserted);
    }
    echo json_encode($result);
  }

  public function detail($id=""){
    if($id == ""){
      redirect('pengalamankerja');
    } else {
      $data = $this->Pengalaman_kerja_model->get_by(array('id'=>$id))->row_array();
      echo json_encode($data);
    }
  }

  public function update(){
    $object = array(
      'id_karyawan'           => $this->input->post('id_karyawan_kerja', true),
      'nama_perusahaan'       => $this->input->post('input_nama_perusahaan', true),
      'bidang'                => $this->input->post('input_bidang', true),
      'jabatan'               => $this->input->post('input_jabatan', true),
      'kota'                  => $this->input->post('input_kota_kerja', true),
      'masa_kerja'            => $this->input->post('input_masa_kerja', true)
    );

    $where = array('id' => $this->input->post('id_kerja', true) );

    $affected_row = $this->Pengalaman_kerja_model->update($object, $where);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

  public function delete($id){
    $affected_row = $this->Pengalaman_kerja_model->delete($id);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

}
