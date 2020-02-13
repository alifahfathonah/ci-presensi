<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisangsuran extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Jenis_angsuran_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/jenis_angsuran/view_jenis_angsuran');
  }

  public function ajax_list(){
    $list = $this->Jenis_angsuran_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {
      $row = array();
      $row[] = $r->ket ;
      $row[] = $r->aktif ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-danger" onclick="delete_data(\''.$r->id.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jenis_angsuran_model->count_all(),
      "recordsFiltered" => $this->Jenis_angsuran_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_angsuran', 'Lama Angsuran', 'required',
    array('required' => 'Lama Angsuran Tidak Boleh Kosong') );

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_angsuran_error' => form_error('input_angsuran', '<b class="fa fa-warning"></b> ', ' ')
      );
    }
    echo json_encode($array);
  }

  function save(){
    $object = array(
      'ket'     => $this->input->post('input_angsuran',true),
      'aktif'   => $this->input->post('input_aktif',true)
    );

    $inserted_id = $this->Jenis_angsuran_model->insert($object);
    $result = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id
    );
    echo json_encode($result);
  }


  function ajax_detail($id){
    $data = $this->Jenis_angsuran_model->get_by('id', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'ket'     => $this->input->post('input_angsuran',true),
      'aktif'   => $this->input->post('input_aktif',true)
    );
    $where = array('id' => $this->input->post('id',true));
    $affected_row = $this->Jenis_angsuran_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function export_to_excel(){
    $data = $this->Jenis_angsuran_model->get_data()->result();
    $title = 'Data Jenis Angsuran KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1">
      <thead>
        <tr>
          <th >Lama Angsuran (Bulan) </th>
          <th >Aktif </th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->ket.'</td>';
              echo '    <td>'.$r->aktif.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

    public function ajax_delete($id){
      $affected_row = $this->Jenis_angsuran_model->delete_id($id);
      echo json_encode(array("status" => TRUE));
    }

}
