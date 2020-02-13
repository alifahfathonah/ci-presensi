<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelunasan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('General_model', 'Pinjaman_model', 'Anggota_model', 'Jenissimpanan_model', 'Lap_kas_anggota_model', 'Profile_model', 'Angsuran_model',
    'Pelunasan_model', 'Bunga_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/pelunasan/view_pelunasan');
  }

  function ajax_list(){
    $list = $this->Pelunasan_model->get_datatables();
    $data = array();
    $no = $_POST['start'];

    foreach ($list as $r) {

      $anggota       = $this->General_model->get_data_anggota($r->anggota_id);
			$jml_bayar     = $this->General_model->get_jml_bayar($r->id);
			$jml_denda     = $this->General_model->get_jml_denda($r->id);
			$total_tagihan = $r->tagihan + $jml_denda->total_denda;
			$sisa_tagihan  = $total_tagihan - $jml_bayar->total;

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = 'TPJ' . sprintf('%05d', $r->id);
      $row[] = $anggota->identitas.' - '.$anggota->nama ;
      $row[] = $anggota->departement ;
      $row[] = explode(' - ', formatTglIndo_datetime_2($r->tgl_pinjam_display))[0]; //$r->tgl_pinjam_display ;
      // $row[] = $r->tgl_pinjam ;
      $row[] = explode(' - ', formatTglIndo_datetime($r->tempo))[0];
      $row[] = $r->lama_angsuran.' Bulan' ;
      $row[] = rupiah(nsi_round($total_tagihan)) ;
      $row[] = rupiah(nsi_round($jml_denda->total_denda)) ;
      $row[] = rupiah(nsi_round($jml_bayar->total)) ;
      $row[] = '<a class="btn btn-sm btn-outline-info" role="button" href="'.base_url().'angsuran_lunas/index/'.$r->id.'"><i class="fa fa-search"></i></a>';

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Pelunasan_model->count_all(),
      "recordsFiltered" => $this->Pelunasan_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

}
