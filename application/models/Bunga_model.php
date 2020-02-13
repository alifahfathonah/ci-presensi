<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bunga_model extends CI_Model{

  function get_key_val() {
    $out = array();
    $this->db->select('id,opsi_key,opsi_val');
    $this->db->from('suku_bunga');
    $query = $this->db->get();
    if($query->num_rows()>0){
      $result = $query->result();
      foreach($result as $value){
        $out[$value->opsi_key] = $value->opsi_val;
      }
      return $out;
    } else {
      return array();
    }
  }

  function simpan() {
    $opsi_val_arr = $this->get_key_val();
    foreach ($opsi_val_arr as $key => $val) {
      if($this->input->post($key) || $this->input->post($key) == 0 ) {
        $data = array ('opsi_val'=> $this->input->post($key));
        $this->db->where('opsi_key', $key);
        if($this->db->update('suku_bunga',$data)) {
          // update view hitungan pinjaman
          if($key == 'pinjaman_bunga_tipe') {
            $this->update_db_view_hpin($this->input->post('pinjaman_bunga_tipe'));
          }
        } else {
          return FALSE;
        }
      }
    }
    return TRUE;
  }


}
