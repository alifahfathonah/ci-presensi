<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Lokasi_absen_model'));
    $this->load->library(array('form_validation'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    if($this->session->userdata('status') !== 'loggedin'){
        redirect(base_url("login"));
    }

  }

  function index(){
    $this->template->load('Template', 'lokasi/view_lokasi');
  }

  function tesmap(){
    $this->template->load('Template', 'lokasi/tesmap');
  }

  function cekLokasi(){

    if($this->session->userdata('id_hak_akses') != '3'){
        redirect(base_url());
    }

    $lat = $_POST['param'][0];
    $lon = $_POST['param'][1];
    $lokasi =[];
    $data = $this->Lokasi_absen_model->get_data()->result();
    $result = [];
    foreach ($data as $r => $val) {
      $resultStatus = $this->areInside($lat, $lon, $val->lat, $val->lon, $val->radius);
      if($resultStatus){
        $result = ['status' => true, 'message' => $val->nama_lokasi];
        break;
      } else {
        $result = ['status' => false, 'message' => "Anda berada diluar lokasi presensi"];
      }
      $lokasi[$r]['nama_lokasi'] = $val->nama_lokasi;
      $lokasi[$r]['lat'] = $val->lat;
      $lokasi[$r]['lon'] = $val->lon;
      $lokasi[$r]['radius'] = $val->radius;
    }
    echo json_encode($result);
  }

  function areInside($checkPointLat, $checkPointLon, $centerPointLat, $centerPointLon, $radius){
      $km = intval($radius)/1000;
      $ky = 40000 / 360;
      $kx = cos( pi() * floatval($centerPointLat) / 180.0) * $ky;
      $dx = abs( floatval($centerPointLon) - floatval($checkPointLon) ) * $kx;
        $dy = abs( floatval($centerPointLat) - floatval($checkPointLat) ) * $ky;
      return sqrt($dx * $dx + $dy * $dy) <= $km;
    }


  // function areInside($checkPointLat, $checkPointLon, $centerPointLat, $centerPointLon, $radius){
  //   $km = $radius/1000;
  //   $ky = 40000 / 360;
  //   $kx = cos( pi() * $centerPointLat / 180.0) * $ky;
  //   $dx = abs( $centerPointLon - $checkPointLon ) * $kx;
  //   $dy = abs( $centerPointLat - $checkPointLat ) * $ky;
  //   return sqrt($dx * $dx + $dy * $dy) <= $km;
  // }

  public function ajax_list(){
    $list = $this->Lokasi_absen_model->get_datatables();
    $data = array();
    $no = $_POST['start'] + 1;
    foreach ($list as $r) {

      $row = array();
      $row[] = $no;
      $row[] = $r->nama_lokasi;
      $row[] = $r->lat;
      $row[] = $r->lon;
      $row[] = $r->radius;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                  <a role="button" title="Edit" class="btn btn-sm btn-secondary text-warning" href="'.base_url('lokasi/edit/'.$r->id).'"><b class="fa fa-edit"></b></a>
                  <button type="button" title="Hapus" class="btn btn-sm btn-secondary text-danger" onclick="hapus_data('.$r->id.')"><b class="fa fa-trash"></b></a>
                </div>';
      $data[] = $row;
      $no++;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Lokasi_absen_model->count_all(),
      "recordsFiltered" => $this->Lokasi_absen_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function add(){
    $this->template->load('Template', 'lokasi/add_lokasi');
  }

  public function validation(){
      $this->form_validation->set_rules('input_nama_lokasi', 'Nama Lokasi Absen', 'required', array('required' => 'Wajib Diisi!'));
      $this->form_validation->set_rules('input_latitude', 'Latitude', 'required', array('required' => 'Wajib Diisi!'));
      $this->form_validation->set_rules('input_longitude', 'Longitude', 'required', array('required' => 'Wajib Diisi!'));
      $this->form_validation->set_rules('input_radius', 'Radius', 'required', array('required' => 'Wajib Diisi!'));

      if ($this->form_validation->run()) {
          $array = array('success' => '<div class="alert alert-success">It works!!!</div>');
      } else {
          $array = array(
              'error' => true,
              'input_nama_lokasi_error_detail'   => form_error('input_nama_lokasi', '<b class="fa fa-warning"></b> ', ' '),
              'input_latitude_error_detail'      => form_error('input_latitude', '<b class="fa fa-warning"></b> ', ' '),
              'input_longitude_error_detail'     => form_error('input_longitude', '<b class="fa fa-warning"></b> ', ' '),
              'input_radius_error_detail'        => form_error('input_radius', '<b class="fa fa-warning"></b> ', ' '),
          );
      }
      echo json_encode($array);
  }


  public function insert(){
    $object = array(
      'id_kantor'         => null,
      'nama_lokasi'       => $this->input->post('input_nama_lokasi', true),
      'lat'               => $this->input->post('input_latitude', true),
      'lon'               => $this->input->post('input_longitude', true),
      'radius'            => $this->input->post('input_radius', true),
      'input_datetime'  => date('Y-m-d H:i:s'),
      'is_del'          => 0,
      'client_id'       => $this->session->userdata('client_id')
    );
    $inserted = $this->Lokasi_absen_model->save($object);
    if($inserted){
      $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Tambah data sukses!
      </div>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Tambah data gagal!
      </div>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  function edit($id=""){
    if($id == ""){
      redirect('lokasi');
    } else {
      $data['record']       = $this->Lokasi_absen_model->get_by(array('id'=>$id))->row_array();
      $this->template->load('Template', 'lokasi/edit_lokasi', $data);
    }
  }


  public function update(){
    $object = array(
      'nama_lokasi'       => $this->input->post('input_nama_lokasi', true),
      'lat'               => $this->input->post('input_latitude', true),
      'lon'               => $this->input->post('input_longitude', true),
      'radius'            => $this->input->post('input_radius', true),
    );
    $inserted = $this->Lokasi_absen_model->update($object, array('id' => $this->input->post('id', true) ));
    if($inserted){
      $this->session->set_flashdata('message', '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-thumbs-up"></i></b> Update data sukses!
      </div>');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">
        <b><i class="fa fa-exclamation-triangle"></i></b> Update data gagal!
      </div>');
    }
    $result = array('status' => true);
    echo json_encode($result);
  }

  public function delete($id){
      $object = array(
          'is_del'         => 1
      );

      $where = array('id' => $id);
      $affected_row = $this->Lokasi_absen_model->update($object, $where);
      $result = array('status' => TRUE, "message" => "Hapus Data Berhasil", "detail_data" => null, "error" => "Delete");
      echo json_encode($result);
  }

  public function get_data(){
    $data = $this->Lokasi_absen_model->get_data()->result();
    echo json_encode($data);
  }
  //
  // public function json_detail($id=""){
  //   $data = $this->Departemen_model->get_by(array('id' => $id))->row_array();
  //   // $result = array('data' => $data);
  //   echo json_encode($data);
  // }


}
