<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
class Cuti extends CI_Controller {

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

	
	function index()
	{	
		$nik=$this->session->nama;
		$data['karyawan']=$this->db->query("select * from karyawan where nik='$nik'")->result();
		$data['saldo_cuti']=$this->db->query("select * from saldo_cuti where nik='$nik'")->result();
		
		//Perhitungan Masa Kerja
		$session=$this->session->nama;
		$karyawan=$this->db->query("select * from karyawan where nik='$session'")->result();
		foreach ($karyawan as $row){
			$masukkerja=$row->tgl_masuk_kerja;
		}
		$start_date = new DateTime($masukkerja);
		$end_date = new DateTime (date("Y-m-d"));
		$data['interval'] = $start_date->diff($end_date);

		//Cuti Bersama
		$date=date('Y');
		foreach ($data['karyawan'] as $row){
            $divisi=$row->divisi;
		}
		if ($divisi=="Product Development"){
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where nama_supervisor='Christ Wina Dyah Ayu Maharani'")->result();
		}else{
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where divisi='$divisi' order by nama_supervisor ASC")->result();
		}
		$data['cuti_bersama']=$this->db->query("select * from cuti_bersama where year(tanggal)='$date'")->num_rows();
		
		$this->load->view('employee/v_cuti',$data);
	}

