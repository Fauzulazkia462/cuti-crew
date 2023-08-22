<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		$data['cuti_bersama']=$this->db->query("select * from cuti_bersama where tahun='$date'")->result();
		$data['atasan']=$this->db->query("select distinct atasan from karyawan")->result();

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
	//	$tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
	//	$tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
		$total_hari=$this->input->post('total_hari');
		$keterangan=$this->input->post('keterangan');

		//Perhtingan 14 Hari Sebelum Cuti
		$start_date = new DateTime($tgl_mulai);
		$end_date = new DateTime (date("Y-m-d"));
		$interval = $start_date->diff($end_date);

		//Total Hari Otomatis
        $start = new DateTime($tgl_mulai);
        $end = new DateTime($tgl_berakhir);
        $holidays = array(
            '2012-01-02',
            '2011-01-17',
        );

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $days = array();
        foreach ($period as $day) {
            $dayOfWeek = $day->format('N');
            if ($dayOfWeek < 7) {
                $format = $day->format('Y-m-d');
                if (false === in_array($format, $holidays)) {
                    $days[] = $day;
                }
            }
        }

		//Data Input Cuti Ke DB
		$data = array(
			'nik' => $nik,
			'jenis' => 'cuti',
			'tgl_mulai' => $tgl_mulai,
			'tgl_berakhir' => $tgl_berakhir,
			'total_hari' => count($days)+1,
			'keterangan' => $keterangan,
			'atasan' => $atasan,
			'created_at' => date('Y-m-d H:i:s'),
		);
		
	
		if (!empty($tgl_mulai)&&!empty($tgl_berakhir)&&!empty($keterangan))
        {	
			if ($interval->days > 7){
			//$this->db->set('created_at', 'NOW()', FALSE);
			$this->m_Data->insert_data($data,'cuti');
       		$this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Cuti</h4>
                    </div>');  
		redirect('employee/cuti');
	}else {
		$this->session->set_flashdata('msg', 
		'<div class="alert alert-danger">
			<h4>Pengajuan Cuti Maximal 7 Hari Sebelum Tanggal Mulai Cuti</h4>
		</div>');  
redirect('employee/cuti');
	}
        }else {
        $this->session->set_flashdata('msg', 
                    '<div class="alert alert-danger">
                        <h4>Semua Nilai Harus Diisi</h4>
                    </div>');  
        redirect('employee/cuti');
    	}$session=$this->session->nama;
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
		$tgl_mulai=date('Y-m-d', strtotime('01-02-1992'));
		$tgl_berakhir=date('Y-d-m', strtotime($tgl_berakhir));
		$total_hari=$this->input->post('total_hari');
		$keterangan=$this->input->post('keterangan');

		//Perhtingan 14 Hari Sebelum Cuti
		$start_date = new DateTime($tgl_mulai);
		$end_date = new DateTime (date("Y-m-d"));
		$interval = $start_date->diff($end_date);

echo $tgl_mulai;
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
				$data = array( 
					array( 
					  'nik'  =>  $row->nik,
					  'saldo_cuti' => 12,
					  'cuti_terpakai' => 0,
					  'saldo_minus' => 0,
					  'tahun' => $yearnow,
				  ),
				);
				$this->db->insert_batch('saldo_cuti', $data);
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
}