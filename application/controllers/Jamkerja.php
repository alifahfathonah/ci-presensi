<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jamkerja extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Jam_kerja_model'));
    $this->load->library(array('form_validation'));
    $this->form_validation->set_error_delimiters('', '');
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }
  }

  public function index(){
    $this->template->load('Template', 'jamkerja/view_jamkerja');
  }

  public function ajax_list(){
    $list = $this->Jam_kerja_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $row = array();
      $row[] = $r->nama_hari;
      $row[] = $r->jam_masuk;
      $row[] = $r->jam_pulang;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <button onclick="modal_edit('.$r->id.')" type="button" name="btn-edit" class="btn btn-sm btn-link text-warning" title="Edit"><b class="fa fa-edit"></b></button>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jam_kerja_model->count_all(),
      "recordsFiltered" => $this->Jam_kerja_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function bulk_insert(){
    $data = $this->Jam_kerja_model->get_data(); //cek data existing
    if(empty($data)){
      $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
      foreach ($hari as $r) {
        $object = array(
            'nama_hari'       => $r,
            'jam_masuk'       => "08:00:00",
            'jam_pulang'      => "17:00:00",
            'input_by'        => $this->session->userdata('username'),
            'input_datetime'  => date('Y-m-d H:i:s'),
            'is_del'          => 0,
            'client_id'       => $this->session->userdata('client_id')
        );
        $this->Jam_kerja_model->save($object);
      }
    } else {
      echo "Sudah ada data";
    }
  }

  function detail($id=""){
    if($id == ""){
      redirect('jamkerja');
    } else {
      $data = $this->Jam_kerja_model->get_by(array('id'=>$id))->row_array();
      echo json_encode($data);
    }
  }

  public function validation(){
      $this->form_validation->set_rules('input_jam_masuk', 'Jam Masuk', 'required', array('required' => 'Jam Masuk Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_jam_pulang', 'Jam Pulang', 'required', array('required' => 'Jam Pulang Tidak Boleh Kosong'));

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_jam_masuk_error_detail'     => form_error('input_jam_masuk', '<b class="fa fa-warning"></b> ', ' '),
              'input_jam_pulang_error_detail'    => form_error('input_jam_pulang', '<b class="fa fa-warning"></b> ', ' ')
          );
      }
      echo json_encode($array);
  }

  public function update(){
    $object = array(
      'jam_masuk'  => $this->input->post('input_jam_masuk', true),
      'jam_pulang' => $this->input->post('input_jam_pulang', true),
    );
    $inserted = $this->Jam_kerja_model->update($object, array('id' => $this->input->post('id', true) ));
    if($inserted){
      $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
      </div>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
      </div>');
    }
    $result = array('status' => true, 'message' => $this->session->flashdata('message'));
    echo json_encode($result);
  }

}
