<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller{
  var $export_data = array();
  var $pdf;
  var $jenis_pinjaman;
  public function __construct(){
    parent::__construct();
    $this->load->model(array('Pinjaman_model', 'Anggota_model', 'Jenissimpanan_model', 'Lap_kas_anggota_model', 'Profile_model', 'Angsuran_model', 'Sukubunga_model'));
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
    $data['kas_id']     = $this->Pinjaman_model->get_data_kas();
		$data['jenis_ags']  = $this->Pinjaman_model->get_data_angsuran();
		$data['suku_bunga'] = $this->Pinjaman_model->get_data_bunga();
    $data['biaya']      = $this->Pinjaman_model->get_biaya_adm();
    $data['opsi_pinjaman_barang']      = $this->Pinjaman_model->get_max_pinjaman_barang();

    $this->template->load('Template', 'back/pinjaman/view_pinjaman', $data);
  }

  function get_jenis_simpanan(){
    $jenis_simpanan_id = $_POST['param'][0];
    $nama_anggota      = $_POST['param'][1];
    $anggota = $this->Anggota_model->get_by('nama', $nama_anggota);

    $total_simpanan  = $this->Lap_kas_anggota_model->get_jml_simpanan($jenis_simpanan_id, $anggota['id']);
    $total_penarikan = $this->Lap_kas_anggota_model->get_jml_penarikan($jenis_simpanan_id, $anggota['id']);
    $result = array('result' => $total_simpanan->jml_total - $total_penarikan->jml_total);
    echo json_encode($result);
  }

  public function ajax_list(){
    $list = $this->Pinjaman_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $data_anggota = $this->Anggota_model->get_by('id', $r->anggota_id);
      $barang = $this->Pinjaman_model->get_data_barang($r->barang_id);
      $jml_bayar = $this->Pinjaman_model->get_jml_bayar($r->id);
      $jml_denda = $this->Pinjaman_model->get_jml_denda($r->id);
      $total_tagihan = $r->tagihan + $jml_denda->total_denda;
      $sisa_tagihan = $total_tagihan - $jml_bayar->total;

      $sisa_angsur = 0;
			if($r->lunas == 'Belum') {
			     $sisa_angsur = $r->lama_angsuran - $r->bln_sudah_angsur;
			}

      $table_hitungan = '<table class="table table-bordered table-striped" >
      <tr>
      <td>Jenis Pinjaman</td><td>:</td><td >'.$barang->nm_barang.'</td>
      </tr>

      <tr>
      <td>Nominal</td><td>:</td><td class="text-right">'.rupiah($r->jumlah).'</td>
      </tr>

      <tr>
      <td>Lama Angsuran</td><td>:</td><td class="text-right">'.$r->lama_angsuran.'  Bulan</td>
      </tr>

      <tr>
      <td>Pokok Angsuran</td><td>:</td><td class="text-right">'.rupiah($r->pokok_angsuran).'</td>
      </tr>

      <tr>
      <td>Bunga Pinjaman</td><td>:</td><td class="text-right">'.rupiah(nsi_round($r->bunga_pinjaman)).'</td>
      </tr>

      <tr>
      <td>Biaya Admin</td><td>:</td><td class="text-right">'.rupiah($r->biaya_adm).'</td>
      </tr>

      </table>';

      $table_tagihan = '<table class="table table-bordered table-striped">
      <tr>
      <td>Jumlah Angsuran</td><td>:</td><td class="text-right">'.rupiah(nsi_round($r->ags_per_bulan)).'</td>
      </tr>

      <tr>
      <td>Total Tagihan</td><td>:</td><td class="text-right">'.rupiah(nsi_round($total_tagihan)).'</td>
      </tr>

      <tr>
      <td>Sudah Dibayar</td><td>:</td><td class="text-right">'.rupiah(nsi_round($jml_bayar->total)).'</td>
      </tr>

      <tr>
      <td>Sisa Angsuran</td><td>:</td><td class="text-right">'.$sisa_angsur.' Bulan</td>
      </tr>

      <tr class="bg-warning text-dark">
      <td>Sisa Tagihan</td><td>:</td><td class="text-right"><b>'.rupiah(nsi_round($sisa_tagihan)).'</b></td>
      </tr>

      </table>';

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = 'PJ' . sprintf('%05d', $r->id);
      $row[] = formatTglIndo_datetime_3($r->tgl_pinjam);
      $row[] = $data_anggota['identitas'].'<br>'.$data_anggota['nama'].'<br>'.$data_anggota['departement'] ; ;
      $row[] = $table_hitungan ; //hitungan
      $row[] = $table_tagihan ; //total tagihan
      $row[] = $r->lunas ;
      $row[] = $r->user_name ;
      $row[] = '
        <div class="btn-group btn-group-vertical" role="group" aria-label="Basic example">
          <a style="font-size: 13px" role="button" title="Detail" class="btn btn-sm btn-success" href="'.base_url('angsuran_detail/index/'.$r->id).'" target="_self"><b class="fa fa-search"></b></a>
          <a style="font-size: 13px" role="button" title="Cetak Nota" class="btn btn-sm btn-info" href="'.base_url('pinjaman/nota/'.$r->id).'" target="_blank"><b class="fa fa-print"></b></a>
        </div>
        <hr>
        <div class="btn-group btn-group-vertical" role="group" aria-label="Basic example">
          <button style="font-size: 13px" title="Edit" class="btn btn-sm btn-warning" onclick="edit(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
          <button style="font-size: 13px" title="Hapus" class="btn btn-sm btn-danger" onclick="delete_data(\''.$r->id.'\',\''.'PJ' . sprintf('%05d', $r->id).'\',\''.$r->barang_id.'\')"><b class="fa fa-trash"></b></button>
        </div>
      ' ;
      $data[] = $row;
    }
    $this->export_data = $data;
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Pinjaman_model->count_all(),
      "recordsFiltered" => $this->Pinjaman_model->count_filtered(),
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
    $pdf->Cell(277, 15, 'Laporan Data Transaksi Penarikan', 0, 1, 'C');

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
    $pdf->Cell(30, 5, 'Jenis Penarikan', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'Jumlah (Rupiah)', 1, 0, 'C', TRUE);
    $pdf->Cell(30, 5, 'User', 1, 0, 'C', TRUE);
    // TOTAL 277

    $pdf->Ln();
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'L', 'R', 'L'));


    $data = $this->Pinjaman_model->get_all_data_by_time($period1, $period2)->result_array();

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

  public function get_excel($type="", $param_1="", $param_2=""){
    $param1 = str_replace("%20", " ", $param_1);
    $param2 = str_replace("%20", " ", $param_2);

    if($type == '2'){
      if($param1 == ""){
        $desc = "get all data tanpa filer";
        $data = $this->Pinjaman_model->get_all_data();
      } else {
        $desc = "get all data hasil search";
        $data = $this->Pinjaman_model->get_all_by($param1);
      }
    } else if($type == '1'){
      $desc = "gell all filter tanggal";
      $data = $this->Pinjaman_model->get_all_by_date($param1, $param2);
    }

    $title = 'Data Transaksi Penarikan KSU Sakrawarih - '.date('d-m-Y');
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
    <th >Jenis Penarikan </th>
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

  public function validation(){
    $this->form_validation->set_rules('input_nama_anggota', 'Nama Anggota', 'required',
    array('required' => 'Nama Anggota Tidak Boleh Kosong') );

    $this->form_validation->set_rules('input_tanggal_pinjam', 'Tanggal Pinjam', 'required',
    array('required' => 'Tanggal Pinjam Tidak Boleh Kosong') );

    $this->form_validation->set_rules('input_jumlah_pinjaman', 'Nominal', 'required|callback_max_nilai_pinjaman_barang',
    array('required' => 'Nominal Tidak Boleh Kosong') );

    $this->form_validation->set_rules('input_jenis_pinjaman', 'Jenis Pinjaman', 'callback_pilih_jenis_pinjam');
    $this->form_validation->set_rules('input_lama_angsuran', 'Lama Angsuran', 'callback_pilih_angsuran|callback_max_cicilan_pinjaman_barang');
    $this->form_validation->set_rules('input_ambil_dari_kas', 'Asal Kas', 'callback_pilih_simpan_ke_kas');

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_tanggal_pinjam_error' => form_error('input_tanggal_pinjam', '<b class="fa fa-warning"></b> ', ' '),
        'input_nama_anggota_error' => form_error('input_nama_anggota', '<b class="fa fa-warning"></b> ', ' '),
        'input_jumlah_pinjaman_error' => form_error('input_jumlah_pinjaman', '<b class="fa fa-warning"></b> ', ' '),
        'input_jenis_pinjaman_error' => form_error('input_jenis_pinjaman', '<b class="fa fa-warning"></b> ', ' '),
        'input_ambil_dari_kas_error' => form_error('input_ambil_dari_kas', '<b class="fa fa-warning"></b> ', ' '),
        'input_lama_angsuran_error' => form_error('input_lama_angsuran', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  public function max_nilai_pinjaman_barang($str){
    $data = $this->Pinjaman_model->get_max_pinjaman_barang();
    $this->jenis_pinjaman = $this->input->post('input_jenis_pinjaman');
    if($this->jenis_pinjaman == 3){
      if ($str > $data['max_hutang_barang']){
        $this->form_validation->set_message('max_nilai_pinjaman_barang', 'Nominal Barang Tidak Boleh Lebih Dari ' . 'Rp. '. rupiah($data['max_hutang_barang']));
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      return TRUE;
    }
  }

  public function max_cicilan_pinjaman_barang($str){
    $data = $this->Pinjaman_model->get_max_pinjaman_barang();
    $this->jenis_pinjaman = $this->input->post('input_jenis_pinjaman');
    if($this->jenis_pinjaman == 3){
      if ($str >= $data['max_cicilan_barang'] + 1){
        $this->form_validation->set_message('max_cicilan_pinjaman_barang', 'Lama Angsuran Tidak Boleh Lebih Dari ' . $data['max_cicilan_barang']);
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      return TRUE;
    }
  }

  public function pilih_angsuran($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_angsuran', 'Silahkan Pilih Lama Angsuran');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_jenis_pinjam($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_jenis_pinjam', 'Silahkan Pilih Jenis Pinjaman');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_simpan_ke_kas($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_simpan_ke_kas', 'Silahkan Pilih Kas Asal');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function test(){
    $date = date_create_from_format('d M Y - H:i', '16 Dec 2019 - 22:46');
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);
    echo $tgl_trans;
  }

  public function save(){
    $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_pinjam'));
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);

    $anggota = $this->Anggota_model->get_by('nama', $this->input->post('input_nama_anggota'));

    $jumlah_pinjam 	= str_replace(',', '', $this->input->post('input_jumlah_pinjaman'));
		$biaya_admin    = $this->input->post('input_biaya_admin');
		$biaya_admin 		= $jumlah_pinjam * ($biaya_admin / 100);

    $object = array(
      'tgl_pinjam'      => $tgl_trans,
      'anggota_id'      => $anggota['id'],
      'barang_id'       => $this->input->post('input_jenis_pinjaman'),
      'lama_angsuran'   => $this->input->post('input_lama_angsuran'),
      'jumlah'          => $this->input->post('input_jumlah_pinjaman'),
      'bunga'           => $this->input->post('input_bunga'),
      'biaya_adm'       => $biaya_admin,
      'dk'              => 'K',
      'kas_id'          => $this->input->post('input_ambil_dari_kas'),
      'jns_trans'       => '7',
      'keterangan'      => $this->input->post('input_keterangan'),
      'user_name'       => $this->session->userdata('username'),
      'is_del'          => 0
    );

    $inserted_id = $this->Pinjaman_model->insert($object);
    $json_return = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id,
      'object'      => $object
    );
    echo json_encode($json_return);
  }

  function ajax_detail($id){
    $data = $this->Pinjaman_model->get_by('id', $id);
    $data_anggota = $this->Anggota_model->get_by('id', $data['anggota_id']);
    $data['file_pic'] = $data_anggota['file_pic'];
    echo json_encode($data);
  }

  public function update(){
    $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_pinjam'));
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);

    $anggota = $this->Anggota_model->get_by('nama', $this->input->post('input_nama_anggota'));

    $object = array(
      'tgl_pinjam'      => $tgl_trans,
      'lama_angsuran'   => $this->input->post('input_lama_angsuran'),
      'jumlah'          => $this->input->post('input_jumlah_pinjaman'),
      'kas_id'          => $this->input->post('input_ambil_dari_kas'),
      'keterangan'      => $this->input->post('input_keterangan'),
      'update_data'			=> date('Y-m-d H:i')
    );
    $where = array(
      'id' => $this->input->post('id')
    );
    $inserted_id = $this->Pinjaman_model->update($object, $where);
    $json_return = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id,
    );
    echo json_encode($json_return);
  }

  public function soft_delete(){
    $barang_id = $_POST['param'][2];
    $object = array(
      'del_datetime' => date('Y-m-d H:i:s'),
      'del_by'       => $this->session->userdata('username'),
      'del_reason'   => $_POST['param'][1],
      'is_del'       => 1,
    );
    $where = array(
      'id' => $_POST['param'][0]
    );
    $inserted_id = $this->Pinjaman_model->soft_delete($object, $where, $barang_id);
    $json_return = array(
      'status'      => TRUE,
      'inserted_id' => $inserted_id,
      'param1' => $_POST['param'][1],
      'param0' => $_POST['param'][0]
    );
    echo json_encode($json_return);
  }

  function nota($id=''){

    $data_instansi = $this->Profile_model->get_data()->row_array();
    foreach ($data_instansi as $key => $value){
			$out[$key] = $value;
		}

    $data =  $this->Pinjaman_model->get_data_pinjam($id);

    $data_anggota = $this->Anggota_model->get_by('id', $data->anggota_id);
    $pdf = new fpdf('L', 'mm', array(210, 150));
    $pdf->SetMargins(5, 2, 5);
    $pdf->SetAutoPageBreak(false);
    $pdf->addPage();

    $pdf->SetFont('Courier','B',12);
    $pdf->cell(100, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

    $pdf->SetFont('Courier','', 9);
    $pdf->cell(100, 3, 'Jl. Dr. Suharso No.52 Purwokerto Tel. 0281-632324', 0, 1);

    $pdf->cell(200, 1, '________________________________________________________________________________________________________', 0, 1);

    $pdf->cell(133, 5, '', 0, 1);

    $pdf->SetFont('Courier','B',9);
    $pdf->cell(190, 5, 'BUKTI PENCAIRAN DANA KREDIT', 0, 1, 'C');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(190, 4, 'Ref. '.Date('Ymd_His'), 0, 1, 'C');

    $pdf->cell(133, 5, '', 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(34, 4, 'Telah terima dari ', 0, 0);

    $pdf->SetFont('Courier','B',9);
    $pdf->cell(90, 4, $out['nama_lembaga'], 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(133, 4, 'Pada Tanggal '.formatTglIndo_3(Date('Y-m-d')).' untuk realisasi kredit sebesar :', 0, 1);

    $pdf->SetFont('Courier','B',9);
    $pdf->cell(10, 4, '', 0, 0);
    $pdf->cell(100, 4, 'Rp. '.rupiah($data->jumlah),0, 1);

    $pdf->SetFont('Courier','I',9);
    $pdf->cell(10, 4, '', 0, 0);
    $pdf->cell(100, 4, str_replace("  ", " ", '('.strtoupper(Terbilang($data->jumlah)).' RUPIAH )'), 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(133, 4, 'Dengan Rincian :', 0, 1);

    $pdf->Ln();

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Nomor Kontrak', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, 'TPJ'.sprintf('%05d', $data->id), 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Id Anggota', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, $data_anggota['identitas'], 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Nama Anggota', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, $data_anggota['nama'], 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Dept', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, $data_anggota['departement'], 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Alamat', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, $data_anggota['alamat'], 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Tanggal Pinjam', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $label_tanggal_pinjam = explode(" ", $data->tgl_pinjam);
    $pdf->cell(150, 4, formatTglIndo_3($label_tanggal_pinjam[0]), 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Tanggal Tempo', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $label_tempo = explode(" ", $data->tempo);
    $pdf->cell(150, 4, formatTglIndo_3($label_tempo[0]), 0, 1);

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Lama Pinjam', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, $data->lama_angsuran. ' Bulan', 0, 1);

    $pdf->Ln();

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Total Pinjaman', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->tagihan)), 0, 1, 'R');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Pokok Pinjaman', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->jumlah)), 0, 1, 'R');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Angsuran Pokok', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->pokok_angsuran)), 0, 1, 'R');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Biaya Admin', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->biaya_adm)), 0, 1, 'R');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'Angsuran Bunga', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->bunga_pinjaman)), 0, 1, 'R');

    $pdf->SetFont('Courier','B',9);
    $pdf->cell(35, 4, 'Jumlah Angsuran', 0, 0);
    $pdf->cell(3, 4, ': Rp. ', 0, 0);
    $pdf->cell(40, 4, rupiah(nsi_round($data->ags_per_bulan)), 0, 1, 'R');

    $pdf->SetFont('Courier','',9);
    $pdf->cell(35, 4, 'TERBILANG', 0, 0);
    $pdf->cell(3, 4, ':', 0, 0);
    $pdf->cell(150, 4, str_replace("  ", " ", strtoupper(Terbilang($data->ags_per_bulan)).' RUPIAH'), 0, 1);

    $pdf->Ln();
    $pdf->cell(100, 4, '', 0, 0);
    $pdf->cell(3, 4, 'Banyumas, Jawa Tengah, '.formatTglIndo_3(Date('Y-m-d')), 0, 1);

    $pdf->Ln();$pdf->Ln();$pdf->Ln();
    $pdf->cell(100, 4, strtoupper($data->user_name), 0, 0,  'C');
    $pdf->cell(70, 4, $data_anggota['nama'], 0, 1,  'C');
    $pdf->SetFont('Courier','I', 8);
    $pdf->cell(210, 4, '** Tanda terima ini sah jika telah dibubuhi cap dan tanda tangan oleh Admin **', 0, 1, 'C');

    $pdf->Output();
  }

  function fetch_autocomplete(){
    echo $this->Pinjaman_model->fetch_autocomplete_data($this->uri->segment(3));
  }

  function cetak_pinjaman_detail($id){

    if(empty($id)){
      redirect('angsuran_detail');
    }

    $data_pinjaman = $this->Pinjaman_model->get_data_pinjam($id);
    $data_anggota  = $this->Anggota_model->get_by('id', $data_pinjaman->anggota_id);
    $data_angsuran = $this->Angsuran_model->get_data_angsuran($data_pinjaman->id);

    $hitung_denda   = $this->Pinjaman_model->get_jml_denda($data_pinjaman->id);
		$hitung_dibayar = $this->Pinjaman_model->get_jml_bayar($data_pinjaman->id);
		$sisa_ags       = $this->Pinjaman_model->get_record_bayar($data_pinjaman->id);

    $tgl_bayar = explode(' ', $data_pinjaman->tgl_pinjam);
		$txt_tanggal = jin_date_ina($tgl_bayar[0]);

		$tgl_tempo = explode(' ', $data_pinjaman->tempo);
		$tgl_tempo = jin_date_ina($tgl_tempo[0]);

    $pdf = new fpdf('P', 'mm', 'A4');
    $pdf->SetMargins(5,5,5);
    $pdf->addPage();

    $pdf->SetFont('Arial','B',15);

    $pdf->Image(base_url().'assets\img\logo.png',5,5, -200);

    $pdf->cell(22, 0, '', 0, 0);
    $pdf->cell(247, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

    $pdf->SetFont('Arial','', 9);
    $pdf->cell(22, 0, '', 0, 0);
    $pdf->cell(247, 5, 'Jl. Dr. Suharso No.52 Purwokerto', 0, 1);

    $pdf->cell(22, 0, '', 0, 0);
    $pdf->cell(247, 5, 'Tel.0281-632324 Email : ksu_sakrawarih@gmail.com', 0, 1);

    $pdf->cell(22, 0, '', 0, 0);
    $pdf->cell(247, 5, 'Web : -', 0, 1);

    $pdf->Line(5, 26, 205, 26);

    $pdf->SetFont('Arial','B', 12);
    $pdf->cell(200, 10, 'Detail Transaksi Pembayaran Kredit', 0, 1, 'C');

    $pdf->SetFont('Arial','', 9);

    $pdf->cell(25, 5, 'ID Anggota', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $data_anggota['identitas'], 0, 0);
    $pdf->cell(30, 5, 'Pokok Pinjaman', 0, 0);
    $pdf->cell(10, 5,  ': Rp', 0, 0);
    $pdf->cell(22, 5, rupiah($data_pinjaman->jumlah), 0, 1, 'R');

    $pdf->cell(25, 5, 'Nama Anggota', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->SetFont('Arial','B', 9);
    $pdf->cell(107, 5, strtoupper($data_anggota['nama']), 0, 0);
    $pdf->SetFont('Arial','', 9);
    $pdf->cell(30, 5, 'Angsuran Pokok', 0, 0);
    $pdf->cell(10, 5,  ': Rp', 0, 0);
    $pdf->cell(22, 5, rupiah($data_pinjaman->pokok_angsuran), 0, 1, 'R');

    $pdf->cell(25, 5, 'Dept', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $data_anggota['departement'], 0, 0);
    $pdf->cell(30, 5, 'Biaya Admin', 0, 0);
    $pdf->cell(10, 5,  ': Rp', 0, 0);
    $pdf->cell(22, 5, rupiah($data_pinjaman->biaya_adm), 0, 1, 'R');

    $pdf->cell(25, 5, 'Alamat', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $data_anggota['alamat'], 0, 0);
    $pdf->cell(30, 5, 'Angsuran Bunga', 0, 0);
    $pdf->cell(10, 5,  ': Rp', 0, 0);
    $pdf->cell(22, 5, rupiah($data_pinjaman->bunga_pinjaman), 0, 1, 'R');

    $pdf->cell(25, 5, 'Nomor Pinjam', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, 'TPJ'.sprintf('%05d', $data_pinjaman->id), 0, 0);
    $pdf->cell(30, 5, 'Jumlah Angsuran', 0, 0);
    $pdf->cell(10, 5,  ': Rp', 0, 0);
    $pdf->cell(22, 5, rupiah(nsi_round($data_pinjaman->ags_per_bulan)), 0, 1, 'R');

    $pdf->cell(25, 5, 'Tanggal Pinjam', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $txt_tanggal, 0, 1);

    $pdf->cell(25, 5, 'Tanggal Tempo', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $tgl_tempo, 0, 1);

    $pdf->cell(25, 5, 'Lama Pinjam', 0, 0);
    $pdf->cell(5, 5, ':', 0, 0);
    $pdf->cell(107, 5, $data_pinjaman->lama_angsuran." Bulan", 0, 1);

    $pdf->SetFont('Arial','B', 9);

    $pdf->Ln();

    $pdf->cell(50, 5, 'Detail Pembayaran ', 0, 1);

    $tagihan       = $data_pinjaman->ags_per_bulan * $data_pinjaman->lama_angsuran;
		$dibayar       = $hitung_dibayar->total;
		$jml_denda     = $hitung_denda->total_denda;
		$sisa_bayar    = $tagihan - $dibayar;
		$total_bayar   = $sisa_bayar + $jml_denda;
		$sisa_angsuran = $data_pinjaman->lama_angsuran - $sisa_ags;

    // $pdf->Ln();
    $pdf->SetFont('Arial','', 9);
    $pdf->cell(30, 5, 'Total Pinjaman', 0, 0);
    $pdf->cell(40, 5, rupiah(nsi_round($tagihan)), 0, 0, 'R');
    $pdf->cell(50, 5, 'Status Lunas : ', 0, 0, 'R');
    $pdf->cell(22, 5, $data_pinjaman->lunas , 0, 1);

    $pdf->cell(30, 5, 'Total Denda', 0, 0);
    $pdf->cell(40, 5, rupiah(nsi_round($jml_denda)), 0, 1, 'R');

    $pdf->cell(30, 5, 'Total Tagihan', 0, 0);
    $pdf->cell(40, 5, rupiah(nsi_round($tagihan + $jml_denda)), 0, 1, 'R');

    $pdf->cell(30, 5, 'Sudah Dibayar', 0, 0);
    $pdf->cell(40, 5, rupiah(nsi_round($dibayar)), 0, 1, 'R');

    $pdf->cell(30, 5, 'Sisa Tagihan', 0, 0);
    $pdf->cell(40, 5, rupiah(nsi_round($total_bayar )), 0, 1, 'R');

    $simulasi_tagihan = $this->Pinjaman_model->get_simulasi_pinjaman($id);

    $pdf->SetFont('Arial','B', 9);
    $pdf->Ln();
    $pdf->cell(50, 5, 'Simulasi Tagihan', 0, 1);

    $pdf->SetFont('Arial','B', 8);
    $pdf->SetFillColor(210,221,242);
    $pdf->cell(20, 5, 'Bln ke', 0, 0, 'C', true);
    $pdf->cell(35, 5, 'Angsuran Pokok', 0, 0, 'C', true);
    $pdf->cell(35, 5, 'Angsuran Bunga', 0, 0, 'C', true);
    $pdf->cell(35, 5, 'Biaya Adm', 0, 0, 'C', true);
    $pdf->cell(35, 5, 'Jumlah Angsuran', 0, 0, 'C', true);
    $pdf->cell(40, 5, 'Tanggal Tempo', 0, 1, 'C', true);
    $pdf->SetFont('Arial','', 8);
    $pdf->SetFillColor(255,255,255);
    $no = 1;
			$row = array();
			$jml_pokok = 0;
			$jml_bunga = 0;
			$jml_ags = 0;
			$jml_adm = 0;
			foreach ($simulasi_tagihan as $row) {

				$txt_tanggal = jin_date_ina($row['tgl_tempo']);
				$jml_pokok += $row['angsuran_pokok'];
				$jml_bunga += $row['bunga_pinjaman'];
				$jml_adm += $row['biaya_adm'];
				$jml_ags += $row['jumlah_ags'];

        $pdf->cell(20, 5, $no, 0, 0, 'C');
        $pdf->cell(35, 5, rupiah(nsi_round($row['angsuran_pokok'])), 0, 0, 'R');
        $pdf->cell(35, 5, rupiah(nsi_round($row['bunga_pinjaman'])), 0, 0, 'R');
        $pdf->cell(35, 5, rupiah(nsi_round($row['biaya_adm'])), 0, 0, 'R');
        $pdf->cell(35, 5, rupiah(nsi_round($row['jumlah_ags'])), 0, 0, 'R');
        $pdf->cell(40, 5, $txt_tanggal, 0, 1, 'R');
				$no++;
			}
      $pdf->SetFillColor(210,221,242);
      $pdf->SetFont('Arial','B', 8);
      $pdf->cell(20, 5, 'JUMLAH', 0, 0, 'C', true);
      $pdf->cell(35, 5, rupiah(nsi_round($jml_pokok)), 0, 0, 'R', true);
      $pdf->cell(35, 5, rupiah(nsi_round($jml_bunga)), 0, 0, 'R', true);
      $pdf->cell(35, 5, rupiah(nsi_round($jml_adm)), 0, 0, 'R', true);
      $pdf->cell(35, 5, rupiah(nsi_round($jml_ags)), 0, 0, 'R', true);
      $pdf->cell(40, 5, '', 0, 1, 'R', true);

      $pdf->SetFont('Arial','B', 9);
      $pdf->Ln();
      $pdf->cell(50, 5, 'Data Pembayaran', 0, 1);

      if(!empty($data_angsuran)){
        $pdf->SetFont('Arial','B', 8);
        $pdf->SetFillColor(210,221,242);
        $pdf->cell(10, 5, 'No.', 0, 0, 'C', true);
        $pdf->cell(25, 5, 'Kode Bayar', 0, 0, 'C', true);
        $pdf->cell(35, 5, 'Tanggal Bayar', 0, 0, 'C', true);
        $pdf->cell(25, 5, 'Angsuran Ke', 0, 0, 'C', true);
        $pdf->cell(35, 5, 'Jenis Pembayaran', 0, 0, 'C', true);
        $pdf->cell(35, 5, 'Jumlah Bayar', 0, 0, 'C', true);
        $pdf->cell(35, 5, 'Denda', 0, 1, 'C', true);

        $no=1;
  			$jml_tot = 0;
  			$jml_denda = 0;

        foreach ($data_angsuran as $rows) {
          $tgl_bayar      = explode(' ', $rows->tgl_bayar);
  				$txt_tanggal    = jin_date_ina($tgl_bayar[0],'p');
  				$jml_tot        += $rows->jumlah_bayar;
  				$jml_denda      += $rows->denda_rp;

          $pdf->SetFont('Arial','', 8);
          $pdf->SetFillColor(255,255,255);
          $pdf->cell(10, 5, $no, 0, 0, 'C', true);
          $pdf->cell(25, 5, 'TBY'.sprintf('%05d',$rows->id), 0, 0, 'C', true);
          $pdf->cell(35, 5, $txt_tanggal, 0, 0, 'C', true);
          $pdf->cell(25, 5, $rows->angsuran_ke, 0, 0, 'C', true);
          $pdf->cell(35, 5, $rows->ket_bayar, 0, 0, 'C', true);
          $pdf->cell(35, 5, rupiah(nsi_round($rows->jumlah_bayar)), 0, 0, 'R', true);
          $pdf->cell(35, 5, rupiah(nsi_round($rows->denda_rp)), 0, 1, 'R', true);
  				$no++;
  			}

        $pdf->SetFont('Arial','B', 8);
        $pdf->SetFillColor(210,221,242);
        $pdf->cell(130, 5, 'Jumlah', 0, 0, 'C', true);
        $pdf->cell(35, 5, rupiah(nsi_round($jml_tot)), 0, 0, 'R', true);
        $pdf->cell(35, 5, rupiah(nsi_round($jml_denda)), 0, 0, 'R', true);

      } else {
        $pdf->SetFont('Arial','I', 9);
        $pdf->cell(50, 5, 'Tidak Ada Data Transkasi Pembayaran', 0, 1);
      }

    $pdf->Output();
  }


}
