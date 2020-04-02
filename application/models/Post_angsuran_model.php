<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_angsuran_model extends CI_Model{
  private $username = "";
  public function __construct(){
    parent::__construct();
    $this->username = $this->session->userdata('username');
  }

  function save_data_ags_temp(){
    $this->db->trans_start();
    $this->db->query("delete from tbl_temp_postangsuran where username = '" . $this->username . "' and view_client is null and view_client is null");
    $this->db->query("insert INTO tbl_temp_postangsuran SELECT *, '". $this->username ."' as username, '1' as view_temp, null as view_client, null as view_client_at from v_post_angsuran");
    $this->db->trans_complete();

    if ($this->db->trans_status() === TRUE) {
      $status = true;
      return $status;
    } else {
      $status = false;
      return $status;
    }
  }

  public function get_data_post_angsuran($post_id = ""){
    if($post_id === ""){
      $sql = "select * FROM tbl_temp_postangsuran where username = '" . $this->username . "' and id_pinjaman != '' and lunas = 'Belum' and view_temp = '1' and view_client is null order by nama asc ";
    } else {
      $sql = "select * FROM tbl_temp_postangsuran where username = '" . $this->username . "' and id_pinjaman != '' and lunas = 'Belum' and view_temp = '1' and view_client = '".$post_id."' order by nama asc ";
    }
    return $this->db->query($sql);
  }

  public function del_data_post_angsuran($id, $kd, $post_id){
    if($post_id == ""){
      $where = array('id_anggota =' => $id, 'id_pinjaman =' => $kd, 'view_client' => null, 'username' => $this->username);
    } else {
      $where = array('id_anggota =' => $id, 'id_pinjaman =' => $kd, 'view_client' => $post_id, 'username' => $this->username) ;
    }

    $this->db->where($where);
    return $this->db->update('tbl_temp_postangsuran', array('view_temp' =>	'0'));
  }

  public function get_data_post_simpanan($post_id = null){
    if($post_id === null){
      $sql = "select * FROM tbl_temp_postangsuran where username = '" . $this->username . "' and simpanan_sukarela != '0' and simpanan_wajib != '0' and view_client is null group by id_anggota order by nama asc";
    } else {
      $sql = "select * FROM tbl_temp_postangsuran where username = '" . $this->username . "' and simpanan_sukarela != '0' and simpanan_wajib != '0' and view_client = '".$post_id."' group by id_anggota order by nama asc";
    }
    // $sql = "select * FROM tbl_temp_postangsuran where simpanan_sukarela != '0' and simpanan_wajib != '0' group by id_anggota order by nama asc";
    return $this->db->query($sql);
  }

  public function del_data_post_simpanan($id, $post_id = ""){
    // $where = array('id_anggota =' => $id);
    if($post_id == ""){
      $where = array('id_anggota =' => $id, 'username' => $this->username);
    } else {
      $where = array('id_anggota =' => $id, 'view_client' => $post_id, 'username' => $this->username);
    }
    $this->db->where($where);
    return $this->db->update('tbl_temp_postangsuran', array('simpanan_sukarela'		=>	0, 'simpanan_pokok'		=>	0, 'simpanan_wajib'		=>	0));
  }

  public function get_tmp($post_id){
    $this->db->where('view_temp', '1');
    $this->db->where('view_client', $post_id);
    $this->db->order_by("nama", "asc");
    $query = $this->db->get('tbl_temp_postangsuran');
    return $query->result();
  }

  public function set_as_view_client($post_id, $date){
    $object = array( 'view_client' => $post_id, 'view_client_at' => $date);
    $this->db->where('view_temp', 1);
    $this->db->where('view_client', null);
    $this->db->update('tbl_temp_postangsuran', $object);
    return $this->db->affected_rows();
  }

  function validasi_posting($id){
    $CI = &get_instance();
    $CI->load->model('Pinjaman_model', 'Angsuran_model');

    $data_pinjam   = $this->Pinjaman_model->get_data_pinjam($id);
    if(!empty($data_pinjam)){
      $total_tagihan = $data_pinjam->tagihan;

      $angsuran_sudah_dibayarkan      = $this->Angsuran_model->get_data_angsuran($id);

      if (empty($angsuran_sudah_dibayarkan)) { //jika BELUM ADA ANGSURAN
        return true; //do bulk posting
      } else {                                     //jika SUDAH ADA ANGSURAN 
        $total_jumlah_angsuran = 0;
        foreach ($angsuran_sudah_dibayarkan as $r) {
          $total_jumlah_angsuran += $r->jumlah_bayar; //NOMINAL ANGSURAN  YG SUDAH DIBAYAR
        }
        if ($total_jumlah_angsuran === $total_tagihan) {
          return false; // SKIP POSTINGAN
        } else if ($total_jumlah_angsuran < $total_tagihan) {
          return true; //do bulk posting
        }
      }
    } else {
      return false;
    }
  }

  function validasi_simpanan($id_anggota){ //cek periode simpanan
    $id = $id_anggota;
    $now = date('Y-m');
    $sql = "SELECT id FROM tbl_trans_sp
    WHERE LEFT(tgl_transaksi, 7) = '$now' and anggota_id = '$id' ";
    $query = $this->db->query($sql)->num_rows();

    if($query == 0){
      return true;
    } else {
      return false;
    }

  }

  public function insertPostingSimpanan($post_id) {
    $sql = "select * FROM tbl_temp_postangsuran where simpanan_sukarela != '0' and simpanan_wajib != '0' and view_client = '".$post_id."' and view_temp = '1' group by id_anggota ";
    $query = $this->db->query($sql)->result();

    $sql2 = "select * FROM tbl_temp_postangsuran where id_pinjaman != '' and lunas = 'Belum' and view_client = '".$post_id."' and view_temp = '1'  ";
    $query2 = $this->db->query($sql2)->result();

    if(sizeof($query) > 0 && sizeof($query2) > 0){
      $now = date("Y-m-d H:i:s");
      $n = 1 ;
      $o = 1 ;
      $p = 1 ;
      $data = array();
      $data2 = array();
      $data3 = array();

      foreach ($query2 as $r) {
        $data3[$p] = array(
          'tgl_bayar'      =>  $now,
          'pinjam_id'      =>  $r->id_pinjaman,
          'angsuran_ke'    => ($r->bln_sudah_angsur) + 1,
          'jumlah_bayar'  =>  $r->ags_per_bulan,
          'denda_rp'      =>  '0',
          'ket_bayar'      =>  'Angsuran',
          'kas_id'        =>  $r->kas_id,
          'jns_trans'      =>  '48',
          'keterangan'    =>  $r->keterangan . "_oka",
          'user_name'      =>   $this->session->userdata('username'),
          'kd_post'        =>   '1'
        );

        $validation_value = $this->validasi_posting($r->id_pinjaman); //cek validasi bulk posting

        if ($validation_value) {
          $this->db->insert('tbl_pinjaman_d', $data3[$p]);
          $data3[$p]['inserted_id'] = $this->db->insert_id();
          $this->save_log($r->view_client, 'tbl_pinjaman_d',  $data3[$p]['inserted_id']);
        }

        $p++;
      }

      $id = $r->id_anggota;
      $siki = date('Y-m');
      $sql = "select id FROM tbl_trans_sp WHERE LEFT(tgl_transaksi, 7) = '$siki' and anggota_id = '$id' and is_del = 0 ";
      $validation_simpanan_value = $this->db->query($sql)->num_rows();

      // $validation_simpanan_value = $this->validasi_simpanan($r->id_anggota); //cek validasi simpanan

      // if (intval($validation_simpanan_value) === 0) {
        foreach ($query as $r) {
          $data[$n] = array(
            'tgl_transaksi'       =>  $now,
            'anggota_id'         =>  $r->id_anggota,
            'jenis_id'           =>  '32',
            'pinjam_id'          =>  $r->id_pinjaman,
            'jumlah'             =>  $r->simpanan_sukarela,
            'keterangan'         => 'Simpanan sukarela',
            'akun'               =>  'Setoran',
            'dk'                 =>  'D',
            'kas_id'             =>  '1',
            'user_name'           => $this->session->userdata('username'),
            'nama_penyetor'       => 'post from import',
            'no_identitas'       => '1803oka '. $validation_simpanan_value,
            'alamat'             => '-',
            'kd_post'             => '1'
          );

          $siki = date('Y-m');
          $sql = "select id FROM tbl_trans_sp WHERE LEFT(tgl_transaksi, 7) = '$siki' and anggota_id = '$r->id_anggota' and keterangan = 'Simpanan sukarela' and is_del = 0 ";
          $validation_simpanan_value = $this->db->query($sql)->num_rows();

          if($validation_simpanan_value == 0){
            $this->db->insert('tbl_trans_sp', $data[$n]);
            $data[$n]['inserted_id'] = $this->db->insert_id();

            $this->save_log($r->view_client, 'tbl_trans_sp',  $data[$n]['inserted_id']);
          }

          $n++;
        }

        foreach ($query as $r) {
          $data2[$o] = array(
            'tgl_transaksi'      =>  $now,
            'anggota_id'        =>  $r->id_anggota,
            'jenis_id'          =>  '41',
            'pinjam_id'         =>  $r->id_pinjaman,
            'jumlah'            =>  $r->simpanan_wajib,
            'keterangan'        =>  'Simpanan wajib',
            'akun'              =>  'Setoran',
            'dk'                =>  'D',
            'kas_id'            =>  '1',
            'user_name'          =>   $this->session->userdata('username'),
            'nama_penyetor'      =>   'post from import',
            'no_identitas'      =>   '1803oka ' . $validation_simpanan_value,
            'alamat'            =>   '-',
            'kd_post'            =>   '1'
          );

        $siki = date('Y-m');
        $sql = "select id FROM tbl_trans_sp WHERE LEFT(tgl_transaksi, 7) = '$siki' and anggota_id = '$r->id_anggota' and keterangan = 'Simpanan wajib'  and is_del = 0 ";
        $validation_simpanan_value = $this->db->query($sql)->num_rows();

          if ($validation_simpanan_value == 0) {
            $this->db->insert('tbl_trans_sp', $data2[$o]);  
            $data2[$o]['inserted_id'] = $this->db->insert_id();

            $this->save_log($r->view_client, 'tbl_trans_sp',  $data2[$o]['inserted_id']);
          }

          $o++;
        }

      // }

      $result = array(
        "status" => TRUE,
        "tbl_trans_sp "   => sizeof($data),
        "tbl_trans_sp "   => sizeof($data2),
        "tbl_pinjaman_d " => sizeof($data3)
      );

      return $result;
    } else {
      $result = array(
        "status" => FALSE,
        "sizeof_query1"  => sizeof($query),
        "sizeof_query2"  => sizeof($query2),
      );

      return $result;
    }
  }

  function save_log($report_id, $table_name, $table_row_id){
    $object = array(
      'report_id'     => $report_id,
      'table_name'    => $table_name,
      'table_row_id'  => $table_row_id,
      'log_datetime'  => date('Y-m-d H:i:s'),
      'userid'        => $this->session->userdata('username')
    );
    $this->db->insert('log_bulk_posting_angsuran', $object);
  }

  function query_log_existing($rep_id){
    $this->db->where('report_id', $rep_id);
    return $this->db->get('log_bulk_posting_angsuran');
  }

}
