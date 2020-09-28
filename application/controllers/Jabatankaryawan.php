<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatankaryawan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Karyawan_model', 'Jabatan_karyawan_model', 'Jabatan_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper', 'download'));

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
    $this->form_validation->set_rules('input_jabatan' , 'Jabatan' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_tmt_jabatan' , 'Tanggal Lahir' , 'required', array('required' => 'TMT Jabatan Tidak Boleh Kosong'));

    if ($this->form_validation->run()) {
      $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
    } else {
      $array = array(
        'error' => true,
        'input_tmt_jabatan_error_detail'  => form_error('input_tmt_jabatan', '<b class="fa fa-warning"></b> ', ' '),
        'input_jabatan_error_icon'     => form_error('input_jabatan', '', '')
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
    $data = $this->Jabatan_karyawan_model->get_by($parameter)->result();
    if(!empty($data)){
      $no = 1;
      echo "<tr>";
      foreach ($data as $r) {

        $parameter = array('id' => $r->id_jabatan);
        $data_jabatan = $this->Jabatan_model->get_by($parameter)->row_array();

        $status = ($r->is_active == 1) ? 'Aktif' : 'Non aktif';

        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$data_jabatan['nama_jabatan']."</td>";
        echo "<td>".$r->detail_jabatan."</td>";
        echo "<td>".formatTglIndo($r->tmt)."</td>";
        echo "<td>".$status."</td>";
        echo "<td>".'<div class="btn-group  btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-warning btn-link" onclick="modal_edit_jabatan('.$r->id.')"><b class="fa fa-edit"></b></button>
                      <button type="button" class="btn btn-sm btn-danger btn-link" onclick="hapus_data_jabatan('.$r->id.')"><b class="fa fa-trash"></b></button>
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
    $originalDate = $this->input->post('input_tmt_jabatan', true);
    $tgl_tmt      = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'id_karyawan'       => $this->input->post('id_karyawan_jabatan', true),
      'id_jabatan'        => $this->input->post('input_jabatan', true),
      'detail_jabatan'    => $this->input->post('input_detail_jabatan', true),
      'tmt'               => $tgl_tmt,
      'is_active'         => $this->input->post('input_aktif', true),
      'input_by'          => $this->session->userdata('username'),
      'input_datetime'    => date('Y-m-d H:i:s'),
      'is_del'            => 0,
      'client_id'         => $this->session->userdata('client_id')
    );

    $inserted = $this->Jabatan_karyawan_model->save($object);
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
      $data = $this->Jabatan_karyawan_model->get_by(array('id'=>$id))->row_array();

      $originalDate = $data['tmt'];
      $data['tmt'] = date("d-m-Y", strtotime($originalDate));

      echo json_encode($data);
    }
  }

  public function update(){
    $originalDate = $this->input->post('input_tmt_jabatan', true);
    $tgl_tmt = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'id_karyawan'       => $this->input->post('id_karyawan_jabatan', true),
      'id_jabatan'        => $this->input->post('input_jabatan', true),
      'detail_jabatan'    => $this->input->post('input_detail_jabatan', true),
      'tmt'               => $tgl_tmt,
      'is_active'         => $this->input->post('input_aktif', true)
    );

    $where = array('id' => $this->input->post('id', true) );

    $affected_row = $this->Jabatan_karyawan_model->update($object, $where);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

  public function delete($id){
    $affected_row = $this->Jabatan_karyawan_model->delete($id);
    $result = array('status' => true, "id" => $affected_row);
    echo json_encode($result);
  }

}
