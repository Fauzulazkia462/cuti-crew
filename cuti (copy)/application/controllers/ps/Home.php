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
        if ($level!='ps'){
            redirect ('welcome');
        }else{
        $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
	
	}

    function index() {
        $this->load->view('ps/v_home');
    }

    function approval() {
        $data['filter'] = $this->input->get('filter');
        $data['search'] = $this->input->get('search');
        $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan from cuti join karyawan on cuti.nik=karyawan.nik where approval like '%".$data['filter']."%' or karyawan.nik like '%".$data['filter']."%' or jenis like '%".$data['filter']."%' or karyawan.nama_karyawan like '%".$data['filter']."%' or tgl_mulai like '%".$data['filter']."%' or tgl_berakhir like '%".$data['filter']."%'")->result();
        $this->load->view('ps/v_approval',$data);
    }

    function approved(){
        $this->db->query("select * from cuti where approval='Ya'")->result();
    }

    function export(){
        {
            $cuti = $this->db->query("select * from cuti join karyawan on cuti.nik=karyawan.nik")->result();
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
            $spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            // Add some data
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'NIK')
            ->setCellValue('B2', 'NAMA KARYAWAN')
            ->setCellValue('C2', 'JENIS')
            ->setCellValue('D2', 'ATASAN')
            ->setCellValue('E2', 'TANGGAL MULAI')
            ->setCellValue('F2', 'TANGGAL BERAKHIR')
            ->setCellValue('G2', 'TOTAL CUTI/IZIN')
            ->setCellValue('H2', 'PERSETUJUAN')
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
            ->setCellValue('D'.$i, $cuti->atasan)
            ->setCellValue('E'.$i, $cuti->tgl_mulai)
            ->setCellValue('F'.$i, $cuti->tgl_berakhir)
            ->setCellValue('G'.$i, $cuti->total_hari)
            ->setCellValue('H'.$i, $value);
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

   function weekend(){
    $start = new DateTime('2020-01-01');
    $end = new DateTime('2020-01-19');
//Define our holidays
//New Years Day
//Martin Luther King Day
    $holidays = array(
	'2020-01-02',
	'2011-01-17',
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
	if( $dayOfWeek < 7 ){
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

function test(){
    $date="20 January 2020";
    echo date('d-m-Y', strtotime($date));
}
}
