<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Jabatan_model'));
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
    $this->template->load('Template', 'jabatan/view_jabatan');
  }

  public function ajax_list(){
    $list = $this->Jabatan_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $row = array();
      $row[] = $no;
      $row[] = $r->nama_jabatan;
      $row[] = $r->keterangan;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <a role="button" title="Edit" class="btn btn-sm btn-secondary text-warning" href="'.base_url('jabatan/edit/'.$r->id).'"><b class="fa fa-edit"></b></a>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jabatan_model->count_all(),
      "recordsFiltered" => $this->Jabatan_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $this->template->load('Template', 'jabatan/add_jabatan');
  }

  public function validation(){
      $this->form_validation->set_rules('input_nama_jabatan', 'Nama Jabatan', 'required', array('required' => 'Nama Jabatan Tidak Boleh Kosong'));

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_nama_jabatan_error_detail'      => form_error('input_nama_jabatan', '<b class="fa fa-warning"></b> ', ' ')
          );
      }
      echo json_encode($array);
  }

  public function insert(){
    $object = array(
      'id'              => id(),
      'nama_jabatan'    => $this->input->post('input_nama_jabatan', true),
      'keterangan'      => $this->input->post('input_keterangan', true),
      'input_by'        => $this->session->userdata('username'),
      'input_datetime'  => date('Y-m-d H:i:s'),
      'is_del'          => 0,
      'client_id'       => $this->session->userdata('client_id')
    );
    $inserted = $this->Jabatan_model->save($object);
    if($inserted){
      $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
      </div>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
      </div>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  function edit($id=""){
    if($id == ""){
      redirect('departemen');
    } else {
      $data['record']       = $this->Jabatan_model->get_by(array('id'=>$id))->row_array();
      $this->template->load('Template', 'jabatan/edit_jabatan', $data);
    }
  }

  public function update(){
    $object = array(
      'nama_jabatan' => $this->input->post('input_nama_jabatan', true),
      'keterangan'      => $this->input->post('input_keterangan', true),
    );
    $inserted = $this->Jabatan_model->update($object, array('id' => $this->input->post('id', true) ));
    if($inserted){
      $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
      </div>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
      </div>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  public function delete($id){
      $object = array(
          'is_del'         => 1
      );

      $where = array('id' => $id);
      $affected_row = $this->Jabatan_model->update($object, $where);
      $result = array('status' => TRUE, "message" => "Hapus Data Berhasil", "detail_data" => null, "error" => "Delete");
      echo json_encode($result);
  }


}
