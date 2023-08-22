<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Izin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //Cek Session
        $nik = $this->session->nama;
        $cek_level = $this->db->query("select level from users where nik='$nik'")->result();
        foreach ($cek_level as $row) {
            $level = $row->level;
        }
        if ($level != 'employee') {
            redirect('welcome');
        } else {
            $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

    }

    public function index()
    {
        //$data['karyawan']=$this->db->query("select * from karyawan")->result();
        $nik = $this->session->nama;
        $data['karyawan'] = $this->db->query("select * from karyawan where nik='$nik'")->result();
        $data['saldo_cuti'] = $this->db->query("select * from saldo_cuti where nik='$nik'")->result();
        $data['atasan'] = $this->db->query("select distinct atasan from karyawan")->result();
        $this->load->view('employee/v_izin', $data);
    }

    public function do_insert()
    {
        $session = $this->session->nama;
        $session = $this->db->query("select * from users where nik='$session'")->result();
        foreach ($session as $row) {
            $nik = $row->nik;
        }

        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_berakhir = $this->input->post('tgl_berakhir');
      //  $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));
        //$tgl_berakhir = date('Y-m-d', strtotime($tgl_berakhir));
        $total_hari = $this->input->post('total_hari');
        $keterangan = $this->input->post('keterangan');
        $atasan = $this->input->post('atasan');

		//Total Hari Otomatis
        $start = new DateTime($tgl_mulai);
        $end = new DateTime($tgl_berakhir);
        $holidays = array(
            '2012-01-02',
            '2011-01-17',
        );

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
//Holds valid DateTime objects of valid dates
        $days = array();
//iterate over the period by day
        foreach ($period as $day) {
            //If the day of the week is not a weekend
            $dayOfWeek = $day->format('N');
            if ($dayOfWeek < 7) {
                //If the day of the week is not a pre-defined holiday
                $format = $day->format('Y-m-d');
                if (false === in_array($format, $holidays)) {
                    //Add the valid day to our days array
                    //This could also just be a counter if that is all that is necessary
                    $days[] = $day;
                }
            }
        }
       // echo count($days);

        $data = array(
            'nik' => $nik,
            'jenis' => 'izin',
            'tgl_mulai' => $tgl_mulai,
            'tgl_berakhir' => $tgl_berakhir,
            'total_hari' => count($days)+1,
          // 'total_hari' => $total_hari, 
           'keterangan' => $keterangan,
            'atasan' => $atasan,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($tgl_mulai) && !empty($tgl_berakhir) && !empty($keterangan)) {
            $this->m_Data->insert_data($data, 'cuti');
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
                        <h4>Berhasil Submit Izin Silahkan Tunggu Persetujuan</h4>
                    </div>');
            redirect('employee/izin');
        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-danger">
                        <h4>Semua Nilai Harus Diisi</h4>
                    </div>');
            redirect('employee/izin');
        }

	}

	function weekend(){
		$start = new DateTime('2020-01-01');
		$end = new DateTime('2020-01-02');
	//Define our holidays
	//New Years Day
	//Martin Luther King Day
		$holidays = array(
		'2022-01-02',
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
}
