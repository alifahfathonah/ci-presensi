<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran extends CI_Controller{

  var $_id="";

  public function __construct(){
    parent::__construct();
    $this->load->model(array('Angsuran_model', 'Pinjaman_model', 'Anggota_model', 'Bunga_model', 'General_model'));
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
      redirect('bayar', '_self');
    }

    $this->_id = $id;

    $data_pinjam = $this->Pinjaman_model->get_data_pinjam($id);

    $data['data_anggota']     = $this->Anggota_model->get_by('id', $data_pinjam->anggota_id);
    $data['row_pinjam']       = $data_pinjam;

    $data['kas_id']           = $this->Angsuran_model->get_data_kas();
    $data['angsuran']         = $this->Angsuran_model->get_data_angsuran($id);

    $data['hitung_denda']     = $this->Pinjaman_model->get_jml_denda($id);
    $data['hitung_dibayar']   = $this->Pinjaman_model->get_jml_bayar($id);
    $data['sisa_ags']         = $this->Pinjaman_model->get_record_bayar($id);

    $data['simulasi_tagihan'] = $this->Pinjaman_model->get_simulasi_pinjaman($id);

    $this->template->load('Template', 'back/angsuran/view_angsuran', $data);
  }

  public function ajax_list(){
    $list = $this->Angsuran_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $r) {

      $pinjam = $this->Pinjaman_model->get_data_pinjam($r->pinjam_id);
      $anggota = $this->Anggota_model->get_by("id", $pinjam->anggota_id);

      // HARI TELAT
      $hari_telat = 0;
      $tgl_pinjam = substr($pinjam->tgl_pinjam, 0, 7) . '-01';
      $tgl_tempo = date('Y-m-d', strtotime("+".$r->angsuran_ke." months", strtotime($tgl_pinjam)));
      $tgl_bayar  = substr($r->tgl_bayar, 0, 10);
      $data_bunga_arr = $this->Bunga_model->get_key_val();
      $denda_hari = $data_bunga_arr['denda_hari'];

      $tgl_tempo_max = date('Y-m-d', strtotime("+".($denda_hari - 1)." days", strtotime($tgl_tempo)));

      $tgl_tempo_h = str_replace('-', '', $tgl_tempo_max);
      $tgl_bayar_h = str_replace('-', '', $tgl_bayar);
      $hari_telat = $tgl_bayar_h - ($tgl_tempo_h);
      if($hari_telat < 0) {
        $hari_telat = 0;
      }

      $txt_tgl_tempo_max = jin_date_ina($tgl_tempo_max);

      $no++;
      $row = array();
      $row[] = $no ;
      $row[] = $r->kode ;
      $row[] = $r->tgl_bayar ;
      $row[] = formatTglIndo($tgl_tempo_max) ;
      $row[] = $r->angsuran_ke ;
      $row[] = rupiah(nsi_round($r->jumlah_bayar)) ;
      $row[] = rupiah($r->denda_rp) ;
      $row[] = $hari_telat.' Hari' ;
      $row[] = $r->user_name ;
      $row[] = '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      <a role="button" title="Cetak nota" class="btn btn-sm btn-primary" href="'.base_url('angsuran/nota/'.$r->id).'" target="_blank"><b class="fa fa-print"></b></a>
      <button type="button" title="Ubah data" class="btn btn-sm btn-warning" onclick="detail(\''.$r->id.'\')"><b class="fa fa-edit"></b></button>
      <button type="button" title="Hapus data" class="btn btn-sm btn-outline-danger" onclick="delete_data(\''.$r->id.'\',\''.$r->kode.'\')"><b class="fa fa-trash"></b></button>
      </div>';
      $data[] = $row;
    }
    $this->export_data = $data;
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Angsuran_model->count_all(),
      "recordsFiltered" => $this->Angsuran_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  function get_ags_ke($master_id) {
    $id_bayar = $this->input->post('id_bayar');
    if($id_bayar > 0) {
      $data_bayar = $this->General_model->get_data_pembayaran_by_id($id_bayar);
      if($data_bayar) {
        $ags_ke = $data_bayar->angsuran_ke;
      } else {
        $ags_ke = 1;
      }
    } else {
      $ags_ke = $this->General_model->get_record_bayar($master_id) + 1;
    }

    // -- bayar angsuran --
    $row_pinjam = $this->General_model->get_data_pinjam($master_id); #data pinjam
    $lama_ags = $row_pinjam->lama_angsuran; # lama angsuran
    $status_lunas = $row_pinjam->lunas; # status lunas
    $sisa_ags = $lama_ags  - $ags_ke; #sisa angsuran
    $jml_pinjaman = $row_pinjam->lama_angsuran  * $row_pinjam->ags_per_bulan; #jml pinjaman

    //hitung denda
    $denda = $this->General_model->get_jml_denda($master_id);
    $jml_denda_num = $denda->total_denda * 1;

    //hitung sudah dibayar
    $dibayar=$this->General_model->get_jml_bayar($master_id);
    $sudah_bayar= $dibayar->total * 1;

    //total harus bayar
    $total_bayar = $jml_pinjaman + $jml_denda_num;

    $sisa_tagihan = rupiah(nsi_round($row_pinjam->ags_per_bulan * $sisa_ags)); #sisa tagihan
    $sisa= $row_pinjam->ags_per_bulan * $sisa_ags; #sisa tagihan

    //sisa pembayaran
    $sisa_pembayaran = $sisa + $jml_denda_num ;

    //--- update angsuran --
    $sisa_ags_det = $row_pinjam->lama_angsuran - ($ags_ke - 1) ;
    $sudah_bayar_det = rupiah(nsi_round($dibayar ->total));
    $sisa_tagihan_num = ($jml_pinjaman - $sudah_bayar);
    $sisa_tagihan_det = rupiah(nsi_round($sisa_tagihan_num));
    $jml_denda_det = rupiah(nsi_round($jml_denda_num));
    $total_bayar_det = rupiah(nsi_round($sisa_tagihan_num + $jml_denda_num));
    $total_tagihan = rupiah(nsi_round($sisa_tagihan_num + $jml_denda_num));

    // DENDA
    $denda = 0;
    $denda_semua = 0;
    $denda_semua_num = 0;
    $tgl_pinjam = substr($row_pinjam->tgl_pinjam, 0, 7) . '-01';
    $tgl_tempo = date('Y-m-d', strtotime("+".$ags_ke." months", strtotime($tgl_pinjam)));
    $tgl_bayar  = isset($_POST['tgl_bayar']) ? $_POST['tgl_bayar'] : '';
    if($tgl_bayar != '') {
      $data_bunga_arr = $this->Bunga_model->get_key_val();
      $denda_hari = $data_bunga_arr['denda_hari'];
      $tgl_tempo = str_replace('-', '', $tgl_tempo);
      $tgl_bayar = str_replace('-', '', $tgl_bayar);
      $tgl_toleransi = $tgl_bayar - ($tgl_tempo - 1);
      if ( $tgl_toleransi > $denda_hari ) {
        $denda = '' . rupiah($data_bunga_arr['denda']);
      }
    }

    if($ags_ke > $lama_ags) {
      $data = array(
        'ags_ke' 				    => 0,
        'sisa_ags' 				  => $sisa_ags,
        'sisa_tagihan'			=> $sisa_tagihan,
        'denda' 				    => $denda,
        'sisa_pembayaran' 	=> $sisa_pembayaran,

        'sisa_ags_det' 			=> $sisa_ags_det,
        'sudah_bayar_det' 	=> $sudah_bayar_det,
        'sisa_tagihan_det'	=> $sisa_tagihan_det,
        'jml_denda_det' 		=> $jml_denda_det,
        'total_bayar_det' 	=> $total_bayar_det,

        'status_lunas' 			=> $status_lunas,
        'total_tagihan' 		=> $total_tagihan,
        'denda_semua' 			=> $denda_semua
      );

      echo json_encode($data);
    } else {
      $data = array(
        'ags_ke' 				      => $ags_ke,
        'sisa_ags' 				    => $sisa_ags,
        'sisa_tagihan'			  => $sisa_tagihan,
        'denda' 				      => $denda,
        'sisa_pembayaran' 		=> $sisa_pembayaran,

        'sisa_ags_det' 			  => $sisa_ags_det,
        'sudah_bayar_det' 		=> $sudah_bayar_det,
        'sisa_tagihan_det'		=> $sisa_tagihan_det,
        'jml_denda_det' 		  => $jml_denda_det,
        'total_bayar_det' 		=> $total_bayar_det,

        'status_lunas' 			  => $status_lunas,
        'total_tagihan' 	  	=> $total_tagihan,
        'denda_semua' 			  => $denda_semua
      );
      echo json_encode($data);
    }
    exit();
  }

  public function validation($method){
    $this->form_validation->set_rules('simpan_ke_kas', 'Untuk Kas', 'callback_pilih_simpan_ke_kas');

    if($this->form_validation->run()){
      $array = array( 'success' => '<div class="alert alert-success">It works!!!</div>' );
    }else {
      $array = array(
        'error' => true,
        'simpan_ke_kas_error' => form_error('simpan_ke_kas', '<b class="fa fa-warning"></b> ', ' '),
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

  function save(){
    if($this->Angsuran_model->save()){
      echo json_encode(array('status' => true, 'message' => 'Success'));
    } else {
      echo json_encode(array('status' => false, 'message' => 'Failed'));
    }
    exit();
  }

  function detail($id){
    $data = $this->Angsuran_model->get_by('id', $id)->row_array();
    $data['tgl_bayar_txt'] = formatTglIndo_datetime_3($data['tgl_bayar']);
    $data['pinjam_id_txt'] =  'TPJ'.sprintf('%05d', $data['pinjam_id']);
    $result = array('status' => true, 'data' => $data);

    echo json_encode($data, true);
  }

  function cek_sebelum_update() {
			$id_bayar = $this->input->post('id_bayar');
			$master_id = $this->input->post('master_id');

			$this->db->select('MAX(id) AS id_akhir');
			$this->db->where('pinjam_id', $master_id);
			$qu_akhir = $this->db->get('tbl_pinjaman_d');
			$row_akhir = $qu_akhir->row();

			$out = array('success' => '0');

			if($row_akhir->id_akhir != $id_bayar) {
				$out = array('success' => '0');
				} else {
				$this->db->select('lama_angsuran, tagihan');
				$this->db->where('id', $master_id);
				$qu_header = $this->db->get('v_hitung_pinjaman');
				$row_header = $qu_header->row();

				// sudah dibayar
				$this->db->select('SUM(jumlah_bayar) AS jumlah_bayar');
				$this->db->where('pinjam_id', $master_id);
				$qu_bayar = $this->db->get('tbl_pinjaman_d');
				$row_bayar = $qu_bayar->row();

				// berapa kali dibayar
				$this->db->select('id');
				$this->db->where('pinjam_id', $master_id);
				$qu_num_bayar = $this->db->get('tbl_pinjaman_d');
				$num_row_bayar = $qu_num_bayar->num_rows();

				//sisa tagihan
				$sisa_tagihan = number_format($row_header->tagihan - $row_bayar->jumlah_bayar);
				if($sisa_tagihan <= 0 ) {
					$sisa_tagihan = 0;
				}
				$out = array('success' => '1', 'sisa_ags' => ($row_header->lama_angsuran - $num_row_bayar), 'sisa_tagihan' => $sisa_tagihan);
			}
			echo json_encode($out);
			exit();
		}

    function update(){
      if($this->Angsuran_model->update()){
        echo json_encode(array('status' => true, 'message' => 'Success'));
      } else {
        echo json_encode(array('status' => false, 'message' => 'Failed'));
      }
      exit();
    }

    function delete(){
      if($this->Angsuran_model->delete()) {
        echo json_encode(array('status' => true, 'message' => 'Success'));
      } else {
        echo json_encode(array('status' => false, 'message' => 'Failed'));
      }
    }

    function nota_det($id){
      $data_angsuran = $this->Angsuran_model->get_by('id', $id)->row_array();
      $data_pinjaman = $this->Pinjaman_model->get_by('id', $data_angsuran['pinjam_id']);
      $data_anggota  = $this->Anggota_model->get_by('id', $data_pinjaman['anggota_id']);

      $pinjaman= $this->General_model->get_data_pinjam($data_angsuran['pinjam_id']);

  		$anggota_id = $data_pinjaman['anggota_id'];
  		$anggota= $this->General_model->get_data_anggota($anggota_id);

  		$hitung_denda = $this->General_model->get_jml_denda($data_angsuran['pinjam_id']);
  		$jml_denda=$hitung_denda->total_denda;

  		$hitung_dibayar = $this->General_model->get_jml_bayar($data_angsuran['pinjam_id']);
  		$dibayar = $hitung_dibayar->total;
  		$tagihan = $pinjaman->ags_per_bulan * $pinjaman->lama_angsuran;
  		$sisa_bayar = $tagihan - $dibayar ;

  		$total_dibayar = $sisa_bayar + $jml_denda;

      echo "<pre>";
      print_r($data_pinjaman);

      echo "<pre>";
      print_r($data_angsuran);

      echo "<pre>";
      print_r($data_anggota);


    }

    function nota($id=""){
      $data_angsuran = $this->Angsuran_model->get_by('id', $id)->row_array();
      $data_pinjaman = $this->Pinjaman_model->get_by('id', $data_angsuran['pinjam_id']);
      $data_anggota  = $this->Anggota_model->get_by('id', $data_pinjaman['anggota_id']);

      $pinjaman= $this->General_model->get_data_pinjam($data_angsuran['pinjam_id']);

  		$anggota_id = $data_pinjaman['anggota_id'];
  		$anggota= $this->General_model->get_data_anggota($anggota_id);

  		$hitung_denda = $this->General_model->get_jml_denda($data_angsuran['pinjam_id']);
  		$jml_denda=$hitung_denda->total_denda;

  		$hitung_dibayar = $this->General_model->get_jml_bayar($data_angsuran['pinjam_id']);
  		$dibayar = $hitung_dibayar->total;
  		$tagihan = $pinjaman->ags_per_bulan * $pinjaman->lama_angsuran;
  		$sisa_bayar = $tagihan - $dibayar ;

  		$total_dibayar = $sisa_bayar + $jml_denda;
      // echo "<pre>";
      // print_r($data_anggota);
      $pdf = new fpdf('L', 'mm', array(210,80));
      $pdf->SetMargins(5, 2, 5);
      $pdf->SetAutoPageBreak(false);
      $pdf->addPage();
      // $pdf->w('80');
      $pdf->cell(133, 5, '', 0, 0);

      $pdf->SetFont('Courier','B',10);
      $pdf->cell(100, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

      $pdf->SetFont('Courier','BU',10);
      $pdf->cell(133, 3, 'BUKTI SETORAN ANGSURAN KREDIT', 0, 0);

      $pdf->SetFont('Courier','',10);
      $pdf->cell(100, 3, 'Jl. Dr. Suharso No.52 Purwokerto', 0, 1);

      $pdf->SetFont('Courier','',9);
      $pdf->Ln();
      $pdf->cell(33, 4, 'Tanggal Transaksi', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, formatTglIndo_datetime($data_angsuran['tgl_bayar']), 0, 0); //field value tanggal transaksi
      $pdf->cell(30, 4, 'Tanggal Cetak', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, formatTglIndo(Date('Y-m-d')) . ' / ' . Date('H:i'),0, 1); //field value tanggal cetak

      $pdf->cell(33, 4, 'Nomor Transaksi', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, 'TRD'.sprintf('%05d', $data_angsuran['id']), 0, 0); //field value nomor transaksi
      $pdf->cell(30, 4, 'User Akun', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, $data_angsuran['user_name'], 0, 1); ////field value user akun

      $pdf->cell(33, 4, 'ID Anggota', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data_anggota['identitas'], 0, 0); //field value id anggota
      $pdf->cell(30, 4, 'Status', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(60, 4, 'Sukses', 0, 1);

      $pdf->cell(33, 4, 'Nama Anggota', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data_anggota['nama'], 0, 1);

      $pdf->cell(33, 4, 'Dept', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data_anggota['departement'], 0, 1);

      $pdf->cell(33, 4, 'Nomor Kontrak', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, 'TPJ'.sprintf('%05d', $data_angsuran['pinjam_id']), 0, 1);

      $pdf->cell(33, 4, 'Angsuran Ke', 0, 0);
      $pdf->cell(5, 4, ':', 0, 0);
      $pdf->cell(80, 4, $data_angsuran['angsuran_ke'] . ' / ' . $data_pinjaman['lama_angsuran'], 0, 1);

      $pdf->cell(33, 4, 'Angsuran Pokok', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($data_pinjaman['pokok_angsuran']), 0, 0, 'R');
      $pdf->cell(25, 4, '', 0, 0, 'R');
      $pdf->cell(30, 4, 'Total Denda', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(40, 4, rupiah(nsi_round($jml_denda)), 0, 1, 'R');

      $pdf->cell(33, 4, 'Bunga Angsuran', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($data_pinjaman['bunga_pinjaman']), 0, 0, 'R');
      $pdf->cell(25, 4, '', 0, 0, 'R');
      $pdf->cell(30, 4, 'Sisa Pinjaman', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(40, 4, rupiah(nsi_round($sisa_bayar)),0, 1, 'R');

      $pdf->cell(33, 4, 'Biaya Admin', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(35, 4, rupiah($data_pinjaman['biaya_adm']), 0, 0, 'R');
      $pdf->cell(25, 4, '', 0, 0, 'R');
      $pdf->cell(30, 4, 'Total Tagihan', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->cell(40, 4, rupiah(nsi_round($total_dibayar)),0, 1, 'R');

      $pdf->cell(33, 4, 'Jumlah Angsuran', 0, 0);
      $pdf->cell(15, 4, ': Rp. ', 0, 0);
      $pdf->SetFont('Courier','B',9);
      $pdf->cell(35, 4, rupiah( $data_angsuran['jumlah_bayar']), 0, 1, 'R');
      $pdf->SetFont('Courier','',9);

      $pdf->cell(33, 4, 'Terbilang', 0, 0);
      $pdf->cell(2, 4, ':', 0, 0);
      $pdf->cell(150, 4, strtoupper(terbilang( $data_angsuran['jumlah_bayar']))."RUPIAH", 0, 1);

      $pdf->SetFont('Courier','', 7);
      $pdf->cell(133, 3, '', 0, 1);
      $pdf->cell(190, 3, 'Ref. '.Date('Ymd_His'), 0, 1, 'C');
      $pdf->cell(190, 3, 'Informasi Hubungi Call Center : 0281-632324', 0, 1, 'C');
      $pdf->cell(190, 3, 'atau dapat diakses melalui : -', 0, 1, 'C');
      $pdf->SetFont('Courier','I', 7);
      $pdf->cell(190, 3, '** Tanda terima ini sah jika telah dibubuhi cap dan tanda tangan oleh Admin ** : -', 0, 1, 'C');

      $pdf->Output();
    }

    function test(){
    $date = date_create_from_format('d M Y H:i', '30 Mar 2020 19:07');
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);
    echo $tgl_trans."<br>";

    $orgDate = "30 Mar 2020 19:07";
    $newDate = date("Y-m-d H:i", strtotime($orgDate));
    echo "New date format is: " . $newDate . " (MM-DD-YYYY)";
    }

}
