<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load library phpspreadsheet
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

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
        if ($level!='ps') {
            redirect ('welcome');
        }else{
        $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
	
	}

    function index() {
        $session=$this->session->nama;
        $data['jumlahkaryawan']=$this->db->query("select * from karyawan")->num_rows();
        $date=date('Y');
        $data['cutibersama']=$this->db->query("select * from cuti_bersama where year(tanggal)='$date'")->num_rows();
        $date=date('Y');
        $data['harilibur']=$this->db->query("select * from holiday where year(tanggal)='$date'")->num_rows();
        $data['karyawan']=$this->db->query("select * from karyawan where nik='$session'")->result();
        $this->load->view('ps/v_home',$data);
    }
	
	function supervisor(){
        $data['supervisor']=$this->m_Data->tampil_data('supervisor')->result();
        $this->load->view('ps/v_supervisor',$data);
    }
	
	function delete_spv($id){
            $where=array(
                'id_supervisor' => $id,
            );
           $this->m_Data->delete_data($where,'supervisor');
           $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Delete Atasan</h4>
                    </div>');  
			redirect('ps/home/supervisor');
      }
	
	
	function do_insert(){

            $nik=$this->input->post('nik');
            $nama_supervisor=$this->input->post('nama_supervisor');
			$email = $this->input->post('email');
			$divisi = $this->input->post('divisi');
			
            $data = array( 
                  'nik'  =>  $nik,
				  'nama_supervisor'  =>  $nama_supervisor,
				  'email'  =>  $email,
				  'divisi'  =>  $divisi,
            );
			
			 $ceknik=$this->db->query("select count(*) as hitung from supervisor where nik='$nik'")->result();
			 
			 foreach ($ceknik as $row){
				 $ceknik=$row->hitung;
			 }
			 
			 
			 
			if ($ceknik > 0){
				$this->session->set_flashdata('msg', 
				'<div class="alert alert-danger">
					<h4>NIK Atasan Sudah Terdaftar</h4>
				</div>');  
				redirect('ps/home/supervisor');
			}
			
			 $cekuser=$this->db->query("select count(*) as hitung from users where nik='$nik'")->result();
			 
			 foreach ($cekuser as $row){
				 $cekuser=$row->hitung;
			 }
			 
			 if ($ceknik == 0){
				$data2 = array(
                'nik' => $nik,
                'password' => md5(123),
                'level' => 'supervisor',
				);
				 
				$this->m_Data->insert_data($data2, 'users');
			}

			if (!empty($nik)){				
                $this->m_Data->insert_data($data,'supervisor');
                $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Data Atasan</h4>
                    </div>');  
					redirect('ps/home/supervisor');
			     }
			else {
					$this->session->set_flashdata('msg', 
						'<div class="alert alert-danger">
							<h4>Nik Tidak Boleh Kosong</h4>
						</div>');  
					redirect('ps/home/supervisor');
				  }
    }


    function approval() {
        // $data['filter'] = $this->input->get('filter');
        // $data['search'] = $this->input->get('search');
        // $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where approval like '%".$data['filter']."%' or karyawan.nik like '%".$data['filter']."%' or jenis like '%".$data['filter']."%' or karyawan.nama_karyawan like '%".$data['filter']."%' or tgl_mulai like '%".$data['filter']."%' or tgl_berakhir like '%".$data['filter']."%' order by created_at DESC")->result();

        $filter = $this->input->get('filter');
        $filter2 = $this->input->get('filter2');

        $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik order by created_at DESC")->result();

        if($filter){
            $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where approval like '%".$filter."%'  order by created_at DESC")->result();
        }

        else if($filter2){
            $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where jenis='$filter2'")->result();
        }

        if($filter2 && $filter){
            $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where approval like '%".$filter."%'  and jenis='$filter2' order by created_at DESC")->result();
        }

        $this->load->view('ps/v_approval',$data);
    }

    function approved(){
        $this->db->query("select * from cuti where approval='Ya'")->result();
    }

    function export(){
        {
            $date_begin=$this->input->post('date_begin');
            $date_end=$this->input->post('date_end');
            $date_begin=date('Y-m-d', strtotime($date_begin));
            $date_end=date('Y-m-d', strtotime($date_end));
            $cuti = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik where date(created_at) BETWEEN '$date_begin' AND '$date_end'")->result();
            $cuti2 = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik")->result();
            $cuti_rows = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik where date(created_at) BETWEEN '$date_begin' AND '$date_end'")->num_rows();

            // if ($cuti_rows == 0){
            //     $this->session->set_flashdata('msg', 
            //     '<div class="alert alert-warning">
            //         <h4>Data Belum Ada</h4>
            //     </div>');  
            // redirect('ps/home/approval');
            // }
            if($cuti_rows==0){
                // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            
            // Set document properties
            $spreadsheet->getProperties()->setCreator('PT Niramas Utama')
            ->setLastModifiedBy('PT Niramas Utama')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
            
           //Title Merge Cell
           $spreadsheet->setActiveSheetIndex(0)
           ->mergeCells('A1:H1');
            $spreadsheet->getActiveSheet()
           ->getCell('A1')
           ->setValue('Report Pengajuan Cuti '.date('d-m-Y'))
           ->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
            //Font Bold
            $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            // Add some data
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'NIK')
            ->setCellValue('B2', 'NAMA KARYAWAN')
            ->setCellValue('C2', 'JENIS')
            ->setCellValue('D2', 'KETERANGAN')
            ->setCellValue('E2', 'ATASAN')
            ->setCellValue('F2', 'TANGGAL MULAI')
            ->setCellValue('G2', 'TANGGAL BERAKHIR')
            ->setCellValue('H2', 'TOTAL CUTI/IZIN')
            ->setCellValue('I2', 'PERSETUJUAN')
            ->setCellValue('J2', 'DI AJUKAN PADA TANGGAL')
            ;
            
            // Miscellaneous glyphs, UTF-8
            $i=3; foreach($cuti2 as $cuti) {
                
                if ($cuti->approval=='Ya') {
                    $value="Telah Disetujui";
                }elseif ($cuti->approval=='Tidak'){
                    $value="Tidak Disetujui";
                }else{
                    $value="Belum Direspond";
                }
				
				if ($cuti->jenis =='cuti khusus')
				{
					$keterangan = $cuti->cuti_khusus;
				}
				else
				{
					$keterangan = $cuti->keterangan;
				}
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $cuti->nik)
            ->setCellValue('B'.$i, $cuti->nama_karyawan)
            ->setCellValue('C'.$i, $cuti->jenis)
            ->setCellValue('D'.$i, $keterangan)
            ->setCellValue('E'.$i, $cuti->atasan)
            ->setCellValue('F'.$i, $cuti->tgl_mulai)
            ->setCellValue('G'.$i, $cuti->tgl_berakhir)
            ->setCellValue('H'.$i, $cuti->total_hari)
            ->setCellValue('I'.$i, $value)
            ->setCellValue('J'.$i, $cuti->created_at);
            $i++;
            }
            
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Report Cuti '.date('d-m-Y H'));
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $spreadsheet->setActiveSheetIndex(0);
            
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Report-Cuti.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  
            $writer->save('php://output');
            exit;
            }
            else{
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            
            // Set document properties
            $spreadsheet->getProperties()->setCreator('PT Niramas Utama')
            ->setLastModifiedBy('PT Niramas Utama')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
            
           //Title Merge Cell
           $spreadsheet->setActiveSheetIndex(0)
           ->mergeCells('A1:H1');
            $spreadsheet->getActiveSheet()
           ->getCell('A1')
           ->setValue('Report Pengajuan Cuti '.date('d-m-Y'))
           ->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
            //Font Bold
            $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            // Add some data
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'NIK')
            ->setCellValue('B2', 'NAMA KARYAWAN')
            ->setCellValue('C2', 'JENIS')
            ->setCellValue('D2', 'KETERANGAN')
            ->setCellValue('E2', 'ATASAN')
            ->setCellValue('F2', 'TANGGAL MULAI')
            ->setCellValue('G2', 'TANGGAL BERAKHIR')
            ->setCellValue('H2', 'TOTAL CUTI/IZIN')
            ->setCellValue('I2', 'PERSETUJUAN')
            ->setCellValue('J2', 'DI AJUKAN PADA TANGGAL')
            ;
            
            // Miscellaneous glyphs, UTF-8
            $i=3; foreach($cuti as $cuti) {
                
                if ($cuti->approval=='Ya') {
                    $value="Telah Disetujui";
                }elseif ($cuti->approval=='Tidak'){
                    $value="Tidak Disetujui";
                }else{
                    $value="Belum Direspond";
                }
				
				if ($cuti->jenis =='cuti khusus')
				{
					$keterangan = $cuti->cuti_khusus;
				}
				else
				{
					$keterangan = $cuti->keterangan;
				}
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $cuti->nik)
            ->setCellValue('B'.$i, $cuti->nama_karyawan)
            ->setCellValue('C'.$i, $cuti->jenis)
            ->setCellValue('D'.$i, $keterangan)
            ->setCellValue('E'.$i, $cuti->atasan)
            ->setCellValue('F'.$i, $cuti->tgl_mulai)
            ->setCellValue('G'.$i, $cuti->tgl_berakhir)
            ->setCellValue('H'.$i, $cuti->total_hari)
            ->setCellValue('I'.$i, $value)
            ->setCellValue('J'.$i, $cuti->created_at);
            $i++;
            }
            
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Report Cuti '.date('d-m-Y H'));
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $spreadsheet->setActiveSheetIndex(0);
            
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Report-Cuti.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  
            $writer->save('php://output');
            exit;
        }
            }
   }

   function weekend(){
    $start = new DateTime('2020-01-09');
    $end = new DateTime('2020-01-11');
//Define our holidays
//New Years Day
//Martin Luther King Day
    $holidays = array(
  // '2020-01-09',
    '2020-01-10',
	'2020-01-11',
    );
//Create a DatePeriod with date intervals of 1 day between start and end dates
$period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );
//Holds valid DateTime objects of valid dates
$days = array();
//iterate over the period by day
foreach( $period as $day )
{
        //If the day of the week is not a weekend
	$dayOfWeek = $day->format( 'N' );
	if( $dayOfWeek < 6 ){
                //If the day of the week is not a pre-defined holiday
		$format = $day->format( 'Y-m-d' );
		if( false === in_array( $format, $holidays ) ){
                        //Add the valid day to our days array
                        //This could also just be a counter if that is all that is necessary
			$days[] = $day;
		}
	}
}

    echo count( $days ); 


}

