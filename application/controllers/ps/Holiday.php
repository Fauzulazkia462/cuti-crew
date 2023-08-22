<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
// End load library phpspreadsheet
class Holiday extends CI_Controller {
	

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

    function index(){
        $data['holiday']=$this->m_Data->tampil_data('holiday')->result();
        $this->load->view('ps/v_holiday',$data);
    }

    function do_insert(){

            $tanggal=$this->input->post('tanggal');
            $tanggal1=date('Y-m-d', strtotime($tanggal));
            $data = array( 
                  'tanggal'  =>  $tanggal1,
            );

			if (!empty($tanggal)){				
                $this->m_Data->insert_data($data,'holiday');
                $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Tanggal Liburan</h4>
                    </div>');  
		redirect('ps/holiday');
			}else {
				$this->session->set_flashdata('msg', 
                    '<div class="alert alert-danger">
                        <h4>Gagal Submit Tanggal Liburan</h4>
                    </div>');  
		redirect('ps/holiday');
			}
        }

        function delete($id){
            $where=array(
                'id_holiday' => $id,
            );
           $this->m_Data->delete_data($where,'holiday');
           $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil</h4>
                    </div>');  
		redirect('ps/holiday');
        }
		
    function upload_holiday(){   
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xls';
        $config['file_name'] = 'holiday.xls';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
							<h4>Gagal Upload, Pastikan Format Data Adalah .xls</h4>
						</div>');
            redirect('ps/holiday');
        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
            $this->import_excel_holiday();
            redirect('ps/holiday');
        }
    }
	
	
	function upload_cuti_bersama(){   
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xls';
        $config['file_name'] = 'cuti_bersama.xls';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                //'<div class="alert alert-warning">
				//			<h4>Gagal Upload, Pastikan Format Data Adalah .xls</h4></div>'
				$error);
            redirect('ps/karyawan/cuti_bersama');
        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
            $this->import_excel_cutber();
            redirect('ps/karyawan/cuti_bersama');
        }
    }


    function import_excel_holiday(){
        $inputFileName = "./excel/holiday.xls";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        for ($i = 1; $i < count($schdeules); $i++) {
            $date = $schdeules[$i]['0'];
			$event = $schdeules[$i]['1'];

			$cek = "select count(tanggal) from holiday where tanggal='$date' ";

			if ($cek == 0)
			{
				$data = array(
					'tanggal' => $date,
					'event' => $event,
				);

				$this->m_Data->insert_data($data, 'holiday');
			}

        }

    }
	
	function import_excel_cutber(){
        $inputFileName = "./excel/cuti_bersama.xls";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();
        
        for ($i = 1; $i < count($schdeules); $i++) {
            $dates = $schdeules[$i]['0'];
			
			// echo $dates;
			//die;

			$cek = "select count(tanggal) as tanggal from cuti_bersama where tanggal='$dates'";

			if ($cek == 0)
			{
				$data_cuti = array(
					'tanggal' => $dates,
				);

					
		            $date = date('Y');
		            $db = $this->db->query("select * from saldo_cuti where tahun='$date'")->result();
		            foreach ($db as $db) {
		            $join=$this->db->query("select * from karyawan where nik='$db->nik'")->result();

		            foreach ($join as $row){
		                $masukkerja=$row->tgl_masuk_kerja;
		                $start_date = new DateTime($masukkerja);
		                $end_date = new DateTime (date("Y-m-d"));
		                $interval=$start_date->diff($end_date);

		                    $where = array(
		                        'nik' => $db->nik,
		    
		                    );

		                if ($interval->days < 365){
		                        $data = array(
		                        'saldo_cuti' => $db->saldo_cuti,
		                    );
		                }else{
		                    $data = array(
		                        'saldo_cuti' => $db->saldo_cuti - 1,
		                    );
		                }
		                if ($date == date('Y', strtotime($dates))) {
		                    $this->m_Data->update_data($where, $data, 'saldo_cuti');
		                }
						
						$cek=$this->db->query("select count(*) as jumlah from cuti where nik='$db->nik' and tgl_mulai='$dates' ")->result(); 
						foreach ($cek as $cek){
							$jml = $cek->jumlah;
							if ($jml == 0)
							{
								$data = array(
											'nik' => $db->nik,
											'jenis' => 'cuti',
											'tgl_mulai' => $dates,
											'tgl_berakhir' => $dates,
											'total_hari' => 1,
											'keterangan' => 'Cuti Bersama',
											'atasan' => 'Automatic',
											'approval' => 'Ya',
											'is_cutber' => 1,
											'created_at' => date('Y-m-d H:i:s'),
										);
								
								$this->m_Data->insert_data($data, 'cuti');
							}
						}
					   }
		             }
					 
			            $this->m_Data->insert_data($data_cuti, 'cuti_bersama');
			            
		        
			}

        }

        $this->db->query('DELETE FROM cuti_bersama WHERE tanggal IS NULL');
        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">
            <h4>Berhasil</h4>
            </div>');
            redirect('ps/karyawan/cuti_bersama');


    }
}