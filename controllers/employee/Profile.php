<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //Cek Session
        $nik=$this->session->nama;
        $cek_level=$this->db->query("select level from users where nik='$nik'")->result();
		foreach ($cek_level as $row){
			$level=$row->level;
		}
        if ($level!='employee'){
            redirect ('welcome');
        }else{
        $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
	
	}

    function index() {
        $this->load->view('employee/v_profile');
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
        redirect('employee/profile');
    }

    function approval () {
        $nik=$this->session->nama;
        $data['cuti']=$this->db->query("select * from cuti where nik='$nik'")->result();
        $this->load->view('employee/v_approval',$data);
    }
}