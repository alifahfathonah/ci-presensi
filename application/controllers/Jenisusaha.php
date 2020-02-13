<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisusaha extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Jenis_usaha_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/jenis_usaha/view_jenis_usaha');
  }

  public function ajax_list(){
    $list = $this->Jenis_usaha_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $row = array();
      $row[] = $r->kd_jenisBisnis ;
      $row[] = $r->jenisBisnis ;
      $row[] = $r->keterangan ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->kd_jenisBisnis.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-danger" onclick="delete_data(\''.$r->kd_jenisBisnis.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jenis_usaha_model->count_all(),
      "recordsFiltered" => $this->Jenis_usaha_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_nama_jenis_usaha', 'Nama Jenis Usaha', 'required', array('required' => 'Nama Jenis Usaha Tidak Boleh Kosong') );

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_nama_jenis_usaha_error' => form_error('input_nama_jenis_usaha', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  function save(){
    $object = array(
      'jenisBisnis' => $this->input->post('input_nama_jenis_usaha',true),
      'keterangan' => $this->input->post('input_keterangan_jenis_usaha',true)
    );

    $id = $this->input->post('input_kode_jenis_usaha',true);
    if($id != null){
      $object['kd_jenisBisnis'] = $id;
    }

    $inserted_id = $this->Jenis_usaha_model->insert($object);
    $result = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id
    );
    echo json_encode($result);
  }

  function ajax_detail($id){
    $data = $this->Jenis_usaha_model->get_by('kd_jenisBisnis', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'jenisBisnis' => $this->input->post('input_nama_jenis_usaha',true),
      'keterangan' => $this->input->post('input_keterangan_jenis_usaha',true)
    );
    $where = array('kd_jenisBisnis' => $this->input->post('input_kode_jenis_usaha',true));
    $affected_row = $this->Jenis_usaha_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function ajax_delete($id){
    $affected_row = $this->Jenis_usaha_model->delete_id($id);
    echo json_encode(array("status" => TRUE));
  }

  public function export_to_excel(){
    $data = $this->Jenis_usaha_model->get_data()->result();
    $title = 'Data Jenis Usaha KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" >
      <thead>
        <tr>
            <th>Kode Jenis Usaha</th>
            <th>Nama Jenis Usaha</th>
            <th>Keterangan</th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->kd_jenisBisnis.'</td>';
              echo '    <td>'.$r->jenisBisnis.'</td>';
              echo '    <td>'.$r->keterangan.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

}
