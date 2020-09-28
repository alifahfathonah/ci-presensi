<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Kode_shift_model'));
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
    $this->template->load('Template', 'shift/view_shift');
  }

  public function ajax_list(){
    $list = $this->Kode_shift_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $row = array();
      $row[] = $no;
      $row[] = $r->kode;
      $row[] = $r->nama_shift;
      $row[] = ($r->jenis_shift == 1) ? "Masuk" : "Libur" ;
      $row[] = ($r->jenis_shift == 1) ? $r->jam_masuk : "-" ;
      $row[] = ($r->jenis_shift == 1) ? $r->jam_pulang : "-" ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <a role="button" title="Edit" class="btn btn-sm btn-secondary text-warning" onclick="detail('.$r->id.')"><b class="fa fa-edit"></b></a>
                  <a role="button" title="Edit" class="btn btn-sm btn-secondary text-danger" onclick="hapus_data('.$r->id.')" ><b class="fa fa-trash"></b></a>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Kode_shift_model->count_all(),
      "recordsFiltered" => $this->Kode_shift_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation(){
      $this->form_validation->set_rules('input_kode_shift', 'Kode Shift', 'required', array('required' => 'Kode Shift Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_nama_shift', 'Nama Shift', 'required', array('required' => 'Nama Shift Tidak Boleh Kosong'));

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_kode_shift_error_detail'  => form_error('input_kode_shift', '<b class="fa fa-warning"></b> ', ' '),
              'input_nama_shift_error_detail'  => form_error('input_nama_shift', '<b class="fa fa-warning"></b> ', ' '),
          );
      }
      echo json_encode($array);
  }

  public function validasi_existing($str){
    $parameter = array('kode' => $str);
    $existing = $this->Kode_shift_model->get_by($parameter)->row_array();
    if (!empty($existing)) {
      $this->form_validation->set_message('validasi_existing', 'Kode Shift <b>'.$str.'</b> sudah ada');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function insert(){
    $object = array(
      'kode'            => $this->input->post('input_kode_shift', true),
      'nama_shift'      => $this->input->post('input_nama_shift', true),
      'jenis_shift'     => $this->input->post('input_jenis_shift', true),
      'jam_masuk'       => $this->input->post('input_jam_masuk', true),
      'jam_pulang'      => $this->input->post('input_jam_pulang', true),
      'input_by'        => $this->session->userdata('username'),
      'input_datetime'  => date('Y-m-d H:i:s'),
      'is_del'          => 0,
      'client_id'       => $this->session->userdata('client_id')
    );
    $inserted = $this->Kode_shift_model->save($object);
    if($inserted){
      $result = array('status' => true);
      echo json_encode($result);
    } else {
      $result = array('status' => false);
      echo json_encode($result);
    }
  }

  function detail($id=""){
    $data       = $this->Kode_shift_model->get_by(array('id'=>$id))->row_array();
    echo json_encode($data);
  }

  public function update(){
    $object = array(
      'kode'            => $this->input->post('input_kode_shift', true),
      'nama_shift'      => $this->input->post('input_nama_shift', true),
      'jenis_shift'     => $this->input->post('input_jenis_shift', true),
      'jam_masuk'       => $this->input->post('input_jam_masuk', true),
      'jam_pulang'      => $this->input->post('input_jam_pulang', true)
    );
    $where = array('id' => $this->input->post('id', true));
    $inserted = $this->Kode_shift_model->update($object, $where);
    $result = array('status' => true);
    echo json_encode($result);
  }

  public function delete($id){
    $object = array(
      'is_del'            => 1
    );
    $where = array('id' => $id);
    $inserted = $this->Kode_shift_model->update($object, $where);
    $result = array('status' => true);
    echo json_encode($result);
  }
  //
  // public function update(){
  //   $object = array(
  //     'nama_jabatan' => $this->input->post('input_nama_jabatan', true),
  //     'keterangan'      => $this->input->post('input_keterangan', true),
  //   );
  //   $inserted = $this->Kode_shift_model->update($object, array('id' => $this->input->post('id', true) ));
  //   if($inserted){
  //     $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
  //       <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
  //     </div>');
  //   } else {
  //     $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
  //       <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
  //     </div>');
  //   }
  //   $result = array('status' => true);
  //   echo json_encode($result);
  // }
  //
  // public function delete($id){
  //     $object = array(
  //         'is_del'         => 1
  //     );
  //
  //     $where = array('id' => $id);
  //     $affected_row = $this->Kode_shift_model->update($object, $where);
  //     $result = array('status' => TRUE, "message" => "Hapus Data Berhasil", "detail_data" => null, "error" => "Delete");
  //     echo json_encode($result);
  // }


}
