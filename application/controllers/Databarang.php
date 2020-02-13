<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Databarang extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Data_barang_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/databarang/view_data_barang');
  }

  public function ajax_list(){
    $list = $this->Data_barang_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $row = array();
      $row[] = $r->nm_barang ;
      $row[] = $r->type ;
      $row[] = $r->merk;
      $row[] = $r->harga ;
      $row[] = $r->jml_brg ;
      $row[] = $r->ket ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-danger" onclick="delete_data(\''.$r->id.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Data_barang_model->count_all(),
      "recordsFiltered" => $this->Data_barang_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_nama_barang', 'Nama Barang', 'required', array('required' => 'Nama Barang Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_jml_barang', 'Jumlah Barang', 'required', array('required' => 'Jumlah Barang Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_harga', 'Harga', 'required', array('required' => 'Harga Tidak Boleh Kosong') );

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_nama_barang_error' => form_error('input_nama_barang', '<b class="fa fa-warning"></b> ', ' '),
        'input_jml_barang_error' => form_error('input_jml_barang', '<b class="fa fa-warning"></b> ', ' '),
        'input_harga_error' => form_error('input_harga', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  function save(){
    $object = array(
      'nm_barang'  => $this->input->post('input_nama_barang',true),
      'type'       => $this->input->post('input_type',true),
      'merk'       => $this->input->post('input_merk',true),
      'harga'      => $this->input->post('input_harga',true),
      'jml_brg'    => $this->input->post('input_jml_barang',true),
      'ket'        => $this->input->post('input_keterangan',true)
    );

    $inserted_id = $this->Data_barang_model->insert($object);
    $result = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id
    );
    echo json_encode($result);
  }

  function ajax_detail($id){
    $data = $this->Data_barang_model->get_by('id', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'nm_barang'  => $this->input->post('input_nama_barang',true),
      'type'       => $this->input->post('input_type',true),
      'merk'       => $this->input->post('input_merk',true),
      'harga'      => $this->input->post('input_harga',true),
      'jml_brg'    => $this->input->post('input_jml_barang',true),
      'ket'        => $this->input->post('input_keterangan',true)
    );
    $where = array('id' => $this->input->post('id',true));
    $affected_row = $this->Data_barang_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function ajax_delete($id){
    $affected_row = $this->Data_barang_model->delete_id($id);
    echo json_encode(array("status" => TRUE));
  }

  public function export_to_excel(){
    $data = $this->Data_barang_model->get_data()->result();
    $title = 'Data Barang KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" >
      <thead>
        <tr>
          <th >Nama Barang </th>
          <th >Type </th>
          <th >Merk </th>
          <th >Harga </th>
          <th >Jumlah Barang </th>
          <th >Keterangan </th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->nm_barang.'</td>';
              echo '    <td>'.$r->type.'</td>';
              echo '    <td>'.$r->merk;
              echo '    <td>'.$r->harga.'</td>';
              echo '    <td>'.$r->jml_brg.'</td>';
              echo '    <td>'.$r->ket.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }


}
