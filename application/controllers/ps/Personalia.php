<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// End load library phpspreadsheet
class Personalia extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //Cek Session
        $nik = $this->session->nama;
        $cek_level = $this->db->query("select level from users where nik='$nik'")->result();
        foreach ($cek_level as $row) {
            $level = $row->level;
        }
        if ($level != 'ps') {
            redirect('welcome');
        } else {
            $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

    }

    public function index()
    {
        $this->load->view('ps/v_profile');

    }

    function ganti_password(){
        $username=$this->session->nama;
        $newpassword=$this->input->post('change_password');
        $newpassword=md5($newpassword);

        $data = array(
            'password' =>$newpassword,
        );
    
        $where = array(
            'nik' => $username
        );
     
        $this->m_Data->update_data($where,$data,'users');
        $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Ganti Password</h4>
                    </div>');  
        redirect('ps/personalia/');
    }

}
