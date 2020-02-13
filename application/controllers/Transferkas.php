<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transferkas extends CI_Controller{
  var $export_data = array();
  var $pdf;
  public function __construct(){
    parent::__construct();
    $this->load->model(array('Transfer_kas_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }

    $this->pdf = new FPDF('l', 'mm', 'A4');
  }

  function index(){
    $this->template->load('Template', 'back/Transferkas/view_transfer_kas');
  }

  public function ajax_list(){
    // print_r($this->input->post());
    $list = $this->Transfer_kas_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {
      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->kd_trans ;
      $row[] = $r->tgl_catat ;
      $row[] = $r->keterangan ;
      $row[] = rupiah($r->jumlah) ;
      $row[] = $r->nama_dari_kas_id ;
      $row[] = $r->nama_untuk_kas_id ;
      $row[] = $r->user_name ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-outline-danger" onclick="delete_data(\''.$r->id.'\',\''.$r->kd_trans.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }
    $this->export_data = $data;
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Transfer_kas_model->count_all(),
      "recordsFiltered" => $this->Transfer_kas_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function test(){
    $period1 = date('Y').'-01-01';
    $period2 = date('Y').'-12-31';
    $data = $this->Transfer_kas_model->get_all_data_by_time($period1, $period2)->result_array();
    print_r($data);
  }

  public function getpdf(){
    $period1 = date('Y').'-01-01';
    $period2 = date('Y').'-12-31';
    $pdf = new PDF_MC_Table('l', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->addPage();

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(277, 15, 'Laporan Data Transfer Kas', 0, 1, 'C');

    $pdf->SetFont('Arial','',9);
    $pdf->Cell(277, -5, 'Periode '.formatTglIndo($period1).' sampai '.formatTglIndo($period2).'', 0, 1, 'C');

    $pdf->cell(277, 5, '', 0, 1);

    $pdf->SetWidths(array(15,30,46,110,46,30));

    $pdf->SetLineHeight(5);

    $pdf->SetFillColor(210,221,242);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(15, 5, 'No.', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'No. Transaksi', 1, 0, 'C', TRUE);
    $pdf->Cell(46, 5, 'Tanggal', 1, 0, 'C', TRUE);
    $pdf->Cell(110, 5, 'Uraian', 1, 0, 'C', TRUE);
    $pdf->Cell(46, 5, 'Jumlah (Rupiah)', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'User', 1, 0, 'C', TRUE);

    $pdf->Ln();
    $pdf->SetAligns(array('C', 'C', 'C', 'L', 'R', 'L'));


    $data = $this->Transfer_kas_model->get_all_data_by_time($period1, $period2)->result_array();

    $i=1;
    $pdf->SetFont('Arial','',8);
    foreach ($data as $r) {
      $pdf->Row(array(
        $i,
        $r['kd_trans'],
        formatTglIndo_datetime_2($r['tgl_catat']),
        $r['keterangan'],
        rupiah($r['jumlah']),
        $r['user_name'],
      ));
      $i++;
    }

    $pdf->Output();

    }

    public function get_excel($type="", $param_1="", $param_2=""){
      $param1 = str_replace("%20", " ", $param_1);
      $param2 = str_replace("%20", " ", $param_2);

      if($type == '2'){
        if($param1 == ""){
          $desc = "get all data tanpa filer";
          $data = $this->Transfer_kas_model->get_all_data();
        } else {
          $desc = "get all data hasil search";
          $data = $this->Transfer_kas_model->get_all_by($param1);
        }
      } else if($type == '1'){
        $desc = "gell all filter tanggal";
        $data = $this->Transfer_kas_model->get_all_by_date($param1, $param2);
      }

      $title = 'Data Transfer Kas KSU Sakrawarih - '.date('d-m-Y');
      header("Content-type: application/vnd-ms-excel; charset=utf-8");
      header('Content-Disposition: attachment; filename="'.$title.'.xls"');
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
      echo '
      <table border="1" style="width: 100%;">
      <thead>
      <tr>
      <th >No </th>
      <th >KD Trans </th>
      <th >Tanggal Transaksi </th>
      <th >Uraian </th>
      <th >Jumlah </th>
      <th >Dari Kas </th>
      <th >Untuk Kas </th>
      <th >User </th>
      </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data->result() as $r) {
        echo '<tr>';
        echo '    <td>'.$i.'</td>';
        echo '    <td>'.$r->kd_trans.'</td>';
        echo '    <td>'.$r->tgl_catat.'</td>';
        echo '    <td>'.$r->keterangan.'</td>';
        echo '    <td>'.rupiah($r->jumlah).'</td>';
        echo '    <td>'.$r->nama_dari_kas_id.'</td>';
        echo '    <td>'.$r->nama_untuk_kas_id.'</td>';
        echo '    <td>'.$r->user_name.'</td>';
        echo '  </tr> ';
        $i++;
      }
      echo '</tbody> </table>';
    }

    public function validation($method){
      $this->form_validation->set_rules('input_jumlah', 'Jumlah', 'required',
      array('required' => 'Jumlah Tidak Boleh Kosong') );

      $this->form_validation->set_rules('input_uraian', 'Keterangan', 'required',
      array('required' => 'Keterangan Tidak Boleh Kosong') );

      $this->form_validation->set_rules('input_untuk_kas', 'Untuk Kas', 'callback_pilih_dari_akun');
      $this->form_validation->set_rules('input_dari_kas', 'Dari Kas', 'callback_pilih_untuk_kas');

      if($this->form_validation->run()){
        $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
      }else {
        $array = array(
          'error' => true,
          'input_jumlah_error' => form_error('input_jumlah', '<b class="fa fa-warning"></b> ', ' '),
          'input_uraian_error' => form_error('input_uraian', '<b class="fa fa-warning"></b> ', ' '),
          'input_untuk_kas_error' => form_error('input_untuk_kas', '<b class="fa fa-warning"></b> ', ' '),
          'input_dari_kas_error' => form_error('input_dari_kas', '<b class="fa fa-warning"></b> ', ' '),
        );
      }
      echo json_encode($array);
    }

    public function pilih_dari_akun($str){
      if ($str == 'x'){
        $this->form_validation->set_message('pilih_dari_akun', 'Silahkan Pilih Jenis Akun');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    public function pilih_untuk_kas($str){
      if ($str == 'x'){
        $this->form_validation->set_message('pilih_untuk_kas', 'Silahkan Pilih Kas');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    public function save(){
      $object = array(
        'tgl_catat'    => $this->input->post('input_tanggal_trans'),
        'jumlah'       => $this->input->post('input_jumlah'),
        'keterangan'   => $this->input->post('input_uraian'),
        'akun'         => 'Transfer',
        'dari_kas_id'  => $this->input->post('input_dari_kas'),
        'untuk_kas_id' => $this->input->post('input_untuk_kas'),
        'dk'           => null,
        'user_name'    => $this->session->userdata('username'),
        'is_del'       => 0
      );
      $inserted_id = $this->Transfer_kas_model->insert($object);
      $json_return = array(
        'status'      => TRUE,
        'inserted_id' => $inserted_id,
      );
      echo json_encode($json_return);
    }

    function ajax_detail($id){
      $data = $this->Transfer_kas_model->get_by('id', $id);
      echo json_encode($data);
    }

    public function update(){
      $object = array(
        'tgl_catat' => $this->input->post('input_tanggal_trans'),
        'jumlah' => $this->input->post('input_jumlah'),
        'keterangan' => $this->input->post('input_uraian'),
        'dari_kas_id'  => $this->input->post('input_dari_kas'),
        'untuk_kas_id' => $this->input->post('input_untuk_kas'),
      );
      $where = array(
        'id' => $this->input->post('id')
      );
      $inserted_id = $this->Transfer_kas_model->update($object, $where);
      $json_return = array(
        'status'      => TRUE,
        'inserted_id' => $inserted_id,
      );
      echo json_encode($json_return);
    }

    public function soft_delete(){
      $object = array(
        'del_datetime' => date('Y-m-d H:i:s'),
        'del_by'       => $this->session->userdata('username'),
        'del_reason'   => $_POST['param'][1],
        'is_del'       => 1,
      );
      $where = array(
        'id' => $_POST['param'][0]
      );
      $inserted_id = $this->Transfer_kas_model->update($object, $where);
      $json_return = array(
        'status'      => TRUE,
        'inserted_id' => $inserted_id,
        'param1' => $_POST['param'][1],
        'param0' => $_POST['param'][0]
      );
      echo json_encode($json_return);
    }

  }
