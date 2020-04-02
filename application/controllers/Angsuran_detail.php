<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_detail extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Pinjaman_model', 'Anggota_model', 'Jenissimpanan_model', 'Lap_kas_anggota_model', 'Profile_model', 'Angsuran_model'));
    $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table'));
    $this->load->helper(array('url', 'language', 'app_helper'));

    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in()){
      redirect('auth/login', 'refresh');
    }
  }

  function index($id){
    if(empty($id)){
      redirect('pinjaman', '_self');
    }else{
      $data_pinjam = $this->Pinjaman_model->get_data_pinjam($id);

      if(empty($data_pinjam)){
        redirect('pinjaman', '_self');
      } else {
        $data['data_anggota']     = $this->Anggota_model->get_by('id', $data_pinjam->anggota_id);
        $data['row_pinjam']       = $data_pinjam;

        $data['kas_id']           = $this->Angsuran_model->get_data_kas();
        $data['angsuran']         = $this->Angsuran_model->get_data_angsuran($id);

        $data['hitung_denda']     = $this->Pinjaman_model->get_jml_denda($id);
        $data['hitung_dibayar']   = $this->Pinjaman_model->get_jml_bayar($id);
        $data['sisa_ags']         = $this->Pinjaman_model->get_record_bayar($id);

        $data['simulasi_tagihan'] = $this->Pinjaman_model->get_simulasi_pinjaman($id);

        $this->template->load('Template', 'back/angsuran_detail/view_angsuran_detail', $data);
      }
    }
  }

}
