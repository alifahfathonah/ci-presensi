<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load library phpspreadsheet
// require('./PHPSpreadsheet/vendor/autoload.php');
//
// use PhpOffice\PhpSpreadsheet\Helper\Sample;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

class Anggota extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Anggota_model', 'Klien_korporasi_model'));
    $this->load->library(array('ion_auth', 'form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/view_anggota');
  }

  public function ajax_list(){
    $list = $this->Anggota_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $anggota) {

      $photo     = ($anggota->file_pic == null || $anggota->file_pic == "") ? base_url().'uploads/photo_default.jpg' : base_url().'uploads/anggota/'.$anggota->file_pic ;
      $is_active = ($anggota->aktif == 'Y') ? 'Aktif' : 'Non Aktif' ;
      $gender    = ($anggota->jk == 'L') ? 'Laki-laki' : 'Perempuan' ;

      $row = array();
      $row[] = '<img src="'.$photo.'" alt="..." class="img-thumbnail" style="max-witdh:25%; height: auto;">';
      $row[] = 'AG'.sprintf('%04d', $anggota->id) ;
      $row[] = $this->Klien_korporasi_model->get_by('id', $anggota->id_korporasi)['nama_klien'] ;
      $row[] = $anggota->nama ;
      $row[] = rupiah($anggota->simpanan_pokok) ;
      $row[] = rupiah($anggota->simpanan_wajib) ;
      $row[] = rupiah($anggota->simpanan_sukarela) ;
      $row[] = $is_active ;
      $row[] = $gender ;
      $row[] = $anggota->alamat ;
      $row[] = $anggota->jabatan_id ;
      $row[] = $anggota->tgl_daftar ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <a role="button" title="Ubah data" class="btn btn-sm btn-warning" href="'.base_url('anggota/edit/'.$anggota->id).'"><b class="fa fa-edit"></b></a>
      <button type="button" title="Hapus data" class="btn btn-sm btn-danger" onclick="delete_data(\''.$anggota->id.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Anggota_model->count_all(),
      "recordsFiltered" => $this->Anggota_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $this->template->load('Template', 'back/view_anggota_add');
  }

  function edit($id){
    $data['record'] = $this->Anggota_model->get_by('ID', $id);
    $this->template->load('Template', 'back/view_anggota_edit', $data);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_nama', 'Nama', 'required', array('required' => 'Nama Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_username', 'Username', 'required', array('required' => 'Username Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_jenis_kelamin', 'Jenis Kelamin', 'callback_pilih_gender');
    $this->form_validation->set_rules('input_tempat_lahir', 'Tempat Lahir', 'required', array('required' => 'Tempat Lahir Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_tanggal_lahir', 'Tanggal Lahir', 'required', array('required' => 'Tanggal Lahir Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_alamat', 'Alamat', 'required', array('required' => 'Alamat Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_kota', 'Kota', 'required', array('required' => 'Kota Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_tanggal_registrasi', 'Tanggal Lahir', 'required', array('required' => 'Tanggal Registrasi Lahir Tidak Boleh Kosong') );
    $this->form_validation->set_rules('input_jabatan', 'Jabatan', 'callback_pilih_jabatan');
    $this->form_validation->set_rules('input_aktif', 'Jabatan', 'callback_pilih_aktif');

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'input_nama_error' => form_error('input_nama', '<b class="fa fa-warning"></b> ', ' '),
        'input_username_error' => form_error('input_username', '<b class="fa fa-warning"></b> ', ' '),
        'input_jenis_kelamin_error' => form_error('input_jenis_kelamin', '<b class="fa fa-warning"></b> ', ' '),
        'input_tempat_lahir_error' => form_error('input_tempat_lahir', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_lahir_error' => form_error('input_tanggal_lahir', '<b class="fa fa-warning"></b> ', ' '),
        'input_alamat_error' => form_error('input_alamat', '<b class="fa fa-warning"></b> ', ' '),
        'input_kota_error' => form_error('input_kota', '<b class="fa fa-warning"></b> ', ' '),
        'input_tanggal_registrasi_error' => form_error('input_tanggal_registrasi', '<b class="fa fa-warning"></b> ', ' '),
        'input_jabatan_error' => form_error('input_jabatan', '<b class="fa fa-warning"></b> ', ' '),
        'input_aktif_error' => form_error('input_aktif', '<b class="fa fa-warning"></b> ', ' ')
      );
    }
    echo json_encode($array);
  }

  public function pilih_gender($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_gender', 'Silahkan Pilih Jenis Kelamin');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_jabatan($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_jabatan', 'Silahkan Pilih Jabatan');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function pilih_aktif($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_aktif', 'Silahkan Status Aktif Keanggotaan');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function save(){
    $file_name = $this->Anggota_model->_uploadImage(str_replace(' ', '-', $this->input->post('input_nama', true)));
    $kd_kerja = $this->Anggota_model->get_id_kerja($this->input->post('input_pekerjaan', true));
    $object = array(
      'nama'                => $this->input->post('input_nama', true),
      'id_korporasi'        => $this->input->post('input_korporasi_id', true),
      'identitas'           => $this->input->post('input_username', true),
      'jk'                  => $this->input->post('input_jenis_kelamin', true),
      'tmp_lahir'           => $this->input->post('input_tempat_lahir', true),
      'tgl_lahir'           => $this->input->post('input_tanggal_lahir', true),
      'status'              => $this->input->post('input_status', true),
      'agama'               => $this->input->post('input_agama', true),
      'departement'         => $this->input->post('input_departement', true),
      'kd_pekerjaan'        => $kd_kerja['id_kerja'],
      'pekerjaan'           => $this->input->post('input_pekerjaan', true),
      'alamat'              => $this->input->post('input_alamat', true),
      'kota'                => $this->input->post('input_kota', true),
      'notelp'              => $this->input->post('input_hp', true),
      'tgl_daftar'          => $this->input->post('input_tanggal_registrasi', true),
      'jabatan_id'          => $this->input->post('input_jabatan', true),
      'aktif'               => $this->input->post('input_aktif', true),
      'pass_word'           => sha1('nsi' . $this->input->post('input_password', true)),
      'file_pic'            => $file_name['original_image'],
      'simpanan_wajib'      => $this->input->post('input_simpanan_wajib', true),
      'simpanan_pokok'      => $this->input->post('input_simpanan_pokok', true),
      'simpanan_sukarela'   => $this->input->post('input_simpanan_sukarela', true)
    );
    $inserted_id = $this->Anggota_model->insert($object);
    $json_return = array(
      'status'      => TRUE,
      // 'id'          => $this->input->post('id'),
      'inserted_id' => $inserted_id,
      'name'        => $object['nama']
    );
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Sukses!</strong> Data berhasil ditambahkan.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>');
    echo json_encode($json_return);
  }

  public function update(){
    $kd_kerja = $this->Anggota_model->get_id_kerja($this->input->post('input_pekerjaan', true));

    if (!empty($_FILES["imgInp"]["name"])) {
      $file_name = $this->Anggota_model->_uploadImage(str_replace(' ', '-', $this->input->post('input_nama', true)));
      $object = array(
        'nama'                => $this->input->post('input_nama', true),
        'id_korporasi'        => $this->input->post('input_korporasi_id', true),
        'identitas'           => $this->input->post('input_username', true),
        'jk'                  => $this->input->post('input_jenis_kelamin', true),
        'tmp_lahir'           => $this->input->post('input_tempat_lahir', true),
        'tgl_lahir'           => $this->input->post('input_tanggal_lahir', true),
        'status'              => $this->input->post('input_status', true),
        'agama'               => $this->input->post('input_agama', true),
        'departement'         => $this->input->post('input_departement', true),
        'kd_pekerjaan'        => $kd_kerja['id_kerja'],
        'pekerjaan'           => $this->input->post('input_pekerjaan', true),
        'alamat'              => $this->input->post('input_alamat', true),
        'kota'                => $this->input->post('input_kota', true),
        'notelp'              => $this->input->post('input_hp', true),
        'tgl_daftar'          => $this->input->post('input_tanggal_registrasi', true),
        'jabatan_id'          => $this->input->post('input_jabatan', true),
        'aktif'               => $this->input->post('input_aktif', true),
        // 'pass_word'           => sha1('nsi' . $this->input->post('input_password', true)),
        'file_pic'            => $file_name['original_image'],
        'simpanan_wajib'      => $this->input->post('input_simpanan_wajib', true),
        'simpanan_pokok'      => $this->input->post('input_simpanan_pokok', true),
        'simpanan_sukarela'   => $this->input->post('input_simpanan_sukarela', true)
      );

      if($this->input->post('input_password', true) != null){
        $object['pass_word'] = sha1('nsi' . $this->input->post('input_password', true));
      }

    } else {
      // $file_name = $this->input->post('old_image',  true);
      $object = array(
        'nama'                => $this->input->post('input_nama', true),
        'identitas'           => $this->input->post('input_username', true),
        'jk'                  => $this->input->post('input_jenis_kelamin', true),
        'tmp_lahir'           => $this->input->post('input_tempat_lahir', true),
        'tgl_lahir'           => $this->input->post('input_tanggal_lahir', true),
        'status'              => $this->input->post('input_status', true),
        'agama'               => $this->input->post('input_agama', true),
        'departement'         => $this->input->post('input_departement', true),
        'kd_pekerjaan'        => $kd_kerja['id_kerja'],
        'pekerjaan'           => $this->input->post('input_pekerjaan', true),
        'alamat'              => $this->input->post('input_alamat', true),
        'kota'                => $this->input->post('input_kota', true),
        'notelp'              => $this->input->post('input_hp', true),
        'tgl_daftar'          => $this->input->post('input_tanggal_registrasi', true),
        'jabatan_id'          => $this->input->post('input_jabatan', true),
        'aktif'               => $this->input->post('input_aktif', true),
        'pass_word'           => sha1('nsi' . $this->input->post('input_password', true)),
        // 'file_pic'            => $file_name['original_image'],
        'simpanan_wajib'      => $this->input->post('input_simpanan_wajib', true),
        'simpanan_pokok'      => $this->input->post('input_simpanan_pokok', true),
        'simpanan_sukarela'   => $this->input->post('input_simpanan_sukarela', true)
      );

      if($this->input->post('input_password', true) != null){
        $object['pass_word'] = sha1('nsi' . $this->input->post('input_password', true));
      }

    }
    $where = array('id' => $this->input->post('id') );
    $affected_row = $this->Anggota_model->update($object, $where);
    $json_return = array(
      'status'      => TRUE,
      'id'          => $this->input->post('id'),
      'inserted_id' => $affected_row,
      'name'        => $object['nama']
    );
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Sukses!</strong> Data berhasil diperbarui.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>');
    echo json_encode($json_return);
  }

  public function ajax_delete($id){
    $affected_row = $this->Anggota_model->delete_id($id);
    echo json_encode(array("status" => TRUE));
  }

  public function export_to_excel(){
    $anggota = $this->Anggota_model->get_data()->result();
    $title = 'Data Anggota KSU Sakrawarih - '.date('d-m-Y');
    header("Content-type: application/vnd-ms-excel; charset=utf-8");
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo '
    <table border="1" style="width: 250%;">
      <thead>
        <tr>
            <th>Photo</th>
            <th>ID Anggota</th>
            <th>Nama Lengkap</th>
            <th>Simpanan Pokok</th>
            <th>Simpanan Wajib</th>
            <th>Simpanan Sukarela</th>
            <th>Aktif Keanggotaan</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Jabatan</th>
            <th>Tanggal Registrasi</th>
        </tr>
      </thead>
      <tbody> ';
      $i=1; foreach($anggota as $anggota) {
              $label_aktif = ($anggota->aktif == 'Y') ? 'Aktif' : 'Non Aktif';
              $label_jk = ($anggota->jk == 'L') ? 'Laki-laki' : 'Perempuan';

              if($anggota->jabatan_id == '1'){
                $label_jabatan = 'Pengurus';
              } else if($anggota->jabatan_id == '2'){
                $label_jabatan = 'Anggota';
              } else {
                $label_jabatan = $anggota->jabatan_id;
              }

              echo '<tr>';
              echo '    <td></td>';
              echo '    <td>'.'AG'.sprintf('%04d', $anggota->id).'</td>';
              echo '    <td>'.$anggota->nama.'</td>';
              echo '    <td>'.$anggota->simpanan_pokok.'</td>';
              echo '    <td>'.$anggota->simpanan_wajib.'</td>';
              echo '    <td>'.$anggota->simpanan_sukarela.'</td>';
              echo '    <td>'.$label_aktif.'</td>';
              echo '    <td>'.$label_jk.'</td>';
              echo '    <td>'.$anggota->alamat.'</td>';
              echo '    <td>'.$label_jabatan.'</td>';
              echo '    <td>'.formatTglIndo_2($anggota->tgl_daftar).'</td>';
              echo '  </tr> ';
            $i++; }
      echo '</tbody> </table>';
    }

  }
