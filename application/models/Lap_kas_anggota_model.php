<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_kas_anggota_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		//Codeigniter : Write Less Do More
	}

	public function cari_data_anggota($nama){
		$id = intval(str_replace('AG', '', $nama));
		$this->db->like('id', $id);
		$this->db->where('aktif', 'y');
		return $this->db->get('tbl_anggota');
	}

	public function get_data_anggota($param=null, $cetak=null){

		$this->load->library('pagination');

		if($param !== null){
			$id = intval(str_replace('AG', '', $param));
			$query = "select * FROM tbl_anggota where aktif = 'y' and id = '". $id."'";
		} else {
			$query = "select * FROM tbl_anggota where aktif = 'y'";
		}

		$config['base_url'] = base_url('kasanggota/lists');
		$config['total_rows'] = $this->db->query($query)->num_rows();
		if($cetak !== null){
			$config['per_page'] = $config['total_rows'];
		} else {
			$config['per_page'] = 10;
		}
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;

		// Style Pagination
		// Agar bisa mengganti stylenya sesuai class2 yg ada di bootstrap
		// Bootstrap 4, work very fine.
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = ['class' => 'page-link'];
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		// End style pagination
		$this->pagination->initialize($config); // Set konfigurasi paginationnya

		$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;

		if ($param !== null) {
			
		} else {
			$query .= " LIMIT " . $page . ", " . $config['per_page'];
		}

		$data['limit'] = $config['per_page'];
		$data['total_rows'] = $config['total_rows'];
		$data['pagination'] = $this->pagination->create_links(); // Generate link pagination nya sesuai config diatas
		$data['anggota'] = $this->db->query($query)->result();
		return $data;
	}

	//panggil data jenis simpan
	function get_jenis_simpan(){
		$this->db->select('*');
		$this->db->from('jns_simpan');
		$this->db->where('tampil', 'Y');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}

	//menghitung jumlah simpanan
	function get_jml_simpanan($jenis, $id)
	{
		$this->db->select('SUM(jumlah) AS jml_total');
		$this->db->from('tbl_trans_sp');
		$this->db->where('anggota_id', $id);
		$this->db->where('dk', 'D');
		$this->db->where('jenis_id', $jenis);
		$query = $this->db->get();
		return $query->row();
	}

	//menghitung jumlah penarikan
	function get_jml_penarikan($jenis, $id)
	{
		$this->db->select('SUM(jumlah) AS jml_total');
		$this->db->from('tbl_trans_sp');
		$this->db->where('dk', 'K');
		$this->db->where('anggota_id', $id);
		$this->db->where('jenis_id', $jenis);
		$query = $this->db->get();
		return $query->row();
	}

	// function get_data_anggota($limit, $start, $q = '')
	// {
	// 	$anggota_id = isset($_REQUEST['anggota_id']) ? $_REQUEST['anggota_id'] : '';
	// 	$sql = '';
	// 	$sql = "SELECT * FROM tbl_anggota WHERE aktif='Y'";
	// 	$q = array('anggota_id' => $anggota_id);
	// 	if (is_array($q)) {
	// 		if ($q['anggota_id'] != '') {
	// 			$q['anggota_id'] = str_replace('AG', '', $q['anggota_id']);
	// 			$sql .= " AND (id LIKE '" . $q['anggota_id'] . "' OR nama LIKE '" . $q['anggota_id'] . "') ";
	// 		}
	// 	}
	// 	$sql .= "LIMIT " . $start . ", " . $limit . " ";
	// 	//$this->db->limit($limit, $start);
	// 	$query = $this->db->query($sql);
	// 	if ($query->num_rows() > 0) {
	// 		$out = $query->result();
	// 		return $out;
	// 	} else {
	// 		return array();
	// 	}
	// }

	// //panggil data anggota
	// function lap_data_anggota()
	// {
	// 	$anggota_id = isset($_REQUEST['anggota_id']) ? $_REQUEST['anggota_id'] : '';
	// 	$sql = '';
	// 	$sql = "SELECT * FROM tbl_anggota WHERE aktif='Y'";
	// 	$q = array('anggota_id' => $anggota_id);
	// 	if (is_array($q)) {
	// 		if ($q['anggota_id'] != '') {
	// 			$q['anggota_id'] = str_replace('AG', '', $q['anggota_id']);
	// 			$sql .= " AND (id LIKE '" . $q['anggota_id'] . "') ";
	// 		}
	// 	}
	// 	$query = $this->db->query($sql);
	// 	if ($query->num_rows() > 0) {
	// 		$out = $query->result();
	// 		return $out;
	// 	} else {
	// 		return array();
	// 	}
	// }

	// function get_jml_data_anggota()
	// {
	// 	$this->db->where('aktif', 'Y');
	// 	return $this->db->count_all_results('tbl_anggota');
	// }

	//ambil data pinjaman header berdasarkan ID peminjam
	function get_data_pinjam($id)
	{
		$this->db->select('*');
		$this->db->from('v_hitung_pinjaman');
		$this->db->where('anggota_id', $id);
		$this->db->where('lunas', 'Belum');
		return $this->db->get();
		// if ($query->num_rows() > 0) {
		// 	$out = $query->result_array();
		// 	return $out;
		// } else {
		// 	return array();
		// }
	}


	function get_peminjam_lunas($id)
	{
		$this->db->select('*');
		$this->db->from('v_hitung_pinjaman');
		$this->db->where('lunas', 'Lunas');
		$this->db->where('anggota_id', $id);
		return $this->db->get();
	}

	function get_peminjam_tot($id)
	{
		$this->db->select('*');
		$this->db->from('v_hitung_pinjaman');
		$this->db->where('anggota_id', $id);
		return $this->db->get();
	}

	//menghitung jumlah yang sudah dibayar
	function get_jml_pinjaman($id)
	{
		$this->db->select('SUM(jumlah) AS total');
		$this->db->from('v_hitung_pinjaman');
		$this->db->where('anggota_id', $id);
		return $this->db->get();
	}

	//menghitung jumlah yang sudah dibayar
	function get_jml_tagihan($id)
	{
		$this->db->select('SUM(tagihan) AS total');
		$this->db->from('v_hitung_pinjaman');
		$this->db->where('anggota_id', $id);
		return $this->db->get();
	}


	//menghitung jumlah yang sudah dibayar
	function get_jml_bayar($id)
	{
		$this->db->select('SUM(jumlah_bayar) AS total');
		$this->db->from('tbl_pinjaman_d');
		$this->db->where('pinjam_id', $id);
		return $this->db->get();
	}

	//menghitung jumlah denda harus dibayar
	function get_jml_denda($id)
	{
		$this->db->select('SUM(denda_rp) AS total_denda');
		$this->db->from('tbl_pinjaman_d');
		$this->db->where('pinjam_id', $id);
		return $this->db->get();
	}


}