function export_karyawan(){
    {
        $karyawan = $this->db->query("select * from karyawan")->result();
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('PT Niramas Utama')
        ->setLastModifiedBy('PT Niramas Utama')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Test result file');
        
       //Title Merge Cell
       $spreadsheet->setActiveSheetIndex(0)
       ->mergeCells('A1:H1');
        $spreadsheet->getActiveSheet()
       ->getCell('A1')
       ->setValue('Data Karyawan '.date('d-m-Y'))
       ->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        //Font Bold
        $spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'NIK')
        ->setCellValue('B2', 'TANGGAL MASUK KERJA')
        ->setCellValue('C2', 'NAMA')
        ->setCellValue('D2', 'DIVISI')
        ->setCellValue('E2', 'DEPARTEMENT')
        ->setCellValue('F2', 'ATASAN')
        ->setCellValue('G2', 'EMAIL')
        ->setCellValue('H2', 'GRADE TITLE')
        ->setCellValue('I2', 'GRADE')
        ->setCellValue('J2', 'LEVEL')
        ->setCellValue('K2', 'LOKASI KERJA')
        ->setCellValue('L2', 'TANGGAL LAHIR')
        ->setCellValue('M2', 'NO KTP')
        ->setCellValue('N2', 'SEX');
        
        // Miscellaneous glyphs, UTF-8
        $i=3; foreach($karyawan as $cuti) {
            
     
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $cuti->nik)
        ->setCellValue('B'.$i, $cuti->tgl_masuk_kerja)
        ->setCellValue('C'.$i, $cuti->nama_karyawan)
        ->setCellValue('D'.$i, $cuti->divisi)
        ->setCellValue('E'.$i, $cuti->departement)
        ->setCellValue('F'.$i, $cuti->atasan)
        ->setCellValue('G'.$i, $cuti->email)
        ->setCellValue('H'.$i, $cuti->grade_title)
        ->setCellValue('I'.$i, $cuti->grade)
        ->setCellValue('J'.$i, $cuti->level)
        ->setCellValue('K'.$i, $cuti->lokasi_kerja)
        ->setCellValue('L'.$i, $cuti->tgl_lahir)
        ->setCellValue('M'.$i, (string)$cuti->no_ktp)
        ->setCellValue('N'.$i, $cuti->sex);

        $i++;
        }
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Data Karyawan '.date('d-m-Y H'));
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data-Karyawan.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
        }
}


