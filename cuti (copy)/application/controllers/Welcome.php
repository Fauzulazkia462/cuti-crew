<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	function aksi_login(){
		$nik = $this->input->post('nik');
		$password = $this->input->post('password');
		$where = array(
			'nik' => $nik,
			'password' => md5($password)
			);
		$cek = $this->m_login->cek_login("users",$where)->num_rows();
		$cek_level=$this->db->query("select level from users where nik='$nik'")->result();
		foreach ($cek_level as $row){
			$level=$row->level;
		}

		//Set User Session
		if($cek > 0){
 
			$data_session = array(
				'nama' => $nik,
				'logged' => TRUE
				//'status' => "login"
				);			
		$this->session->set_userdata($data_session);
		if ($level == 'employee'){
			redirect ('employee/home/');
		}elseif ($level=='supervisor'){
			redirect ('supervisor/home/');
		}elseif ($level=='admin'){
			redirect ('admin/home');
		}elseif ($level=='ps'){
			redirect ('ps/home');
		}
	}
		
else{
	echo "<script language='javascript'>
			alert('Username atau Password Salah');
			 window.location='../welcome';
		  </script>";
	}
}
	
	public function logout()
  	{
    $this->session->sess_destroy();
    redirect('welcome');
	}

	function coba(){
        $cuti=$this->db->query("select saldo_cuti.nik, saldo_cuti.saldo_minus, karyawan.tgl_masuk_kerja from saldo_cuti join karyawan on saldo_cuti.nik=karyawan.nik")->result();
        foreach ($cuti as $cuti) {
            $minus=$cuti->saldo_minus;
            $masukkerja=$cuti->tgl_masuk_kerja;
            $sesi=$cuti->nik;
       
        $saldo=12-$minus;
        $start_date = new DateTime($masukkerja);
		$end_date = new DateTime (date("Y-m-d"));
        $interval=$start_date->diff($end_date);
        $tahun=date('Y')-substr($masukkerja,0,4);
        $kelipatan=$tahun/4;
        $modulus=$tahun%4;
        $kurang=substr($kelipatan,0,1);
       // echo $modulus;
        if ($modulus==0){
            $kerja=($interval->days-$kelipatan)%365;
            $masakerja=$interval->days-$kelipatan;
        }else {
            $kerja=($interval->days-$kurang)%365;
            $masakerja=$interval->days-$kurang;
        }
            if ($kerja==0){
                $data=array(
                    'saldo_cuti' => $saldo,
                    'cuti_terpakai' => 0,
                    'saldo_minus' => 0,
                    'tahun' => date('Y'),
                );
                $where=array(
                    'nik' => $sesi,
                );
                $this->m_Data->update_data($where,$data,'saldo_cuti');
                echo "berhasil update data";
            }
	}   }
	
	function test(){
		echo "test";
	}
}