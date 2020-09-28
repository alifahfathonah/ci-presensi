<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembur extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Karyawan_model', 'Lembur_model', 'Departemen_model', 'Time_dim_model'));
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
    $data['departemen'] = $this->Departemen_model->get_data()->result();
    $this->template->load('Template', 'lembur/view_lembur', $data);
  }

  public function ajax_list(){
    $list = $this->Lembur_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

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

      $row = array();
      $row[] = $no;
      $row[] = $karyawan['nama_lengkap'];
      $row[] = $departemen_detail['nama_departemen'];
      $row[] = formatTglIndo($r->tanggal);
      $row[] = $r->jam_masuk;
      $row[] = $r->jam_pulang;
      $row[] = $status_approval;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <a href="'.base_url('lembur/detail/'.$r->id).'" role="button" name="btn-edit" class="btn btn-sm btn-link text-warning" title="Detail"><b class="fa fa-search"></b></a>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Lembur_model->count_all(),
      "recordsFiltered" => $this->Lembur_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function dummy(){
    $data_tanggal = $this->Time_dim_model->get_current_day_month()->result();
    $data_karyawan = $this->Karyawan_model->get_data()->result();
    $insert_data = array();
    foreach ($data_tanggal as $r) {
      $tanggal = $r->id;

      if($r->day_name !== "Saturday" && $r->day_name !== "Sunday"){

        foreach ($data_karyawan as $rr) {

          $insert_data[] = array(
                            'id_karyawan'       => $rr->id,
                            'id_departemen'     => $rr->id_departemen,
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
                            'status_approval'   => '0',
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

    for ($i=0; $i < 20 ; $i++) {
      echo "<pre>";
      print_r($insert_data[$i]);
      // $result = $this->Lembur_model->save($insert_data[$i]);
    }

    // $result = $this->Lembur_model->save_batch($insert_data);
    // echo "total inserted ".$result;

  }

  function getKaryawanByDept($id_dept){
    $data = $this->Karyawan_model->get_by(['id_departemen' => $id_dept])->result();
    echo '<option value="x" selected>All</option>';
    foreach ($data as $r) {
      echo '<option value="'.$r->id.'">'.$r->nama_lengkap.'</option>';
    }
  }

  function detail($id){
    if($id == ""){
      redirect('lembur');
    } else {
      $parameter = ['id' => $id];
      $data['record'] = $this->Lembur_model->get_by($parameter)->row_array();

      $parameter = array('id' => $data['record']['id_karyawan']);
      $karyawan = $this->Karyawan_model->get_by($parameter)->row_array();

      $parameter         = array('id' => $data['record']['id_departemen']);
      $departemen_detail = $this->Departemen_model->get_by($parameter)->row_array();

      $data['record']['nama_lengkap']    = $karyawan['nama_lengkap'];
      $data['record']['nama_departemen'] = $departemen_detail['nama_departemen'];
      // print_r($data['record']);
      if(empty($data['record'])){
        redirect('lembur');
      } else {
        $this->template->load('Template', 'lembur/view_detail_lembur', $data);
      }
    }
  }

  function updateStatusApproval(){
    $object = ['status_approval' => $_POST['param'][1]];
    $where  = ['id' => $_POST['param'][0]];
    $this->Lembur_model->update($object, $where);
    echo json_encode(['status' => true]);
  }

}
