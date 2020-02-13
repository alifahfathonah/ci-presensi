<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_lunas extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('Profile_model', 'Pinjaman_model', 'Anggota_model', 'Jenissimpanan_model', 'Lap_kas_anggota_model',
    'Profile_model', 'Angsuran_lunas_model', 'Angsuran_model', 'General_model'));
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
    } else {
      $data_pinjam = $this->Pinjaman_model->get_data_pinjam($id);

      $data['data_anggota']     = $this->Anggota_model->get_by('id', $data_pinjam->anggota_id);
      $data['row_pinjam']       = $data_pinjam;

      $data['kas_id']           = $this->Angsuran_model->get_data_kas();
      $data['angsuran']         = $this->Angsuran_model->get_data_angsuran($id);

      $data['hitung_denda']     = $this->Pinjaman_model->get_jml_denda($id);
      $data['hitung_dibayar']   = $this->Pinjaman_model->get_jml_bayar($id);
      $data['sisa_ags']         = $this->Pinjaman_model->get_record_bayar($id);

      $data['simulasi_tagihan'] = $this->Pinjaman_model->get_simulasi_pinjaman($id);
      $this->template->load('Template', 'back/angsuran_lunas/view_angsuran_lunas', $data);
    }
  }

  public function ajax_list(){
    // $_POST['id'] = $this->uri->segment(3);
    $list = $this->Angsuran_lunas_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->kode ;
      $row[] = $r->tgl_bayar ;
      $row[] = rupiah(nsi_round($r->jumlah_bayar)) ;
      $row[] = $r->ket_bayar ;
      $row[] = $r->user_name ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <a role="button" title="Cetak nota" class="btn btn-sm btn-primary" href="'.base_url('angsuran_lunas/nota/'.$r->id).'" target="_blank"><b class="fa fa-print"></b></a>
      <button type="button" title="Hapus data" class="btn btn-sm btn-outline-danger" onclick="delete_data(\''.$r->id.'\',\''.$r->kode.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }
    $this->export_data = $data;
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Angsuran_lunas_model->count_all(),
      "recordsFiltered" => $this->Angsuran_lunas_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function validation($method){
    $this->form_validation->set_rules('input_jumlah_bayar', 'Jumlah Bayar', 'required', array('required' => 'Jumlah Bayar Tidak Boleh Kosong') );
    $this->form_validation->set_rules('simpan_ke_kas', 'Untuk Kas', 'callback_pilih_simpan_ke_kas');

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'simpan_ke_kas_error' => form_error('simpan_ke_kas', '<b class="fa fa-warning"></b> ', ' '),
        'input_jumlah_bayar_error' => form_error('input_jumlah_bayar', '<b class="fa fa-warning"></b> ', ' '),
      );
    }
    echo json_encode($array);
  }

  public function pilih_simpan_ke_kas($str){
    if ($str == 'x'){
      $this->form_validation->set_message('pilih_simpan_ke_kas', 'Silahkan Pilih Kas');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function save(){
    if(!isset($_POST)) {
      show_404();
    }
    if($this->Angsuran_lunas_model->insert()){
      echo json_encode(array('status' => true, 'msg' => 'Data berhasil disimpan'));
    }else{
      echo json_encode(array('status' => false, 'msg' => 'Gagal menyimpan data, pastikan nilai lebih dari 0 (NOL)'));
    }
    exit();
  }

  public function nota($id){
    $data_instansi = $this->Profile_model->get_data()->row_array();
    foreach ($data_instansi as $key => $value){
      $out[$key] = $value;
    }

    $data_angsuran = $this->Angsuran_model->get_by('id', $id)->result();

    $pdf = new fpdf('L', 'mm', array(210, 150));
    $pdf->SetMargins(5, 2, 5);
    $pdf->SetAutoPageBreak(false);
    $pdf->addPage();

    $pdf->SetFont('Courier','B',12);
    $pdf->cell(100, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

    $pdf->SetFont('Courier','', 9);
    $pdf->cell(100, 3, 'Jl. Dr. Suharso No.52 Purwokerto Tel. 0281-632324', 0, 1);

    $pdf->cell(200, 1, '________________________________________________________________________________________________________', 0, 1);

    $pdf->cell(133, 5, '', 0, 1);

    $pdf->SetFont('Courier','B',9);
    $pdf->cell(190, 5, 'BUKTI PELUNASAN KREDIT', 0, 1, 'C');

    foreach ($data_angsuran as $r) {
      $pinjaman   = $this->General_model->get_data_pinjam($r->pinjam_id);

      $anggota_id = $pinjaman->anggota_id;
      $anggota    = $this->General_model->get_data_anggota($anggota_id);

      $hitung_dibayar = $this->General_model->get_jml_bayar($r->pinjam_id);
      $dibayar        = $hitung_dibayar->total;
      $tagihan        = $pinjaman->ags_per_bulan * $pinjaman->lama_angsuran;

      $hitung_denda = $this->General_model->get_jml_denda($r->pinjam_id);
      $jml_denda=$hitung_denda->total_denda;

      $sisa_bayar = $tagihan - $dibayar + $jml_denda ;


      $pdf->SetFont('Courier','',9);
      $pdf->cell(190, 4, 'No. Transaksi '.'TRD'.sprintf('%05d', $r->id), 0, 1, 'C');
      $pdf->cell(133, 5, '', 0, 1);
      $pdf->SetFont('Courier','',9);
      $pdf->cell(133, 4, 'Telah terima dari Bapak/Ibu '.strtoupper($anggota->nama).' ('.'AG'.sprintf('%04d', $anggota_id).') pada tanggal '.
      explode(' - ', formatTglIndo_datetime($r->tgl_bayar))[0], 0, 1);
      $pdf->cell(18, 4, 'sejumlah', 0, 0);
      $pdf->SetFont('Courier','B',9);
      $pdf->cell(100, 4, 'Rp. '.rupiah($r->jumlah_bayar).' ('.strtoupper(terbilang($r->jumlah_bayar)).'RUPIAH )', 0, 0);
      $pdf->SetFont('Courier','',9);
      $pdf->cell(18, 4, 'untuk Pelunasan Pembayaran Kredit', 0, 1);

      $pdf->Ln();

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Nomor Pinjam', 0, 0);
      $pdf->cell(3, 4, ':', 0, 0);
      $pdf->cell(150, 4, 'TPJ'.sprintf('%05d', $pinjaman->id), 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Tanggal Pinjam', 0, 0);
      $pdf->cell(3, 4, ':', 0, 0);
      $pdf->cell(150, 4, explode(' - ', formatTglIndo_datetime($pinjaman->tgl_pinjam))[0], 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Tanggal Tempo', 0, 0);
      $pdf->cell(3, 4, ':', 0, 0);
      $pdf->cell(150, 4, explode(' - ', formatTglIndo_datetime($pinjaman->tempo))[0], 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Lama Pinjam', 0, 0);
      $pdf->cell(3, 4, ':', 0, 0);
      $pdf->cell(150, 4, $pinjaman->lama_angsuran . ' Bulan', 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Pokok Pinjaman', 0, 0);
      $pdf->cell(3, 4, ':', 0, 0);
      $pdf->cell(150, 4, 'Rp. '. rupiah($pinjaman->jumlah), 0, 1);

      $pdf->Ln();

      $pdf->SetFont('Courier','B',9);
      $pdf->cell(35, 4, 'Detail Pembayaran', 0, 1);

      $pdf->Ln();

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Total Pinjaman', 0, 0);
      $pdf->cell(3, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($tagihan), 0, 0, 'R');
      $pdf->cell(40, 4, 'Sisa Tagihan :', 0, 0, 'R');
      $pdf->cell(40, 4, 'Rp. '.rupiah($sisa_bayar), 0, 1, 'l');

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Total Denda', 0, 0);
      $pdf->cell(3, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($jml_denda), 0, 0, 'R');
      $pdf->cell(40, 4, 'Status Pelunasan :', 0, 0, 'R');
      $pdf->SetFont('Courier','B',9);
      $pdf->cell(40, 4, strtoupper($pinjaman->lunas), 0, 1, 'l');

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Total Tagihan', 0, 0);
      $pdf->cell(3, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($tagihan + $jml_denda), 0, 1, 'R');

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Sudah Dibayar', 0, 0);
      $pdf->cell(3, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($dibayar - $r->jumlah_bayar), 0, 1, 'R');

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'Pelunasan', 0, 0);
      $pdf->cell(3, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($r->jumlah_bayar), 0, 1, 'R');

      $pdf->Ln();

      $pdf->SetFont('Courier','',9);
      $pdf->cell(35, 4, 'TERBILANG :'.strtoupper(terbilang($r->jumlah_bayar). 'RUPIAH'), 0, 1);

      $pdf->Ln();
      $pdf->cell(100, 4, '', 0, 0);
      $pdf->cell(3, 4, $out['kota'].', '.formatTglIndo_3(Date('Y-m-d')), 0, 1);

      $pdf->Ln();$pdf->Ln();$pdf->Ln();
      $pdf->cell(100, 4, strtoupper($r->user_name), 0, 0,  'C');
      $pdf->cell(70, 4, strtoupper($anggota->nama), 0, 1,  'C');

    }
    $pdf->Ln();$pdf->Ln();$pdf->Ln();
    $pdf->SetFont('Courier','I', 8);
    $pdf->cell(210, 4, '** Tanda terima ini sah jika telah dibubuhi cap dan tanda tangan oleh Admin **', 0, 1, 'C');

    $pdf->Output();
  }

  function delete(){
    $id        = $_POST['param'][0];
		$master_id = $_POST['param'][1];
    $reason    = $_POST['param'][2];
		if($this->Angsuran_lunas_model->delete($id, $master_id, $reason)){
      echo json_encode(array('status' => true, 'message' => 'Success'));
    } else {
      echo json_encode(array('status' => false, 'message' => 'Failed'));
    }
	}
  

}
