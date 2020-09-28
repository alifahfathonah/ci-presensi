<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load library phpspreadsheet
require('./eksel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet

class Kehadiran extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Log_presensi_model', 'Time_dim_model', 'Karyawan_model', 'Departemen_model',
    'Jam_kerja_model', 'Toleransitelat_model',  'Jenis_izin_model', 'Data_izin_model', 'Shift_karyawan_model',
    'Kode_shift_model'));
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
    $data['karyawan']    = $this->Karyawan_model->get_data()->result();
    $data['departemen']  = $this->Departemen_model->get_data()->result();
    $data['bulan']       = $this->Time_dim_model->get_current_day_month()->result();
    $this->template->load('Template', 'kehadiran/view_kehadiran', $data);
  }

  function load_data(){
    $total_hadir = 0;
    $total_hadir_terlambat = 0;
    $akumulasi_total_hadir_terlambat = 0;
    $total_alpha = 0;
    $total_izin = 0;
    $total_cuti = 0;
    $total_sakit = 0;
    $label_telat = "";

    if($_POST['param'][0] == 'x'){
      $karyawan    = $this->Karyawan_model->get_data()->result();
    } else {
      $parameter = array('id_departemen' => $_POST['param'][0]);
      $karyawan    = $this->Karyawan_model->get_by($parameter)->result();
    }

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
    <div class="table-responsive-xl main-table" id="table-kehadiran-container" >
    <table class="table table-striped table-sm small" style="max-width: 1500%" id="table">
    <thead class="text-center " style="background-color: #1fc2d6">
    <tr  >
    <th style="white-space: nowrap; width: 1%;" class="fixed-side" scope="col">Nama Karyawan \ Tanggal</th>';

    foreach ($bulan as $r) {
      $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;
      $label_weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "title='Weekend'" : "title='".$r->day_name."'" ;
      echo "<th $weekend $label_weekend >".$r->day." </th>";
    }
    echo '<th style="background-color: #ff6666" >Summary</th>';
    echo '</tr>
    </thead>
    <tbody class="text-center ">';

    foreach ($karyawan as $rr) {
      echo "<tr>";
      echo "<td style='white-space: nowrap; width: 1%;' class='fixed-side'><a href=".base_url('kehadiran/search/'.$rr->id).">".$rr->nama_lengkap."</a></td>";

      $H = 0;
      foreach ($bulan as $r) {

        $data_log = $this->get_log_detail($rr->id, $r->db_date)->row_array();
        $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;

        if($data_log['id'] != null ){

          if($data_log['id_izin'] == "-" || $data_log['id_izin'] == null){
            $shift_detail = $this->get_shift_detail($rr->id, $r->db_date);

            if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
              if($data_log['jam_masuk'] > $shift_detail['extra']){
                $text_label = 'HT';
                $text_label_color = 'success';
                $total_hadir_terlambat += 1;

                $akumulasi_total_hadir_terlambat += $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);

              } else {
                $text_label = 'H';
                $text_label_color = 'success';
              }
            } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
              $text_label = 'TAP';
              $text_label_color = 'warning';
            }

            $total_hadir += 1;

            echo "<td $weekend > <a role='button' target='_blank' href='".base_url('kehadiran/detail/'.$rr->id.'/'.$r->db_date)."'
            class='btn btn-sm btn-link text-".$text_label_color." ' style='font-size: 15px;'> ".$text_label." </a> </td>";
          } else {
            $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['id_izin']])->row_array();
            $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

            echo "<td $weekend ><a target='_blank' role='button' class='btn btn-sm btn-link text-warning' style='font-size: 15px;'
            href='".base_url('izin/edit/'.$data_log['id_izin'])."' > ".$nama_jenis_izin['kode']." </a></td>";

            if($nama_jenis_izin['kode'] == "I"){
              $total_izin += 1;
            } else if($nama_jenis_izin['kode'] == "C"){
              $total_cuti +=1;
            } else if($nama_jenis_izin['kode'] == "S"){
              $total_sakit +=1;
            }

          }

        } else {
          if($r->day_name == "Sunday" or $r->day_name == "Saturday"){
            echo "<td $weekend > </td>";
          } else {
            // if($nama_jenis_izin['kode'] != null){
            //   $data_result[$r]['jam_kerja'] = $shift_detail['nama_shift'];
            // } else {
            //   $data_result[$r]['jam_kerja'] = "Reguler";
            // }

            if($r->db_date < date('Y-m-d')){
              $total_alpha += 1;
              $text_label = 'A';
              $text_label_color = 'danger';
            }

            $total_alpha += 1;
            $text_label = 'A';
            $text_label_color = 'danger';
            echo "<td $weekend > <button class='btn btn-sm btn-link text-".$text_label_color." ' style='font-size: 15px;'> ".$text_label." </button> </td>";
          }
        }


        // $total_hadir++;
      }

      $hours = floor($akumulasi_total_hadir_terlambat / 3600);
      $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
      $seconds = $akumulasi_total_hadir_terlambat % 60;

      $label_telat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

      echo "<td>
      <table style='margin: 0; padding: 0'>
      <tr >
      <td style='margin: 0; padding: 0'>H: ".$total_hadir." hari &nbsp</td>
      <td style='margin: 0; padding: 0'>A: ".$total_alpha." hari &nbsp</td>
      <td style='margin: 0; padding: 0'>S: ".$total_sakit." hari &nbsp</td>
      </tr>
      <tr>
      <td style='margin: 0; padding: 0'>HT: ".$total_hadir_terlambat." hari, Total ".$label_telat." &nbsp</td>
      <td style='margin: 0; padding: 0'>I : ".$total_izin." hari &nbsp</td>
      <td style='margin: 0; padding: 0'>C: ".$total_cuti." hari &nbsp</td>
      </tr>
      </table>
      </td>";
      echo "</tr>";

      $total_hadir = 0;
      $total_hadir_terlambat = 0;
      $akumulasi_total_hadir_terlambat = 0;
      $total_alpha = 0;
      $total_izin = 0;
      $total_cuti = 0;
      $total_sakit = 0;
      $label_telat = "";
    }

    echo '</tbody>
    </table>
    </div>
    </div>';
  }

  function get_log_detail($id_karyawan, $tanggal){
    $data = $this->Log_presensi_model->get_by(array('id_karyawan' => $id_karyawan, 'tanggal' => $tanggal));
    return $data;
  }

  function get_shift_detail($id_karyawan, $tanggal){
    $data_shift   = $this->Shift_karyawan_model->get_by(array('id_karyawan' => $id_karyawan, 'tanggal' => $tanggal))->row_array();
    if(!empty($data_shift)){
      $detail_shift = $this->Kode_shift_model->get_by(['id' => $data_shift['id_shift']])->row_array();
      $toleransi_telat = $this->Toleransitelat_model->get_data()->row_array();
      $detail_shift['extra'] = sum_the_time($detail_shift['jam_masuk'], $toleransi_telat['extra']);
      return $detail_shift;
    } else {
      $date = $this->Time_dim_model->get_date($tanggal, $tanggal)->row_array();
      $toleransi_telat = $this->Toleransitelat_model->get_data()->row_array();
      $detail_dayhour  =$this->Jam_kerja_model->get_by(['nama_hari' => hari($date['day_name']) ])->row_array();
      $detail_dayhour['extra'] = sum_the_time($detail_dayhour['jam_masuk'], $toleransi_telat['extra']);
      $detail_dayhour['kode'] = null;
      return $detail_dayhour;
    }
  }

  function selisih_jam($start, $end){
    $waktu_awal   = strtotime($start);
    $waktu_akhir  = strtotime($end);
    $diff    =$waktu_akhir - $waktu_awal;
    $jam    =floor($diff / (60 * 60));
    $menit    =$diff - $jam * (60 * 60);
    return $diff;
  }

  function detail($id_karyawan = "", $tanggal = ""){
    if($id_karyawan == "" ||  $tanggal == ""){
      redirect('karyawan');
    } else {
      $data_log = $this->get_log_detail($id_karyawan, $tanggal)->row_array();
      
      if(!empty($data_log)){
          $data_karyawan = $this->Karyawan_model->get_by(['id' => $id_karyawan])->row_array();
          $data_dept = $this->Departemen_model->get_by(['id' => $data_karyawan['id_departemen'] ])->row_array();
    
          $shift_detail = $this->get_shift_detail($id_karyawan, $tanggal);
    
          if($shift_detail['kode'] != null){
            $data_log['jam_kerja'] = "Shift ".$shift_detail['nama_shift'];
          } else {
            $data_log['jam_kerja'] = "Reguler";
          }
    
          if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
            if($data_log['jam_masuk'] > $shift_detail['extra']){
              $status_kehadiran = 'Hadir Terlambat';
              $akumulasi_total_hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);
    
              $hours = floor($akumulasi_total_hadir_terlambat / 3600);
              $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
              $seconds = $akumulasi_total_hadir_terlambat % 60;
    
              $akumulasi_total_hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";
    
            } else {
              $status_kehadiran = 'Hadir';
              $akumulasi_total_hadir_terlambat = "";
            }
          } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
            if($data_log['jam_masuk'] > $shift_detail['extra']){
              $status_kehadiran = 'Hadir Terlambat';
              $akumulasi_total_hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);
    
              $hours = floor($akumulasi_total_hadir_terlambat / 3600);
              $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
              $seconds = $akumulasi_total_hadir_terlambat % 60;
    
              $akumulasi_total_hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";
    
            } else {
              $status_kehadiran = 'Hadir';
              $akumulasi_total_hadir_terlambat = "";
            }
            $status_kehadiran = 'Tidak Absen Pulang';
          }
    
          $data_log['terlambat'] = $akumulasi_total_hadir_terlambat;
          $data_log['status_kehadiran'] = $status_kehadiran;
          $data_log['nama_lengkap'] = $data_karyawan['nama_lengkap'];
          $data_log['nama_departemen'] = $data_dept['nama_departemen'];
    
          // echo "<pre>";
          // print_r($data_log);
          // die();
    
          $this->template->load('Template', 'kehadiran/view_kehadiran_detail', $data_log);   
      } else {
          redirect('kehadiran');
      }
    }
  }

  function byId($id_karyawan="", $bulan=""){
    if($id_karyawan == ""){
      redirect('kehadiran');
    } else {
      if($bulan == ""){
        $bulan = date('Y-m');
        $data_bulan = $this->Time_dim_model->get_current_day_month()->result();
      }

      $total_hadir = 0;
      $total_hadir_terlambat = 0;
      $akumulasi_total_hadir_terlambat = 0;

      $total_alpha = 0;
      $total_izin = 0;
      $total_cuti = 0;
      $total_sakit = 0;
      $label_telat = "";

      $data_bulan = $this->Time_dim_model->get_date($bulan."-1", $bulan."-31")->result();
      $H = 0;
      foreach ($data_bulan as $r) {

        $data_log = $this->get_log_detail($id_karyawan, $r->db_date)->row_array();
        $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;

        if($data_log['id'] != null){

          if($data_log['id_izin'] == "-" || $data_log['id_izin'] == null){
            $shift_detail = $this->get_shift_detail($id_karyawan, $r->db_date);

            if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
              if($data_log['jam_masuk'] > $shift_detail['extra']){
                $text_label = 'HT';
                $text_label_color = 'success';
                $total_hadir_terlambat += 1;

                $akumulasi_total_hadir_terlambat += $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);

              } else {
                $text_label = 'H';
                $text_label_color = 'success';
              }
            } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
              $text_label = 'TAP';
              $text_label_color = 'warning';
            }

            $total_hadir += 1;

          } else {
            $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['id_izin']])->row_array();
            $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

            if($nama_jenis_izin['kode'] == "I"){
              $total_izin += 1;
            } else if($nama_jenis_izin['kode'] == "C"){
              $total_cuti +=1;
            } else if($nama_jenis_izin['kode'] == "S"){
              $total_sakit +=1;
            }

          }

        } else {
          if($r->day_name == "Sunday" or $r->day_name == "Saturday"){
            echo "<td $weekend > </td>";
          } else {
            $total_alpha += 1;
            $text_label = 'A';
            $text_label_color = 'danger';
          }
        }

      }

      $hours = floor($akumulasi_total_hadir_terlambat / 3600);
      $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
      $seconds = $akumulasi_total_hadir_terlambat % 60;

      $label_telat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

      $bulan_ = explode("-", $bulan);
      $where = "id_karyawan = '".$id_karyawan."' and tanggal like '%".$bulan."%' order by tanggal asc ";
      $data_log['record']   = $this->Log_presensi_model->get_by($where)->result_array();
      $hadir_terlambat = 0;
      $akumulasi_total_hadir_terlambat_ = 0;

      foreach ($data_log['record'] as $k => $value) {
        $shift_detail = $this->get_shift_detail($value['id_karyawan'], $value['tanggal']);
        if($shift_detail['kode'] != null){
          $data_log['record'][$k]['jam_kerja'] = $shift_detail['nama_shift'];
        } else {
          $data_log['record'][$k]['jam_kerja'] = "Reguler";
        }

        if($value['jam_masuk'] != "" && $value['jam_pulang'] != "" ){
          if($value['jam_masuk'] > $shift_detail['extra']){
            $status_kehadiran = 'Hadir Terlambat 1';
            $hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $value['jam_masuk']);

            $hours = floor($hadir_terlambat / 3600);
            $minutes = floor(($hadir_terlambat / 60) % 60);
            $seconds = $hadir_terlambat % 60;

            $hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

          } else {
            $status_kehadiran = 'Hadir';
            $hadir_terlambat = "";
          }
        } else if($value['jam_masuk'] != "" && $value['jam_pulang'] == "" ){
          if($value['jam_masuk'] > $shift_detail['extra']){
            $status_kehadiran = 'Hadir Terlambat 2';
            $hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $value['jam_masuk']);

            $hours = floor($hadir_terlambat / 3600);
            $minutes = floor(($hadir_terlambat / 60) % 60);
            $seconds = $hadir_terlambat % 60;

            $hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

          } else {
            $status_kehadiran = 'Hadir';
            $hadir_terlambat = "";
          }
          $status_kehadiran = 'Tidak Absen Pulang';
        }

        if($data_log['record'][$k]['id_izin'] != "-"){
          $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['record'][$k]['id_izin']])->row_array();
          $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

          $status_kehadiran = $nama_jenis_izin['kode'];

        }

        $data_log['record'][$k]['terlambat'] = $hadir_terlambat;
        $data_log['record'][$k]['status_kehadiran'] = $status_kehadiran;

      }

      $data_karyawan = $this->Karyawan_model->get_by(['id' => $id_karyawan])->row_array();
      $data_dept = $this->Departemen_model->get_by(['id' => $data_karyawan['id_departemen'] ])->row_array();

      $data_log['total_hadir']   = $total_hadir;
      $data_log['total_hadir_terlambat']   = $label_telat ;
      $data_log['total_alpha']   = $total_alpha;
      $data_log['total_izin']   = $total_izin;
      $data_log['total_cuti']   = $total_cuti;
      $data_log['total_sakit']   = $total_sakit;
      $data_log['start']   = $total_sakit;
      $data_log['end']   = $total_sakit;


      $data_log['karyawan']   = $data_karyawan;
      $data_log['departemen'] = $data_dept;
      $data_log['periode']    = "Periode ".bulan_1($bulan_[1])." ".$bulan_[0];
      $this->template->load('Template', 'kehadiran/view_kehadiran_karyawan', $data_log);
      // echo "<pre>";
      // print_r($data_log['record']);
    }
  }

  function search($id_karyawan="", $start="", $end=""){
    if($id_karyawan == ""){
      redirect('kehadiran');
    } else {
      $start_date ="";
      $end_date ="";
      $data_result = array();
      if($start=="" && $end==""){
        $data_bulan = $this->Time_dim_model->get_day_month(date('m'), date('Y'));
        $where = "id_karyawan = '".$id_karyawan."' and tanggal like '%".date('Y').'-'.date('m')."%' order by tanggal asc ";

        $data['periode']    = "Periode ".date('m').'-'.date('Y');

      } else {
        $originalDate = $start;
        $start_date = date("Y-m-d", strtotime($originalDate));

        $originalDate = $end;
        $end_date = date("Y-m-d", strtotime($originalDate));

        $data_bulan = $this->Time_dim_model->get_date($start_date, $end_date);
        $where = "id_karyawan = '".$id_karyawan."' and tanggal >= '".$start_date."' and tanggal <= '".$end_date."' order by tanggal asc ";

        $data['periode']    = "Periode ".$start.' s.d '.$end;

      }
      // echo "<pre>";
      // print_r($data_bulan->result_array());
      // die();

      $total_hadir = 0;
      $total_hadir_terlambat = 0;
      $akumulasi_total_hadir_terlambat = 0;

      $total_alpha = 0;
      $total_izin = 0;
      $total_cuti = 0;
      $total_sakit = 0;
      $label_telat = "";
      $H = 0;

      foreach ($data_bulan->result() as $r => $value) {
        $data_result[]['tanggal'] = $value->db_date;
        $data_log = $this->get_log_detail($id_karyawan, $value->db_date)->row_array();
        $weekend = ($value->day_name == "Sunday" or $value->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;

        $data_result[$r]['hari'] = hari($value->day_name);
        $data_result[$r]['id_karyawan'] = $id_karyawan;
        $data_result[$r]['tanggal_absen'] = "";
        $data_result[$r]['status_kehadiran'] = "";
        $data_result[$r]['jam_masuk'] = "";
        $data_result[$r]['jam_pulang'] = "";
        $data_result[$r]['terlambat'] = "";
        $data_result[$r]['id_izin'] = "";

        $shift_detail = $this->get_shift_detail($id_karyawan, $value->db_date);

        if($data_log['id'] != null){
          $data_result[$r]['tanggal_absen'] = $data_log['tanggal'];

          if($shift_detail['kode'] != null){
            $data_result[$r]['jam_kerja'] = $shift_detail['nama_shift'];
          } else {
            $data_result[$r]['jam_kerja'] = "Reguler";
          }

          if($data_log['id_izin'] == "-" || $data_log['id_izin'] == null){

            if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
              $hadir_terlambat = 0;
              if($data_log['jam_masuk'] > $shift_detail['extra']){
                $data_result[$r]['status_kehadiran'] = 'Hadir (Terlambat)';
                $total_hadir_terlambat += 1;

                $hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);
                $akumulasi_total_hadir_terlambat += $hadir_terlambat;
                $hours = floor($hadir_terlambat / 3600);
                $minutes = floor(($hadir_terlambat / 60) % 60);
                $seconds = $hadir_terlambat % 60;

                $hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

                $data_result[$r]['terlambat'] = $hadir_terlambat;

              } else {
                $data_result[$r]['status_kehadiran'] = 'Hadir';
              }
            } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
              $data_result[$r]['status_kehadiran'] = 'Hadir (Tidak Absen Pulang)';
            }

            $total_hadir += 1;
            $data_result[$r]['jam_masuk'] = $data_log['jam_masuk'];
            $data_result[$r]['jam_pulang'] = $data_log['jam_pulang'];
          } else {
            $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['id_izin']])->row_array();
            $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

            if($nama_jenis_izin['kode'] == "I"){
              $data_result[$r]['status_kehadiran'] = 'I';
              $total_izin += 1;
            } else if($nama_jenis_izin['kode'] == "C"){
              $data_result[$r]['status_kehadiran'] = 'C';
              $total_cuti +=1;
            } else if($nama_jenis_izin['kode'] == "S"){
              $data_result[$r]['status_kehadiran'] = 'S';
              $total_sakit +=1;
            }
            $data_result[$r]['id_izin'] = $jenis_izin['id'];
            $data_result[$r]['jam_masuk'] = $data_log['jam_masuk'];
            $data_result[$r]['jam_pulang'] = $data_log['jam_pulang'];

          }

        } else {
          if($value->day_name == "Sunday" or $value->day_name == "Saturday"){
            $data_result[$r]['jam_kerja'] = "Libur";
          } else {
            if($shift_detail['kode'] != null){
              $data_result[$r]['jam_kerja'] = $shift_detail['nama_shift'];
            } else {
              $data_result[$r]['jam_kerja'] = "Reguler";
            }

            if($value->db_date < date('Y-m-d')){
              $data_result[$r]['status_kehadiran'] = 'A';
              $total_alpha += 1;
            }
          }
        }

      }

      // echo "<pre>";
      // print_r($data_result); die();

      $hours = floor($akumulasi_total_hadir_terlambat / 3600);
      $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
      $seconds = $akumulasi_total_hadir_terlambat % 60;

      $label_telat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";
      $data['record']   = $data_result;

      $data_karyawan = $this->Karyawan_model->get_by(['id' => $id_karyawan])->row_array();
      $data_dept = $this->Departemen_model->get_by(['id' => $data_karyawan['id_departemen'] ])->row_array();

      $data['total_hadir']   = $total_hadir;
      $data['total_hadir_terlambat']   = $label_telat ;
      $data['total_alpha']   = $total_alpha;
      $data['total_izin']   = $total_izin;
      $data['total_cuti']   = $total_cuti;
      $data['total_sakit']   = $total_sakit;

      $data['start']   = $start;
      $data['end']   = $end;
      $data['karyawan']   = $data_karyawan;
      $data['departemen'] = $data_dept;

      // echo "<pre>";
      // print_r($data); die();

      $this->template->load('Template', 'kehadiran/view_kehadiran_karyawan', $data);

    }
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

  function excel_dept($id_dept="", $start="", $end=""){

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

    $total_hadir = 0;
    $total_hadir_terlambat = 0;
    $akumulasi_total_hadir_terlambat = 0;
    $total_alpha = 0;
    $total_izin = 0;
    $total_cuti = 0;
    $total_sakit = 0;
    $label_telat = "";

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
      $file_name = ($dept['nama_departemen'] == "") ? "Laporan Kehadiran Dept. All Departemen" : 'Laporan Kehadiran Dept. '.$dept['nama_departemen'] ;
      $file_name .= ' Periode '.date('m')."-".date('Y');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $file_name);
    } else {
      $originalDate = $start;
      $start_date = date("Y-m-d", strtotime($originalDate));

      $originalDate = $end;
      $end_date = date("Y-m-d", strtotime($originalDate));

      $bulan = $this->Time_dim_model->get_date($start_date, $end_date)->result();
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'Periode '. formatTglIndo($start_date) . ' s/d ' . formatTglIndo($end_date));
      $file_name = 'Laporan Kehadiran Dept. '.$dept['nama_departemen'].' Periode '. formatTglIndo($start_date) . ' s.d ' . formatTglIndo($end_date);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $file_name);
    }
    $spreadsheet->getActiveSheet()->getStyle("A2")->applyFromArray($style_header);

    // Add some data
    $row_number = 5;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row_number , 'No');
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $row_number , 'Nama Karyawan \ Tanggal');

    foreach ($bulan as $key => $value) {
      $column = $this->getNameFromNumber($key + 2);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $row_number , $value->day);
    }
    $column = $this->getNameFromNumber(count($bulan) + 2);
    $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $row_number , 'Summary');

    $spreadsheet->getActiveSheet()->mergeCells("A2:".$column."2");
    $spreadsheet->getActiveSheet()->mergeCells("A3:".$column."3");

    $spreadsheet->getActiveSheet()->getStyle("A2:".$column."2")->applyFromArray($style_judul);
    $spreadsheet->getActiveSheet()->getStyle("A3:".$column."3")->applyFromArray($style_judul);


    $spreadsheet->getActiveSheet()->getStyle("A" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getStyle("B" . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

    foreach ($bulan as $key => $value) {
      $column = $this->getNameFromNumber($key + 2);
      $spreadsheet->getActiveSheet()->getStyle($column . $row_number)->applyFromArray($data_style_header);
      $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
    }
    $column = $this->getNameFromNumber(count($bulan) + 2);
    $spreadsheet->getActiveSheet()->getStyle($column . $row_number)->applyFromArray($data_style_header);
    $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);

    // Miscellaneous glyphs, UTF-8

    $i = $row_number + 1;
    $n = 1;
    foreach ($karyawan as $key => $r) {

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $n)->setCellValue('B' . $i, $r->nama_lengkap);

      //STYLING
      $max_row_index = $i;
      $row_number = $row_number + 1;

      $spreadsheet->getActiveSheet()->getStyle('A' . $i . ":".'A'. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getStyle('B' . $i . ":".'B'. $max_row_index . "")->applyFromArray($data_style);

      foreach ($bulan as $key => $value) {
        $column = $this->getNameFromNumber($key + 2);
        $spreadsheet->getActiveSheet()->getStyle($column . $row_number . ":".$column. $max_row_index . "")->applyFromArray($data_style);
        $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);

        $data_log = $this->get_log_detail($r->id, $value->db_date)->row_array();
        $weekend = ($value->day_name == "Sunday" or $value->day_name == "Saturday") ? "-" : "" ;
        if($data_log['id'] != null){
          if($data_log['id_izin'] == "-" || $data_log['id_izin'] == null){
            $shift_detail = $this->get_shift_detail($r->id, $value->db_date);

            if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
              if($data_log['jam_masuk'] > $shift_detail['extra']){
                $text_label = 'HT';
                $text_label_color = 'success';
                $total_hadir_terlambat += 1;

                $akumulasi_total_hadir_terlambat += $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);

              } else {
                $text_label = 'H';
                $text_label_color = 'success';
              }
            } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
              $text_label = 'TAP';
              $text_label_color = 'warning';
            }

            $total_hadir += 1;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $i, $text_label);
          } else {
            $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['id_izin']])->row_array();
            $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

            $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $i, $nama_jenis_izin['kode']);

            if($nama_jenis_izin['kode'] == "I"){
              $total_izin += 1;
            } else if($nama_jenis_izin['kode'] == "C"){
              $total_cuti +=1;
            } else if($nama_jenis_izin['kode'] == "S"){
              $total_sakit +=1;
            }

          }
        } else {
          if($value->day_name == "Sunday" or $value->day_name == "Saturday"){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $i, $weekend);
          } else {
            $total_alpha += 1;
            $text_label = 'A';
            $text_label_color = 'danger';
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $i, $text_label);
          }
        }
      }

      $hours = floor($akumulasi_total_hadir_terlambat / 3600);
      $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
      $seconds = $akumulasi_total_hadir_terlambat % 60;

      $label_telat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

      $out_summary  = "H: ".$total_hadir." hari | HT: ".$total_hadir_terlambat." hari, Total ".$label_telat." | S: ".$total_sakit." hari | A: ".$total_alpha." hari | I : ".$total_izin." hari | C: ".$total_cuti." hari";

      $column = $this->getNameFromNumber(count($bulan) + 2);
      $spreadsheet->getActiveSheet()->getStyle($column . $row_number . ":".$column. $max_row_index . "")->applyFromArray($data_style);
      $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue($column . $i, $out_summary);

      $total_hadir = 0;
      $total_hadir_terlambat = 0;
      $akumulasi_total_hadir_terlambat = 0;
      $total_alpha = 0;
      $total_izin = 0;
      $total_cuti = 0;
      $total_sakit = 0;
      $label_telat = "";

      $i++;
      $n++;
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Data Presensi');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)

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

  function excel($id_karyawan="", $start="", $end=""){
    if($start == "" || $end == ""){
      $start = '01-'.date('m').'-'.date('Y');
      $end = '31-'.date('m').'-'.date('Y');
    }

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

    $data_karyawan = $this->Karyawan_model->get_by(['id' => $id_karyawan])->row_array();
    $data_dept = $this->Departemen_model->get_by(['id' => $data_karyawan['id_departemen'] ])->row_array();

    $originalDate = $start;
    $start_date = date("Y-m-d", strtotime($originalDate));

    $originalDate = $end;
    $end_date = date("Y-m-d", strtotime($originalDate));

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
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Laporan Presensi '.$data_karyawan['nama_lengkap']);
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'Periode '. formatTglIndo($start_date) . ' s/d ' . formatTglIndo($end_date));
    $spreadsheet->getActiveSheet()->getStyle("A2")->applyFromArray($style_header);

    // Add some data
    $row_number = 5;
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A' . $row_number , 'No')
    ->setCellValue('B' . $row_number , 'NIK')
    ->setCellValue('C' . $row_number , 'Tanggal')
    ->setCellValue('D' . $row_number , 'Shifting')
    ->setCellValue('E' . $row_number , 'Jam Mulai')
    ->setCellValue('F' . $row_number , 'Lokasi Mulai')
    ->setCellValue('G' . $row_number , 'Keterangan Mulai')
    ->setCellValue('H' . $row_number , 'Jam Selesai')
    ->setCellValue('I' . $row_number , 'Lokasi Selesai')
    ->setCellValue('J' . $row_number , 'Keterangan Selesai')
    ->setCellValue('K' . $row_number , 'Status Presensi')
    ->setCellValue('L' . $row_number , 'Terlambat')
    ->setCellValue('M' . $row_number , 'Keterangan')
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

    // Miscellaneous glyphs, UTF-8

    $total_hadir = 0;
    $total_hadir_terlambat = 0;
    $akumulasi_total_hadir_terlambat = 0;

    $total_alpha = 0;
    $total_izin = 0;
    $total_cuti = 0;
    $total_sakit = 0;
    $label_telat = "";

    $data_bulan = $this->Time_dim_model->get_date($start_date, $end_date)->result();
    $H = 0;
    foreach ($data_bulan as $r) {

      $data_log = $this->get_log_detail($id_karyawan, $r->db_date)->row_array();
      // $weekend = ($r->day_name == "Sunday" or $r->day_name == "Saturday") ? "style='background-color: #faf0af'" : "" ;

      if($data_log['id'] != null){

        if($data_log['id_izin'] == "-" || $data_log['id_izin'] == null) {
          $shift_detail = $this->get_shift_detail($id_karyawan, $r->db_date);

          if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] != "" ){
            if($data_log['jam_masuk'] > $shift_detail['extra']){
              $text_label = 'HT';
              $text_label_color = 'success';
              $total_hadir_terlambat += 1;

              $akumulasi_total_hadir_terlambat += $this->selisih_jam($shift_detail['extra'], $data_log['jam_masuk']);

            } else {
              $text_label = 'H';
              $text_label_color = 'success';
            }
          } else if($data_log['jam_masuk'] != "" && $data_log['jam_pulang'] == "" ){
            $text_label = 'TAP';
            $text_label_color = 'warning';
          }

          $total_hadir += 1;

        } else {
          $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['id_izin']])->row_array();
          $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

          if($nama_jenis_izin['kode'] == "I"){
            $total_izin += 1;
          } else if($nama_jenis_izin['kode'] == "C"){
            $total_cuti +=1;
          } else if($nama_jenis_izin['kode'] == "S"){
            $total_sakit +=1;
          }

        }

      } else {
        if($r->day_name == "Sunday" or $r->day_name == "Saturday"){
          echo "";
        } else {
          $total_alpha += 1;
          $text_label = 'A';
          $text_label_color = 'danger';
        }
      }

    }

    $hours = floor($akumulasi_total_hadir_terlambat / 3600);
    $minutes = floor(($akumulasi_total_hadir_terlambat / 60) % 60);
    $seconds = $akumulasi_total_hadir_terlambat % 60;

    $label_telat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

    // $bulan_ = explode("-", $bulan);
    $where = "id_karyawan = '".$id_karyawan."' and tanggal >= '".$start_date."' and tanggal <= '".$end_date."' order by tanggal asc ";
    $data_log['record']   = $this->Log_presensi_model->get_by($where)->result_array();
    $hadir_terlambat = 0;
    $akumulasi_total_hadir_terlambat_ = 0;

    foreach ($data_log['record'] as $k => $value) {
      $shift_detail = $this->get_shift_detail($value['id_karyawan'], $value['tanggal']);
      if($shift_detail['kode'] != null){
        $data_log['record'][$k]['jam_kerja'] = $shift_detail['nama_shift'];
      } else {
        $data_log['record'][$k]['jam_kerja'] = "Reguler";
      }

      if($value['jam_masuk'] != "" && $value['jam_pulang'] != "" ){
        if($value['jam_masuk'] > $shift_detail['extra']){
          $status_kehadiran = 'Hadir Terlambat';
          $hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $value['jam_masuk']);

          $hours = floor($hadir_terlambat / 3600);
          $minutes = floor(($hadir_terlambat / 60) % 60);
          $seconds = $hadir_terlambat % 60;

          $hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

        } else {
          $status_kehadiran = 'Hadir';
          $hadir_terlambat = "";
        }
      } else if($value['jam_masuk'] != "" && $value['jam_pulang'] == "" ){
        if($value['jam_masuk'] > $shift_detail['extra']){
          $status_kehadiran = 'Hadir Terlambat';
          $hadir_terlambat = $this->selisih_jam($shift_detail['extra'], $value['jam_masuk']);

          $hours = floor($hadir_terlambat / 3600);
          $minutes = floor(($hadir_terlambat / 60) % 60);
          $seconds = $hadir_terlambat % 60;

          $hadir_terlambat = $hours." Jam ".$minutes." Menit ".$seconds." Detik";

        } else {
          $status_kehadiran = 'Hadir';
          $hadir_terlambat = "";
        }
        $status_kehadiran = 'Tidak Absen Pulang';
      }
      $nama_izin = "";
      if($data_log['record'][$k]['id_izin'] != "-"){
        $jenis_izin = $this->Data_izin_model->get_by(['id' => $data_log['record'][$k]['id_izin']])->row_array();
        $nama_jenis_izin = $this->Jenis_izin_model->get_by(['id' => $jenis_izin['id_jenis_izin'] ])->row_array();

        $status_kehadiran = $nama_jenis_izin['kode'];
        $nama_izin = $jenis_izin['keterangan'];
      }
      $data_log['record'][$k]['keterangan_izin'] = $nama_izin;
      $data_log['record'][$k]['terlambat'] = $hadir_terlambat;
      $data_log['record'][$k]['status_kehadiran'] = $status_kehadiran;

    }



    $data_log['total_hadir']   = $total_hadir;
    $data_log['total_hadir_terlambat']   = $label_telat ;
    $data_log['total_alpha']   = $total_alpha;
    $data_log['total_izin']   = $total_izin;
    $data_log['total_cuti']   = $total_cuti;
    $data_log['total_sakit']   = $total_sakit;

    $data_log['karyawan']   = $data_karyawan;
    $data_log['departemen'] = $data_dept;
    $data_log['periode']    = "Periode ".$start." ".$end;

    // echo "<pre>";
    // print_r($data_log['record']);

    $i = $row_number + 1;
    $n = 1;
    foreach ($data_log['record'] as $key => $r) {

      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A' . $i, $n)
      ->setCellValue('B' . $i, $data_karyawan['nip'])
      ->setCellValue('C' . $i, $r['tanggal'])
      ->setCellValue('D' . $i, $r['jam_kerja'])
      ->setCellValue('E' . $i, $r['jam_masuk'])
      ->setCellValue('F' . $i, $r['lokasi_masuk'])
      ->setCellValue('G' . $i, $r['keterangan_masuk'])
      ->setCellValue('H' . $i, $r['jam_pulang'])
      ->setCellValue('I' . $i, $r['lokasi_pulang'])
      ->setCellValue('J' . $i, $r['keterangan_pulang'])
      ->setCellValue('K' . $i, $r['status_kehadiran'])
      ->setCellValue('L' . $i, $r['terlambat'])
      ->setCellValue('M' . $i, $r['keterangan_izin']);

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

      $i++;
      $n++;
    }

    $max_row = 5 + 1 + count($data_log['record']);

    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $max_row, 'This report generated at ' . Date('d-m-Y H:i:s'));
    // $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
    $spreadsheet->getActiveSheet()->getStyle("A" . $max_row)->getFont()->setSize(8);

    $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
    $spreadsheet->getActiveSheet()->mergeCells("A3:M3");

    $spreadsheet->getActiveSheet()->getStyle("A2:M2")->applyFromArray($style_judul);
    $spreadsheet->getActiveSheet()->getStyle("A3:M3")->applyFromArray($style_judul);

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Data Presensi');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    $file_name = 'Laporan Presensi '.$data_karyawan['nama_lengkap'] .' Periode '. formatTglIndo($start_date) . ' s.d ' . formatTglIndo($end_date);
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

  function dummy_data(){
    $data_tanggal = $this->Time_dim_model->get_current_day_month()->result();
    $data_karyawan = $this->Karyawan_model->get_data()->result();
    $insert_data = array();
    foreach ($data_tanggal as $r) {
      $tanggal = $r->id;

      if($r->day_name !== "Saturday" && $r->day_name !== "Sunday"){

        foreach ($data_karyawan as $rr) {
          $insert_data[] = array(
            'id_karyawan'       => $rr->id,
            'tanggal'           => $tanggal,
            'jam_masuk'         => '08:00:00',
            'lokasi_masuk'      => 'PT Hablun Citramas Persada Purwokerto',
            'kordinat_masuk'    => '-7.4452512,109.2366381',
            'foto_masuk'        => '-',
            'keterangan_masuk'  => 'Jam Masuk Pagi',
            'jam_pulang'        => '17:03:00',
            'lokasi_pulang'     => 'PT Hablun Citramas Persada Purwokerto',
            'kordinat_pulang'   => '-7.4452512,109.2366381',
            'foto_pulang'       => '-',
            'keterangan_pulang' => 'Pulang Sore',
            'status_kehadiran'  => 'H',
            'id_izin'           =>  '-',
            'input_by'          => $this->session->userdata('username'),
            'input_datetime'    => date('Y-m-d H:i:s'),
            'is_del'            => 0,
            'client_id'         => $this->session->userdata('client_id')
          );
        }
      }

    }
    $result = $this->Log_presensi_model->save_batch($insert_data);
    echo "total inserted ".$result;
  }

  function masuk(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Log_presensi_model->_uploadImage("presensi_" . id());
      if ($file_name['status']) {

        $insert_data[] = array(
          'id_karyawan'       => $this->session->userdata('id_karyawan'),
          'tanggal'           => date('Y-m-d'),
          'jam_masuk'         => date('H:i:s'),
          'lokasi_masuk'      => 'PT Hablun Citramas Persada Purwokerto',
          'kordinat_masuk'    => '-7.4452512,109.2366381',
          'foto_masuk'        => $file_name['original_image'],
          'keterangan_masuk'  => $this->input->post('input_keterangan', true),
          'input_by'          => $this->session->userdata('username'),
          'input_datetime'    => date('Y-m-d H:i:s'),
          'is_del'            => 0,
          'client_id'         => $this->session->userdata('client_id')
        );

        $result = $this->Log_presensi_model->save_batch($insert_data);

        $result = array('status' => TRUE, "message" => $file_name['status']);
        echo json_encode($result);

      } else {
        $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
        echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

}
