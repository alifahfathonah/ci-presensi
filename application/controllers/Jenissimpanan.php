<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenissimpanan extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Jenissimpanan_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/jenis_simpanan/view_jenis_simpanan');
  }

  public function ajax_list(){
    $list = $this->Jenissimpanan_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {
      $label_tampil = ($r->tampil == 'Y') ? 'Ya' : 'Tidak' ;
      $row = array();
      $row[] = $r->jns_simpan ;
      $row[] = rupiah($r->jumlah);
      $row[] = $label_tampil;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jenissimpanan_model->count_all(),
      "recordsFiltered" => $this->Jenissimpanan_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function ajax_detail($id){
    $data = $this->Jenissimpanan_model->get_by('id', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'jns_simpan' => $this->input->post('input_nama_jenis_simpanan',true),
      'jumlah'     => $this->input->post('input_jumlah',true),
      'tampil'     => $this->input->post('input_tampil',true),
    );
    $where = array('id' => $this->input->post('id',true));
    $affected_row = $this->Jenissimpanan_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function ajax_delete($id){
    $affected_row = $this->Jenissimpanan_model->delete_id($id);
    echo json_encode(array("status" => TRUE));
  }

  public function export_to_excel(){
    $data = $this->Jenissimpanan_model->get_data()->result();
    $title = 'Data Jenis Simpanan KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" >
      <thead>
        <tr>
          <th >Jenis Simpanan </th>
          <th >Jumlah </th>
          <th >Tampil </th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->jns_simpan.'</td>';
              echo '    <td>'.$r->jumlah.'</td>';
              echo '    <td>'.$r->tampil.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

}
