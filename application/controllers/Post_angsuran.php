<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require('./excel/vendor/autoload.php');
//
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Post_angsuran extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Post_angsuran_model', 'Anggota_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/post_angsuran/view_post_angsuran');
  }

  public function saveDataAgsTemp(){
    $status = $this->Post_angsuran_model->save_data_ags_temp();
    echo json_encode($status);
  }

  public function data_post_angsuran($post_id = ""){
    if($post_id === ""){
      $data = $this->Post_angsuran_model->get_data_post_angsuran()->result();
    } else {
      $data = $this->Post_angsuran_model->get_data_post_angsuran($post_id)->result();
    }

    // echo "<pre>";
    // print_r($data);
    //
    // echo "<hr?";

    if($data){
      $i=1;
      echo "
      <table id='table_post_angsuran' class='h-100 table table-hover table-striped table-sm small' style='width:100%; font-size: 11px;'>
      <thead class=''>
      <tr>
      <th >No </th>
      <th >Aksi </th>
      <th >Nama </th>
      <th >Sisa Pinjaman </th>
      <th >Ke </th>
      <th >Pokok </th>
      <th >Bunga </th>
      <th >Jumlah Angsuran </th>
      <th >Lainnya </th>
      <th >Keterangan </th>
      </tr>
      </thead>
      <tbody> ";
      foreach ($data as $r) {
        $sisa_pinjaman = ($r->jumlah - ($r->ags_per_bulan * $r->bln_sudah_angsur));
        $ags_ke = ($r->bln_sudah_angsur)+1;
        echo "<tr title='# ".$r->view_client."'>";
        echo "<td >".$i."</td>";
        echo "<td >"."<button class='btn btn-sm btn-outline-danger' onclick='deleteDataAgsTemp(\"".$r->nama."\", ".$r->id_anggota.", ".$r->id_pinjaman.", \"".$r->view_client."\")'><b class='fa fa-trash'></b></button>"."</td>";
        echo "<td >".$r->nama."</td>";
        echo "<td style='text-align: right'>".rupiah($sisa_pinjaman)."</td>";
        echo "<td >".$ags_ke."</td>";
        echo "<td style='text-align: right'>".rupiah($r->pokok_angsuran)."</td>";
        echo "<td style='text-align: right'>".rupiah($r->bunga_pinjaman)."</td>";
        echo "<td style='text-align: right'>".rupiah($r->ags_per_bulan)."</td>";
        echo "<td >"."-"."</td>";
        echo "<td >"."-"."</td>";
        echo "</tr>";
        $i++;
      }
      echo "</tbody></table>";
    } else {
      echo "No Data";
    }
  }

  public function del_post_angsuran(){
    $id = $_POST["param"][0];
    $kd = $_POST["param"][1];
    $post_id = $_POST["param"][2];
    if($this->Post_angsuran_model->del_data_post_angsuran($id, $kd, $post_id)){
      $status = true;
    } else {
      $status = false;
    }
    $result = array('status' => $status, 'post' =>  $_POST );
    echo json_encode($result);
  }

  public function data_post_simpanan($post_id = null){
    if($post_id === null){
      $data = $this->Post_angsuran_model->get_data_post_simpanan()->result();
    } else {
      $data = $this->Post_angsuran_model->get_data_post_simpanan($post_id)->result();
    }

    if($data){
      $i=1;
      echo "
      <table id='table_post_simpanan' class='h-100 table table-hover table-striped table-sm small' style='width:100%; font-size: 11px;'>
      <thead class=''>
      <tr>
      <th >No </th>
      <th >Aksi </th>
      <th >Nama </th>
      <th >Simpanan Wajib </th>
      <th >Simpanan Sukarela </th>
      <th >Jumlah Simpanan </th>
      </tr>
      </thead>
      <tbody> ";
      foreach ($data as $r) {
        $sisa_pinjaman = ($r->jumlah - ($r->ags_per_bulan * $r->bln_sudah_angsur));
        $ags_ke = $r->bln_sudah_angsur + 1;
        echo "<tr title='# ".$r->view_client."'>";
        echo "<td >".$i."</td>";
        echo "<td >"."<button class='btn btn-sm btn-outline-danger' onclick='deleteDataSimpTemp(\"".$r->nama."\", ".$r->id_anggota.", ".$r->id_pinjaman.", \"".$r->view_client."\")'><b class='fa fa-trash'></b></button>"."</td>";
        echo "<td >".$r->nama."</td>";
        echo "<td >".rupiah($r->simpanan_wajib)."</td>";
        echo "<td >".rupiah($r->simpanan_sukarela)."</td>";
        echo "<td >".rupiah($r->simpanan_wajib + $r->simpanan_sukarela)."</td>";
        echo "</tr>";
        $i++;
      }
      echo "</tbody></table>";
    } else {
      echo "No Data";
    }
  }

  public function del_post_simpanan(){
    $id = $_POST["param"][0];
    $post_id = $_POST["param"][1];
    if($this->Post_angsuran_model->del_data_post_simpanan($id, $post_id)){
      $status = true;
    } else {
      $status = false;
    }
    $result = array('status' => $status, 'post' =>  $_POST );
    echo json_encode($result);
  }

  public function clear_angsuran(){
    echo "
    <table id='table_post_angsuran' class='h-100 table table-hover table-striped table-sm small' style='width:100%; font-size: 11px;'>
    <thead class=''>
    <tr>
    <th >No </th>
    <th >Aksi </th>
    <th >Nama </th>
    <th >Sisa Pinjaman </th>
    <th >Ke </th>
    <th >Pokok </th>
    <th >Bunga </th>
    <th >Jumlah Angsuran </th>
    <th >Lainnya </th>
    <th >Keterangan </th>
    </tr>
    </thead>
    <tbody> ";
    echo "</tbody></table>";
  }

  public function clear_simpanan(){
    echo "
    <table id='table_post_simpanan' class='h-100 table table-hover table-striped table-sm small' style='width:100%; font-size: 11px;'>
    <thead class=''>
    <tr>
    <th >No </th>
    <th >Aksi </th>
    <th >Nama </th>
    <th >Simpanan Wajib </th>
    <th >Simpanan Sukarela </th>
    <th >Jumlah Simpanan </th>
    </tr>
    </thead>
    <tbody> ";
    echo "</tbody></table>";
  }

  public function toExcel(){
    include APPPATH.'libraries/PHPExcel/PHPExcel.php';
    $session_report_id = base_convert(microtime(false), 8, 36);
    // $result_query = $this->Anggota_model->get_data()->result();
    // $data_size    = sizeof($result_query);
    $generated_date = date('Y-m-d H:i:s');

    if(!$this->update_view_client($session_report_id, $generated_date)){
      die;
    }

    $excel = new PHPExcel();							// Panggil class PHPExcel nya
    // Settingan awal fil excel
    $excel->getProperties()->setCreator('KSU SAKRAWARIH')
    ->setLastModifiedBy('Koperasi Online')
    ->setTitle("Daftar Potongan")
    ->setDescription("Export Data Potongan Bulanan")
    ->setKeywords("Daftar Potongan");
    // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
    $style_col = array(
      'font' => array('bold' => true), // Set font nya jadi bold
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ),
      'borders' => array(
        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
      )
    );
    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = array(
      'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ),
      'borders' => array(
        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
      )
    );

    $format_date = array(
      'numberformat' => array(
        'code' => 'DD/mm/YYYY',
      ),
    );

    $y = date('m/Y');
    $bulan = array (1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $split 	  = explode('/', $y);
  $tanggal = $bulan[(int)$split[0]] . ' ' . $split[1];

  $excel->setActiveSheetIndex(0)->setCellValue('A1', "KOPERASI SERBA USAHA");
  $excel->setActiveSheetIndex(0)->setCellValue('A2', "SAKRA  WARIH");
  // $excel->setActiveSheetIndex(0)->setCellValue('A3', "DAFTAR  POTONGAN  BULAN   : ");
  $excel->setActiveSheetIndex(0)->setCellValue('A3', "DAFTAR  POTONGAN  BULAN  : ".$tanggal);

  $excel->getActiveSheet()->mergeCells('A1:Q1');
  $excel->getActiveSheet()->mergeCells('A2:Q2');
  $excel->getActiveSheet()->mergeCells('A3:Q3');
  $excel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(TRUE); // Set bold kolom A1
  $excel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
  $excel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->applyFromArray($format_date); // Set text center untuk kolom A1

  $excel->setActiveSheetIndex(0)->setCellValue('C4', "Report ID: ".$session_report_id );
  $excel->setActiveSheetIndex(0)->setCellValue('C5', "This report generated at : ".$generated_date );

  // Buat header tabel nya pada baris ke 3
  // $excel->setActiveSheetIndex(0)->setCellValue('A6', "ID"); // Set kolom A3 dengan tulisan "NO"
  $excel->setActiveSheetIndex(0)->setCellValue('B6', "NO");
  $excel->setActiveSheetIndex(0)->setCellValue('C6', "NAMA");
  $excel->setActiveSheetIndex(0)->setCellValue('D6', "JUMLAH PINJAMAN");
  $excel->setActiveSheetIndex(0)->setCellValue('D8', "SISA  PINJ\n S/D BLN INI");
  $excel->setActiveSheetIndex(0)->setCellValue('E8', "");
  $excel->setActiveSheetIndex(0)->setCellValue('F8', "");
  $excel->setActiveSheetIndex(0)->setCellValue('G8', "P E M B A Y A R A N");
  $excel->setActiveSheetIndex(0)->setCellValue('G9', "KE");
  $excel->setActiveSheetIndex(0)->setCellValue('H9', "POKOK");
  $excel->setActiveSheetIndex(0)->setCellValue('I9', "BUNGA");
  $excel->setActiveSheetIndex(0)->setCellValue('J9', "JUMLAH");
  $excel->setActiveSheetIndex(0)->setCellValue('K6', "JUMLAH SIMPANAN");
  $excel->setActiveSheetIndex(0)->setCellValue('K8', "POKOK");
  $excel->setActiveSheetIndex(0)->setCellValue('L8', "WAJIB");
  $excel->setActiveSheetIndex(0)->setCellValue('M8', "SUKARELA");
  $excel->setActiveSheetIndex(0)->setCellValue('N8', "JUMLAH");
  $excel->setActiveSheetIndex(0)->setCellValue('O6', "LAIN - LAIN");
  $excel->setActiveSheetIndex(0)->setCellValue('P6', "JUMLAH");
  $excel->setActiveSheetIndex(0)->setCellValue('Q6', "KETERANGAN");
  // Apply style header yang telah kita buat tadi ke masing-masing kolom header
  // $excel->getActiveSheet()->getStyle('A6:A9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('B6:B9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('C6:C9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('D6:J7')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('D8:D9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('E8:E9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('F8:F9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('G8:J8')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('G9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('H9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('I9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('J9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('K6:N7')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('K8:K9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('L8:L9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('M8:M9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('N8:N9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('O6:O9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('P6:P9')->applyFromArray($style_col);
  $excel->getActiveSheet()->getStyle('Q6:Q9')->applyFromArray($style_col);
  $excel->getActiveSheet()->mergeCells('A6:A9');
  $excel->getActiveSheet()->mergeCells('B6:B9');
  $excel->getActiveSheet()->mergeCells('C6:C9');
  $excel->getActiveSheet()->mergeCells('D6:J7');
  $excel->getActiveSheet()->mergeCells('D8:D9');
  $excel->getActiveSheet()->mergeCells('E8:E9');
  $excel->getActiveSheet()->mergeCells('F8:F9');
  $excel->getActiveSheet()->mergeCells('G8:J8');
  $excel->getActiveSheet()->mergeCells('K6:N7');
  $excel->getActiveSheet()->mergeCells('K8:K9');
  $excel->getActiveSheet()->mergeCells('L8:L9');
  $excel->getActiveSheet()->mergeCells('M8:M9');
  $excel->getActiveSheet()->mergeCells('N8:N9');
  $excel->getActiveSheet()->mergeCells('O6:O9');
  $excel->getActiveSheet()->mergeCells('P6:P9');
  $excel->getActiveSheet()->mergeCells('Q6:Q9');
  // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
  $data_tmp = $this->Post_angsuran_model->get_tmp($session_report_id);
  $no = 1; // Untuk penomoran tabel, di awal set dengan 1
  $numrow = 10; // Set baris pertama untuk isi tabel adalah baris ke 4

  foreach($data_tmp as $data){ // Lakukan looping pada variabel siswa
    $jumSimpanan 	= ($data->simpanan_wajib) + ($data->simpanan_sukarela);
    $jumAll 		= ($data->ags_per_bulan) + ($jumSimpanan);
    $angsuran		= $data->bln_sudah_angsur;
    if ($angsuran != ''){
      $angsuranT = $angsuran+1;
    } else {
      $angsuranT = '';
    }

    // $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->id_anggota);
    $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $no);
    $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->nama);
    $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->tagihan);
    $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, '-');
    $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, '-');
    $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $angsuranT);
    $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data->pokok_angsuran);
    $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->bunga_pinjaman);
    $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data->ags_per_bulan);
    $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, '-');
    $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $data->simpanan_wajib);
    $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $data->simpanan_sukarela);
    $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $jumSimpanan);
    $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, '-');
    $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $jumAll);
    $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $data->keterangan);

    // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
    // $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);

    $no++; // Tambah 1 setiap kali looping
    $numrow++; // Tambah 1 setiap kali looping
  }
  // Set width kolom
  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(1); // Set width kolom A
  $excel->getActiveSheet()->getColumnDimension('B')->setWidth(5); // Set width kolom B
  $excel->getActiveSheet()->getColumnDimension('C')->setWidth(35); // Set width kolom C
  $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
  $excel->getActiveSheet()->getColumnDimension('E')->setVisible(false);
  $excel->getActiveSheet()->getColumnDimension('F')->setVisible(false);
  $excel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
  $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
  $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);

  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
  // Set orientasi kertas jadi LANDSCAPE
  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  // Set judul file excel nya
  $excel->getActiveSheet(0)->setTitle("Daftar Potongan");
  $excel->setActiveSheetIndex(0);
  // Proses file excel
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Daftar Potongan '.$tanggal.'.xlsx"');
  header('Cache-Control: max-age=0');

  $qrcode_img = $this->qrcode($session_report_id);

  $gdImage = imagecreatefrompng('uploads/barcode/'.$qrcode_img);

  $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
  $objDrawing->setName('Sample image');
  $objDrawing->setDescription('Sample image');
  $objDrawing->setImageResource($gdImage);
  $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
  $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
  $objDrawing->setHeight(75);
  $objDrawing->setCoordinates('C1');
  $objDrawing->setWorksheet($excel->getActiveSheet());

  $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $write->save('php://output');
}

