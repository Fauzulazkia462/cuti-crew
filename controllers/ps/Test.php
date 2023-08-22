<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
//require APPPATH . 'libraries/RestController.php';
class Test extends Rest_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function user_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }

    public function cuti_get(){
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
                //$this->response
            }
	}
    }

    function weekend(){
        $date = '2011-01-01';
        $timestamp = strtotime($date);

    $weekday= date("l", $timestamp );

    if ($weekday =="Saturday" OR $weekday =="Sunday") { return true; } 
    else {return false; }
}

function testing(){
    //Define out start and end dates
$start = new DateTime('2020-01-01');
$end = new DateTime('2020-01-19');
//Define our holidays
//New Years Day
//Martin Luther King Day
$holidays = array(
	'2011-01-01',
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
var_dump( count( $days ) );
}
}