<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
       
        $session=$this->session->nama;
        $data['karyawan']=$this->db->query("select * from karyawan where nik='$session'")->result();
        $this->load->view('employee/v_home',$data);

    }

    function test(){
        require 'vendor/autoload.php'; 
$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("ridwanfps98@gmail.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
    }

    function testing(){
        $sesi=$this->session->nama;
        $db=$this->db->query("select * from karyawan where nik='$sesi'")->result();
        foreach ($db as $db){
            $masukkerja=$db->tgl_masuk_kerja;
        }
        $cuti=$this->db->query("select * from saldo_cuti where nik='$sesi'")->result();
        foreach ($cuti as $cuti) {
            $minus=$cuti->saldo_minus;
        }
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
        if (date("Y-m-d")){
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
            }else{
                echo "gagal";
            }
        }else{
            echo "bukan";
        }
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
    }   
}
}