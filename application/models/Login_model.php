<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function cek_login($table, $where)
    {
        return $this->db->get_where($table, $where);
    }

    public function updatePassword($username, $data)
    {
        $this->db->where('username', $username);
        $this->db->update('tbl_username', $data);
        return $this->db->affected_rows();
    }

    public function insert($data)
    {
        $this->db->insert('tbl_username', $data);
        return $this->db->insert_id();
    }
}
