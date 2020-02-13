<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller{
  var $export_data = array();
  var $pdf;
  public function __construct(){
    parent::__construct();
    $this->load->model(array('Simpanan_model', 'Anggota_model', 'Jenissimpanan_model'));
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
    $this->template->load('Template', 'back/simpanan/view_simpanan');
  }

  function get_jenis_simpanan($id){
    $data = $this->Jenissimpanan_model->get_by('id', $id);
    $result = array( 'result' => $data['jumlah'] );
    echo json_encode($result);
  }

  public function ajax_list(){
    // print_r($this->input->post());
    $list = $this->Simpanan_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {
      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->kode_transaksi ;
      $row[] = $r->tgl_transaksi ;
      $row[] = $r->identitas ;
      $row[] = $r->nama ;
      $row[] = $r->departement ;
      $row[] = $r->jns_simpan ;
      $row[] = rupiah($r->jumlah) ;
      $row[] = $r->user_name ;
      $row[] = '<a role="button" title="Cetak Nota" class="btn btn-sm btn-outline-info" href="'.base_url('simpanan/nota/'.$r->id).'" target="_blank"><b class="fa fa-print"></b></a>' ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-outline-danger" onclick="delete_data(\''.$r->id.'\',\''.$r->kode_transaksi.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }
    $this->export_data = $data;
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Simpanan_model->count_all(),
      "recordsFiltered" => $this->Simpanan_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function getpdf(){
    $period1 = date('Y').'-01-01';
    $period2 = date('Y').'-12-31';
    $pdf = new PDF_MC_Table('l', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->addPage();

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(277, 15, 'Laporan Data Transaksi Setoran Tunai', 0, 1, 'C');

    $pdf->SetFont('Arial','',9);
    $pdf->Cell(277, -5, 'Periode '.formatTglIndo($period1).' sampai '.formatTglIndo($period2).'', 0, 1, 'C');

    $pdf->cell(277, 5, '', 0, 1);

    $pdf->SetWidths(array(15,30,30,20,60,30,30,30,30));

    $pdf->SetLineHeight(5);

    $pdf->SetFillColor(210,221,242);

    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(15, 5, 'No.', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Kode Transaksi', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Tanggal', 1, 0, 'C', TRUE);
    $pdf->Cell(20, 5, 'ID Anggota', 1, 0, 'C', TRUE);
    $pdf->Cell(60, 5, 'Nama Anggota', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Department', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Jenis Simpanan', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Jumlah (Rupiah)', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'User', 1, 0, 'C', TRUE);
    // TOTAL 277

    $pdf->Ln();
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'L', 'R', 'L'));


    $data = $this->Simpanan_model->get_all_data_by_time($period1, $period2)->result_array();

    $i=1;
    $pdf->SetFont('Arial','',8);
    foreach ($data as $r) {
      $pdf->Row(array(
        $i,
        $r['kode_transaksi'],
        formatTglIndo_datetime_2($r['tgl_transaksi']),
        $r['identitas'],
        $r['nama'],
        $r['departement'],
        $r['jns_simpan'],
        rupiah($r['jumlah']),
        $r['user_name'],
      ));
      $i++;
    }

    $pdf->Output();

    }

    function tes(){
      $string = '07 Dec 2019 17:43';
      $date = date_create_from_format('d M Y H:i', $string);
      $timestamp = $date->getTimestamp();
      echo date('Y-m-d H:i', $timestamp);
    }

    public function get_excel($type="", $param_1="", $param_2=""){
      $param1 = str_replace("%20", " ", $param_1);
      $param2 = str_replace("%20", " ", $param_2);

      if($type == '2'){
        if($param1 == ""){
          $desc = "get all data tanpa filer";
          $data = $this->Simpanan_model->get_all_data();
        } else {
          $desc = "get all data hasil search";
          $data = $this->Simpanan_model->get_all_by($param1);
        }
      } else if($type == '1'){
        $desc = "gell all filter tanggal";
        $data = $this->Simpanan_model->get_all_by_date($param1, $param2);
      }

      $title = 'Data Transaksi Setoran Tunai KSU Sakrawarih - '.date('d-m-Y');
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
      <th >Tgl Transaksi </th>
      <th >ID Anggota </th>
      <th >Nama Anggota </th>
      <th >Department </th>
      <th >Jenis Simpanan </th>
      <th >Jumlah </th>
      <th >User </th>
      </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($data->result() as $r) {
        $row[] = $r->kode_transaksi ;
        $row[] = $r->tgl_transaksi ;
        $row[] = $r->identitas ;
        $row[] = $r->nama ;
        $row[] = $r->departement ;
        $row[] = $r->jns_simpan ;
        $row[] = rupiah($r->jumlah) ;
        $row[] = $r->user_name ;
        echo '<tr>';
        echo '    <td>'.$i.'</td>';
        echo '    <td>'.$r->kode_transaksi.'</td>';
        echo '    <td>'.$r->tgl_transaksi.'</td>';
        echo '    <td>'.$r->identitas.'</td>';
        echo '    <td>'.$r->nama.'</td>';
        echo '    <td>'.$r->departement.'</td>';
        echo '    <td>'.$r->jns_simpan.'</td>';
        echo '    <td>'.rupiah($r->jumlah).'</td>';
        echo '    <td>'.$r->user_name.'</td>';
        echo '  </tr> ';
        $i++;
      }
      echo '</tbody> </table>';
    }

    public function validation($method){
      $this->form_validation->set_rules('input_jumlah_simpanan', 'Jumlah Simpanan', 'required',
      array('required' => 'Jumlah Simpanan Tidak Boleh Kosong') );

      $this->form_validation->set_rules('input_tanggal_trans', 'Tanggal Transaksi', 'required',
      array('required' => 'Tanggal Transaksi Tidak Boleh Kosong') );

      $this->form_validation->set_rules('input_nama_anggota', 'Nama Anggota', 'required',
      array('required' => 'Nama Anggota Tidak Boleh Kosong') );

      $this->form_validation->set_rules('input_jenis_simpanan', 'Jenis Simpanan', 'callback_pilih_jenis_simpanan');
      $this->form_validation->set_rules('input_simpan_ke_kas', 'Kas Tujuan', 'callback_pilih_simpan_ke_kas');

      if($this->form_validation->run()){
        $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
      }else {
        $array = array(
          'error' => true,
          'input_tanggal_trans_error' => form_error('input_tanggal_trans', '<b class="fa fa-warning"></b> ', ' '),
          'input_jumlah_simpanan_error' => form_error('input_jumlah_simpanan', '<b class="fa fa-warning"></b> ', ' '),
          'input_nama_anggota_error' => form_error('input_nama_anggota', '<b class="fa fa-warning"></b> ', ' '),
          'input_jenis_simpanan_error' => form_error('input_jenis_simpanan', '<b class="fa fa-warning"></b> ', ' '),
          'input_simpan_ke_kas_error' => form_error('input_simpan_ke_kas', '<b class="fa fa-warning"></b> ', ' '),
        );
      }
      echo json_encode($array);
    }

    public function pilih_anggota($str){
      if ($str == 'x'){
        $this->form_validation->set_message('pilih_anggota', 'Silahkan Pilih Nama Anggota');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    public function pilih_jenis_simpanan($str){
      if ($str == 'x'){
        $this->form_validation->set_message('pilih_jenis_simpanan', 'Silahkan Pilih Kas Tujuan');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    public function pilih_simpan_ke_kas($str){
      if ($str == 'x'){
        $this->form_validation->set_message('pilih_simpan_ke_kas', 'Silahkan Pilih Kas Tujuan');
        return FALSE;
      } else {
        return TRUE;
      }
    }

    public function save(){
      $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_trans'));
      $timestamp = $date->getTimestamp();
      $tgl_trans = date('Y-m-d H:i', $timestamp);

      $anggota = $this->Anggota_model->get_by('nama', $this->input->post('input_nama_anggota'));

      $object = array(
        'tgl_transaksi' => $tgl_trans,
        'anggota_id'    => $anggota['id'],
        'jenis_id'      => $this->input->post('input_jenis_simpanan'),
        'jumlah'        => $this->input->post('input_jumlah_simpanan'),
        'keterangan'    => $this->input->post('input_keterangan'),
        'akun'          => 'Setoran',
        'dk'            => 'D',
        'kas_id'        => $this->input->post('input_simpan_ke_kas'),
        'nama_penyetor' => $this->input->post('input_nama_penyetor'),
        'no_identitas'  => $this->input->post('input_nomor_id_penyetor'),
        'alamat'        => $this->input->post('input_alamat_penyetor'),
        'user_name'    => $this->session->userdata('username'),
        'is_del'       => 0
      );
      $inserted_id = $this->Simpanan_model->insert($object);
      $json_return = array(
        'status'      => TRUE,
        'inserted_id' => $inserted_id,
        'object' => $object
      );
      echo json_encode($json_return);
    }

    function ajax_detail($id){
      $data = $this->Simpanan_model->get_by('id', $id);
      $data_anggota = $this->Anggota_model->get_by('id', $data['anggota_id']);
      $data['file_pic'] = $data_anggota['file_pic'];
      echo json_encode($data);
    }

    public function update(){
      $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_trans'));
      $timestamp = $date->getTimestamp();
      $tgl_trans = date('Y-m-d H:i', $timestamp);

      $anggota = $this->Anggota_model->get_by('nama', $this->input->post('input_nama_anggota'));

      $object = array(
        'tgl_transaksi' => $tgl_trans,
        'anggota_id'    => $anggota['id'],
        'jenis_id'      => $this->input->post('input_jenis_simpanan'),
        'jumlah'        => $this->input->post('input_jumlah_simpanan'),
        'keterangan'    => $this->input->post('input_keterangan'),
        'akun'          => 'Setoran',
        'dk'            => 'D',
        'kas_id'        => $this->input->post('input_simpan_ke_kas'),
        'nama_penyetor' => $this->input->post('input_nama_penyetor'),
        'no_identitas'  => $this->input->post('input_nomor_id_penyetor'),
        'alamat'        => $this->input->post('input_alamat_penyetor'),
      );
      $where = array(
        'id' => $this->input->post('id')
      );
      $inserted_id = $this->Simpanan_model->update($object, $where);
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
      $inserted_id = $this->Simpanan_model->update($object, $where);
      $json_return = array(
        'status'      => TRUE,
        'inserted_id' => $inserted_id,
        'param1' => $_POST['param'][1],
        'param0' => $_POST['param'][0]
      );
      echo json_encode($json_return);
    }

    function nota($id=''){

      $data = $this->Simpanan_model->get_by('id', $id);
      // echo "<pre>";
      // print_r($data);
      $pdf = new fpdf('L', 'mm', array(210,80));
      $pdf->SetMargins(5, 2, 5);
      $pdf->SetAutoPageBreak(false);
      $pdf->addPage();
      // $pdf->w('80');
      $pdf->cell(133, 5, '', 0, 0);

      $pdf->SetFont('Courier','B',10);
      $pdf->cell(100, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

      $pdf->SetFont('Courier','BU',10);
      $pdf->cell(133, 3, 'BUKTI SETORAN TUNAI', 0, 0);

      $pdf->SetFont('Courier','',10);
      $pdf->cell(100, 3, 'Jl. Dr. Suharso No.52 Purwokerto', 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->Ln();
      $pdf->cell(33, 4, 'Tanggal Transaksi', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['tgl_transaksi'],0, 0); //field value tanggal transaksi
      $pdf->cell(30, 4, 'Tanggal Cetak', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, formatTglIndo(Date('Y-m-d')) . ' / ' . Date('H:i'),0, 1); //field value tanggal cetak

      $pdf->cell(33, 4, 'Nomor Transaksi', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['kode_transaksi'], 0, 0); //field value nomor transaksi
      $pdf->cell(30, 4, 'User Akun', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, $data['user_name'], 0, 1); ////field value user akun

      $pdf->cell(33, 4, 'ID Anggota', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['identitas'], 0, 0); //field value id anggota
      $pdf->cell(30, 4, 'Status', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, 'Sukses', 0, 1);

      $pdf->cell(33, 4, 'Nama Anggota', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['nama'], 0, 1);

      $pdf->cell(33, 4, 'Dept', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['departement'], 0, 1);

      $pdf->cell(33, 4, 'Nama Penyetor', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['nama_penyetor'], 0, 0);
      $pdf->cell(30, 4, '', 0, 0);
      $pdf->cell(5, 4, 'Paraf,', 0, 1);

      $pdf->cell(33, 4, 'Alamat', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['alamat'], 0, 1);

      $pdf->cell(33, 4, 'Jenis Akun', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data['jns_simpan'], 0, 1);

      $pdf->cell(33, 4, 'Jumlah Setoran', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, 'Rp. '.rupiah($data['jumlah']), 0, 0);
      $pdf->cell(30, 4, '', 0, 0);
      $pdf->cell(5, 4, '______________________', 0, 1);

      $pdf->cell(33, 4, 'Terbilang', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, strtoupper(Terbilang($data['jumlah'])) . ' RUPIAH', 0, 1);

      $pdf->SetFont('Courier','', 7);
      $pdf->cell(133, 8, '', 0, 1);
      $pdf->cell(190, 4, 'Ref. '.Date('Ymd_His'), 0, 1, 'C');
      $pdf->cell(190, 4, 'Informasi Hubungi Call Center : 0281-632324', 0, 1, 'C');
      $pdf->cell(190, 4, 'atau dapat diakses melalui : -', 0, 1, 'C');
      $pdf->cell(190, 1, '', 0, 1);
      $pdf->SetFont('Courier','I', 7);
      $pdf->cell(190, 4, '** Tanda terima ini sah jika telah dibubuhi cap dan tanda tangan oleh Admin ** : -', 0, 1, 'C');

      $pdf->Output();
    }

    function fetch_autocomplete(){
      echo $this->Simpanan_model->fetch_autocomplete_data($this->uri->segment(3));
    }

  }
