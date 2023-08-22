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

    function approval() {
        $data['filter'] = $this->input->get('filter');
        $data['search'] = $this->input->get('search');
        $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where approval like '%".$data['filter']."%' or karyawan.nik like '%".$data['filter']."%' or jenis like '%".$data['filter']."%' or karyawan.nama_karyawan like '%".$data['filter']."%' or tgl_mulai like '%".$data['filter']."%' or tgl_berakhir like '%".$data['filter']."%' order by created_at DESC")->result();
        $this->load->view('ps/v_approval',$data);
    }

    function approved(){
        $this->db->query("select * from cuti where approval='Ya'")->result();
    }

    function export(){
        {
            $tanggal=$this->input->post('tanggal');
            $bulan=$this->input->post('bulan');
            $tahun=$this->input->post('tahun');
            $cuti = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik where month(created_at)='$bulan' and year(created_at)='$tahun' and day(created_at)='$tanggal'")->result();
            $cuti_rows = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik where month(created_at)='$bulan' and year(created_at)='$tahun' and day(created_at)='$tanggal'")->num_rows();
            if ($cuti_rows ==0){
                $this->session->set_flashdata('msg', 
                '<div class="alert alert-warning">
                    <h4>Data Belum Ada</h4>
                </div>');  
    redirect('ps/home/approval');
            }else{
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
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $cuti->nik)
            ->setCellValue('B'.$i, $cuti->nama_karyawan)
            ->setCellValue('C'.$i, $cuti->jenis)
            ->setCellValue('D'.$i, $cuti->keterangan)
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

function jenis_cuti() {
    $filter = $this->input->get('filter');
  //  $data['search'] = $this->input->get('search');
    $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where jenis='$filter'")->result();
    $this->load->view('ps/v_approval',$data);
}


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
            $jumlah_pengurangan_cuti = $schdeules[$i]['1'];

            $query=$this->db->query("select * from saldo_cuti where nik='$nik'")->result();
            foreach ($query as $row){
                $saldo_cuti=$row->saldo_cuti;
            }
            $saldo_cuti = $saldo_cuti - $jumlah_pengurangan_cuti;
            $this->db->query("update saldo_cuti set saldo_cuti='$saldo_cuti' where nik='$nik'");

        }

    }

    function upload_karyawan_resign(){   
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xls';
        $config['file_name'] = 'data_karyawan_resign.xls';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
							<h4>Gagal Upload, Pastikan Format Data Adalah .xlsx</h4>
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
