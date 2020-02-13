<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bayar extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('General_model','Pinjaman_model', 'Anggota_model', 'Jenissimpanan_model', 'Lap_kas_anggota_model', 'Profile_model', 'Angsuran_model',
    'Bayar_model', 'Bunga_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index(){
    $this->template->load('Template', 'back/bayar/view_bayar');
  }

  function ajax_list(){
    $list = $this->Bayar_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $data_bunga_arr = $this->Bunga_model->get_key_val();
    foreach ($list as $r) {

      $anggota = $this->General_model->get_data_anggota($r->anggota_id);

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = 'PJ' . sprintf('%05d', $r->id) ;
      $row[] = explode(' - ', formatTglIndo_datetime_3($r->tgl_pinjam))[0]; //$r->tgl_pinjam_display ;
      $row[] = $anggota->identitas ;
      $row[] = $r->nama ;
      $row[] = rupiah($r->jumlah) ;
      $row[] = $r->lama_angsuran . " Bulan";
      $row[] = rupiah($r->biaya_adm) ;
      $row[] = rupiah($r->pokok_angsuran) ;
      $row[] = rupiah($r->bunga_pinjaman) ;
      $row[] = rupiah(nsi_round($r->ags_per_bulan)) ;

      // Jatuh Tempo
			$sdh_ags_ke = $r->bln_sudah_angsur;
			$ags_ke = $r->bln_sudah_angsur + 1;

			$denda_hari = $data_bunga_arr['denda_hari'];
			$tgl_pinjam = substr($r->tgl_pinjam, 0, 7) . '-01';
			$tgl_tempo = date('Y-m-d', strtotime("+".$ags_ke." months", strtotime($tgl_pinjam)));
			$tgl_tempo = substr($tgl_tempo, 0, 7) . '-' . sprintf("%02d", $denda_hari);
			$txt_status = '';
			$txt_status_tip = 'Ags Ke: ' . $ags_ke . ' Tempo: ' . $tgl_tempo;

      if($tgl_tempo < date('Y-m-d')) {
				$row['merah'] = 1;
				$txt_status .= '<span title="'.$txt_status_tip.'" class="text-danger"><i class="fa fa-warning"></i></span>';
			} else {
				$row['merah'] = 0;
				$txt_status .= '<span title="'.$txt_status_tip.'" class="text-success"><i class="fa fa-check-circle" title="'.$txt_status_tip.'"></i></span>';
			}

      $row[] = $txt_status . " " . '<a href="'.base_url().'angsuran/index/'.$r->id.'"><i class="fa fa-money"></i> Bayar</a>' ;

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Bayar_model->count_all(),
      "recordsFiltered" => $this->Bayar_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

}