function export_report(){
    {
        $report = $this->db->query("select a.nik AS nik, b.nama_karyawan AS nama_karyawan, a.tahun AS tahun, a.saldo_cuti AS saldo_cuti, a.cuti_terpakai AS cuti_terpakai, (a.saldo_cuti - a.cuti_terpakai) AS sisa_cuti from (cuticrew.saldo_cuti a join cuticrew.karyawan b) where (a.nik = b.nik) order by a.nik")->result();
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('PT Niramas Utama')
        ->setLastModifiedBy('PT Niramas Utama')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Test result file');
        
       //Title Merge Cell
       $spreadsheet->setActiveSheetIndex(0)
       ->mergeCells('A1:F1');
        $spreadsheet->getActiveSheet()
       ->getCell('A1')
       ->setValue('Data Saldo Cuti Karyawan '.date('d-m-Y'))
       ->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
        //Font Bold
        $spreadsheet->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'NIK')
        ->setCellValue('B2', 'NAMA')
        ->setCellValue('C2', 'TAHUN')
        ->setCellValue('D2', 'SALDO CUTI')
        ->setCellValue('E2', 'TERPAKAI')
        ->setCellValue('F2', 'SISA CUTI');
        
        // Miscellaneous glyphs, UTF-8
        $i=3; foreach($report as $cuti) {
            
     
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $cuti->nik)
        ->setCellValue('B'.$i, $cuti->nama_karyawan)
        ->setCellValue('C'.$i, $cuti->tahun)
        ->setCellValue('D'.$i, $cuti->saldo_cuti)
        ->setCellValue('E'.$i, $cuti->cuti_terpakai)
        ->setCellValue('F'.$i, $cuti->sisa_cuti);

        $i++;
        }
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Saldo Cuti '.date('d-m-Y H'));
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data-Saldo-Cuti-Karyawan.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
       }
}