// base_convert(microtime(false), 10, 36);

  function qrcode($code){
    $this->load->library('ciqrcode'); //pemanggilan library QR CODE

    $config['cacheable']    = true; //boolean, the default is true
    $config['cachedir']     = './uploads/'; //string, the default is application/cache/
    $config['errorlog']     = './uploads/'; //string, the default is application/logs/
    $config['imagedir']     = './uploads/barcode/'; //direktori penyimpanan qr code
    $config['quality']      = true; //boolean, the default is true
    $config['size']         = '1024'; //interger, the default is 1024
    $config['black']        = array(224,255,255); // array, default is array(255,255,255)
    $config['white']        = array(70,130,180); // array, default is array(0,0,0)
    $this->ciqrcode->initialize($config);

    $image_name = $code .'.png'; //buat name dari qr code sesuai dengan nim

    $params['data'] = $code; //data yang akan di jadikan QR CODE
    $params['level'] = 'H'; //H=High
    $params['size'] = 10;
    $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
    $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

    return $image_name;
  }

  function update_view_client($post_id, $date){
    $affected_row = $this->Post_angsuran_model->set_as_view_client($post_id, $date);
    if($affected_row > 0){
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function bulk_posting(){
    $post_id = $_POST['param'][0];
    $result = $this->Post_angsuran_model->insertPostingSimpanan($post_id);
    // $result = array('status' => TRUE );
    echo json_encode($result);
  }

  function tes($post_id){

  }

}
