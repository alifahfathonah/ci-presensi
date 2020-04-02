<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman_model extends CI_Model{

  var $table = 'v_hitung_pinjaman';
  var $column_order = array('kode', 'id', 'tgl_pinjam', 'tgl_pinjam_display', 'tgl_pinjam_search', 'nama', 'lama_angsuran', 'jumlah', 'bunga', 'biaya_adm', 'lunas', 'dk', 'kas_id', 'user_name', 'pokok_angsuran', 'bunga_pinjaman', 'ags_per_bulan', 'tempo', 'tagihan', 'keterangan', 'barang_id', 'bln_sudah_angsur');
  var $column_search = array('kode', 'id', 'tgl_pinjam', 'tgl_pinjam_display', 'tgl_pinjam_search', 'nama', 'lama_angsuran', 'jumlah', 'bunga', 'biaya_adm', 'lunas', 'dk',
  'kas_id', 'user_name', 'pokok_angsuran', 'bunga_pinjaman', 'ags_per_bulan', 'tempo', 'tagihan', 'keterangan', 'barang_id', 'bln_sudah_angsur');
  var $order = array('tgl_pinjam' => 'desc');

  public function __construct(){
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  private function _get_datatables_query(){

    if($this->input->post('filter_status_pinjam')){
      if($_POST["filter_status_pinjam"] !== 'x'){
        $status_pinjam = $_POST["filter_status_pinjam"];
        $this->db->where('lunas = "'.$status_pinjam.'"');
      }
    }

    if($this->input->post('start_date') and $this->input->post('end_date')){
      $start_date = str_replace("+", " ", $_POST["start_date"]);
      $end_date   = str_replace("+", " ", $_POST["end_date"]);

      $this->db->where('DATE(tgl_pinjam) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
    }

    $this->db->where('dk = "K"');
    // $this->db->where('is_del = 0');

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

  function get_all_data(){
    $data = $this->db->get($this->table);
    return $data;
  }

  //menghitung jumlah yang sudah dibayar dengan id pinjam
  function get_jml_bayar($id) {
    $this->db->select('SUM(jumlah_bayar) AS total');
    $this->db->from('tbl_pinjaman_d');
    $this->db->where('pinjam_id',$id);
    $this->db->where('is_del', 0);
    $query = $this->db->get();
    return $query->row();
  }

  //mecari banyaknya data yg diinput pinjaman detail
  function get_record_bayar($id) {
    $this->db->select('id');
    $this->db->from('tbl_pinjaman_d');
    $this->db->where('pinjam_id',$id);
    $this->db->where('ket_bayar','Angsuran');
    $this->db->where('is_del', 0);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function get_jml_denda($id) {
    $this->db->select('SUM(denda_rp) AS total_denda');
    $this->db->from('tbl_pinjaman_d');
    $this->db->where('pinjam_id',$id);
    $this->db->where('is_del', 0);
    $query = $this->db->get();
    return $query->row();
  }

  //data barang berdasarkan ID
  function get_data_barang($id) {
    $this->db->select('*');
    $this->db->from('tbl_barang');
    $this->db->where('id',$id);
    $query = $this->db->get();

    if($query->num_rows()>0){
      $out = $query->row();
      return $out;
    } else {
      return array();
    }
  }

  //ambil data pinjaman header berdasarkan ID
  function get_data_pinjam($id) {
    $this->db->select('*');
    $this->db->from('v_hitung_pinjaman');
    $this->db->where('id',$id);
    $query = $this->db->get();

    if($query->num_rows() > 0) {
      $out = $query->row();
      return $out;
    } else {
      return FALSE;
    }
  }

  function get_simulasi_pinjaman($pinjam_id) {
    $row = $this->get_data_pinjam($pinjam_id);
    $this->load->model('Bunga_model');
    if($row) {
      $out = array();
      $conf_bunga = $this->Bunga_model->get_key_val();
      $denda_hari = sprintf('%02d', $conf_bunga['denda_hari']);
      $biaya_admin = $conf_bunga['biaya_adm'];
      $tgl_tempo_next = 0;
      for ($i=1; $i <= $row->lama_angsuran; $i++) {
        $odat = array();
        $odat['angsuran_pokok'] = $row->pokok_angsuran * 1;
        $odat['tgl_pinjam'] = substr($row->tgl_pinjam, 0, 10);
        $odat['biaya_adm'] = $row->biaya_adm;
        $odat['bunga_pinjaman'] = $row->bunga_pinjaman;
        $odat['jumlah_ags'] = $row->ags_per_bulan;
        $tgl_tempo_var = substr($row->tgl_pinjam, 0, 7) . '-01';
        $tgl_tempo = date("Y-m-d", strtotime($tgl_tempo_var . " +".$i." month"));
        $tgl_tempo = substr($tgl_tempo, 0, 7) . '-' . $denda_hari;
        $odat['tgl_tempo'] = $tgl_tempo;
        $out[] = $odat;
      }
      return $out;
    } else {
      return FALSE;
    }
  }

  //data kas
  function get_data_kas() {
    $this->db->select('*');
    $this->db->from('nama_kas_tbl');
    $this->db->where('aktif', 'Y');
    $this->db->where('tmpl_pinjaman', 'Y');
    $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return array();
    }
  }

  //data jenis angsuran
  function get_data_angsuran() {
    $this->db->select('*');
    $this->db->from('jns_angsuran');
    $this->db->where('aktif', 'Y');
    $this->db->order_by('ket', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return array();
    }
  }

  //data Bunga
  function get_data_bunga() {
    $this->db->select('bg_pinjam');
    $this->db->from('suku_bunga_n');
    // $this->db->where('opsi_key', 'bg_pinjam');
    // $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return FALSE;
    }
  }

  //data biaya adm
  function get_biaya_adm() {
    $this->db->select('biaya_adm');
    $this->db->from('suku_bunga_n');
    // $this->db->where('opsi_key', 'biaya_adm');
    // $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $out = $query->result();
      return $out;
    } else {
      return FALSE;
    }
  }

  //data max cicilan barang
  function get_max_pinjaman_barang(){
    $this->db->select('max_cicilan_barang, max_hutang_barang');
    $this->db->from('suku_bunga_n');
    // $this->db->where('opsi_key', 'biaya_adm');
    // $this->db->order_by('id', 'ASC');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $out = $query->row_array();
      return $out;
    } else {
      return FALSE;
    }
  }


  // function get_all_data_by_time($period1, $period2){
  //   $this->db->where('tgl_transaksi_asli >=', $period1);
  //   $this->db->where('tgl_transaksi_asli <=', $period2);
  //   $this->db->order_by('tgl_transaksi_asli', 'asc');
  //   $data = $this->db->get($this->table);
  //   return $data;
  // }
  //
  public function insert($object){
    $this->db->trans_start();

    // update stok barang berkurang
    $this->db->where('id', $this->input->post('input_jenis_pinjaman'));
    $this->db->where('type <>', 'uang');
    $this->db->set('jml_brg', 'jml_brg - 1', FALSE);
    $this->db->update('tbl_barang');

    $this->db->insert('tbl_pinjaman_h', $object);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return '0';
    } else {
      $this->db->trans_complete();
      return $this->db->insert_id();
    }

  }

  public function update($object, $where){
    $this->db->where($where);
    $this->db->update('tbl_pinjaman_h', $object);
    return $this->db->affected_rows();
  }

  public function get_by($field, $parameter){
    $this->db->where($field, $parameter);
    return $this->db->get($this->table)->row_array();
  }

  public function soft_delete($object, $where, $barang_id){
    $this->db->trans_start();

    $this->db->where('id', $barang_id);
    $this->db->set('jml_brg', 'jml_brg + 1', FALSE);
    $this->db->update('tbl_barang');

    $this->db->where($where);
    $this->db->update('tbl_pinjaman_h', $object);

    $this->db->where('pinjam_id', $where['id']);
    $this->db->update('tbl_pinjaman_d', $object);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return 0;
    } else {
      $this->db->trans_complete();
      return $this->db->affected_rows();
    }
  }
  //
  // public function get_all_by($parameter){
  //   // $parameter = "23 nov";
  //   $this->db->from($this->table);
  //   $i = 0;
  //   foreach ($this->column_search as $item){
  //     if($parameter){
  //       if($i===0){
  //         $this->db->group_start();
  //         $this->db->like($item, $this->db->escape_like_str($parameter));
  //       } else {
  //         $this->db->or_like($item, $this->db->escape_like_str($parameter));
  //       }
  //
  //       if(count($this->column_search) - 1 == $i)
  //       $this->db->group_end();
  //     }
  //     $i++;
  //   }
  //   // $query = $this->db->get_compiled_select();
  //   $query = $this->db->get();
  //   return $query;
  // }
  //
  // public function get_all_by_date($start_date, $end_date){
  //   $this->db->where('DATE(tgl_transaksi_asli) BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
  //   $this->db->from($this->table);
  //   $query = $this->db->get();
  //   return $query;
  // }
  //
  // public function get_data(){
  //   return $this->db->get($this->table);
  // }
  //
  // public function delete_id($id){
  //   $this->db->where('id', $id);
  //   $this->db->delete($this->table);
  //   return $this->db->affected_rows();
  // }
  //
  function fetch_autocomplete_data($query){
    $this->db->like('nama', $query);
    $query = $this->db->get('tbl_anggota');
    if($query->num_rows() > 0)
    {
      foreach($query->result_array() as $row)
      {
        $output[] = array(
          'name'       => $row["nama"],
          'id'         => 'AG'.sprintf('%04d', $row["id"]),
          'identitas'  => $row["identitas"],
          'image'      => ($row["file_pic"] == null || $row["file_pic"] == "") ? 'photo_default.jpg' : $row["file_pic"],
        );
      }
      echo json_encode($output);
    }
  }

}
