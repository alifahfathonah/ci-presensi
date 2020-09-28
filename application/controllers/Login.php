<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model', 'model');
        $this->load->model(array('Karyawan_model'));
    }

    function index()
    {
        if ($this->session->userdata('status') !== 'loggedin') {
            $this->load->view('view_login_2');
        } else {
            redirect('dashboard');
        }
    }

    function action()
    {
        $username        = $this->input->post('username');
        $plain_password  = $this->input->post('password');
        $where = array(
            'username' => $username,
        );
        $cek = $this->model->cek_login("view_user_login", $where);
        if (!$cek->result()) {
            $this->session->set_flashdata('flash_error', 'Username Anda Salah.');
            redirect("login", "refresh");
        } else {
            if ( $cek->num_rows() >= 1) {
                foreach ($cek->result() as $row) {
                    $verify = $this->hash_verify($plain_password, $row->password);
                    if ($verify == TRUE) {
                      $login_data = $cek->row_array();
                      $login_data['status'] = 'loggedin';
                      $this->session->set_userdata($login_data);
                      // redirect('dashboard');

                      if($cek->row_array()['id_hak_akses'] == 1){
                        redirect('dashboard');
                      } else{
                        redirect("user");
                      }
                    } else {
                        $this->session->set_flashdata('flash_error', 'Password Anda Salah.');
                        redirect("login", "refresh");
                    }
                }
            } else {
                $this->session->set_flashdata('flash_error', 'Username / Password Tidak Ditemukan');
                redirect("login", "refresh");
            }
        }
    }

    function tes(){
      $karyawan = $this->Karyawan_model->get_by(array('id' => '100kgebif868g' ))->row_array();
      echo $karyawan['nama_lengkap'];
    }

    public function hash_string($string)
    {
        $hashed_string = password_hash($string, PASSWORD_BCRYPT);
        return $hashed_string;
    }

    public function hash_verify($plain_text, $hashed_string)
    {
        $hashed_string = password_verify($plain_text, $hashed_string);
        return $hashed_string;
    }

    // public function addUser(){
    //   $data = array(
    //               'id_karyawan'     => null,
    //               'username'        => 'admin',
    //               'password'        => $this->hash_string('rahasia'),
    //               'id_hak_akses'    => 1,
    //               'input_by'        => '1',
    //               'input_datetime'  => date('Y-m-d H:i:s'),
    //               'is_del'          => 0,
    //               'client_id'       => 1
    //             );
    //   $insertedId = $this->model->insert($data);
    //   echo $insertedId;
    // }

    //
    // function checkOldPassword(){
    //     $username = $_POST['datane'][0];
    //     $password = $_POST['datane'][1];
    //     $where = array(
    //       'username' => $username,
    //       'password' => md5($password)
    //       );
    //     $cek = $this->model->cek_login("view_login",$where)->row_array();
    //
    //     if(!empty($cek)){
    //         $cek['status'] = 'loggedin';
    //         echo json_encode( array('status' => '1' ));
    //     }else{
    //         echo json_encode( array('status' => '0' ));
    //     }
    // }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }


}
