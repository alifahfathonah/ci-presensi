<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas_anggota_model extends CI_Model{

    public function __construct(){
        parent::__construct();
        //Codeigniter : Write Less Do More
    }

    function fetch_autocomplete_data($query)
    {
        $this->db->like('nama', $query);
        $query = $this->db->get('tbl_anggota');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $output[] = array(
                    'base_id'    => $row["id"],
                    'name'       => 'AG' . sprintf('%04d', $row["id"]) . " - " . $row["nama"],
                    'id'         => 'AG' . sprintf('%04d', $row["id"]),
                    'identitas'  => $row["identitas"],
                    'image'      => ($row["file_pic"] == null || $row["file_pic"] == "") ? 'photo_default.jpg' : $row["file_pic"],
                );
            }
            echo json_encode($output);
        }
    }

    //panggil data jenis simpan
    function get_jenis_simpan()
    {
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

}