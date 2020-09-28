<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Departemen_model','Karyawan_model','User_model',
    'Shift_karyawan_model', 'Kode_shift_model', 'Toleransitelat_model', 'Time_dim_model', 'Jam_kerja_model',
    'Log_presensi_model', 'Cuti_model', 'Data_izin_model', 'Jenis_izin_model', 'Departemen_model', 'Log_presensi_model', 'Logo_model'));
    $this->load->helper(array('app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

    if($this->session->userdata('id_hak_akses') != '3'){
        redirect(base_url());
    }
  }

  function validasi_cuti($id_karyawan){
    $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($id_karyawan);
    $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
    $result = ['sudah_ambil' => $total_cuti_sudah_diambil, 'jatah' => $jatah_cuti_tahunan];
    echo json_encode($result);
  }

  function getKaryawanByDept($id_dept){
    $data = $this->Karyawan_model->get_by(['id_departemen' => $id_dept])->result();
    echo '<option value="x" selected>All</option>';
    foreach ($data as $r) {
      echo '<option value="'.$r->id.'">'.$r->nama_lengkap.'</option>';
    }
  }

  function index(){
    $shift_detail = $this->get_shift_detail($this->session->userdata('id_karyawan'), date('Y-m-d'));
    $data['karyawan']   = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])->row_array();
    $data['departemen'] = $this->Departemen_model->get_by(['id' => $data['karyawan']['id_departemen']] )->row_array();
    $data['title_1'] = ($shift_detail['kode'] == "") ? "Reguler" : $shift_detail['kode'] ;
    $data['title_1'] .= " ( ".$shift_detail['jam_masuk']." - ".$shift_detail['jam_pulang']." ) ";
    
    $data['logo']    = $this->Logo_model->get_data($this->session->userdata('client_id'))->row_array();

    $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->session->userdata('id_karyawan'));
    $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
    $result = ['sudah_ambil' => $total_cuti_sudah_diambil, 'jatah' => $jatah_cuti_tahunan];

    $today_attendance = $this->Log_presensi_model->get_by(['id_karyawan' => $this->session->userdata('id_karyawan'), 'tanggal' => date('Y-m-d')])->row_array();
    if(empty($today_attendance)){ //jika tidak ada record == belum absen pagi
      $button = '<a role="button" class="btn btn-sm btn-primary" href="'.base_url('user/presensimasuk').'">Masuk <br></a>';
      $data['button'] = $button;
      $data['id_record'] = "";
    } else { //JIKA ADA RECORD
        
        if($today_attendance['id_izin'] == null){
            if($today_attendance['jam_pulang'] == null || $today_attendance['jam_pulang'] == "00:00:00"){
                $button = '<a role="button" class="btn btn-sm btn-warning" href="'.base_url('user/presensipulang').'">Pulang <br></a>';
                $data['button'] = $button;
            } else {
                $button = '<button type="button" class="btn btn-warning" onclick="">Sudah Absen <br></button>';
                $data['button'] = "Hari Ini Sudah Absen Masuk & Pulang";
            }   
        } else {
            $data['button'] = "Anda memiliki pengajuan izin / cuti / sakit yang sudah di approve pada hari ini";
        }
    }

    if($jatah_cuti_tahunan == $total_cuti_sudah_diambil){
      $data['sisa_cuti'] = 0;
    } else {
      $data['sisa_cuti'] = $jatah_cuti_tahunan - $total_cuti_sudah_diambil;
    }

    $data['izin'] = $this->jumlah_izin_tahun_ini($this->session->userdata('id_karyawan'));

    $data_hadir = $this->Log_presensi_model->get_data_current_month([
      'id_karyawan' => $this->session->userdata('id_karyawan'),
      'jam_masuk != ' => "00:00:00",
    //   'jam_pulang != ' => "00:00:00",
      'id_izin is null '
    ], date('Y-m'))->result();

    $data['hadir'] = count($data_hadir);
    
    $this->template->load('Template', 'user/view_user', $data);
    // echo "<pre>";
    // print_r($data['hadir']);
  }

  function jumlah_cuti_tahun_ini($id_karyawan=""){
    $where = "status_approval = '1' and id_jenis_izin = '3' and id_karyawan = '".$id_karyawan."' and tanggal_awal like '%".date('Y')."%' and tanggal_akhir like '%".date('Y')."%'  ";
    $total_cuti_sudah_diambil = $this->Data_izin_model->get_by($where)->result();
    $result = [];
    $i = 0;
    foreach ($total_cuti_sudah_diambil as $r) {
      $result[$i] = $r->id;
      $i++;
    }
    $total = 0;
    foreach ($result as $key => $value) {
      $data = $this->Log_presensi_model->get_by(['id_izin' => $value])->result();
      $total += count($data);
    }
    return $total;
  }

  function jumlah_izin_tahun_ini($id_karyawan=""){
    $where = "status_approval = '1' and id_karyawan = '".$id_karyawan."' and tanggal_awal like '%".date('Y')."%' and tanggal_akhir like '%".date('Y')."%'  ";
    $total_cuti_sudah_diambil = $this->Data_izin_model->get_by($where)->result();
    $result = [];
    $i = 0;
    foreach ($total_cuti_sudah_diambil as $r) {
      $result[$i] = $r->id;
      $i++;
    }
    $total = 0;
    foreach ($result as $key => $value) {
      $data = $this->Log_presensi_model->get_by(['id_izin' => $value])->result();
      $total += count($data);
    }
    return $total;
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

  function upload(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->User_model->_uploadImage("presensi_" . id());
      if ($file_name['status']) {

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

  function listKehadiran($periode){
    $id_karyawan = $this->session->userdata('id_karyawan');
    // $periode     = $_POST['param'][1]."-".$_POST['param'][2];
    $result = [];
    $where  = "id_karyawan = '".$id_karyawan."' and tanggal like '%".$periode."%'";
    $data = $this->Log_presensi_model->get_by($where)->result();
    foreach ($data as $key => $value) {

      $date = $this->Time_dim_model->get_date($value->tanggal, $value->tanggal)->row_array();

      $result[$key]['hari'] = hari($date['day_name']);
      $result[$key]['tanggal'] = $value->tanggal;
      $result[$key]['jam_masuk'] = $value->jam_masuk;
      $result[$key]['lokasi_masuk'] = $value->lokasi_masuk;
      $result[$key]['jam_pulang'] = $value->jam_pulang;
      $result[$key]['lokasi_pulang'] = $value->lokasi_pulang;
      $result[$key]['id_izin'] = $value->id_izin;
    }

    if(!empty($result)){
      foreach ($result as $key => $value) {
        
        if($value['id_izin'] == null){
            echo '<div class="card" style="border: 1px solid none; margin-bottom: 5px; margin-top: 0px; margin-left: 0px; margin-right: 0px">
              <div class="card-header" style="border: 1px solid none; padding-top: 5px; padding-bottom: 5px;">
                '.$value['hari'].', '.formatTglIndo($value['tanggal']).'
              </div>
              <div class="card-body" style=" padding-top: 0px; padding-bottom: 0px; font-size:10px; padding-bottom: 5px">
                <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-success">Masuk: '.$value['jam_masuk'].' </p>
                <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-success">Lokasi: '.$value['lokasi_masuk'].' </p>
                <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-primary">Pulang: '.$value['jam_pulang'].' </p>
                <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-primary">Lokasi: '.$value['lokasi_pulang'].' </p>
              </div>
            </div>';     
        } else {
            $data_izin = $this->Data_izin_model->get_by(['id' => $value['id_izin'] ])->row_array();
            $jenis_izin = $this->Jenis_izin_model->get_by(['id' => $data_izin['id_jenis_izin'] ])->row_array();
            echo '<div class="card" style="border: 1px solid none; margin-bottom: 5px; margin-top: 0px; margin-left: 0px; margin-right: 0px">
              <div class="card-header" style="border: 1px solid none; padding-top: 5px; padding-bottom: 5px;">
                '.$value['hari'].', '.formatTglIndo($value['tanggal']).'
              </div>
              <div class="card-body" style=" padding-top: 0px; padding-bottom: 0px; font-size:10px; padding-bottom: 5px">
                <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-danger">'.strtoupper($jenis_izin['nama_izin']).': '.$data_izin['keterangan'].' </p>
              </div>
            </div>';    
        }
      }
    } else {
      echo "Data tidak ditemukan";
    }
  }

  function add_izin(){
    $data['list'] = $this->Jenis_izin_model->get_data();
    $data['departemen'] = $this->Departemen_model->get_data()->result();
    $data['karyawan']   = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])->row_array();
    $data['departemen'] = $this->Departemen_model->get_by(['id' => $data['karyawan']['id_departemen']] )->row_array();
    $this->template->load('Template', 'izin/add_izin', $data);
  }

  function masuk(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
    //   $file_name = $this->User_model->_uploadImage("presensi_" . id());
      $file_name['status'] = true;
      if ($file_name['status']) {

        if(file_exists($_FILES['imgInp']['tmp_name'])){
            $filePhoto 	= htmlspecialchars($_FILES['imgInp']['name'],ENT_QUOTES);
            $sizePhoto 	= htmlspecialchars($_FILES['imgInp']['size'],ENT_QUOTES);
            $tempPhoto 	= htmlspecialchars($_FILES['imgInp']['tmp_name'],ENT_QUOTES);
            $tipePhoto 	= htmlspecialchars($_FILES['imgInp']['type'],ENT_QUOTES);
            $extPhoto 	= substr($filePhoto,strrpos($filePhoto,'.'));
            
            // $newPhoto 	= "presensimasuk_".$extPhoto;
            $isinya 	= file_get_contents($tempPhoto);
            $tempnya 	= base64_encode($isinya);
            $newPhoto 	= 'data:'.$tipePhoto.';base64,'.$tempnya;
    
            $insert_data[] = array(
              'id_karyawan'       => $this->session->userdata('id_karyawan'),
              'tanggal'           => date('Y-m-d'),
              'jam_masuk'         => date('H:i:s'),
              'lokasi_masuk'      => $this->input->post('input_lokasi', true),
              'kordinat_masuk'    => $this->input->post('input_lat', true).",".$this->input->post('input_long', true),
              'foto_masuk'        => "-", //$file_name['original_image'],
              'foto_masuk_text'   => $newPhoto,
              'keterangan_masuk'  => $this->input->post('input_keterangan', true),
              'input_by'          => $this->session->userdata('username'),
              'input_datetime'    => date('Y-m-d H:i:s'),
              'is_del'            => 0,
              'client_id'         => $this->session->userdata('client_id')
            );
    
            $result = $this->Log_presensi_model->save_batch($insert_data);
    
            // $result = array('status' => TRUE, "message" => $file_name['status']);
            // echo json_encode($result);
            
            $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-thumbs-up"></i></b> Presensi Masuk Berhasil
              </div>');   
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-minus-circle"></i></b> Error code 114 
              </div>');
        }

      } else {
        // $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
        // echo json_encode($result);
        
        $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-minus-circle"></i></b> Presensi Masuk Gagal. Error code: 111. 
          </div>');

      }
    } else {
    //   $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
    //   echo json_encode($result);
    
    $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-minus-circle"></i></b> Presensi Masuk Gagal. Error code: 112. Image empty.
          </div>');
    }
    
    redirect('user');
  }
  
  function pulang(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
    //   $file_name = $this->User_model->_uploadImage("presensi_" . id());
       $file_name['status'] = true;
      if ($file_name['status']) {
          
        if(file_exists($_FILES['imgInp']['tmp_name'])){
            $filePhoto 	= htmlspecialchars($_FILES['imgInp']['name'],ENT_QUOTES);
            $sizePhoto 	= htmlspecialchars($_FILES['imgInp']['size'],ENT_QUOTES);
            $tempPhoto 	= htmlspecialchars($_FILES['imgInp']['tmp_name'],ENT_QUOTES);
            $tipePhoto 	= htmlspecialchars($_FILES['imgInp']['type'],ENT_QUOTES);
            $extPhoto 	= substr($filePhoto,strrpos($filePhoto,'.'));
            
            // $newPhoto 	= "presensimasuk_".$extPhoto;
            $isinya 	= file_get_contents($tempPhoto);
            $tempnya 	= base64_encode($isinya);
            $newPhoto 	= 'data:'.$tipePhoto.';base64,'.$tempnya;
            
            $object = array(
              'jam_pulang'         => date('H:i:s'),
              'lokasi_pulang'      => $this->input->post('input_lokasi', true),
              'kordinat_pulang'    => $this->input->post('input_lat', true).",".$this->input->post('input_long', true),
              'foto_pulang'        => "-",
              'foto_pulang_text'   => $newPhoto,
              'keterangan_pulang'  => $this->input->post('input_keterangan', true)
            );
            
            $where = ['id' => $this->input->post('input_id_record', true) ];
            $result = $this->Log_presensi_model->update($object, $where);
    
            // $result = array('status' => TRUE, "message" => $file_name['status']);
            // echo json_encode($result);
            
            $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-thumbs-up"></i></b> Presensi Pulang Berhasil
              </div>');
               
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-minus-circle"></i></b> Error code 114 
              </div>');
        }

        

      } else {
        // $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
        // echo json_encode($result);
        
        $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-minus-circle"></i></b> Presensi Pulang Gagal. Error code: 113
          </div>');
      }
    } else {
    //   $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
    //   echo json_encode($result);
    
    $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-minus-circle"></i></b> Presensi Pulang Gagal. Error code: 114
          </div>');
    }
    
    redirect('user');
  }
  
  public function presensimasuk(){
    $today_attendance = $this->Log_presensi_model->get_by(['id_karyawan' => $this->session->userdata('id_karyawan'), 'tanggal' => date('Y-m-d')])->row_array();
    if(empty($today_attendance)){ //jika tidak ada record == belum absen pagi
        $data['karyawan']   = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])->row_array();
        $this->template->load('Template', 'user/view_masuk', $data);
    } else {
        redirect(base_url('user'));
        if($today_attendance['jam_pulang'] == null || $today_attendance['jam_pulang'] == "00:00:00"){
            $button = '<a role="button" class="btn btn-sm btn-warning" href="'.base_url('user/presensipulang').'">Pulang <br></a>';
            $data['button'] = $button;
        } else {
            $button = '<button type="button" class="btn btn-warning" onclick="">Sudah Absen <br></button>';
            $data['button'] = "Hari Ini Sudah Absen Masuk & Pulang";
        }
    }

    // $data['karyawan']   = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])->row_array();
    // $this->template->load('Template', 'user/view_masuk', $data);
  }
  
  public function presensipulang(){
      
    $today_attendance = $this->Log_presensi_model->get_by(['id_karyawan' => $this->session->userdata('id_karyawan'), 'tanggal' => date('Y-m-d')])->row_array();
    
    if($today_attendance['jam_pulang'] == null || $today_attendance['jam_pulang'] == "00:00:00"){
            $data['today_attendance'] = $this->Log_presensi_model->get_by(['id_karyawan' => $this->session->userdata('id_karyawan'), 'tanggal' => date('Y-m-d')])->row_array();
            $data['karyawan']   = $this->Karyawan_model->get_by(['id' => $this->session->userdata('id_karyawan')])->row_array();
            $this->template->load('Template', 'user/view_pulang', $data);
        } else {
            redirect(base_url('user'));
        }
    
    
  }
 

}
