<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisakun extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Jenis_akun_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/jenis_akun/view_jenis_akun');
  }

  public function ajax_list(){
    $list = $this->Jenis_akun_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {
      $row = array();
      $row[] = $r->kd_aktiva ;
      $row[] = $r->jns_trans ;
      $row[] = $r->akun ;
      $row[] = $r->pemasukan ;
      $row[] = $r->pengeluaran ;
      $row[] = $r->aktif ;
      $row[] = $r->laba_rugi ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Jenis_akun_model->count_all(),
      "recordsFiltered" => $this->Jenis_akun_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_kd_aktiva', 'Kode Aktiva', 'required',
    array('required' => 'Kode Aktiva Tidak Boleh Kosong') );

    $this->form_validation->set_rules('input_jenis_trans', 'Jenis Transaksi', 'required',
    array('required' => 'Jenis Transaksi Tidak Boleh Kosong') );

    $this->form_validation->set_rules('input_akun', 'Akun', 'callback_pilih_akun');
    $this->form_validation->set_rules('input_pemasukan', 'Pemasukan', 'callback_pilih_pemasukan');
    $this->form_validation->set_rules('input_pengeluaran', 'Pengeluaran', 'callback_pilih_pengeluaran');
    $this->form_validation->set_rules('input_aktif', 'Aktif', 'callback_pilih_aktif');

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_kd_aktiva_error' => form_error('input_kd_aktiva', '<b class="fa fa-warning"></b> ', ' '),
        'input_jenis_trans_error' => form_error('input_jenis_trans', '<b class="fa fa-warning"></b> ', ' '),
        'input_akun_error' => form_error('input_akun', '<b class="fa fa-warning"></b> ', ' '),
        'input_pemasukan_error' => form_error('input_pemasukan', '<b class="fa fa-warning"></b> ', ' '),
        'input_pengeluaran_error' => form_error('input_pengeluaran', '<b class="fa fa-warning"></b> ', ' '),
        'input_aktif_error' => form_error('input_aktif', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  public function pilih_akun($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_akun', 'Silahkan Pilih Akun');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_pemasukan($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_pemasukan', 'Silahkan Pilih Pemasukan');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_pengeluaran($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_pengeluaran', 'Silahkan Pilih Pengeluaran');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_aktif($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_aktif', 'Silahkan Status');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function save(){
    $object = array(
      'kd_aktiva'   => $this->input->post('input_kd_aktiva',true),
      'jns_trans'   => $this->input->post('input_jenis_trans',true),
      'akun'        => $this->input->post('input_akun',true),
      'laba_rugi'   => $this->input->post('input_labarugi',true),
      'pemasukan'   => $this->input->post('input_pemasukan',true),
      'pengeluaran' => $this->input->post('input_pengeluaran',true),
      'aktif'       => $this->input->post('input_aktif',true)
    );

    $inserted_id = $this->Jenis_akun_model->insert($object);
    $result = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id
    );
    echo json_encode($result);
  }


  function ajax_detail($id){
    $data = $this->Jenis_akun_model->get_by('id', $id);
    echo json_encode($data);
  }

  function update(){
    $object = array(
      'kd_aktiva'   => $this->input->post('input_kd_aktiva',true),
      'jns_trans'   => $this->input->post('input_jenis_trans',true),
      'akun'        => $this->input->post('input_akun',true),
      'laba_rugi'   => $this->input->post('input_labarugi',true),
      'pemasukan'   => $this->input->post('input_pemasukan',true),
      'pengeluaran' => $this->input->post('input_pengeluaran',true),
      'aktif'       => $this->input->post('input_aktif',true)
    );
    $where = array('id' => $this->input->post('id',true));
    $affected_row = $this->Jenis_akun_model->update($object, $where);
    $result = array(
      'status'      => TRUE,
      'affected_row' => $affected_row
    );
    echo json_encode($result);
  }

  public function export_to_excel(){
    $data = $this->Jenis_akun_model->get_data()->result();
    $title = 'Data Jenis Akun Transaksi KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" style="width: 100%;">
      <thead>
        <tr>
          <th >Kd Aktiva </th>
          <th >Jenis Transaksi </th>
          <th >Akun </th>
          <th >Pemasukan </th>
          <th >Pengeluaran </th>
          <th >Aktif </th>
          <th >Laba-Rugi </th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data as $r) {

              echo '<tr>';
              echo '    <td>'.$r->kd_aktiva.'</td>';
              echo '    <td>'.$r->jns_trans.'</td>';
              echo '    <td>'.$r->akun.'</td>';
              echo '    <td>'.$r->pemasukan.'</td>';
              echo '    <td>'.$r->pengeluaran.'</td>';
              echo '    <td>'.$r->aktif.'</td>';
              echo '    <td>'.$r->laba_rugi.'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

}
