<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kantor extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Kantor_model', 'Indonesia_model', 'Jeniskantor_model'));
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

  function index(){
    $this->template->load('Template', 'kantor/view_kantor');
  }

  public function ajax_list(){
    $list = $this->Kantor_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $nama_provinsi = $this->Indonesia_model->get_nama_provinsi($r->id_provinsi)->row_array();
      $nama_kota     = $this->Indonesia_model->get_nama_kabupaten($r->id_kota)->row_array();

      $row = array();
      $row[] = $no;
      $row[] = $r->nama_kantor;
      $row[] = $r->alamat_kantor;
      $row[] = $nama_kota['name'];
      $row[] = $nama_provinsi['name'];
      $row[] = $r->no_telp_1 . " / " . $r->no_telp_2;
      $row[] = $r->keterangan;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <a role="button" title="Edit" class="btn btn-sm btn-secondary text-warning" href="'.base_url('kantor/edit/'.$r->id).'"><b class="fa fa-edit"></b></a>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Kantor_model->count_all(),
      "recordsFiltered" => $this->Kantor_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $data['jenis_kantor'] = $this->Jeniskantor_model->get_data();
    $data['kabupaten'] = $this->Indonesia_model->get_data_kabupaten();
    $data['provinsi']  = $this->Indonesia_model->get_data_provinsi();
    $this->template->load('Template', 'kantor/add_kantor', $data);
  }

  public function validation(){
      $this->form_validation->set_rules('input_nama_kantor', 'Nama Kantor', 'required', array('required' => 'Nama Kantor Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_alamat', 'Alamat', 'required', array('required' => 'Alamat Tidak Boleh Kosong'));
      $this->form_validation->set_rules('input_no_telp_1', 'No. Telp 1', 'required', array('required' => 'No. Telp 1 Tidak Boleh Kosong'));

      $this->form_validation->set_rules('input_jenis_kantor', 'Jenis Kantor', 'callback_validasi_pilih');
      $this->form_validation->set_rules('input_kota', 'Kota/Kabupaten', 'callback_validasi_pilih');
      $this->form_validation->set_rules('input_provinsi', 'Provinsi', 'callback_validasi_pilih');

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_nama_kantor_error_detail'      => form_error('input_nama_kantor', '<b class="fa fa-warning"></b> ', ' '),
              'input_alamat_error_detail'           => form_error('input_alamat', '<b class="fa fa-warning"></b> ', ' '),
              'input_no_telp_1_error_detail'        => form_error('input_no_telp_1', '<b class="fa fa-warning"></b> ', ' '),
              'input_jenis_kantor_error_icon'       => form_error('input_jenis_kantor', '', ''),
              'input_kota_error_icon'               => form_error('input_kota', '', ''),
              'input_provinsi_error_icon'           => form_error('input_provinsi', '', '')
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
      'id'              => id(),
      'id_jenis_kantor' => $this->input->post('input_jenis_kantor', true),
      'nama_kantor'     => $this->input->post('input_nama_kantor', true),
      'alamat_kantor'   => $this->input->post('input_alamat', true),
      'id_kota'         => $this->input->post('input_kota', true),
      'id_provinsi'     => $this->input->post('input_provinsi', true),
      'no_telp_1'       => $this->input->post('input_no_telp_1', true),
      'no_telp_2'       => $this->input->post('input_no_telp_2', true),
      'keterangan'      => $this->input->post('input_keterangan', true),
      'input_by'        => $this->session->userdata('username'),
      'input_datetime'  => date('Y-m-d H:i:s'),
      'is_del'          => 0,
      'client_id'       => $this->session->userdata('client_id')
    );
    $inserted = $this->Kantor_model->save($object);
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
      redirect('kantor');
    } else {
      $data['record']       = $this->Kantor_model->get_by(array('id'=>$id))->row_array();
      $data['jenis_kantor'] = $this->Jeniskantor_model->get_data();
      $data['kabupaten']    = $this->Indonesia_model->get_data_kabupaten();
      $data['provinsi']     = $this->Indonesia_model->get_data_provinsi();
      $this->template->load('Template', 'kantor/edit_kantor', $data);
    }
  }

  public function update(){
    $object = array(
      'id_jenis_kantor' => $this->input->post('input_jenis_kantor', true),
      'nama_kantor'     => $this->input->post('input_nama_kantor', true),
      'alamat_kantor'   => $this->input->post('input_alamat', true),
      'id_kota'         => $this->input->post('input_kota', true),
      'id_provinsi'     => $this->input->post('input_provinsi', true),
      'no_telp_1'       => $this->input->post('input_no_telp_1', true),
      'no_telp_2'       => $this->input->post('input_no_telp_2', true),
      'keterangan'      => $this->input->post('input_keterangan', true),
    );
    $inserted = $this->Kantor_model->update($object, array('id' => $this->input->post('id', true) ));
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
      $affected_row = $this->Kantor_model->update($object, $where);
      $result = array('status' => TRUE, "message" => "Hapus Data Berhasil", "detail_data" => null, "error" => "Delete");
      echo json_encode($result);
  }

}
