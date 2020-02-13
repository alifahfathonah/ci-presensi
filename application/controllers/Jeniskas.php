<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jeniskas extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Jenis_kas_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/jeniskas/view_jenis_kas');
  }

  public function ajax_list(){
    $list = $this->Jenis_kas_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $row = array();
      $row[] = $r->nama ;
      $row[] = $r->aktif ;
      $row[] = $r->tmpl_simpan;
      $row[] = $r->tmpl_penarikan ;
      $row[] = $r->tmpl_pinjaman ;
      $row[] = $r->tmpl_bayar ;
      $row[] = $r->tmpl_pemasukan ;
      $row[] = $r->tmpl_pengeluaran ;
      $row[] = $r->tmpl_transfer ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-danger" onclick="delete_data(\''.$r->id.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jenis_kas_model->count_all(),
      "recordsFiltered" => $this->Jenis_kas_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_nama_kas', 'Nama Jenis Kas', 'required', array('required' => 'Nama Jenis Kas Tidak Boleh Kosong') );

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_nama_kas_error' => form_error('input_nama_kas', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  function save(){
    $object = array(
      'nama'              => $this->input->post('input_nama_kas',true),
      'aktif'             => $this->input->post('input_aktif',true),
      'tmpl_simpan'       => $this->input->post('input_simpanan',true),
      'tmpl_penarikan'    => $this->input->post('input_penarikan',true),
      'tmpl_pinjaman'     => $this->input->post('input_pinjaman',true),
      'tmpl_bayar'        => $this->input->post('input_angsuran',true),
      'tmpl_pemasukan'    => $this->input->post('input_pemasukan',true),
      'tmpl_pengeluaran'  => $this->input->post('input_pengeluaran',true),
      'tmpl_transfer'     => $this->input->post('input_transfer',true),
    );

    $inserted_id = $this->Jenis_kas_model->insert($object);
    $result = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id
    );
    echo json_encode($result);
  }

  function ajax_detail($id){
    $data = $this->Jenis_kas_model->get_by('id', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'nama'              => $this->input->post('input_nama_kas',true),
      'aktif'             => $this->input->post('input_aktif',true),
      'tmpl_simpan'       => $this->input->post('input_simpanan',true),
      'tmpl_penarikan'    => $this->input->post('input_penarikan',true),
      'tmpl_pinjaman'     => $this->input->post('input_pinjaman',true),
      'tmpl_bayar'        => $this->input->post('input_angsuran',true),
      'tmpl_pemasukan'    => $this->input->post('input_pemasukan',true),
      'tmpl_pengeluaran'  => $this->input->post('input_pengeluaran',true),
      'tmpl_transfer'     => $this->input->post('input_transfer',true),
    );
    $where = array('id' => $this->input->post('id',true));
    $affected_row = $this->Jenis_kas_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function ajax_delete($id){
    $affected_row = $this->Jenis_kas_model->delete_id($id);
    echo json_encode(array("status" => TRUE));
  }

  public function export_to_excel(){
    $data = $this->Jenis_kas_model->get_data()->result();
    $title = 'Data Jenis Kas KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" >
      <thead>
        <tr>
          <th >Nama Kas </th>
          <th >Aktif </th>
          <th >Simpanan </th>
          <th >Penarikan </th>
          <th >Pinjaman </th>
          <th >Angsuran </th>
          <th >Pemasukan Kas </th>
          <th >Pengeluaran Kas </th>
          <th >Transfer Kas </th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->nama.'</td>';
              echo '    <td>'.$r->aktif.'</td>';
              echo '    <td>'.$r->tmpl_simpan;
              echo '    <td>'.$r->tmpl_penarikan.'</td>';
              echo '    <td>'.$r->tmpl_pinjaman.'</td>';
              echo '    <td>'.$r->tmpl_bayar.'</td>';
              echo '    <td>'.$r->tmpl_pemasukan.'</td>';
              echo '    <td>'.$r->tmpl_pengeluaran.'</td>';
              echo '    <td>'.$r->tmpl_transfer.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

}