function export_report_resign(){
    {
        $report = $this->db->query("select * from v_cuti_resign")->result();
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('PT Niramas Utama')
        ->setLastModifiedBy('PT Niramas Utama')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Test result file');
        
       //Title Merge Cell
       $spreadsheet->setActiveSheetIndex(0)
       ->mergeCells('A1:F1');
        $spreadsheet->getActiveSheet()
       ->getCell('A1')
       ->setValue('Data Saldo Cuti Karyawan Resign '.date('d-m-Y'))
       ->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
        //Font Bold
        $spreadsheet->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'NIK')
        ->setCellValue('B2', 'NAMA')
        ->setCellValue('C2', 'TAHUN')
        ->setCellValue('D2', 'SALDO CUTI')
        ->setCellValue('E2', 'TERPAKAI')
        ->setCellValue('F2', 'SISA CUTI');
        
        // Miscellaneous glyphs, UTF-8
        $i=3; foreach($report as $cuti) {
            
			$sisaan = ($cuti->saldo_cuti - $cuti->cuti_terpakai);
     
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $cuti->nik)
        ->setCellValue('B'.$i, $cuti->nama_karyawan)
        ->setCellValue('C'.$i, $cuti->tahun)
        ->setCellValue('D'.$i, $cuti->saldo_cuti)
        ->setCellValue('E'.$i, $cuti->cuti_terpakai)
        ->setCellValue('F'.$i, $sisaan);

        $i++;
        }
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Saldo Cuti '.date('d-m-Y H'));
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data-Saldo-Cuti-Karyawan.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
       }
}

