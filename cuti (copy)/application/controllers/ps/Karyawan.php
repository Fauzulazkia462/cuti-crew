<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
// End load library phpspreadsheet
class Karyawan extends CI_Controller {
	

	public function __construct()
    {
        parent::__construct();
        //Cek Session
        $nik=$this->session->nama;
        $cek_level=$this->db->query("select level from users where nik='$nik'")->result();
		foreach ($cek_level as $row){
			$level=$row->level;
		}
        if ($level!='ps'){
            redirect ('welcome');
        }else{
        $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
	
	}

	public function index(){
	//	$this->load->database();
		$jumlah_data = $this->m_Data->jumlah_data();
		$config['full_tag_open']   = '<ul class="pagination pagination-sm m-t-xs m-b-xs">';
        $config['full_tag_close']  = '</ul>';
        
        $config['first_link']      = 'First'; 
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';
        
        $config['last_link']       = 'Last'; 
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';
        
        $config['next_link']       = ' <i class="glyphicon glyphicon-menu-right"></i> '; 
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';
        
        $config['prev_link']       = ' <i class="glyphicon glyphicon-menu-left"></i> '; 
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';
        
        $config['cur_tag_open']    = '<li class="active"><a href="#">';
        $config['cur_tag_close']   = '</a></li>';
         
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';

		$this->load->library('pagination');
		$config['base_url'] = base_url().'ps/karyawan/index/';
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 10;
		$from = $this->uri->segment(4);
		$this->pagination->initialize($config);		
		$data['karyawan'] = $this->m_Data->data($config['per_page'],$from);
		$this->load->view('ps/v_karyawan', $data);
    }

    function do_insert() {
		//Perhitungan Masa Kerja dan Pengambilan Saldo Cuti Tahun Sebelumnya
        $yearnow=date('Y');
        $tahun=$this->input->post('tahun');
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
					  'tahun' => $tahun,
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
					  'tahun' => $tahun,
				  ),
				);
				$this->db->insert_batch('saldo_cuti', $data);
			}
        }
        $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Saldo Cuti</h4>
                    </div>');  
        redirect('ps/home');	
	}

	function cuti_bersama(){
		$data['cuti_bersama']=$this->m_Data->tampil_data('cuti_bersama')->result();
		$this->load->view('ps/v_cuti_bersama',$data);
	}
	function upload_excel() {
	$config['upload_path']          = './excel/';
        $config['allowed_types']        = 'xlsx';
		$config['file_name'] = 'data_karyawan.xlsx';
		$config['overwrite'] = TRUE;
		//$this->upload->overwrite = true;
	//	$config['max_size']             = 100;
	//	$config['max_width']            = 1024;
	//	$config['max_height']           = 768;
 
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
 
		if ( ! $this->upload->do_upload('berkas')){
			$error = $this->upload->display_errors();
			$this->session->set_flashdata('msg', 
                    '<div class="alert alert-warning">
                        <h4>Gagal Upload, Pastikan Format Data Adalah .xlsx</h4>
                    </div>');  
			redirect ('ps/karyawan');
		}else{
			//$data = array('upload_data' => $this->upload->data());
			$this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Upload Data</h4>
					</div>');  
			$this->import_excel();
			$this->buat_user();
			redirect ('ps/karyawan');
			//$this->load->view('ps/v_karyawan', $data);
		}
	}

	function import_excel() {
		$inputFileName = "./excel/data_karyawan.xlsx";

		/**  Identify the type of $inputFileName  **/
		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
		
		/**  Create a new Reader of the type that has been identified  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		
		/**  Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = $reader->load($inputFileName);
		
		/**  Convert Spreadsheet Object to an Array for ease of use  **/
		$schdeules = $spreadsheet->getActiveSheet()->toArray();

		
		for($i = 1;$i < count($schdeules);$i++)
			{
				$nik = $schdeules[$i]['0'];
				$tgl_masuk_kerja=$schdeules[$i]['1'];
				$nama_karyawan=$schdeules[$i]['2'];
				$divisi=$schdeules[$i]['3'];
				$departement=$schdeules[$i]['4'];
				$atasan=$schdeules[$i]['5'];
				$email=$schdeules[$i]['6'];
				$grade=$schdeules[$i]['7'];
				$level=$schdeules[$i]['8'];
				$lokasi_kerja=$schdeules[$i]['9'];
				$tgl_lahir=$schdeules[$i]['10'];
				$no_ktp=$schdeules[$i]['11'];
				$sex=$schdeules[$i]['12'];
				echo $nik."<br>";
				$data=array(
					'nik'=>$nik,
					'tgl_masuk_kerja' => $tgl_masuk_kerja,
					'nama_karyawan' => $nama_karyawan,
					'divisi' => $divisi,
					'departement' => $departement,
					'atasan' => $atasan,
					'email' => $email,
					'grade' => $grade,
					'level' => $level,
					'lokasi_kerja' => $lokasi_kerja,
					'tgl_lahir' => $tgl_lahir,
					'no_ktp' => $no_ktp,
					'sex' => $sex
				);
				 $this->m_Data->insert_data($data,'karyawan');
			}
	}

	function buat_user() {
		$inputFileName = "./excel/data_karyawan.xlsx";

		/**  Identify the type of $inputFileName  **/
		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
		
		/**  Create a new Reader of the type that has been identified  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		
		/**  Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = $reader->load($inputFileName);
		
		/**  Convert Spreadsheet Object to an Array for ease of use  **/
		$schdeules = $spreadsheet->getActiveSheet()->toArray();
		
		for($i = 1;$i < count($schdeules);$i++)
			{
				$nik = $schdeules[$i]['0'];
				$tgl_masuk_kerja=$schdeules[$i]['10'];
				$result = preg_replace("/[^0-9]/", "", $tgl_masuk_kerja);
				$password=date('dmY', strtotime($result));
				
				$data=array(
					'nik' => $nik,
					'password' => md5($password),
					'level' => 'employee',
				);

				$this->m_Data->insert_data($data,'users');
			
			//	return $this->m_Data->insert_data($data,'karyawan');
			}

		//$string='1998-01-02';
		//$result = preg_replace("/[^0-9]/", "", $string);
		//echo $result;
		//echo "<br>";
		//echo date('dmY', strtotime('1994-02-15'));

	}

	function do_insert_cuti_bersama(){
		$jumlah=$this->input->post('jumlah');
		$tahun=date('Y');

		$data=array(
			'jumlah' => $jumlah,
			'tahun' => $tahun,
		);

		if (!empty($jumlah)){
		$this->m_Data->insert_data($data,'cuti_bersama');
		//Update saldo cuti
		$date=date('Y');
		$db=$this->db->query("select * from saldo_cuti where tahun='$date'")->result();
		foreach ($db as $db){
			$where=array(
				'nik' => $db->nik,

			);
			$data=array(
				'saldo_cuti' => $db->saldo_cuti-$jumlah,
			);
			$this->m_Data->update_data($where,$data,'saldo_cuti');
		}
		
		$this->session->set_flashdata('msg', 
		'<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');  
		redirect ('ps/karyawan/cuti_bersama');
		}else{
			$this->session->set_flashdata('msg', 
		'<div class="alert alert-warning">
			<h4>Isi Jumlah Data</h4>
		</div>');  
		redirect ('ps/karyawan/cuti_bersama');
		}
		
	}

	function edit_cuti_bersama($id_cuti_bersama){

		$where=array(
			'id_cuti_bersama' => $id_cuti_bersama,
		);
		$data['cuti_bersama']=$this->m_Data->edit_data($where, 'cuti_bersama')->result();
		$this->load->view('ps/v_edit_cuti_bersama',$data);
	}

	function update_cuti_bersama(){
		$jumlah=$this->input->post('jumlah');
		$where_cuti_bersama=array(
			'id_cuti_bersama' => $this->input->post('id_cuti_bersama'),
		);
		$data_cuti_bersama=array(
			'jumlah' => $jumlah,
		);
		
		//Update Saldo Cuti
		$date=date('Y');
		$jumlahcuti=$this->db->query("select * from cuti_bersama where tahun='$date'")->result();
		foreach ($jumlahcuti as $row){
			$jumlahcuti=$row->jumlah;
		}
		$db=$this->db->query("select * from saldo_cuti where tahun='$date'")->result();
		foreach ($db as $db){
			$where=array(
				'nik' => $db->nik,

			);
			$data=array(
				'saldo_cuti' => $db->saldo_cuti+($jumlahcuti-$jumlah),
			);
		
			$this->m_Data->update_data($where,$data,'saldo_cuti');
		}		
		$this->m_Data->update_data($where_cuti_bersama,$data_cuti_bersama,'cuti_bersama');
		$this->session->set_flashdata('msg', 
		'<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');  
		redirect ('ps/karyawan/cuti_bersama');
	}

	function kurang_jatah_cuti(){
		$nik=$this->input->post('nik');
		$jumlah=$this->input->post('jumlah');

		//$ceknik=$this->m_Data->tampil_data('karyawan');
		//Cek Saldo Cuti
		$tahun=date('Y');
		$ceksaldo=$this->db->query("select * from saldo_cuti where nik='$nik' and tahun='$tahun'")->result();
		foreach ($ceksaldo as $row){
			$saldo_cuti=$row->saldo_cuti;
		}
		//Cek Nik
		$ceknik=$this->db->query("select * from karyawan where nik='$nik'");
		$ceknik=$ceknik->num_rows();
		if ($ceknik < 1){
			$this->session->set_flashdata('msg', 
		'<div class="alert alert-danger">
			<h4>NIK Tidak Ditemukan</h4>
		</div>');  
		redirect ('ps/karyawan/cuti_bersama');
		}else {
			$where=array(
				'nik' => $nik,
			);
			$data=array(
				'saldo_cuti' => $saldo_cuti+$jumlah,
			);
			$this->m_Data->update_data($where,$data,'saldo_cuti');
			$this->session->set_flashdata('msg', 
		'<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');  
		redirect ('ps/karyawan/cuti_bersama');
		}
	}

	function details($id_karyawan){
		$data['karyawan']=$this->db->query("select * from karyawan where nik='$id_karyawan'")->result();
		$this->load->view('ps/v_karyawan_details',$data);
	}

	function pengajuan(){
		$data['atasan']=$this->db->query("select distinct atasan from karyawan")->result();
		$this->load->view('ps/v_pengajuan_cuti',$data);
	}
	function pengajuan_cuti_do_insert(){
		//Input Form
		$atasan=$this->input->post('atasan');
		$nik=$this->input->post('nik');
        $tgl_mulai=$this->input->post('tgl_mulai');
		$tgl_berakhir=$this->input->post('tgl_berakhir');
	//	$tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
	//	$tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
		$total_hari=$this->input->post('total_hari');
		$keterangan=$this->input->post('keterangan');

		//Data Input Cuti Ke DB
		$data = array(
			'nik' => $nik,
			'jenis' => 'cuti',
			'tgl_mulai' => $tgl_mulai,
			'tgl_berakhir' => $tgl_berakhir,
			'total_hari' => $total_hari,
			'keterangan' => $keterangan,
			'atasan' => $atasan,
			'created_at' => date('Y-m-d H:i:s'),
		);
		
		//CEKNIK
		$n=$this->db->query("select * from karyawan where nik='$nik'");
		$row_n=$n->num_rows();

	
		if (!empty($nik)&&!empty($tgl_mulai)&&!empty($tgl_berakhir)&&!empty($total_hari)&&!empty($keterangan))
        {	
			if ($row_n < 1){
				$this->session->set_flashdata('msg', 
                    '<div class="alert alert-danger">
                        <h4>Nik tidak terdaftar</h4>
                    </div>');  
       		 redirect('ps/karyawan/pengajuan');
			}
			else{
			$this->m_Data->insert_data($data,'cuti');
       		$this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Cuti</h4>
                    </div>');  
		redirect('ps/karyawan/pengajuan');
	}
}
else {
	$this->session->set_flashdata('msg', 
				'<div class="alert alert-danger">
					<h4>Semua Nilai Harus Diisi</h4>
				</div>');  
	redirect('ps/karyawan/pengajuan');
	}
	}

	function delete($id_karyawan){
			$where=array(
				'nik' => $id_karyawan,
			);
			$this->m_Data->delete_data($where,'karyawan');
			$this->session->set_flashdata('msg', 
					  '<div class="alert alert-danger">
						  <h4>Delete Data Berhasil</h4>
					  </div>');  
			redirect('ps/karyawan');
		
	}
}