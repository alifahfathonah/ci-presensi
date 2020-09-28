<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Data_izin_model', 'Karyawan_model', 'Jabatan_karyawan_model', 'Departemen_model', 'Jabatan_model', 'Jenis_izin_model',
    'Time_dim_model', 'Log_presensi_model', 'Cuti_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
      redirect(base_url("login"));
    }

    // if($this->session->userdata('id_hak_akses') == '3'){
    //     redirect(base_url("user"));
    // }
  }

  function index(){
    $data['list'] = $this->Jenis_izin_model->get_data();
    $data['departemen'] = $this->Departemen_model->get_data()->result();
    if($this->session->userdata('id_hak_akses') != '1'){
      // $this->session->set_flashdata('message_user', $this->session->flashdata('message'));
      redirect(base_url());
    } else {
      $this->template->load('Template', 'izin/view_izin', $data);
    }
  }

  public function ajax_list(){
    $list = $this->Data_izin_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $parameter = array('id' => $r->id_jenis_izin);
      $nama_izin = $this->Jenis_izin_model->get_by($parameter)->row_array();

      $parameter = array('id' => $r->id_karyawan);
      $karyawan = $this->Karyawan_model->get_by($parameter)->row_array();

      $parameter         = array('id' => $karyawan['id_departemen']);
      $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

      if($r->status_approval == 0){
        $status_approval = "Belum Di Approve";
      } else if($r->status_approval == 1){
        $status_approval = "Sudah Di Approve";
      } else if($r->status_approval == 2){
        $status_approval = "Tidak Di Approve";
      }

      if($r->attachment !== null){
        $img_src = '<img id="myImg" src="'.base_url("uploads/attachment/".$r->attachment).'" alt="" style="width:100%; max-width:50px" onclick="load_modal_image()">';
      } else {
        $img_src = "Tidak Ada";
      }

      $row = array();
      $row[] = $no;
      $row[] = $karyawan['nama_lengkap'];
      $row[] = $departemen_detail['nama_departemen'] ;
      $row[] = "(".$nama_izin['kode'].") ".$nama_izin['nama_izin'];
      $row[] = $r->keterangan;
      $row[] = formatTglIndo($r->tanggal_awal) . " sd. " . formatTglIndo($r->tanggal_akhir);
      $row[] = $status_approval;
      $row[] = $img_src;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <a role="button" title="Edit" class="btn btn-sm btn-link text-warning" href="'.base_url('izin/edit/'.$r->id).'"><b class="fa fa-edit"></b></a>
      <a role="button" title="Hapus" class="btn btn-sm btn-link text-danger" onclick="hapus_data(\''.$r->id.'\')"><b class="fa fa-trash"></b></a>
      </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Data_izin_model->count_all(),
      "recordsFiltered" => $this->Data_izin_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $data['list'] = $this->Jenis_izin_model->get_data();
    $data['departemen'] = $this->Departemen_model->get_data()->result();
    $this->template->load('Template', 'izin/add_izin', $data);
  }

  function suggestKaryawan(){
    if(!empty($_POST['query'])){
      $param = $_POST['query'];
    } else {
      $param = '';
    }
    $result = $this->Karyawan_model->get_autocomplete_karyawan($param)->result();
    foreach ($result as $row) {

      $parameter        = array('id_karyawan' => $row->id, 'is_active' => 1);
      $jabatan_karyawan = $this->Jabatan_karyawan_model->get_by($parameter)->row_array();

      $parameter        = array('id' => $jabatan_karyawan['id_jabatan']);
      $jabatan_detail   = $this->Jabatan_model->get_by($parameter)->row_array();

      $parameter         = array('id' => $row->id_departemen);
      $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

      $data[] = $row->nama_lengkap. "param '".$param . "' (NIP. ".$row->nip."; Dept. ". $departemen_detail['nama_departemen'] . ")";
    }
    echo json_encode($data);
  }

  public function validation(){
    // $this->form_validation->set_rules('input_nama_karyawan' , 'Nama Karyawan' , 'required', array('required' => 'Nama Karyawan Tidak Boleh Kosong'));
    $this->form_validation->set_rules('input_tanggal_awal' , 'Tanggal Awal' , 'required', array('required' => 'Wajib isi'));
    $this->form_validation->set_rules('input_tanggal_akhir' , 'Tanggal Akhir' , 'required', array('required' => 'Wajib isi'));
    $this->form_validation->set_rules('input_keterangan' , 'Keterangan' , 'required', array('required' => 'Keterangan Tidak Boleh Kosong'));

    $this->form_validation->set_rules('input_status_approval' , 'Status Approval' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_jenis_izin' , 'Jenis Izin' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_departemen' , 'Departemen' , 'callback_validasi_pilih');
    $this->form_validation->set_rules('input_karyawan' , 'Karyawan' , 'callback_validasi_pilih');

    if ($this->form_validation->run()) {
      $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
    } else {
      $array = array(
        'error' => true,
        // 'input_nama_karyawan_error_detail'      => form_error('input_nama_karyawan', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_awal_error_detail'       => form_error('input_tanggal_awal', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_akhir_error_detail'      => form_error('input_tanggal_akhir', '<b class="fa fa-warning"></b> ', ' '),
        'input_keterangan_error_detail'         => form_error('input_keterangan', '<b class="fa fa-warning"></b> ', ' '),

        'input_status_approval_error_icon'     => form_error('input_status_approval', '', ''),
        'input_jenis_izin_error_icon'          => form_error('input_jenis_izin', '', ''),
        'input_departemen_error_icon'          => form_error('input_departemen', '', ''),
        'input_karyawan_error_icon'            => form_error('input_karyawan', '', ''),

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

  function validasi_cuti($id_karyawan){
    $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($id_karyawan);
    $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
    $result = ['sudah_ambil' => $total_cuti_sudah_diambil, 'jatah' => $jatah_cuti_tahunan];
    echo json_encode($result);
  }

  function insert(){
    $originalDate = $this->input->post('input_tanggal_awal', true);
    $tgl_awal = date("Y-m-d", strtotime($originalDate));

    $originalDate = $this->input->post('input_tanggal_akhir', true);
    $tgl_akhir = date("Y-m-d", strtotime($originalDate));

    $existing_data = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tgl_awal, $tgl_akhir);

    if($existing_data['status'] == 1){
      if($this->input->post('input_jenis_izin', true) == 3){
        $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];

        $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
        $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();

        $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;

        if($simulated > $jatah_cuti_tahunan){
          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
          </div>');
          $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
          echo json_encode($result);
        } else {
          if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){

            $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
            </div>');
            $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
            echo json_encode($result);

          } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
            $file_name = $this->Data_izin_model->_uploadImage("izin_" . id());
            if ($file_name['status']) {

              $object = array(
                'id'            => id(),
                'id_karyawan'   => $this->input->post('input_karyawan', true),
                'id_departemen' => $this->input->post('input_departemen', true),
                'id_jenis_izin' => $this->input->post('input_jenis_izin', true),
                'tanggal_awal'  => $tgl_awal,
                'tanggal_akhir' => $tgl_akhir,
                'keterangan'    => $this->input->post('input_keterangan', true),
                'status_approval' => $this->input->post('input_status_approval', true),
                'attachment'      => $file_name['original_image'],
                'input_by'          => $this->session->userdata('username'),
                'input_datetime'    => date('Y-m-d H:i:s'),
                'is_del'            => 0,
                'client_id'         => $this->session->userdata('client_id')
              );

              $inserted = $this->Data_izin_model->save($object);
              if($inserted !== ""){

                if($object['status_approval'] == 1){
                  $result = $this->insertLog($object['id_karyawan'], $object['id'], $object['tanggal_awal'], $object['tanggal_akhir']  );
                  if($result){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
                    </div>');
                    $result = array('status' => true, "id" => $inserted, "message" => "sukses");
                    echo json_encode($result);
                  }
                } else {
                  $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
                  </div>');
                  $result = array('status' => true, "id" => $inserted, "message" => "sukses");
                  echo json_encode($result);
                }

              } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
                </div>');
                $result = array('status' => false, "id" => $inserted, "message" => "gagal");
                echo json_encode($result);
              }
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal. '.$file_name['message'].'
              </div>');
              $result = array('status' => false, "id" => null, "message" => $file_name['message']);
              echo json_encode($result);
            }
          }
        }
      } else {
        $file_name = $this->Data_izin_model->_uploadImage("izin_" . id());
        if ($file_name['status']) {

          $object = array(
            'id'            => id(),
            'id_karyawan'   => $this->input->post('input_karyawan', true),
            'id_departemen' => $this->input->post('input_departemen', true),
            'id_jenis_izin' => $this->input->post('input_jenis_izin', true),
            'tanggal_awal'  => $tgl_awal,
            'tanggal_akhir' => $tgl_akhir,
            'keterangan'    => $this->input->post('input_keterangan', true),
            'status_approval' => $this->input->post('input_status_approval', true),
            'attachment'      => $file_name['original_image'],
            'input_by'          => $this->session->userdata('username'),
            'input_datetime'    => date('Y-m-d H:i:s'),
            'is_del'            => 0,
            'client_id'         => $this->session->userdata('client_id')
          );

          $inserted = $this->Data_izin_model->save($object);
          if($inserted !== ""){

            if($object['status_approval'] == 1){
              $result = $this->insertLog($object['id_karyawan'], $object['id'], $object['tanggal_awal'], $object['tanggal_akhir']  );
              if($result){
                $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
                </div>');
                $result = array('status' => true, "id" => $inserted, "message" => "sukses");
                echo json_encode($result);
              }
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
              </div>');
              $result = array('status' => true, "id" => $inserted, "message" => "sukses");
              echo json_encode($result);
            }

          } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
            </div>');
            $result = array('status' => false, "id" => $inserted, "message" => "gagal");
            echo json_encode($result);
          }
        } else {
          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal. '.$file_name['message'].'
          </div>');
          $result = array('status' => false, "id" => null, "message" => $file_name['message']);
          echo json_encode($result);
        }
      }

    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
      <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! '.$existing_data['message'].'
      </div>');
      $result = array('status' => false, "id" => null, "message" => $existing_data['message']);
      echo json_encode($result);
    }
  }

  function edit($id){
    if($id == ""){
      redirect("izin");
    } else {
      if($this->session->userdata('id_hak_akses') != '1'){
          redirect(base_url());
      } else {
        $parameter = array('id' => $id);
        $data['record'] = $this->Data_izin_model->get_by($parameter)->row_array();
        $data['list'] = $this->Jenis_izin_model->get_data();
        $data['departemen'] = $this->Departemen_model->get_data()->result();
        $data['karyawan'] = $this->Karyawan_model->get_by(['id_departemen' => $data['record']['id_departemen']])->result();

        $parameter         = array('id' => $data['record']['id_departemen']);
        $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

        $parameter = array('nip' => $data['record']['id_karyawan']);
        $karyawan = $this->Karyawan_model->get_by($parameter)->row_array();

        $data['record']['nama_karyawan'] = $karyawan['nama_lengkap'] . " (NIP. ".$data['record']['id_karyawan']."; Dept. ". $departemen_detail['nama_departemen'] . ")"; ;
        // echo "<pre>";
        // print_r($data);
        $this->template->load('Template', 'izin/edit_izin', $data);
      }

    }
  }

  function update(){

    $originalDate = $this->input->post('input_tanggal_awal', true);
    $tgl_awal = date("Y-m-d", strtotime($originalDate));

    $originalDate = $this->input->post('input_tanggal_akhir', true);
    $tgl_akhir = date("Y-m-d", strtotime($originalDate));

    if (!empty($_FILES["imgInp"]["name"])) {
      $file_name = $this->Data_izin_model->_uploadImage("izin_" . id());
      if ($file_name['status']) {

        $parameter = array('id' => $this->input->post('id'));
        $existing_data = $this->Data_izin_model->get_by($parameter)->row_array();

        if($existing_data['attachment'] !== null){
          unlink('uploads/attachment/'.$existing_data['attachment']); //hapus existing file
        }

        $object = array(
          'id_karyawan'   => $this->input->post('input_karyawan', true),
          'id_departemen' => $this->input->post('input_departemen', true),
          'id_jenis_izin' => $this->input->post('input_jenis_izin', true),
          'tanggal_awal'  => $tgl_awal,
          'tanggal_akhir' => $tgl_akhir,
          'keterangan'    => $this->input->post('input_keterangan', true),
          'status_approval'   => $this->input->post('input_status_approval', true),
          'attachment'        => $file_name['original_image'],
        );

        $where = array('id' => $this->input->post('id'));

        if($object['status_approval'] == 1){ // jika di approve

          $tanggal_awal_existing   = $existing_data['tanggal_awal'];
          $tanggal_akhir_existing  = $existing_data['tanggal_akhir'];

          $tanggal_awal_new        = $tgl_awal;
          $tanggal_akhir_new       = $tgl_akhir;

          if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_existing == $tanggal_akhir_new){

            $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

            if($existing_data_2['status'] == 1){

              if($this->input->post('input_jenis_izin', true) == 3){
                $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
                $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
                $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
                $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
                if($simulated > $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else {
                  if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                    </div>');
                    $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                    echo json_encode($result);
                  } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                    ///UPDATE HERE
                    $inserted = $this->Data_izin_model->update($object, $where);

                    $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                    if($result){
                      $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                      <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                      </div>');
                      $result = array('status' => true, "id" => $inserted, "message" => "sukses 1");
                      echo json_encode($result);
                    }
                  }
                }
              } else {
                ///UPDATE HERE
                $inserted = $this->Data_izin_model->update($object, $where);

                $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                if($result){
                  $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                  </div>');
                  $result = array('status' => true, "id" => $inserted, "message" => "sukses 1");
                  echo json_encode($result);
                }
              }

            } else {
              if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_new == $tanggal_akhir_new){

                $inserted = $this->Data_izin_model->update($object, $where);

                $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                </div>');
                $result = array('status' => true, "id" => NULL, "message" => "sukses 2");
                echo json_encode($result);
              } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
                </div>');
                $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
                echo json_encode($result);
              }
            }

          } else if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_existing != $tanggal_akhir_new){ // jika ada perubahan tanggal akhir

            //hapus existing log by id izin
            $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

            $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

            if($existing_data_2['status'] == 1){
              $inserted = $this->Data_izin_model->update($object, $where);

              $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
              if($result){
                $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                </div>');
                $result = array('status' => true, "id" => $inserted, "message" => "sukses 3");
                echo json_encode($result);
              }
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
              </div>');
              $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
              echo json_encode($result);
            }

          } else if($tanggal_awal_existing != $tanggal_awal_new && $tanggal_akhir_existing == $tanggal_akhir_new){
            //hapus existing log by id izin
            $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

            $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

            if($existing_data_2['status'] == 1){

              if($this->input->post('input_jenis_izin', true) == 3){
                $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
                $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
                $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
                $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
                if($simulated > $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else {
                  if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                    </div>');
                    $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                    echo json_encode($result);
                  } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                    ///UPDATE HERE
                    $inserted = $this->Data_izin_model->update($object, $where);

                    $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                    if($result){
                      $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                      <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                      </div>');
                      $result = array('status' => true, "id" => $inserted, "message" => "sukses 4");
                      echo json_encode($result);
                    }
                  }
                }
              } else {
                ///UPDATE HERE
                $inserted = $this->Data_izin_model->update($object, $where);

                $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                if($result){
                  $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                  </div>');
                  $result = array('status' => true, "id" => $inserted, "message" => "sukses 1");
                  echo json_encode($result);
                }
              }
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
              </div>');
              $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
              echo json_encode($result);
            }
          } else if($tanggal_awal_existing != $tanggal_awal_new && $tanggal_akhir_existing != $tanggal_akhir_new){
            //hapus existing log by id izin
            $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

            $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

            if($existing_data_2['status'] == 1){
              if($this->input->post('input_jenis_izin', true) == 3){
                $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
                $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
                $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
                $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
                if($simulated > $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else {
                  if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                    </div>');
                    $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                    echo json_encode($result);
                  } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                    $inserted = $this->Data_izin_model->update($object, $where);

                    $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                    if($result){
                      $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                      <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                      </div>');
                      $result = array('status' => true, "id" => $inserted, "message" => "sukses 5");
                      echo json_encode($result);
                    }
                  }
                }
              }
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
              </div>');
              $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
              echo json_encode($result);
            }
          } else {
            $inserted = $this->Data_izin_model->update($object, $where);

            $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
            </div>');
            $result = array('status' => true, "id" => null, "message" => "sukses 6");
            echo json_encode($result);
          }

        } else {
          $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);
          $inserted = $this->Data_izin_model->update($object, $where);

          $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
          </div>');
          $result = array('status' => true, "id" => null, "message" => "sukses 7");
          echo json_encode($result);
        }

      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal. '.$file_name['message'].'
        </div>');
        $result = array('status' => false, "id" => null, "message" => $file_name['message']);
        echo json_encode($result);
      }
    } else {

      if($_FILES["imgInp"]["name"] == ""){
        $file_name = null;
      } else {
        $file_name = $this->input->post('old_image',  true);
      }

      $parameter = array('id' => $this->input->post('id'));
      $existing_data = $this->Data_izin_model->get_by($parameter)->row_array();

      if($existing_data['attachment'] !== null){
        unlink('uploads/attachment/'.$existing_data['attachment']); //hapus existing file
      }

      $object = array(
        'id_karyawan'   => $this->input->post('input_karyawan', true),
        'id_departemen' => $this->input->post('input_departemen', true),
        'id_jenis_izin' => $this->input->post('input_jenis_izin', true),
        'tanggal_awal'  => $tgl_awal,
        'tanggal_akhir' => $tgl_akhir,
        'keterangan'    => $this->input->post('input_keterangan', true),
        'status_approval'   => $this->input->post('input_status_approval', true),
        'attachment'        => $file_name
      );

      $where = array('id' => $this->input->post('id'));

      if($object['status_approval'] == 1){ // jika di approve

        $tanggal_awal_existing   = $existing_data['tanggal_awal'];
        $tanggal_akhir_existing  = $existing_data['tanggal_akhir'];

        $tanggal_awal_new        = $tgl_awal;
        $tanggal_akhir_new       = $tgl_akhir;

        if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_existing == $tanggal_akhir_new){

          $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

          if($existing_data_2['status'] == 1){
            if($this->input->post('input_jenis_izin', true) == 3){
              $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
              $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
              $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
              $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
              if($simulated > $jatah_cuti_tahunan){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                </div>');
                $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                echo json_encode($result);
              } else {
                if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                  ///UPDATE HERE
                  $inserted = $this->Data_izin_model->update($object, $where);

                  $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                  if($result){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                    </div>');
                    $result = array('status' => true, "id" => $inserted, "message" => "sukses 1 ini");
                    echo json_encode($result);
                  }
                }
              }
            }
          } else {
            if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_new == $tanggal_akhir_new){
              $inserted = $this->Data_izin_model->update($object, $where);
              $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
              </div>');
              $result = array('status' => true, "id" => NULL, "message" => "sukses 2 ini");
              echo json_encode($result);
            } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
              <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
              </div>');
              $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
              echo json_encode($result);
            }
          }

        } else if($tanggal_awal_existing == $tanggal_awal_new && $tanggal_akhir_existing != $tanggal_akhir_new){ // jika ada perubahan tanggal akhir

          //hapus existing log by id izin
          $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

          $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

          if($existing_data_2['status'] == 1){
            if($this->input->post('input_jenis_izin', true) == 3){
              $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
              $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
              $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
              $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
              if($simulated > $jatah_cuti_tahunan){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                </div>');
                $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                echo json_encode($result);
              } else {
                if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                  ///UPDATE HERE
                  $inserted = $this->Data_izin_model->update($object, $where);

                  $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                  if($result){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                    </div>');
                    $result = array('status' => true, "id" => $inserted, "message" => "sukses 3 ini");
                    echo json_encode($result);
                  }
                }
              }
            }

          } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
            </div>');
            $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
            echo json_encode($result);
          }

        } else if($tanggal_awal_existing != $tanggal_awal_new && $tanggal_akhir_existing == $tanggal_akhir_new){
          //hapus existing log by id izin
          $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

          $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

          if($existing_data_2['status'] == 1){
            if($this->input->post('input_jenis_izin', true) == 3){
              $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
              $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
              $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
              $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
              if($simulated > $jatah_cuti_tahunan){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                </div>');
                $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                echo json_encode($result);
              } else {
                if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                  ///UPDATE HERE
                  $inserted = $this->Data_izin_model->update($object, $where);

                  $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                  if($result){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                    </div>');
                    $result = array('status' => true, "id" => $inserted, "message" => "sukses 4 ini");
                    echo json_encode($result);
                  }
                }
              }
            }

          } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
            </div>');
            $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
            echo json_encode($result);
          }
        }  else if($tanggal_awal_existing != $tanggal_awal_new && $tanggal_akhir_existing != $tanggal_akhir_new){
          //hapus existing log by id izin
          $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);

          $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tanggal_awal_new, $tanggal_akhir_new);

          if($existing_data_2['status'] == 1){
            if($this->input->post('input_jenis_izin', true) == 3){
              $jatah_cuti_tahunan = $this->Cuti_model->get_data()->row_array()['hak_cuti'];
              $total_cuti_sudah_diambil = $this->jumlah_cuti_tahun_ini($this->input->post('input_karyawan', true));
              $total_pengajuan = $this->Time_dim_model->get_date($tgl_awal, $tgl_akhir)->result();
              $simulated = count($total_pengajuan) + $total_cuti_sudah_diambil;
              if($simulated > $jatah_cuti_tahunan){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                <b><i class="fa fa-exclamation-triangle"></i></b> Mohon periksa kembali tanggal pengajuan yg melebihi jatah cuti tahunan
                </div>');
                $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                echo json_encode($result);
              } else {
                if($total_cuti_sudah_diambil >= $jatah_cuti_tahunan){
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                  <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal! Jatah cuti tahunan sudah habis
                  </div>');
                  $result = array('status' => false, "id" => null, "message" => "Jatah cuti tahunan sudah habis");
                  echo json_encode($result);
                } else if($total_cuti_sudah_diambil < $jatah_cuti_tahunan){
                  ///UPDATE HERE
                  $inserted = $this->Data_izin_model->update($object, $where);

                  $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
                  if($result){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
                    <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
                    </div>');
                    $result = array('status' => true, "id" => $inserted, "message" => "sukses 5 ini");
                    echo json_encode($result);
                  }
                }
              }
            }

          } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
            <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
            </div>');
            $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
            echo json_encode($result);
          }
        }

      } else {
        $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);
        $inserted = $this->Data_izin_model->update($object, $where);

        $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
        </div>');
        $result = array('status' => true, "id" => null, "message" => "sukses 6 ini");
        echo json_encode($result);
      }
    }
  }

  function delete($id){
    $object = array(
      'is_del'            => 1,
    );

    $where = array('id' => $id);
    $inserted = $this->Data_izin_model->update($object, $where);
    $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $id]);
    $result = array('status' => true);
    echo json_encode($result);
  }

  function insertLog($id_karyawan, $id_izin, $start_date, $end_date){
    $insert_data = array();
    $date_list = $this->Time_dim_model->get_date($start_date, $end_date)->result();
    foreach ($date_list as $r) {
      $insert_data[] = array(
        'id_karyawan'       => $id_karyawan,
        'tanggal'           => $r->db_date,
        'jam_masuk'         => '00:00:00',
        'lokasi_masuk'      => '-',
        'kordinat_masuk'    => '-',
        'foto_masuk'        => '-',
        'keterangan_masuk'  => '-',
        'jam_pulang'        => '00:00:00',
        'lokasi_pulang'     => '-',
        'kordinat_pulang'   => '-',
        'foto_pulang'       => '-',
        'keterangan_pulang' => '-',
        'status_kehadiran'  => '-',
        'id_izin'           => $id_izin,
        'input_by'          => $this->session->userdata('username'),
        'input_datetime'    => date('Y-m-d H:i:s'),
        'is_del'            => 0,
        'client_id'         => $this->session->userdata('client_id')
      );
    }

    $result = $this->Log_presensi_model->save_batch($insert_data);
    if($result > 0){
      return true;
    } else {
      return false;
    }
  }

  function cekExistingIzin($id_karyawan, $start_date, $end_date){
    $tanggal_pengajuan  = array();
    $pengajuan_existing = array();
    $date_list = $this->Time_dim_model->get_date($start_date, $end_date)->result();
    foreach ($date_list as $r) {
      $id_izin = $this->Log_presensi_model->get_by( ['id_karyawan' => $id_karyawan, 'tanggal' => $r->db_date] )->row_array()['id_izin'];
      if($id_izin != "" && $id_izin != "-"){
        $pengajuan_existing[] = array( 'tanggal'  => $r->db_date, 'id_izin' => $id_izin );
      }
      $tanggal_pengajuan[] = array( 'tanggal'  => $r->db_date, 'id_izin' => $id_izin );
    }

    if(!empty($pengajuan_existing)){
      $list = "";
      foreach ($pengajuan_existing as $rr) {
        $list .= formatTglIndo($rr['tanggal']).", ";
      }
      $list .= ".";
      $list = str_replace(", .", ".", $list);
      $result = array('status'=> 0, 'message' => "Sudah ada data izin atau cuti di tanggal ".$list);
    } else {
      $result = array('status'=> 1, 'message' => "OK");
    }

    return $result;
  }

  function removeExistingLogIzin(){
    $delete = $this->Log_presensi_model->deleteBy(['id_izin' => '2larnybiiaass', 'id_karyawan' => '2fj864jigbokk']);
  }

  function crap(){
    if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){
      //hapus existing log by id izin
      $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);
      $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tgl_awal, $tgl_akhir);

      if($existing_data_2['status'] == 1){

        $inserted = $this->Data_izin_model->update($object, $where);

        $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
        if($result){
          $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
          </div>');
          $result = array('status' => true, "id" => $inserted, "message" => "sukses");
          echo json_encode($result);
        }
      } else {
        if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){

          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
          </div>');
          $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
          echo json_encode($result);

        } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] == $tgl_akhir){
          $result = array('status' => true, "id" => null, "message" => "sukses");
          echo json_encode($result);
        }
      }

    } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] == $tgl_akhir){
      $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tgl_awal, $tgl_akhir);

      if($existing_data_2['status'] == 1){

        $inserted = $this->Data_izin_model->update($object, $where);

        $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
        if($result){
          $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
          </div>');
          $result = array('status' => true, "id" => $inserted, "message" => "sukses");
          echo json_encode($result);
        }
      } else {
        if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){

          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
          </div>');
          $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
          echo json_encode($result);

        } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] == $tgl_akhir){
          $result = array('status' => true, "id" => null, "message" => "sukses");
          echo json_encode($result);
        }
      }
    } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir) {
      //hapus existing log by id izin
      $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);
      $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tgl_awal, $tgl_akhir);

      if($existing_data_2['status'] == 1){

        $inserted = $this->Data_izin_model->update($object, $where);

        $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
        if($result){
          $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
          </div>');
          $result = array('status' => true, "id" => $inserted, "message" => "sukses");
          echo json_encode($result);
        }
      } else {
        if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){

          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
          </div>');
          $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
          echo json_encode($result);

        } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] == $tgl_akhir){
          $result = array('status' => true, "id" => null, "message" => "sukses");
          echo json_encode($result);
        }
      }
    } else if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir) {
      //hapus existing log by id izin
      $delete = $this->Log_presensi_model->deleteBy(['id_izin' => $this->input->post('id'), 'id_karyawan' => $this->input->post('input_karyawan')]);
      $existing_data_2 = $this->cekExistingIzin($this->input->post('input_karyawan', true), $tgl_awal, $tgl_akhir);

      if($existing_data_2['status'] == 1){

        $inserted = $this->Data_izin_model->update($object, $where);

        $result = $this->insertLog($object['id_karyawan'], $this->input->post('id'), $object['tanggal_awal'], $object['tanggal_akhir']  );
        if($result){
          $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
          </div>');
          $result = array('status' => true, "id" => $inserted, "message" => "sukses");
          echo json_encode($result);
        }
      } else {
        if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){

          $this->session->set_flashdata('message', '<div class="alert alert-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
          <b><i class="fa fa-exclamation-triangle"></i></b> Edit data gagal! '.$existing_data_2['message'].'
          </div>');
          $result = array('status' => false, "id" => null, "message" => $existing_data_2['message']);
          echo json_encode($result);

        } else if($existing_data['tanggal_awal'] == $tgl_awal && $existing_data['tanggal_akhir'] == $tgl_akhir){
          $result = array('status' => true, "id" => null, "message" => "sukses");
          echo json_encode($result);
        }
      }
    }

    // die();

    // if($existing_data['tanggal_awal'] != $tgl_awal){
    // } else {
    //   $existing_data = ['status' => 1];
    // }

    // if($delete){
    //
    //
    // }

    // if($existing_data['tanggal_awal'] != $tgl_awal && $existing_data['tanggal_akhir'] != $tgl_akhir){
    //
    //
    // } else {
    //   $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
    //   <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
    //   </div>');
    //   $result = array('status' => true, "id" => $inserted, "message" => "sukses");
    //   echo json_encode($result);
    // }



    // $result = $this->insertLog($object['id_karyawan'], $object['id'], $object['tanggal_awal'], $object['tanggal_akhir']  );
    // if($result){
    //   $this->session->set_flashdata('message', '<div class="alert alert-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
    //   <b><i class="fa fa-thumbs-up"></i></b> Edit data sukses!
    //   </div>');
    //   $result = array('status' => true, "id" => $inserted, "message" => "sukses");
    //   echo json_encode($result);
    // }
  }

}
