<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Load library phpspreadsheet
require('./eksel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet
class Shiftkaryawan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Shift_karyawan_model', 'kode_shift_model', 'Log_presensi_model', 'Time_dim_model', 'Karyawan_model', 'Departemen_model', 'Jam_kerja_model', 'Toleransitelat_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
      redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') == '3'){
        redirect(base_url("user"));
    }

  }

  function index(){
    $data['departemen']  = $this->Departemen_model->get_data()->result();
    $data['shift']  = $this->kode_shift_model->get_data()->result();
    $this->template->load('Template', 'shiftkaryawan/view_shiftkaryawan', $data);
  }

  function load_data(){
    if($_POST['param'][0] == 'x'){
      $karyawan    = $this->Karyawan_model->get_data()->result();
    } else {
      $parameter = array('id_departemen' => $_POST['param'][0]);
      $karyawan    = $this->Karyawan_model->get_by($parameter)->result();
    }
    // $departemen  = $this->Departemen_model->get_data()->result();
    if($_POST['param'][1] == null && $_POST['param'][2] == null){
      $bulan = $this->Time_dim_model->get_current_day_month()->result();
    } else {
      $originalDate = $_POST['param'][1];
      $start_date = date("Y-m-d", strtotime($originalDate));

      $originalDate = $_POST['param'][2];
      $end_date = date("Y-m-d", strtotime($originalDate));

      $bulan = $this->Time_dim_model->get_date($start_date, $end_date)->result();
    }

    echo '<div class="table-wrap">
      <div class="table-responsive-xl main-table" >
        <table class="table table-striped table-sm small" style="max-width: 1500%" id="table">
          <thead class="text-center " style="background-color: #1fc2d6">
            <tr  >
            <th style="white-space: nowrap; width: 1%;" class="fixed-side" scope="col">Nama Karyawan \ Tanggal</th>';

              foreach ($bulan as $r) {
                $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;
                echo "<th $weekend >".$r->day."</th>";
              }

    echo '</tr>
          </thead>
          <tbody class="text-center">';

            foreach ($karyawan as $rr) {
              echo "<tr>";
              echo "<td style='white-space: nowrap; width: 1%;' class='fixed-side'>".$rr->nama_lengkap."</td>";

              foreach ($bulan as $r) {

                $data_log = $this->get_log_detail($rr->id, $r->db_date)->row_array();

                $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;

                $kode_shift = $this->kode_shift_model->get_by(array('id' => $data_log['id_shift']))->row_array();

                echo "<td $weekend > <button class='btn btn-sm btn-link' onclick='update(\"".$data_log['id']."\")'> ".$kode_shift['kode']."</button> </td>";

              }

              echo "</tr>";
            }

    echo '</tbody>
        </table>
      </div>
    </div>';
  }

  function get_log_detail($id_karyawan, $tanggal){
    $data = $this->Shift_karyawan_model->get_by(array('id_karyawan' => $id_karyawan, 'tanggal' => $tanggal));
    return $data;
  }

  function get_log_detail_2($id_karyawan, $start=null, $end=null){
    $where = "id_karyawan ='".$id_karyawan."' and tanggal >= '".$start."' and tanggal <= '".$end."'";
    $data = $this->Shift_karyawan_model->get_by($where);
    return $data;
  }

  public function validation(){
    $this->form_validation->set_rules('input_departemen' , 'Departemen' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_karyawan' , 'Nama Karyawan' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_tanggal_form' , 'Tanggal' , 'required', array('required' => 'Wajib isi'));
    $this->form_validation->set_rules('input_kode_shift' , 'Kode' , 'callback_validasi_pilih');

    if ($this->form_validation->run()) {
      $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
    } else {
      $array = array(
        'error' => true,

        'input_departemen_error_icon'     => form_error('input_departemen', '', ''),
        'input_karyawan_error_icon'       => form_error('input_karyawan', '', ''),
        'input_tanggal_form_error_detail'   => form_error('input_tanggal_form', '<b class="fa fa-warning"></b> ', ' '),
        'input_kode_shift_error_icon'     => form_error('input_kode_shift', '', ''),

      );
    }
    echo json_encode($array);
  }

  public function validation2(){
    $this->form_validation->set_rules('input_departemen_delete' , 'Departemen' , 'callback_validasi_pilih');
    // $this->form_validation->set_rules('input_karyawan_delete' , 'Nama Karyawan' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_tanggal_start_delete' , 'Tanggal' , 'required', array('required' => 'Wajib isi'));
    $this->form_validation->set_rules('input_tanggal_end_delete' , 'Tanggal' , 'required', array('required' => 'Wajib isi'));

    if ($this->form_validation->run()) {
      $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
    } else {
      $array = array(
        'error' => true,

        'input_departemen_delete_error_icon'     => form_error('input_departemen_delete', '', ''),
        // 'input_karyawan_delete_error_icon'       => form_error('input_karyawan_delete', '', ''),
        'input_tanggal_start_delete_error_detail'   => form_error('input_tanggal_start_delete', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_end_delete_error_detail'   => form_error('input_tanggal_end_delete', '<b class="fa fa-warning"></b> ', ' '),

      );
    }
    echo json_encode($array);
  }

  public function validasi_pilih($str){
    if ($str == 'x') {
      $this->form_validation->set_message('validasi_pilih', 'Silahkan Pilih Salah Satu');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function ss(){
    $existing = $this->Shift_karyawan_model->get_data(['id_karyawan' => '2fj864jigbokk', 'tanggal'=> '2020-08-30'])->row_array();
    echo "<pre>";
    print_r($existing);
  }

  function insert(){
    $originalDate = $this->input->post('input_tanggal_form', true);
    $tanggal = date("Y-m-d", strtotime($originalDate));

    $existing = $this->Shift_karyawan_model->get_by(['id_karyawan' => $this->input->post('input_karyawan', true), 'tanggal'=>$tanggal]);
    if(!empty($existing->row_array())){
      $result = ['status' => false, 'message' => 'Data sudah ada '];
    } else {
      $object = [
        'id_shift'          => $this->input->post('input_kode_shift', true),
        'id_karyawan'       => $this->input->post('input_karyawan', true),
        'id_departemen'     => $this->input->post('input_departemen', true),
        'tanggal'           => $tanggal,
        'input_by'          => $this->session->userdata('username'),
        'input_datetime'    => date('Y-m-d H:i:s'),
        'is_del'            => 0,
        'client_id'         => $this->session->userdata('client_id')
      ];
      $result = $this->Shift_karyawan_model->save($object);
      $result = ['status' => true, 'message' => 'Sukses'];
    }
    echo json_encode($result);
  }

  function update(){
    $originalDate = $this->input->post('input_tanggal_form', true);
    $tanggal = date("Y-m-d", strtotime($originalDate));

    $object = [
      'id_shift'          => $this->input->post('input_kode_shift', true)
    ];

    $where = ['id'=>$this->input->post('id', true)];
    $result = $this->Shift_karyawan_model->update($object, $where);
    $result = ['status' => true, 'message' => 'Sukses'];

    echo json_encode($result);
  }

  function delete($id){
    $aff = $this->Shift_karyawan_model->delete($id);
    $result = ['status' => true, 'message' => 'Hapus Sukses'];
    echo json_encode($result);
  }

  function dummy_data(){

    $data_tanggal = $this->Time_dim_model->get_current_day_month()->result();
    $data_karyawan = $this->Karyawan_model->get_data()->result();
    $insert_data = array();
    foreach ($data_tanggal as $r) {
      $tanggal = $r->id;

      if($r->day_name !== "Saturday" && $r->day_name !== "Sunday"){

        foreach ($data_karyawan as $rr) {
          $insert_data[] = array(
                            'id_shift'          => 3,
                            'id_karyawan'       => $rr->id,
                            'id_departemen'     => $rr->id_departemen,
                            'tanggal'           => $tanggal,
                            'input_by'          => $this->session->userdata('username'),
                            'input_datetime'    => date('Y-m-d H:i:s'),
                            'is_del'            => 0,
                            'client_id'         => $this->session->userdata('client_id')
          );
        }
      }

    }

    // echo "<pre>";
    // print_r($insert_data);

    $result = $this->Shift_karyawan_model->save_batch($insert_data);
    echo "total inserted ".$result;

  }

  function download_template($id_dept="", $start="", $end=""){

    $style_header = [
      'font' => [
        'bold' => true,
      ]
    ];

    $style_judul = array(
      'alignment' => array(
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
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


    if($id_dept == 'x'){
      $karyawan    = $this->Karyawan_model->get_data()->result();
    } else {
      $parameter = array('id_departemen' => $id_dept );
      $karyawan    = $this->Karyawan_model->get_by($parameter)->result();
    }

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

    $dept = $this->Departemen_model->get_by(['id' => $id_dept])->row_array();

    //TITLE HEADER

    if( $start == null && $end == null){
      $bulan = $this->Time_dim_model->get_current_day_month()->result();
      $file_name = ($dept['nama_departemen'] == "") ? "Form Upload Shift Dept. All Departemen" : 'Form Upload Shift Dept. '.$dept['nama_departemen'] ;
      $file_name .= ' Periode '.date('m')."-".date('Y');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $file_name);
    } else {
      $originalDate = $start;
      $start_date = date("Y-m-d", strtotime($originalDate));

      $originalDate = $end;
      $end_date = date("Y-m-d", strtotime($originalDate));

      $bulan = $this->Time_dim_model->get_date($start_date, $end_date)->result();
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'Periode '. formatTglIndo($start_date) . ' s/d ' . formatTglIndo($end_date));
      $file_name = 'Form Upload Shift Dept. '.$dept['nama_departemen'].' Periode '. formatTglIndo($start_date) . ' s.d ' . formatTglIndo($end_date);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $file_name);
    }
    $spreadsheet->getActiveSheet()->getStyle("A2")->applyFromArray($style_header);

    // Add some data
    $row_number = 5;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row_number , 'No');
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $row_number , 'NIK');
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $row_number , 'Nama Karyawan \ Tanggal');

    foreach ($bulan as $key => $value) {
      $column = $this->getNameFromNumber($key + 3);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $row_number , $value->db_date);
    }

    $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
    $spreadsheet->getActiveSheet()->mergeCells("A3:G3");

    $spreadsheet->getActiveSheet()->getStyle("A2:".$column."2")->applyFromArray($style_judul);
    $spreadsheet->getActiveSheet()->getStyle("A3:".$column."3")->applyFromArray($style_judul);


    $spreadsheet->getActiveSheet()->getStyle("A" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("B" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("C" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

    foreach ($bulan as $key => $value) {
      $column = $this->getNameFromNumber($key + 3);
      $spreadsheet->getActiveSheet()->getStyle($column . $row_number)->applyFromArray($data_style_header);
      $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
    }
    // Miscellaneous glyphs, UTF-8

    $i = $row_number + 1;
    $n = 1;
    foreach ($karyawan as $key => $r) {

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $n)->setCellValue('B' . $i, $r->nip)->setCellValue('C' . $i, $r->nama_lengkap);

      //STYLING
      $max_row_index = $i;
      $row_number = $row_number + 1;

      $spreadsheet->getActiveSheet()->getStyle('A' . $i . ":".'A'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('B' . $i . ":".'B'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('C' . $i . ":".'C'. $max_row_index . "")->applyFromArray($data_style);

      foreach ($bulan as $key => $value) {
        $column = $this->getNameFromNumber($key + 3);
        $spreadsheet->getActiveSheet()->getStyle($column . $row_number . ":".$column. $max_row_index . "")->applyFromArray($data_style);
        $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
      }

      $i++;
      $n++;
    }

    $max_row = 5 + 1 + count($karyawan);

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'This report generated at ' . Date('d-m-Y H:i:s'));
    $spreadsheet->getActiveSheet()->getStyle("A" . $max_row)->getFont()->setSize(8);

    $max_row = 5 + 3 + count($karyawan);

    $data_style_header_2 = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      ]
    ];

    $spreadsheet->getActiveSheet()->getStyle("A" . $max_row)->applyFromArray($data_style_header_2);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'Petunjuk Pengisian: ');
    $max_row = $max_row + 1;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'Isi kotak kosong dengan KODE SHIFT karyawan');

    $max_row = $max_row + 1;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'Jika tidak ada jadwal shift maka kosongkan saja');

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Form Upload Shift');

    //sheet 2 -------------------------------------------------------------------------------------------------
    // Create a new worksheet called "My Data"
    $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Kode Shift');

    // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
    $spreadsheet->addSheet($myWorkSheet);
    $row_number = 1;
    $spreadsheet->setActiveSheetIndex(1)->setCellValue('A' . $row_number , 'Kode');
    $spreadsheet->setActiveSheetIndex(1)->setCellValue('B' . $row_number , 'Nama Shift');
    $spreadsheet->setActiveSheetIndex(1)->setCellValue('C' . $row_number , 'Jenis Shift');
    $spreadsheet->setActiveSheetIndex(1)->setCellValue('D' . $row_number , 'Jam Masuk');
    $spreadsheet->setActiveSheetIndex(1)->setCellValue('E' . $row_number , 'Jam Pulang');


    $spreadsheet->getActiveSheet()->getStyle("A" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("B" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("C" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("D" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("E" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    $kode_shift = $this->kode_shift_model->get_data()->result();

    $i = $row_number + 1;
    $n = 1;
    foreach ($kode_shift as $key => $r) {

      $spreadsheet->setActiveSheetIndex(1)
      ->setCellValue('A' . $i, $r->kode)
      ->setCellValue('B' . $i, $r->nama_shift)
      ->setCellValue('C' . $i, ($r->jenis_shift == 1) ? "Masuk" : "Libur")
      ->setCellValue('D' . $i, $r->jam_masuk)
      ->setCellValue('E' . $i, $r->jam_pulang)
      ;

      //STYLING
      $max_row_index = $i;
      $row_number = $row_number + 1;

      $spreadsheet->getActiveSheet()->getStyle('A' . $i . ":".'A'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('B' . $i . ":".'B'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('C' . $i . ":".'C'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('D' . $i . ":".'E'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('E' . $i . ":".'E'. $max_row_index . "")->applyFromArray($data_style);

      $i++;
      $n++;
    }

    // Redirect output to a client’s web browser (Xlsx)

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
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

  function getNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
      return $this->getNameFromNumber($num2 - 1) . $letter;
    } else {
      return $letter;
    }
  }


  function tes_(){
    include APPPATH.'third_party/PHPExcel/PHPExcel.php';
    $excelreader     = new PHPExcel_Reader_Excel2007();
    $loadexcel       = $excelreader->load('excel/65f30443cd3a6d7bd78d1585c823a89d.xlsx'); // Load file yang telah diupload ke folder excel
    // $loadexcel       = $excelreader->load('excel/137005b23b177be92d6fb345ba90ed45.xlsx'); // Load file yang telah diupload ke folder excel
    $sheet           = $loadexcel->getActiveSheet('Record')->toArray(null, true, true ,true);

    $data = array();
    $data_layer_2 = array();
    $data_layer_3 = array();
    $data_tanggal_layer_1 = array();
    $data_tanggal_layer_2 = array();
    $numrow = 1;

    foreach($sheet as $row){
      if($numrow > 5){
        if($row['B'] != ""){
          array_push($data_layer_2, array(
            'nip'              => $row['B'],
          ));
        }
      }
      $numrow++;
    }

    foreach ($sheet[5] as $key => $value) {
      array_push($data_tanggal_layer_1, $value);
    }

    foreach ($data_tanggal_layer_1 as $key => $value) {
      if($key >= 3){
        array_push($data_tanggal_layer_2, $value);
      }
    }

    foreach ($data_layer_2 as $key => $value) {
      foreach ($data_tanggal_layer_2 as $key2 => $value2) {
        array_push($data_layer_3, array(
          'index' =>$key2 + 3, 'nip' => $value['nip'], 'tanggal' => $value2
        ));
      }
    }

   $data_a = array();
   $data_b = array();
    $numrow = 1;
    foreach($sheet as $row ){
      if($numrow > 5){
        if($row['B'] != ""){
          array_push($data_a, $sheet[$numrow]);
        }
      }

      $numrow++;
    }

    foreach ($data_a as $key => $value) {
      array_push($data_b, array_values($data_a[$key]));
    }

    foreach ($data_layer_3 as $key => $value) {
      $a = $this->cariarray($data_b, 1, $value['nip']);
      $data_layer_3[$key]['kode'] = $data_b[$a][$value['index']];
      $karyawan_detail = $this->Karyawan_model->get_by(['nip' => $value['nip']] )->row_array();
      $data_layer_3[$key]['id_karyawan'] = $karyawan_detail['id'];
      $data_layer_3[$key]['id_departemen'] = $karyawan_detail['id_departemen'];

      $kode_shift = $this->kode_shift_model->get_by(['kode' => $data_b[$a][$value['index']]])->row_array();
      $data_layer_3[$key]['id_shift'] = $kode_shift['id'];

      $data_layer_3[$key]['input_by'] = $this->session->userdata('username');
      $data_layer_3[$key]['input_datetime'] = date('Y-m-d H:i:s');
      $data_layer_3[$key]['is_del'] = 0;
      $data_layer_3[$key]['client_id'] = $this->session->userdata('client_id');

      unset($data_layer_3[$key]['index']);
      unset($data_layer_3[$key]['nip']);
      unset($data_layer_3[$key]['kode']);
    }

    echo "<pre>";
    print_r($data_layer_3);
    die();
  }

  function cariarray($products, $field, $value){
     foreach($products as $key => $product)
     {
        if ( $product[$field] === $value )
           return $key;
     }
     return false;
  }

  function do_import(){
    include APPPATH.'third_party/PHPExcel/PHPExcel.php';
    $config['upload_path'] = realpath('excel');
    $config['allowed_types'] = 'xlsx|xls|csv';
    // $config['max_size'] = '10000';
    $config['encrypt_name'] = true;

    $this->load->library('upload', $config);
    if (!$this->upload->do_upload()) { //jika gagal import
      $result = array('status' => false, 'message' => $this->upload->display_errors());
      echo json_encode($result);
    } else {
      $data_upload = $this->upload->data();

      $excelreader     = new PHPExcel_Reader_Excel2007();
      $loadexcel       = $excelreader->load('excel/'.$data_upload['file_name']); // Load file yang telah diupload ke folder excel
      $sheet           = $loadexcel->getActiveSheet('Record')->toArray(null, true, true ,true);

      $data = array();
      $data_layer_2 = array();
      $data_layer_3 = array();
      $data_tanggal_layer_1 = array();
      $data_tanggal_layer_2 = array();
      $numrow = 1;

      foreach($sheet as $row){
        if($numrow > 5){
          if($row['B'] != ""){
            array_push($data_layer_2, array(
              'nip'              => $row['B'],
            ));
          }
        }
        $numrow++;
      }

      foreach ($sheet[5] as $key => $value) {
        array_push($data_tanggal_layer_1, $value);
      }

      foreach ($data_tanggal_layer_1 as $key => $value) {
        if($key >= 3){
          array_push($data_tanggal_layer_2, $value);
        }
      }

      foreach ($data_layer_2 as $key => $value) {
        foreach ($data_tanggal_layer_2 as $key2 => $value2) {
          array_push($data_layer_3, array(
            'index' =>$key2 + 3, 'nip' => $value['nip'], 'tanggal' => $value2
          ));
        }
      }

     $data_a = array();
     $data_b = array();
      $numrow = 1;
      foreach($sheet as $row ){
        if($numrow > 5){
          if($row['B'] != ""){
            array_push($data_a, $sheet[$numrow]);
          }
        }

        $numrow++;
      }

      foreach ($data_a as $key => $value) {
        array_push($data_b, array_values($data_a[$key]));
      }

      foreach ($data_layer_3 as $key => $value) {
        $a = $this->cariarray($data_b, 1, $value['nip']);
        $data_layer_3[$key]['kode'] = $data_b[$a][$value['index']];
        $karyawan_detail = $this->Karyawan_model->get_by(['nip' => $value['nip']] )->row_array();
        $data_layer_3[$key]['id_karyawan'] = $karyawan_detail['id'];
        $data_layer_3[$key]['id_departemen'] = $karyawan_detail['id_departemen'];

        $kode_shift = $this->kode_shift_model->get_by(['kode' => $data_b[$a][$value['index']]])->row_array();
        $data_layer_3[$key]['id_shift'] = $kode_shift['id'];

        $data_layer_3[$key]['input_by'] = $this->session->userdata('username');
        $data_layer_3[$key]['input_datetime'] = date('Y-m-d H:i:s');
        $data_layer_3[$key]['is_del'] = 0;
        $data_layer_3[$key]['client_id'] = $this->session->userdata('client_id');

        unset($data_layer_3[$key]['index']);
        unset($data_layer_3[$key]['nip']);
        unset($data_layer_3[$key]['kode']);
      }

      $this->Shift_karyawan_model->save_batch($data_layer_3);
      unlink(realpath('excel/'.$data_upload['file_name']));
      $result = array('status' => true);
      echo json_encode($result);
    }
  }

  function detail($id){
    $data = $this->Shift_karyawan_model->get_by(['id' => $id])->row_array();
    if($data['id_shift'] > 0){
      $originalDate = $data['tanggal'];
      $data['tanggal'] = date("d-m-Y", strtotime($originalDate));
      $data['nama'] = $this->Karyawan_model->get_by(['id' => $data['id_karyawan']])->row_array()['nama_lengkap'];
      echo json_encode($data);
    }
  }

  function delete_filter(){
    $originalDate = $this->input->post('input_tanggal_start_delete');
    $start_date = date("Y-m-d", strtotime($originalDate));

    $originalDate = $this->input->post('input_tanggal_end_delete');
    $end_date = date("Y-m-d", strtotime($originalDate));

    if($this->input->post('input_karyawan_delete') == 'x'){
        $where_sql = " id_departemen = '".$this->input->post('input_departemen_delete')."' and tanggal >= '".$start_date."' and tanggal <= '".$end_date."' ";
    } else {
        $where_sql = " id_departemen = '".$this->input->post('input_departemen_delete')."' and id_karyawan = '".$this->input->post('input_karyawan_delete')."'
        and tanggal >= '".$start_date."' and tanggal <= '".$end_date."' ";
    }
    $aff = $this->Shift_karyawan_model->delete_by($where_sql);
    $result = ['status' => true, 'message' => 'Hapus Sukses'];
    echo json_encode($result);
  }

}
