<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';


use SMSGatewayMe\Client\Api\DeviceApi;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class Welcome extends CI_Controller
{
    function device(){
        // Configure client
  $config = Configuration::getDefaultConfiguration();
  $config->setApiKey('Authorization','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTU4MDc3MDAxMCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjc3MTY0LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.PRIZL48USgdO4nwk8wFbFtVFkkGi8DJLdd3dljiXKYY');
  $apiClient = new ApiClient($config);

  // Create device client
  $deviceClient = new DeviceApi($apiClient);

  // Get device information
  $device = $deviceClient->getDevice(1);
  print_r($device);
    }

    function testing(){
            // Configure client
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTU4MDc3MDAxMCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjc3MTY0LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.PRIZL48USgdO4nwk8wFbFtVFkkGi8DJLdd3dljiXKYY');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);

            // Sending a SMS Message
            $sendMessageRequest1 = new SendMessageRequest([
                'phoneNumber' => '6285814431078',
                'message' => 'test1',
                'deviceId' => 1
            ]);
            // $sendMessageRequest2 = new SendMessageRequest([
            //     'phoneNumber' => '07791064781',
            //     'message' => 'test2',
            //     'deviceId' => 2
            // ]);
            $sendMessages = $messageClient->sendMessages([
                $sendMessageRequest1
            ]);
            print_r($sendMessages);
    }
    public function index()
    {
        $this->load->view('welcome_message');
      //  $this->load->view('v_mt');
    }

    public function aksi_login()
    {
        $nik = $this->input->post('nik');
        $password = $this->input->post('password');
        $where = array(
            'nik' => $nik,
            'password' => md5($password),
        );
        $cek = $this->m_login->cek_login("users", $where)->num_rows();
        $cek_level = $this->db->query("select level from users where nik='$nik'")->result();
        foreach ($cek_level as $row) {
            $level = $row->level;
        }

        //Set User Session
        if ($cek > 0) {

            $data_session = array(
                'nama' => $nik,
                'logged' => true,
                //'status' => "login"
            );
            $this->session->set_userdata($data_session);
            if ($level == 'employee') {
                redirect('employee/home/');
            } elseif ($level == 'supervisor') {
                redirect('supervisor/home/');
            } elseif ($level == 'admin') {
                redirect('admin/home');
            } elseif ($level == 'ps' or $level == 'evi') {
                redirect('ps/home');
            }
        } else {
        //     echo "<script language='javascript'>
		// 	alert('Username atau Password Salah');
		// 	 window.location='../welcome';
		//   </script>";
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('welcome');
    }

    public function coba()
    {
        $cuti = $this->db->query("select saldo_cuti.nik, saldo_cuti.saldo_minus, karyawan.tgl_masuk_kerja from saldo_cuti join karyawan on saldo_cuti.nik=karyawan.nik")->result();
        foreach ($cuti as $cuti) {
            $minus = $cuti->saldo_minus;
            $masukkerja = $cuti->tgl_masuk_kerja;
            $sesi = $cuti->nik;

            $saldo = 12 - 0;
            $start_date = new DateTime($masukkerja);
            $end_date = new DateTime(date("Y-m-d"));
            $interval = $start_date->diff($end_date);
            $tahun = date('Y') - substr($masukkerja, 0, 4);
            $kelipatan = $tahun / 4;
            $modulus = $tahun % 4;
            $kurang = substr($kelipatan, 0, 1);
            // echo $modulus;
            if ($modulus == 0) {
                $kerja = ($interval->days - $kelipatan) % 365;
                $masakerja = $interval->days - $kelipatan;
            } else {
                $kerja = ($interval->days - $kurang) % 365;
                $masakerja = $interval->days - $kurang;
            }
            if ($kerja == 0) {
                $data = array(
                    'saldo_cuti' => $saldo,
                    'cuti_terpakai' => 0,
                    'saldo_minus' => 0,
                    'tahun' => date('Y'),
                );
                $where = array(
                    'nik' => $sesi,
                );
                $this->m_Data->update_data($where, $data, 'saldo_cuti');
                //echo "berhasil update data";
            }
			}
	}

    public function backup()
    {
        $this->load->dbutil();

        $prefs = array(
            'format' => 'zip',
            'filename' => 'backup-db.sql',
        );

        $backup = &$this->dbutil->backup($prefs);

        $db_name = 'backup-db-' . date("Y-m-d-H-i-s") . '.zip';
        $save = 'backup/' . $db_name;

        $this->load->helper('file');
        write_file($save, $backup);

        $this->load->helper('download');
        force_download($db_name, $backup);
    }

    function mt(){
        $this->load->view('v_mt');
    }
}
