<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Load library phpspreadsheet
require('./eksel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet
class Export extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Karyawan_model', 'Indonesia_model', 'Login_model', 'Username_model',
    'Agama_model', 'Departemen_model', 'Jabatan_karyawan_model', 'Jabatan_model', 'Level_pendidikan_model', 'Riwayat_pendidikan_model',
    'Berkas_karyawan_model', 'Jenis_berkas_model', 'Keys_model', 'Time_dim_model', 'Log_presensi_model', 'Shift_karyawan_model', 'Kode_shift_model', 'Toleransitelat_model', 'Data_izin_model', 'Jenis_izin_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper', 'download'));

    if($this->session->userdata('status') !== 'loggedin'){
      redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }

  }

  function index(){
    redirect(base_url());
  }


  function karyawan(){

    $style_header = [
      'font' => [
        'bold' => true,
      ]
    ];

    $style_judul = array(
      'alignment' => array(
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      )
    );

    $data_style_header = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $data_style = [
      'font' => [
        'bold' => false,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Set document properties
    $spreadsheet->getProperties()->setCreator('Hamba Allah')
    ->setLastModifiedBy('-')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('File export data karyawan')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('File export data presensi');

    //TITLE HEADER
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Data Karyawan ');
    $spreadsheet->getActiveSheet()->getStyle("A2")->applyFromArray($style_header);

    // Add some data
    $row_number = 4;
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A' . $row_number , 'No')
    ->setCellValue('B' . $row_number , 'NIK')
    ->setCellValue('C' . $row_number , 'Nama')
    ->setCellValue('D' . $row_number , 'Jenis Kelamin')
    ->setCellValue('E' . $row_number , 'Tempat Lahir')
    ->setCellValue('F' . $row_number , 'Tanggal Lahir')
    ->setCellValue('G' . $row_number , 'Agama')
    ->setCellValue('H' . $row_number , 'No. Telp')
    ->setCellValue('I' . $row_number , 'Alamat')
    ->setCellValue('J' . $row_number , 'Kota')
    ->setCellValue('K' . $row_number , 'Provinsi')
    ->setCellValue('L' . $row_number , 'No. KTP')
    ->setCellValue('M' . $row_number , 'Alamat KTP')
    ->setCellValue('N' . $row_number , 'Golongan Darah')
    ->setCellValue('O' . $row_number , 'Status Kawin')
    ->setCellValue('P' . $row_number , 'E-mail')
    ->setCellValue('Q' . $row_number , 'Status Karyawan')
    ->setCellValue('R' . $row_number , 'Tanggal Masuk')
    ->setCellValue('S' . $row_number , 'Departemen')
    ->setCellValue('T' . $row_number , 'Gaji Pokok')
    ->setCellValue('U' . $row_number , 'Rekening')
    ->setCellValue('V' . $row_number , 'Status')
    ;

    $spreadsheet->getActiveSheet()->getStyle("A" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("B" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("C" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("D" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("E" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("F" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("G" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("H" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("I" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("J" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("K" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("L" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("M" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("N" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("O" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("P" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("Q" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("R" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("S" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("T" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("U" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("V" . $row_number)->applyFromArray($data_style_header);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);

    $data = $this->Karyawan_model->get_data()->result_array();

    $i = $row_number + 1;
    $n = 1;
    foreach ($data as $key => $r) {

      $tempat_lahir = $this->Indonesia_model->get_nama_kabupaten($r['tempat_lahir'])->row_array();
      $agama = $this->Agama_model->get_by(['id'=>$r['id_agama']])->row_array();

      $kota = $this->Indonesia_model->get_nama_kabupaten($r['id_kota'])->row_array();
      $provinsi = $this->Indonesia_model->get_nama_provinsi($r['id_provinsi'])->row_array();

      $object = array('1' => 'Tetap', '2' => 'Kontrak', '3' => 'Training', '4' => 'Magang');
      $status = $object[$r['status_karyawan']];

      $departemen = $this->Departemen_model->get_by(['id' => $r['id_departemen']])->row_array();

      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A' . $i, $n)
      ->setCellValue('B' . $i, $r['nip'])
      ->setCellValue('C' . $i, $r['nama_lengkap'])
      ->setCellValue('D' . $i, $r['jenis_kelamin'])
      ->setCellValue('E' . $i, $tempat_lahir['name'])
      ->setCellValue('F' . $i, formatTglIndo($r['tanggal_lahir']))
      ->setCellValue('G' . $i, $agama['nama_agama'])
      ->setCellValue('H' . $i, $r['no_telp'])
      ->setCellValue('I' . $i, $r['alamat'])
      ->setCellValue('J' . $i, $kota['name'])
      ->setCellValue('K' . $i, $provinsi['name'])
      ->setCellValue('L' . $i, $r['no_ktp'])
      ->setCellValue('M' . $i, $r['alamat_ktp'])
      ->setCellValue('N' . $i, $r['golongan_darah'])
      ->setCellValue('O' . $i, $r['status_kawin'])
      ->setCellValue('P' . $i, $r['email'])
      ->setCellValue('Q' . $i, $status)
      ->setCellValue('R' . $i, formatTglIndo($r['tanggal_masuk']))
      ->setCellValue('S' . $i, $departemen['nama_departemen'])
      ->setCellValue('T' . $i, $r['gaji_pokok'])
      ->setCellValue('U' . $i, $r['rekening'])
      ->setCellValue('V' . $i, ($r['is_active'] == 1) ? "Aktif" : "Non-aktif");

      //STYLING
      $max_row_index = $i;
      $row_number = $row_number + 1;

      $spreadsheet->getActiveSheet()->getStyle("A" . $row_number . ":A" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("B" . $row_number . ":B" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("C" . $row_number . ":C" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("D" . $row_number . ":D" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("E" . $row_number . ":E" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("F" . $row_number . ":F" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("G" . $row_number . ":G" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("H" . $row_number . ":H" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("I" . $row_number . ":I" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("J" . $row_number . ":J" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("K" . $row_number . ":K" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("L" . $row_number . ":L" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("M" . $row_number . ":M" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("N" . $row_number . ":N" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("O" . $row_number . ":O" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("P" . $row_number . ":P" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("Q" . $row_number . ":Q" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("R" . $row_number . ":R" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("S" . $row_number . ":S" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("T" . $row_number . ":T" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("U" . $row_number . ":U" . $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle("V" . $row_number . ":V" . $max_row_index . "")->applyFromArray($data_style);

      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);

      $i++;
      $n++;
    }
    //
    $max_row = 4 + 1 + count($data);
    //
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'This report generated at ' . Date('d-m-Y H:i:s'));
    // // $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
    $spreadsheet->getActiveSheet()->getStyle("A" . $max_row)->getFont()->setSize(8);

    // $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
    //
    // $spreadsheet->getActiveSheet()->getStyle("A2:M2")->applyFromArray($style_judul);

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Data Karyawan');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Karyawan.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
  }


}