// function jenis_cuti() {
//     $filter = $this->input->get('filter');
//     $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where jenis='$filter'")->result();
//   //  $data['search'] = $this->input->get('search');
//     $this->load->view('ps/v_approval',$data);
// }


function pengurangan_cuti() {
        $this->load->view('ps/v_pengurangan_cuti');
}
function karyawan_resign() {
    $this->load->view('ps/v_karyawan_resign');
}
function upload_pengurangan_cuti(){   
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xls';
        $config['file_name'] = 'data_pengurangan_cuti.xls';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
							<h4>Gagal Upload, Pastikan Format Data Adalah .xlsx</h4>
						</div>');
            redirect('ps/home/pengurangan_cuti');
        } else {
            //$data = array('upload_data' => $this->upload->data());
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
            // $this->import_excel();
            // $this->buat_user();
            // $this->buat_saldocuti();
            $this->import_excel_pengurangan_cuti();
            redirect('ps/home/pengurangan_cuti');
            //$this->load->view('ps/v_karyawan', $data);
        }
    }

    function import_excel_pengurangan_cuti(){
        $inputFileName = "./excel/data_pengurangan_cuti.xls";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        for ($i = 1; $i < count($schdeules); $i++) {
            $nik = $schdeules[$i]['0'];
            // $jumlah_pengurangan_cuti = $schdeules[$i]['1'];

            $query=$this->db->query("select * from saldo_cuti where nik='$nik'")->result();
            foreach ($query as $row){
                $saldo_cuti=$row->saldo_cuti;
            }
            $saldo_cuti = $saldo_cuti + 7;
            $this->db->query("update saldo_cuti set saldo_cuti='$saldo_cuti' where nik='$nik'");

        }

    }

    function upload_karyawan_resign(){   
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xlsx|xls';
		$config['max_size']  = '2048';
        $config['file_name'] = 'data_karyawan_resign.xls';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
			//echo $error;
			//die;
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
						<h4>Gagal Upload, Pastikan Format Data Adalah .xls</h4>
					</div>');
            redirect('ps/home/karyawan_resign');
        } else {
            //$data = array('upload_data' => $this->upload->data());
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
            // $this->import_excel();
            // $this->buat_user();
            // $this->buat_saldocuti();
            $this->import_excel_karyawan_resign();
            redirect('ps/home/karyawan_resign');
            //$this->load->view('ps/v_karyawan', $data);
        }
    }


    function import_excel_karyawan_resign(){
        $inputFileName = "./excel/data_karyawan_resign.xls";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        for ($i = 1; $i < count($schdeules); $i++) {
            $nik = $schdeules[$i]['0'];
            // $jumlah_pengurangan_cuti = $schdeules[$i]['1'];

            $this->db->query("delete from karyawan where nik='$nik'");

        }

    }

}