	function do_insert(){
		$session=$this->session->nama;
		$karyawan=$this->db->query("select * from karyawan where nik='$session'")->result();
		foreach ($karyawan as $row){
			$masukkerja=$row->tgl_masuk_kerja;
		//	$atasan=$row->atasan;
		}

		//Input Form
		$atasan=$this->input->post('atasan');
		$nik=$session;
        $tgl_mulai=$this->input->post('tgl_mulai');
		$tgl_berakhir=$this->input->post('tgl_berakhir');
		$tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
        $tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
	//	$tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
	//	$tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
		$total_hari=$this->input->post('total_hari');
		$keterangan=$this->input->post('keterangan');

		//Perhtingan 14 Hari Sebelum Cuti
		$start_date = new DateTime($tgl_mulai);
		$end_date = new DateTime (date("Y-m-d"));
		$inter = $start_date->diff($end_date);

	 //Hitung Hari Otomatis
	 $datetime1 = new DateTime($tgl_mulai);
	 $datetime2 = new DateTime($tgl_berakhir);
	 $interval = $datetime1->diff($datetime2);
	 $woweekends = 1;
	 for($i=1; $i<=$interval->d; $i++){
		 $datetime1->modify('+1 day');
		 $weekday = $datetime1->format('w');
	 
		 if($weekday !== "0" && $weekday !== "7"){ // 0 for Sunday and 6 for Saturday
			 $woweekends++;  
		 }
	 
	 }
	 $holiday=0;
	 $now=date('Y');
	 $before=$now-1;
	 $holidays=$this->db->query("select tanggal from holiday where year(tanggal)='$now' or year(tanggal)='$before'")->result();
	 foreach ($holidays as $row){
		 $tgl=$row->tanggal;
		 if ($tgl_mulai < $tgl AND $tgl_berakhir > $tgl){
			 $holiday=$holiday+1;
		 }else{
			 $holiday=$holiday+0;
		 }
	 }

	 //Initial Send Email
	 $nama=$this->db->query("select * from karyawan where nik='$nik'")->result();
	 foreach($nama as $row){
		 $nama=$row->nama_karyawan;
	 }
	 $mail=$this->db->query("select * from supervisor where nama_supervisor='$atasan'")->result();
	 foreach ($mail as $row){
		 $mail=$row->email;
		
		 $nama_supervisor=$row->nama_supervisor;
	 }
	 $jumlahhari=(string)$woweekends-$holiday;
	 $email = new \SendGrid\Mail\Mail(); 
	 $email->setFrom("admin@niramasutama.com", "PT Niramas Utama");
	 $email->setSubject("Pengajuan Izin - NIK ".$nik);
	 $email->addTo((string)$mail, $nama_supervisor);
	// $email->addContent("text/plain", "Pengajuan Izin");
	 $email->addContent(
		 "text/html", "Berdasarkan Data Dibawah Ini<br>NIK : ".$nik."<br>Nama : ".$nama."<br>Jenis Pengajuan : Cuti<br>Tanggal Mulai : ".$tgl_mulai."<br>Tanggal berahir : ".$tgl_berakhir."<br>Jumlah Hari : ".$jumlahhari."<br>Keterangan : ".$keterangan."<br>Silahkan Login <a href='https://cuticrew.niramasutama.com/'>Disini </a> Untuk Approval"
	 );
	 // 
	 $sendgrid = new \SendGrid('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');

	 //CEK SALDO CUT
	 $ceksaldo=$this->db->query("select * from saldo_cuti where nik='$nik'")->result();
	 foreach ($ceksaldo as $row){
		 $ceksaldo=$row->saldo_cuti;
	 }


		//Data Input Cuti Ke DB
		$data = array(
			'nik' => $nik,
			'jenis' => 'cuti',
			'tgl_mulai' => $tgl_mulai,
			'tgl_berakhir' => $tgl_berakhir,
			'total_hari' => $woweekends-$holiday,
			'keterangan' => $keterangan,
			'atasan' => $atasan,
			'created_at' => date('Y-m-d H:i:s'),
		);

		$join=$this->db->query("select * from karyawan where nik='$session'")->result();
		foreach ($join as $row){
			$kerja=$row->tgl_masuk_kerja;
			$kerja = New DateTime($kerja);
		}
		$in = $kerja->diff($end_date);
		
		if ($in->days<365){
			$this->session->set_flashdata('msg', 
			'<div class="alert alert-danger">
				<h4>Anda Belum Setahun Bekerja, Tidak Bisa Mengajukan Cuti</h4>
			</div>');  
			redirect('employee/cuti');
		}

		if ($woweekends-$holiday > $ceksaldo){
			$this->session->set_flashdata('msg', 
			'<div class="alert alert-danger">
				<h4>Pengajuan Cuti Tidak Boleh Lebih Dari Sisa Cuti</h4>
			</div>');  
			redirect('employee/cuti');
	
		}
		if ($woweekends-$holiday > 5){
			$this->session->set_flashdata('msg', 
			'<div class="alert alert-danger">
				<h4>Pengajuan Cuti Tidak Boleh Lebih Dari 5 Hari</h4>
			</div>');  
			redirect('employee/cuti');
	
		}
		
		if (!empty($tgl_mulai)&&!empty($tgl_berakhir)&&!empty($keterangan))
        {	
			if ($inter->days > 7){
			//$this->db->set('created_at', 'NOW()', FALSE);
			$sendgrid->send($email);
			$this->m_Data->insert_data($data,'cuti');
       		$this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Cuti</h4>
                    </div>');  
		redirect('employee/cuti');
	}else {
		$this->session->set_flashdata('msg', 
		'<div class="alert alert-danger">
			<h4>Pengajuan Cuti Minimal 7 Hari Sebelum Tanggal Mulai Cuti</h4>
		</div>');  
redirect('employee/cuti');
	}
        }else {
        $this->session->set_flashdata('msg', 
                    '<div class="alert alert-danger">
                        <h4>Semua Nilai Harus Diisi</h4>
                    </div>');  
        redirect('employee/cuti');
    	}
	}

	function testing() {
		//Perhitungan Masa Kerja dan Pengambilan Saldo Cuti Tahun Sebelumnya
		$yearnow=date("Y");
		$join=$this->db->query("select karyawan.nik,karyawan.tgl_masuk_kerja,saldo_cuti.saldo_cuti,saldo_cuti.tahun,saldo_cuti.saldo_minus from karyawan join saldo_cuti on karyawan.nik=saldo_cuti.nik where saldo_cuti.tahun='$yearnow'")->result();
		foreach ($join as $row){
			$masukkerja=$row->tgl_masuk_kerja;
			$saldo_cuti=12-$row->saldo_minus;
			$start_date = new DateTime($masukkerja);
			$end_date = new DateTime (date("Y-m-d"));
			$interval=$start_date->diff($end_date);
			if ($interval->days > 365){
				$data = array( 
					array( 
					  'nik'  =>  $row->nik,
					  'saldo_cuti' => $saldo_cuti,
					  'cuti_terpakai' => 0,
					  'saldo_minus' => 0,
					  'tahun' => $yearnow+1,
				  ),
				);
				$this->db->insert_batch('saldo_cuti', $data);
			}else {
				$data = array( 
					array( 
					  'nik'  =>  $row->nik,
					  'saldo_cuti' => $saldo_cuti,
					  'cuti_terpakai' => 0,
					  'saldo_minus' => 0,
					  'tahun' => $yearnow+1,
				  ),
				);
				$this->db->insert_batch('saldo_cuti', $data);
			}
		}	
	}
	function testing_yes() {
		//Perhitungan Masa Kerja dan Pengambilan Saldo Cuti Tahun Sebelumnya
		$yearnow=date("Y");
		$join=$this->db->query("select * from karyawan")->result();
		foreach ($join as $row){
			$masukkerja=$row->tgl_masuk_kerja;
			$start_date = new DateTime($masukkerja);
			$end_date = new DateTime (date("Y-m-d"));
			$interval=$start_date->diff($end_date);
			if ($interval->days > 365){
				// $data = array( 
				// 	array( 
				// 	  'nik'  =>  $row->nik,
				// 	  'saldo_cuti' => 12,
				// 	  'cuti_terpakai' => 0,
				// 	  'saldo_minus' => 0,
				// 	  'tahun' => $yearnow,
				//   ),
				// );
				// $this->db->insert_batch('saldo_cuti', $data);
				
			}else {
				$data = array( 
					array( 
					  'nik'  =>  $row->nik,
					  'saldo_cuti' => 0,
					  'cuti_terpakai' => 0,
					  'saldo_minus' => 0,
					  'tahun' => $yearnow,
				  ),
				);
				$this->db->insert_batch('saldo_cuti', $data);
			}
		}
	}

	function atasan() {
		$db=$this->db->query("select distinct atasan from karyawan")->result();
		foreach ($db as $db){
			$data=array(
				array(
					'nik' => $db->atasan,
					'password' => md5('123'),
					'level' => 'supervisor'
				),
			);
			if ($this->db->insert_batch('users',$data)){
				$respons->http='success';
				echo json_encode($respons);
			}
		}
	}


	function email(){
		$atasan=$this->db->query('select distinct atasan,email from karyawan')->result();
		//$email=$this->db->query('select * from supervisor')->result();
		$no=1;
		foreach ($atasan as $row){
		
		//	$email=$row->email;
			$data=array(
				'nik' => $no++,
				'email' => $row->email,
				'nama_supervisor' => $row->atasan,
			//	'nama_supervisor' => $atasan,
			);
		$this->m_Data->insert_data($data,'supervisor');
	}
	
	
}

function email1(){
	$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("ridwanfps@gmail.com", "Example User");
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
}