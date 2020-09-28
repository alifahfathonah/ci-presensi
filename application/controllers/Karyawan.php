<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load library phpspreadsheet
require('./eksel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet

class Karyawan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Karyawan_model', 'Indonesia_model', 'Login_model', 'Username_model',
    'Agama_model', 'Departemen_model', 'Jabatan_karyawan_model', 'Jabatan_model', 'Level_pendidikan_model', 'Riwayat_pendidikan_model',
    'Berkas_karyawan_model', 'Jenis_berkas_model', 'Keys_model', 'Time_dim_model', 'Log_presensi_model', 
    'Shift_karyawan_model', 'Kode_shift_model', 'Toleransitelat_model', 'Data_izin_model', 'Jenis_izin_model'));
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
    $data['departemen'] = $this->Departemen_model->get_data();
    $this->template->load('Template', 'karyawan/view_karyawan', $data);
  }

  public function ajax_list(){
    $list = $this->Karyawan_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {
      $parameter        = array('id_karyawan' => $r->id, 'is_active' => 1);
      $jabatan_karyawan = $this->Jabatan_karyawan_model->get_by($parameter)->row_array();

      $parameter        = array('id' => $jabatan_karyawan['id_jabatan']);
      $jabatan_detail   = $this->Jabatan_model->get_by($parameter)->row_array();

      $parameter         = array('id' => $r->id_departemen);
      $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

      $row = array();
      $row[] = $no;
      $row[] = $r->nip;
      $row[] = $r->nama_lengkap;
      $row[] = $departemen_detail['nama_departemen'];
      $row[] = $jabatan_detail['nama_jabatan'] . ' / '.$jabatan_karyawan['detail_jabatan'];
      $row[] = ($r->is_active == 1) ? "Aktif" : "Tidak Aktif";
      $row[] = $r->no_telp;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <a role="button" title="Profil" class="btn btn-sm btn-link btn-warning" href="' . base_url('karyawan/profil/' . $r->id) . '">
      <b class="fa fa-search"></b>
      </a>
      </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Karyawan_model->count_all(),
      "recordsFiltered" => $this->Karyawan_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $data['departemen'] = $this->Departemen_model->get_data();
    $data['kabupaten']  = $this->Indonesia_model->get_data_kabupaten();
    $data['provinsi']   = $this->Indonesia_model->get_data_provinsi();
    $data['agama']      = $this->Agama_model->get_data();
    $this->template->load('Template', 'karyawan/add_karyawan', $data);
  }

  public function validation(){
    $this->form_validation->set_rules('input_nip' , 'Nomor Induk' , 'required', array('required' => 'Nomor Induk Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_nama_lengkap' , 'Nama Lengkap' , 'required', array('required' => 'Nama Lengkap Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_tanggal_lahir' , 'Tanggal Lahir' , 'required', array('required' => 'Tanggal Lahir Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_alamat' , 'Alamat' , 'required', array('required' => 'Alamat Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_no_telp' , 'No. Telpon' , 'required', array('required' => 'No. Telpon Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_no_ktp' , 'No. KTP' , 'required', array('required' => 'No. KTP Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_alamat_ktp' , 'Alamat KTP' , 'required', array('required' => 'Alamat KTP Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_email' , 'E-mail' , 'required', array('required' => 'E-mail Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_gaji_pokok' , 'Gaji Pokok' , 'required', array('required' => 'Gaji Pokok Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_rekening' , 'No. Rekening' , 'required', array('required' => 'No. Rekening Tidak Boleh Kosong'));

    $this->form_validation->set_rules('input_jenis_kelamin' , 'Jenis Kelamin' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_tempat_lahir' , 'Tempat Lahir' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_kota' , 'Kota' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_provinsi' , 'Provinsi' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_agama' , 'Agama' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_golongan_darah' , 'Golongan Darah' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_status_kawin' , 'Status Kawin' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_status_karyawan' , 'Status Karyawan' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_departemen' , 'Departemen' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_lock_area' , 'Lock Area' , 'callback_validasi_pilih');

    if ($this->form_validation->run()) {
      $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
    } else {
      $array = array(
        'error' => true,
        // 'input_nama_kantor_error_detail'      => form_error('input_nama_kantor', '<b class="fa fa-warning"></b> ', ' '),
        'input_nip_error_detail'            => form_error('input_nip', '<b class="fa fa-warning"></b> ', ' '),
        'input_nama_lengkap_error_detail'   => form_error('input_nama_lengkap', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_lahir_error_detail'  => form_error('input_tanggal_lahir', '<b class="fa fa-warning"></b> ', ' '),
        'input_alamat_error_detail'         => form_error('input_alamat', '<b class="fa fa-warning"></b> ', ' '),
        'input_no_telp_error_detail'        => form_error('input_no_telp', '<b class="fa fa-warning"></b> ', ' '),
        'input_no_ktp_error_detail'         => form_error('input_no_ktp', '<b class="fa fa-warning"></b> ', ' '),
        'input_alamat_ktp_error_detail'     => form_error('input_alamat_ktp', '<b class="fa fa-warning"></b> ', ' '),
        'input_email_error_detail'          => form_error('input_email', '<b class="fa fa-warning"></b> ', ' '),
        'input_rekening_error_detail'       => form_error('input_rekening', '<b class="fa fa-warning"></b> ', ' '),
        'input_gaji_pokok_error_detail'     => form_error('input_gaji_pokok', '<b class="fa fa-warning"></b> ', ' '),

        'input_jenis_kantor_error_icon'     => form_error('input_jenis_kantor', '', ''),
        'input_jenis_kelamin_error_icon'    => form_error('input_jenis_kelamin', ' ', ' '),
        'input_tempat_lahir_error_icon'     => form_error('input_tempat_lahir', ' ', ' '),
        'input_kota_error_icon'             => form_error('input_kota', ' ', ' '),
        'input_provinsi_error_icon'         => form_error('input_provinsi', ' ', ' '),
        'input_agama_error_icon'            => form_error('input_agama', ' ', ' '),
        'input_golongan_darah_error_icon'   => form_error('input_golongan_darah', ' ', ' '),
        'input_status_kawin_error_icon'     => form_error('input_status_kawin', ' ', ' '),
        'input_status_karyawan_error_icon'  => form_error('input_status_karyawan', ' ', ' '),
        'input_departemen_error_icon'       => form_error('input_departemen', ' ', ' '),
        'input_lock_area_error_icon'        => form_error('input_lock_area', ' ', ' '),

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

  public function insert(){
    $originalDate = $this->input->post('input_tanggal_lahir', true);
    $tgl_lahir = date("Y-m-d", strtotime($originalDate));

    $originalDate = $this->input->post('input_tanggal_masuk', true);
    $tgl_masuk = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'id'                => id(),
      'nip'               => $this->input->post('input_nip', true),
      'nama_lengkap'      => $this->input->post('input_nama_lengkap', true),
      'jenis_kelamin'     => $this->input->post('input_jenis_kelamin', true),
      'tempat_lahir'      => $this->input->post('input_tempat_lahir', true),
      'tanggal_lahir'     => $tgl_lahir,
      'id_agama'          => $this->input->post('input_agama', true),
      'no_telp'           => $this->input->post('input_no_telp', true),
      'alamat'            => $this->input->post('input_alamat', true),
      'id_kota'           => $this->input->post('input_kota', true),
      'id_provinsi'       => $this->input->post('input_provinsi', true),
      'no_ktp'            => $this->input->post('input_no_ktp', true),
      'alamat_ktp'        => $this->input->post('input_alamat_ktp', true),
      'golongan_darah'    => $this->input->post('input_golongan_darah', true),
      'status_kawin'      => $this->input->post('input_status_kawin', true),
      'email'             => $this->input->post('input_email', true),
      'status_karyawan'   => $this->input->post('input_status_karyawan', true),
      'is_active'         => $this->input->post('input_aktif', true),
      'tanggal_masuk'     => $tgl_masuk,
      'id_departemen'     => $this->input->post('input_departemen', true),
      'lock_area'         => $this->input->post('input_lock_area', true),
      'rekening'          => $this->input->post('input_rekening', true),
      'gaji_pokok'        => str_replace(".", "", $this->input->post('input_gaji_pokok', true)),
      'input_by'          => $this->session->userdata('username'),
      'input_datetime'    => date('Y-m-d H:i:s'),
      'is_del'            => 0,
      'client_id'         => $this->session->userdata('client_id')
    );

    $inserted = $this->Karyawan_model->save($object);

    if($inserted){
      // add Username
      $data = array(
                  'id_karyawan'       => $inserted,
                  'username'          => $this->input->post('input_nip', true), //default username = nip
                  'password'          => $this->hash_string($tgl_lahir), //defaul password = tanggal lahir
                  'id_hak_akses'      => 3, //default hak akses 3 = staff
                  'input_by'          => $this->session->userdata('username'),
                  'input_datetime'    => date('Y-m-d H:i:s'),
                  'is_del'            => 0,
                  'client_id'         => $this->session->userdata('client_id')
                );
      $insertedId = $this->Login_model->insert($data);
      if($insertedId){
        $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
        </div>');
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
        </div>');
      }

    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
      <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
      </div>');
    }
    $result = array('status' => true, "id" => $inserted);
    echo json_encode($result);
  }

  function profil($id=""){
    if($id == ""){
      redirect("karyawan");
    } else {
      $parameter = array('id' => $id);
      $data['record'] = $this->Karyawan_model->get_by($parameter)->row_array();
      if(!empty($data['record'])){

        $parameter        = array('id_karyawan' => $id, 'is_active' => 1);
        $jabatan_karyawan = $this->Jabatan_karyawan_model->get_by($parameter)->row_array();

        $jabatan_list     = $this->Jabatan_model->get_data()->result();

        $parameter        = array('id' => $jabatan_karyawan['id_jabatan']);
        $jabatan_detail   = $this->Jabatan_model->get_by($parameter)->row_array();

        $parameter         = array('id' => $data['record']['id_departemen']);
        $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

        $tempat_lahir      = $this->Indonesia_model->get_nama_kabupaten($data['record']['tempat_lahir'])->row_array();

        $kota              = $this->Indonesia_model->get_nama_kabupaten($data['record']['id_kota'])->row_array();

        $provinsi          = $this->Indonesia_model->get_nama_provinsi($data['record']['id_provinsi'])->row_array();

        $parameter         = array('id' => $data['record']['id_agama']);
        $agama             = $this->Agama_model->get_by($parameter)->row_array();

        $parameter         = array('id_karyawan' => $id);
        $username          = $this->Username_model->get_by($parameter)->row_array();

        if($jabatan_karyawan['detail_jabatan'] != null || $jabatan_karyawan['detail_jabatan'] != "" || $jabatan_karyawan['detail_jabatan'] != "-"){
          $jabatan = $jabatan_detail['nama_jabatan'] . ' / '.$jabatan_karyawan['detail_jabatan'];
        } else {
          $jabatan = $jabatan_detail['nama_jabatan'];
        }

        $data['record']['jabatan']      = $jabatan;
        $data['record']['departemen']   = $departemen_detail['nama_departemen'];
        $data['record']['tempat_lahir'] = $tempat_lahir['name'];
        $data['record']['kota']         = $kota['name'];
        $data['record']['provinsi']     = $provinsi['name'];
        $data['record']['agama']        = $agama['nama_agama'];
        $data['kabupaten']              = $this->Indonesia_model->get_data_kabupaten();
        $data['pendidikan']             = $this->Level_pendidikan_model->get_data();
        $data['riwayat_pendidikan']     = $this->Riwayat_pendidikan_model->get_by(array('id_karyawan' => $id ))->row_array();
        $data['username']               = $username;
        $data['list_jabatan']           = $jabatan_list;

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 2); //ambil data ktp
        $file_attachment_ktp            = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 3); //ambil data kk
        $file_attachment_kk             = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 4); //ambil data ijazah
        $file_attachment_ijazah         = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 5); //ambil data transkrip
        $file_attachment_transkrip      = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 6); //ambil data cv
        $file_attachment_cv             = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $parameter_attachment           = array('id_karyawan' => $id, 'id_jenis_berkas' => 7); //ambil data pengalaman
        $file_attachment_pengalaman     = $this->Berkas_karyawan_model->get_by($parameter_attachment)->row_array();

        $data['attachment_ktp']             = $file_attachment_ktp['file_path'];
        $data['attachment_kk']              = $file_attachment_kk['file_path'];
        $data['attachment_ijazah']          = $file_attachment_ijazah['file_path'];
        $data['attachment_transkrip']        = $file_attachment_transkrip['file_path'];
        $data['attachment_cv']              = $file_attachment_cv['file_path'];
        $data['attachment_pengalamankerja'] = $file_attachment_pengalaman['file_path'];

        $this->template->load('Template', 'karyawan/profil_karyawan', $data);
      } else {
        redirect("karyawan");
      }
    }
  }

  function edit($id=""){
    if($id == ""){
      redirect("karyawan");
    } else {
      $parameter = array('id' => $id);
      $data['record'] = $this->Karyawan_model->get_by($parameter)->row_array();
      if(!empty($data['record'])){
        $data['departemen'] = $this->Departemen_model->get_data();
        $data['kabupaten']  = $this->Indonesia_model->get_data_kabupaten();
        $data['provinsi']   = $this->Indonesia_model->get_data_provinsi();
        $data['agama']      = $this->Agama_model->get_data();
        $this->template->load('Template', 'karyawan/edit_karyawan', $data);
      } else {
        redirect("karyawan");
      }
    }
  }

  public function update(){

    $originalDate = $this->input->post('input_tanggal_lahir', true);
    $tgl_lahir = date("Y-m-d", strtotime($originalDate));

    $originalDate = $this->input->post('input_tanggal_masuk', true);
    $tgl_masuk = date("Y-m-d", strtotime($originalDate));

    $object = array(
      'nip'               => $this->input->post('input_nip', true),
      'nama_lengkap'      => $this->input->post('input_nama_lengkap', true),
      'jenis_kelamin'     => $this->input->post('input_jenis_kelamin', true),
      'tempat_lahir'      => $this->input->post('input_tempat_lahir', true),
      'tanggal_lahir'     => $tgl_lahir,
      'id_agama'          => $this->input->post('input_agama', true),
      'no_telp'           => $this->input->post('input_no_telp', true),
      'alamat'            => $this->input->post('input_alamat', true),
      'id_kota'           => $this->input->post('input_kota', true),
      'id_provinsi'       => $this->input->post('input_provinsi', true),
      'no_ktp'            => $this->input->post('input_no_ktp', true),
      'alamat_ktp'        => $this->input->post('input_alamat_ktp', true),
      'golongan_darah'    => $this->input->post('input_golongan_darah', true),
      'status_kawin'      => $this->input->post('input_status_kawin', true),
      'email'             => $this->input->post('input_email', true),
      'status_karyawan'   => $this->input->post('input_status_karyawan', true),
      'is_active'         => $this->input->post('input_aktif', true),
      'tanggal_masuk'     => $tgl_masuk,
      'id_departemen'     => $this->input->post('input_departemen', true),
      'lock_area'         => $this->input->post('input_lock_area', true),
      'rekening'          => $this->input->post('input_rekening', true),
      'gaji_pokok'        => str_replace(".", "", $this->input->post('input_gaji_pokok', true)),
    );

    $where = array('id' => $this->input->post('id', true));

    $affected_row = $this->Karyawan_model->update($object, $where);

    if($affected_row){
      $this->session->set_flashdata('message', '<div class="alert alert-light bg bg-success text-white"
      style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px; border: 1px solid green;">
      <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
      <button type="button" class="close float-right" data-dismiss="alert" aria-label="Close" style="margin-bottom: 0px; height: 30px; line-height:30px; padding:0px 15px;">
      <span aria-hidden="true">&times;</span>
      </button>
      </div><br>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light bg bg-danger text-white" style=" border: 1px solid red; margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
      <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
      <button type="button" class="close float-right" data-dismiss="alert" aria-label="Close" style="margin-bottom: 0px; height: 30px; line-height:30px; padding:0px 15px;">
      <span aria-hidden="true">&times;</span>
      </button>
      </div><br>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  public function upload_foto(){
    if(!empty($_FILES["imgInp"]["name"])){ // cek input file isi atau kosong
      $file_name = $this->Karyawan_model->_uploadImage("profil_" . id());
      if ($file_name['status']) {
        $parameter = array('id' => $this->input->post('id_karyawan_file', true)); //get data
        $existing_data = $this->Karyawan_model->get_by($parameter)->row_array();

        if(empty($existing_data)){ //jika tidak ada maka insert
          $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "not found");
          echo json_encode($result);
        } else { //jika ada maka update

          if($existing_data['image_path'] != null){
            unlink('uploads/photo_profil/'.$existing_data['image_path']); //hapus existing file
          }

          $object = array(
            'image_path'         => $file_name['original_image']
          );

          $where = array('id' => $this->input->post('id_karyawan_file', true));
          $inserted_id = $this->Karyawan_model->update($object, $where);
        }

        if ($inserted_id != null) {
          $parameter = array('id' => $this->input->post('id_karyawan_file', true)); //cek apakah existing data ktp sudah ada
          $existing_data = $this->Karyawan_model->get_by($parameter)->row_array();
          $result = array('status' => TRUE, "message" => "Update data berhasil", 'file' => $existing_data['image_path'], 'id_container' => "img_profil",
          'btn_hapus' => '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $file_name['original_image'] .'\' , \'' . "img_profil" . '\')"><i class="material-icons">clear</i></button>');
          echo json_encode($result);
        } else {
          $result = array('status' => FALSE, "message" => "Update data gagal", "detail_data" => null, "error" => "update");
          echo json_encode($result);
        }

      } else {
        $result = array('status' => FALSE, "message" => $file_name['message'], "detail_data" => null, "error" => "file");
        echo json_encode($result);
      }
    } else {
      $result = array('status' => FALSE, "message" => "Silahkan pilih file utk di upload", "detail_data" => null, "error" => "file");
      echo json_encode($result);
    }
  }

  function hapus_foto_profil($id=""){
    if($id == ""){
      echo json_encode(array('status' => false));
    } else {
      $parameter = array('id' => $id);
      $data = $this->Karyawan_model->get_by($parameter)->row_array();
      if(!empty($data)){

        $object = array('image_path' => null );

        $affected_row = $this->Karyawan_model->update($object, $parameter);

        unlink('uploads/photo_profil/'.$data['image_path']); //hapus existing file
        echo json_encode(array('status' => true));
      } else {
        echo json_encode(array('status' => false));
      }
    }
  }

  public function delete($id){
    $object = array(
      'is_del'            => 1,
      'status_karyawan'   => 0
    );

    $where = array('id' => $id);
    $affected_row = $this->Karyawan_model->update($object, $where);

    if($affected_row){
      $this->session->set_flashdata('message', '<div class="alert alert-light bg bg-success text-white"
      style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px; border: 1px solid green;">
      <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
      <button type="button" class="close float-right" data-dismiss="alert" aria-label="Close" style="margin-bottom: 0px; height: 30px; line-height:30px; padding:0px 15px;">
      <span aria-hidden="true">&times;</span>
      </button>
      </div><br>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light bg bg-danger text-white" style=" border: 1px solid red; margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
      <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
      <button type="button" class="close float-right" data-dismiss="alert" aria-label="Close" style="margin-bottom: 0px; height: 30px; line-height:30px; padding:0px 15px;">
      <span aria-hidden="true">&times;</span>
      </button>
      </div><br>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  function export($id=""){
    if($id == ""){
      // redirect("karyawan");
    } else {
      $parameter = array('id' => $id);
      $data['record'] = $this->Karyawan_model->get_by($parameter)->row_array();
      if(!empty($data['record'])){

        $parameter        = array('id_karyawan' => $id, 'is_active' => 1);
        $jabatan_karyawan = $this->Jabatan_karyawan_model->get_by($parameter)->row_array();

        $parameter        = array('id' => $jabatan_karyawan['id_jabatan']);
        $jabatan_detail   = $this->Jabatan_model->get_by($parameter)->row_array();

        $parameter         = array('id' => $data['record']['id_departemen']);
        $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

        $tempat_lahir      = $this->Indonesia_model->get_nama_kabupaten($data['record']['tempat_lahir'])->row_array();

        $kota              = $this->Indonesia_model->get_nama_kabupaten($data['record']['id_kota'])->row_array();

        $provinsi          = $this->Indonesia_model->get_nama_provinsi($data['record']['id_provinsi'])->row_array();

        $parameter         = array('id' => $data['record']['id_agama']);
        $agama             = $this->Agama_model->get_by($parameter)->row_array();

        if($jabatan_karyawan['detail_jabatan'] != null || $jabatan_karyawan['detail_jabatan'] != "" || $jabatan_karyawan['detail_jabatan'] != "-"){
          $jabatan = $jabatan_detail['nama_jabatan'] . ' / '.$jabatan_karyawan['detail_jabatan'];
        } else {
          $jabatan = $jabatan_detail['nama_jabatan'];
        }

        $data['record']['jabatan'] = $jabatan_detail['nama_jabatan'];
        $data['record']['detail_jabatan'] = $jabatan_karyawan['detail_jabatan'];
        $data['record']['agama'] = $agama['nama_agama'];

        echo "<pre>";
        print_r($data);
      } else {
        redirect("karyawan");
      }
    }
  }

  // function import(){
  //   $this->template->load('Template', 'karyawan/import_karyawan');
  // }

  public function downladTemplate(){
    force_download('excel/Form Template Upload Karyawan.xlsx',NULL);
  }

  function do_import(){
    include APPPATH.'third_party/PHPExcel/PHPExcel.php';
    $config['upload_path'] = realpath('excel');
    $config['allowed_types'] = 'xlsx|xls|csv';
    $config['max_size'] = '10000';
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

      $numrow = 1;
      foreach($sheet as $row){
        if($numrow > 8){

          $parameter         = array('nama_agama' => $row['G']);
          $agama             = $this->Agama_model->get_by($parameter)->row_array();

          $parameter         = array('name' =>  $row['E']);
          $tempat_lahir      = $this->Indonesia_model->get_by('regencies', $parameter)->row_array();

          $parameter         = array('name' => $row['J']);
          $kota              = $this->Indonesia_model->get_by('regencies', $parameter)->row_array();

          $parameter         = array('name' => $row['K']);
          $prov              = $this->Indonesia_model->get_by('provinces', $parameter)->row_array();

          $parameter         = array('kode_departemen' => $row['S']);
          $departemen        = $this->Departemen_model->get_by($parameter)->row_array();

          $status_karyawan   = ucwords(strtolower($row['Q']));

          if($status_karyawan == "Tetap"){
            $id_status_karyawan = 1;
          } else if($status_karyawan == "Kontrak"){
            $id_status_karyawan = 2;
          } else if($status_karyawan == "Training"){
            $id_status_karyawan = 3;
          } else if($status_karyawan == "Magang"){
            $id_status_karyawan = 4;
          } else {
            $id_status_karyawan = null;
          }
          
          if($row['N'] == "-" && $row['N'] == ""){
             $row['N'] = null;
          }

          array_push($data, array(
            'id'               => id(),
            'nip'              => $row['B'],
            'nama_lengkap'     => $row['C'],
            'jenis_kelamin'    => ($row['D'] == "L") ? "Laki - laki" : "Perempuan",
            'tempat_lahir'     => (!empty($tempat_lahir)) ? $tempat_lahir['id'] : null,
            'tanggal_lahir'    => $row['F'],
            'id_agama'         => (!empty($agama)) ? $agama['id'] : null,
            'no_telp'          => $row['H'],
            'alamat'           => $row['I'],
            'id_kota'          => (!empty($kota)) ? $kota['id'] : null, //$kota['id'],
            'id_provinsi'      => (!empty($prov)) ? $prov['id'] : null, //$prov['id'],
            'no_ktp'           => $row['L'],
            'alamat_ktp'       => $row['M'],
            'golongan_darah'   => $row['N'],
            'status_kawin'     => $row['O'],
            'email'            => $row['P'],
            'status_karyawan'  => $id_status_karyawan,
            'is_active'        => 1,
            'tanggal_masuk'    => ($row['R'] == null) ? "0000-00-00" : $row['R'],
            'id_departemen'    => (!empty($departemen)) ? $departemen['id'] : null, //$departemen['id'],
            'rekening'         => $row['T'],
            'lock_area'        => 1,
            'input_by'         => $this->session->userdata('username'),
            'input_datetime'   => date('Y-m-d H:i:s'),
            'is_del'           => 0,
            'client_id'        => $this->session->userdata('client_id')
          ));
        }
        $numrow++;
      }

      $this->db->insert_batch('tbl_karyawan', $data);
      foreach ($data as $r) {
        $data = array(
                    'id_karyawan'       => $r['id'],
                    'username'          => $r['nip'], //default username = nip
                    'password'          => $this->hash_string($r['tanggal_lahir']), //default password = tanggal lahir
                    'id_hak_akses'      => 3, //default hak akses 3 = staff
                    'input_by'          => $this->session->userdata('username'),
                    'input_datetime'    => date('Y-m-d H:i:s'),
                    'is_del'            => 0,
                    'client_id'         => $this->session->userdata('client_id')
                  );
        $insertedId = $this->Login_model->insert($data);
      }
      unlink(realpath('excel/'.$data_upload['file_name']));
      $result = array('status' => true);
      echo json_encode($result);
    }
  }

  public function hash_string($string)
  {
      $hashed_string = password_hash($string, PASSWORD_BCRYPT);
      return $hashed_string;
  }

  public function hash_verify($plain_text, $hashed_string)
  {
      $hashed_string = password_verify($plain_text, $hashed_string);
      return $hashed_string;
  }


}