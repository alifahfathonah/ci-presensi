<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Klienkorporasi extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('General_model','Klien_korporasi_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/korporasi/view_klien_korporasi');
  }

  function ajax_list(){
    $list = $this->Klien_korporasi_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->nama_klien;
      $row[] = $r->alamat; //$r->tgl_pinjam_display ;
      $row[] = $r->kota;
      $row[] = $r->no_telpon;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-warning" onclick="edit_data(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
                  <button type="button" class="btn btn-outline-danger" onclick="delete_data(\''.$r->id.'\')" ><b class="fa fa-trash"></b></button>
                </div>';

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Klien_korporasi_model->count_all(),
      "recordsFiltered" => $this->Klien_korporasi_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation(){
    $this->form_validation->set_rules('input_nama_korporasi', 'Nama Korporasi', 'required', array('required' => 'Nama Korporasi Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_alamat_korporasi', 'Alamat', 'required', array('required' => 'Alamat Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_kota_korporasi', 'Kota', 'required', array('required' => 'Kota Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_telepon_korporasi', 'Nomor Telepon', 'required', array('required' => 'Nomor Telepon Tidak Boleh Kosong') );

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_nama_korporasi_error' => form_error('input_nama_korporasi', '<b class="fa fa-warning"></b> ', ' '),
        'input_alamat_korporasi_error' => form_error('input_alamat_korporasi', '<b class="fa fa-warning"></b> ', ' '),
        'input_kota_korporasi_error' => form_error('input_kota_korporasi', '<b class="fa fa-warning"></b> ', ' '),
        'input_telepon_korporasi_error' => form_error('input_telepon_korporasi', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }


  public function save(){
    $object = array(
      'nama_klien'  => $this->input->post('input_nama_korporasi', true),
      'alamat'      => $this->input->post('input_alamat_korporasi', true),
      'kota'        => $this->input->post('input_kota_korporasi', true),
      'no_telpon'   => $this->input->post('input_telepon_korporasi', true),
      'input_by'    => $this->session->userdata('username'),
      'input_datetime' => date('Y-m-d H:i:s'),
      'is_del'      => 0
    );

    $inserted_id = $this->Klien_korporasi_model->insert($object);

    if($inserted_id > 0){
      $result = array('status' => TRUE );

      $insert_group = array(
        'name'        => 'admin',
        'description' => 'Corporate Admin',
        'id_korporasi'=> $inserted_id,
      );

      $this->db->insert('groups', $insert_group);

    } else {
      $result = array('status' => FALSE );
    }
    echo json_encode($result);
  }

  public function detail($id){
    $data = $this->Klien_korporasi_model->get_by('id', $id);
    echo json_encode($data);
  }

  public function update(){
    $object = array(
      'nama_klien'  => $this->input->post('input_nama_korporasi', true),
      'alamat'      => $this->input->post('input_alamat_korporasi', true),
      'kota'        => $this->input->post('input_kota_korporasi', true),
      'no_telpon'   => $this->input->post('input_telepon_korporasi', true),
    );

    $where = array('id' => $this->input->post('id'));

    $inserted_id = $this->Klien_korporasi_model->update($object, $where);

    if($inserted_id > 0){
      $result = array('status' => TRUE );

    } else {
      $result = array('status' => FALSE );
    }
    echo json_encode($result);
  }

  public function delete($id){
    $object = array(
      'is_del'   => 1,
    );

    $where = array('id' => $id);

    $inserted_id = $this->Klien_korporasi_model->update($object, $where);

    if($inserted_id > 0){
      $result = array('status' => TRUE );

    } else {
      $result = array('status' => FALSE );
    }
    echo json_encode($result);
  }

}
