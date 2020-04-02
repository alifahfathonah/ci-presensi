<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_model extends CI_Model{

  var $table = 'view_t_pinjaman_d';
  var $column_order = array('id', 'kode_kode', 'tgl_bayar_asli', 'tgl_bayar', 'tgl_bayar_search', 'pinjam_id', 'angsuran_ke', 'jumlah_bayar', 'denda_rp', 'terlambat', 'ket_bayar', 'dk', 'kas_id', 'jns_trans', 'update_data',
  'user_name', 'keterangan', 'kd_post', 'sisa_pelunasan');
  var $column_search = array('id', 'kode_kode', 'tgl_bayar_asli', 'tgl_bayar', 'tgl_bayar_search', 'pinjam_id', 'angsuran_ke', 'jumlah_bayar', 'denda_rp', 'terlambat', 'ket_bayar', 'dk', 'kas_id', 'jns_trans', 'update_data',
  'user_name', 'keterangan', 'kd_post', 'sisa_pelunasan');
  var $order = array('tgl_bayar_asli' => 'desc');

  public function __construct(){
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  private function _get_datatables_query(){
    if($this->input->post('start_date') and $this->input->post('end_date')){
      $start_date = str_replace("+", " ", $_POST["start_date"]);
      $end_date   = str_replace("+", " ", $_POST["end_date"]);

      $this->db->where('DATE(tgl_bayar_asli) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
    }

    $this->db->where('pinjam_id  = "'.$_POST["id"].'"');

    $this->db->from($this->table);
    $i = 0;

    foreach ($this->column_search as $item){
      if($_POST['search']['value']){
        if($i===0){
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if(isset($_POST['order'])){
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if(isset($this->order)){
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }

  }

  function get_datatables(){
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered(){
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all(){
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  function get_data_kas(){
    $this->db->select('*');
    $this->db->from('nama_kas_tbl');
    $this->db->where('aktif', 'Y');
    $this->db->where('tmpl_bayar', 'Y');
    $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return FALSE;
    }
  }

  //panggil detail  angsuran
  function get_data_angsuran($pinjam_id) {
    $this->db->select('*');
    $this->db->from('tbl_pinjaman_d');
    $this->db->where('pinjam_id', $pinjam_id);
    $this->db->where('is_del', 0);
    $this->db->order_by('tgl_bayar', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return FALSE;
    }
  }

  function cek_periode() { //cek periode simpanan
    $id = $this->input->post('input_anggota_id');
    $now = date('Y-m');
    $sql = "SELECT id FROM tbl_trans_sp
    WHERE LEFT(tgl_transaksi, 7) = '$now' and anggota_id = '$id' ";
    $query = $this->db->query($sql);
    $count = $query->num_rows();

    if($count == 0){
      $metaCode = 201;
    } else {
      $metaCode = 304;
    }

    $response = array(
      "metaData" => array("code" => $metaCode)
    );
    return json_encode($response);
  }

  function save(){
    // $CI =& get_instance();
    // $CI->load->model('General_model');

    $res = json_decode($this->cek_periode());

    if ($res->metaData->code == 201) {
      $cek = 0;
    } else {
      $cek = 1;
    }

    $ags_ke = $this->General_model->get_record_bayar($this->input->post('input_nomor_pinjaman_id')) + 1;
    $jumlah = str_replace(',', '', $this->input->post('input_sisa_tagihan')) * 1;
    $denda  = str_replace(',', '', $this->input->post('input_denda'))*1;
    $jumlah_bayar = $jumlah + $denda;

    $sim_sukarela = $this->input->post('input_simpanan_sukarela');
    $sim_wajib    = $this->input->post('input_simpanan_wajib');

    // $date = date_create_from_format('d M Y H:i', $this->input->post('input_tanggal_trans'));
    // $timestamp = $date->getTimestamp();
    // $tgl_trans = date('Y-m-d H:i', $timestamp);
    $tgl_trans = date("Y-m-d H:i", strtotime($this->input->post('input_tanggal_trans')));

    $data = array(
      'tgl_bayar'		=> $tgl_trans,
      'pinjam_id'		=> $this->input->post('input_nomor_pinjaman_id'),
      'angsuran_ke'	=> $ags_ke,
      'jumlah_bayar'=> str_replace(',', '', $this->input->post('input_jumlah_angsuran')),
      'denda_rp'		=> $denda,
      'ket_bayar'		=> 'Angsuran',
      'kas_id'		  => $this->input->post('simpan_ke_kas'),
      'jns_trans'		=> '48',
      'keterangan'	=> $this->input->post('input_keterangan'),
      'user_name'		=> $this->session->userdata('username'),
      'is_del'      => 0
    );

    $data_SimSuk = array(
      'tgl_transaksi'	=> $tgl_trans,
      'anggota_id'		=> $this->input->post('input_anggota_id'),
      'jenis_id'			=> '32',
      'pinjam_id'     => $this->input->post('input_nomor_pinjaman_id'),
      'jumlah'				=> str_replace(',', '', $this->input->post('input_simpanan_sukarela')),
      'keterangan'		=> 'Simpanan sukarela',
      'akun'					=> 'Setoran',
      'dk'					  => 'D',
      'kas_id'				=> '1',
      'user_name'			=> $this->session->userdata('username'),
      'nama_penyetor'	=> 'post from angsuran',
      'no_identitas'	=> '1803',
      'alamat'				=> '-'
    );

    $data_SimWaj = array(
      'tgl_transaksi'	=>  $tgl_trans,
      'anggota_id'		=>	$this->input->post('input_anggota_id'),
      'jenis_id'			=>	'41',
      'pinjam_id'     =>  $this->input->post('input_nomor_pinjaman_id'),
      'jumlah'				=>	str_replace(',', '', $this->input->post('input_simpanan_wajib')),
      'keterangan'		=> 	'Simpanan wajib',
      'akun'					=>	'Setoran',
      'dk'					  =>	'D',
      'kas_id'				=>	'1',
      'user_name'			=> 	$this->session->userdata('username'),
      'nama_penyetor'	=> 	'post from angsuran',
      'no_identitas'	=> 	'1803',
      'alamat'				=> 	'-'
    );

    $this->db->trans_start();
    if($sim_sukarela != '0' && $sim_wajib != '0' && $cek == '0'){
      $this->db->insert('tbl_pinjaman_d', $data);
      $this->db->insert('tbl_trans_sp', $data_SimSuk);
      $this->db->insert('tbl_trans_sp', $data_SimWaj);
    } else if ($sim_sukarela == '0' && $sim_wajib == '0' && $cek == '0') {
      $this->db->insert('tbl_pinjaman_d', $data);
      // $this->db->insert('tbl_trans_sp', $data_SimSuk);
      // $this->db->insert('tbl_trans_sp', $data_SimWaj);
    }
    else if($sim_sukarela == '0' && $sim_wajib != '0' && $cek == '0') {
      $this->db->insert('tbl_pinjaman_d', $data);
      $this->db->insert('tbl_trans_sp', $data_SimWaj);
    }
    else if($sim_sukarela != '0' && $sim_wajib == '0' && $cek == '0'){
      $this->db->insert('tbl_pinjaman_d', $data);
      $this->db->insert('tbl_trans_sp', $data_SimSuk);
    }
    else if ($cek == '1'){
      $this->db->insert('tbl_pinjaman_d', $data);
    }

    if($jumlah_bayar == 0) {
      $status = 'Lunas';
    } else {
      $status = 'Belum';
    }
    $data = array('lunas' => $status);
    $this->db->where('id', $this->input->post('input_nomor_pinjaman_id'));
    $this->db->update('tbl_pinjaman_h', $data);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_complete();
      return TRUE;
    }

  }

  function get_by($field, $value){
    $this->db->where($field, $value);
    return $this->db->get('tbl_pinjaman_d');
  }

  public function update() {

    $date      = date_create_from_format('d M Y H:i', str_replace(" - ", " ", $this->input->post('input_tanggal_trans')));
    $timestamp = $date->getTimestamp();
    $tgl_trans = date('Y-m-d H:i', $timestamp);

    $object = array(
      'tgl_bayar'		=> $tgl_trans,
      'kas_id'		  => $this->input->post('simpan_ke_kas'),
      'denda_rp'		=> str_replace(',', '', $this->input->post('input_denda')),
      'update_data'	=> date('Y-m-d H:i'),
      'keterangan'	=> $this->input->post('input_keterangan'),
      'user_name'		=> $this->session->userdata('username')
    );

    $this->db->where('id', $this->input->post('id_bayar'));
    return $this->db->update('tbl_pinjaman_d', $object);
  }

  public function delete() {
    // cek apakah yg dihapus adalah bukan yg terakhir
    $this->db->select('MAX(id) AS id_akhir');
    $this->db->where('pinjam_id', $_POST['param'][1]);
    $this->db->where('is_del', 0);
    $qu_akhir = $this->db->get('tbl_pinjaman_d');
    $row_akhir = $qu_akhir->row();
    if($row_akhir->id_akhir != $_POST['param'][0]) {
      return false;
    } else {
      // $this->db->delete('tbl_pinjaman_d', array('id' => $id));
      $object = array(
        'del_datetime' => date('Y-m-d H:i:s'),
        'del_by'       => $this->session->userdata('username'),
        'del_reason'   => $_POST['param'][2],
        'is_del'       => 1,
      );
      $this->db->where('id', $_POST['param'][0]);
      return $this->db->update('tbl_pinjaman_d', $object);

      $this->auto_status_lunas($_POST['param'][1]);
      return true;
    }

    // $this->db->delete('tbl_pinjaman_d', array('id' => $id));
    $object = array(
      'del_datetime' => date('Y-m-d H:i:s'),
      'del_by'       => $this->session->userdata('username'),
      'del_reason'   => $_POST['param'][2],
      'is_del'       => 1,
    );
    $this->db->where('id', $_POST['param'][0]);
    return $this->db->update('tbl_pinjaman_d', $object);

    if($this->auto_status_lunas($_POST['param'][1])) {
      return true;
    }

  }

  function auto_status_lunas($master_id) {
			$pinjam  = $this->General_model->get_data_pinjam($master_id);
			$tagihan = $pinjam->lama_angsuran * $pinjam->ags_per_bulan;
			$denda   = $this->General_model->get_semua_denda_by_pinjaman($master_id);
			$total_tagihan = $tagihan + $denda;
			if($total_tagihan <= 0) {
			$status = 'Lunas';}
			else {
			$status = 'Belum';}
			$data = array('lunas' => $status);
			$this->db->where('id', $master_id);
			$this->db->update('tbl_pinjaman_h', $data);
			return TRUE;
		}

}
