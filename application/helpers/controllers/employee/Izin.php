<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('vendor/autoload.php');
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
        foreach ($data['karyawan'] as $row){
            $divisi=$row->divisi;
        }
        if ($divisi=="Product Development"){
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where nama_supervisor='Christ Wina Dyah Ayu Maharani'")->result();
		}else{
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where divisi='$divisi' order by nama_supervisor ASC")->result();
        }
           $this->load->view('employee/v_izin', $data);
    }

    public function do_insert()
    {
        

        //Session
        $session = $this->session->nama;
        $session = $this->db->query("select * from users where nik='$session'")->result();
        foreach ($session as $row) {
            $nik = $row->nik;
        }

        $tgl_mulai= $this->input->post('tgl_mulai');
        $tgl_berakhir = $this->input->post('tgl_berakhir');
        $tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
        $tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
      //  $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));
        //$tgl_berakhir = date('Y-m-d', strtotime($tgl_berakhir));
        $total_hari = $this->input->post('total_hari');
        $keterangan = $this->input->post('keterangan');
        $atasan = $this->input->post('atasan');

        //Perhtingan 2 Hari Setelah pengajuan
		$start_date = new DateTime($tgl_mulai);
		$end_date = new DateTime (date("Y-m-d"));
		$inter = $start_date->diff($end_date);

        if ($atasan=='NULL'){
            $this->session->set_flashdata('msg',
            '<div class="alert alert-danger">
                    <h4>Silahkan Isi Kolom Atasan</h4>
                </div>');
        redirect('employee/izin');
        }

       //Total Hari Otomatis
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
            "text/html", "Berdasarkan Data Dibawah Ini<br>NIK : ".$nik."<br>Nama : ".$nama."<br>Jenis Pengajuan : Izin<br>Tanggal Mulai : ".$tgl_mulai."<br>Tanggal berahir : ".$tgl_berakhir."<br>Jumlah Hari : ".$jumlahhari."<br>Keterangan : ".$keterangan."<br>Silahkan Login <a href='https://cuticrew.niramasutama.com/'>Disini </a> Untuk Approval"
        );
        $sendgrid = new \SendGrid('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');
        // 
      //  $sendgrid = new \SendGrid('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');
        // try {
        //     $response = $sendgrid->send($email);
        //     print $response->statusCode() . "\n";
        //     print_r($response->headers());
        //     print $response->body() . "\n";
        // } catch (Exception $e) {
        //     echo 'Caught exception: '. $e->getMessage() ."\n";
        //     }

        $data = array(
            'nik' => $nik,
            'jenis' => 'izin',
            'tgl_mulai' => $tgl_mulai,
            'tgl_berakhir' => $tgl_berakhir,
            'total_hari' => $woweekends-$holiday,
          // 'total_hari' => $total_hari, 
           'keterangan' => $keterangan,
            'atasan' => $atasan,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($tgl_mulai) && !empty($tgl_berakhir) && !empty($keterangan)) {
            if ($inter->days <= 2){
           $sendgrid->send($email);
            $this->m_Data->insert_data($data, 'cuti');
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
                        <h4>Berhasil Submit Izin Silahkan Tunggu Persetujuan</h4>
                    </div>');
            redirect('employee/izin');
            }else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger">
                            <h4>Pengajuan Izin Maksimal 2 Hari Setelah Pengajuan</h4>
                        </div>');
                redirect('employee/izin');
            }

        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-danger">
                        <h4>Semua Nilai Harus Diisi</h4>
                    </div>');
            redirect('employee/izin');
        }

	}
    
    function testing(){

        $holidays=$this->db->query("select tanggal from holiday")->result_array();
        print_r ($holidays);
 
    }


    function week(){
        $date1='2019-12-28';
        $date2='2020-01-04';
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
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
            if ($date1 < $tgl AND $date2 > $tgl){
                $holiday=$holiday+1;
            }else{
                $holiday=$holiday+0;
            }
        }
       echo "holiday ".$holiday."<br>";
       echo "weekend ".$woweekends."<br>";
        echo $woweekends-$holiday." days without weekend <br>";
        

        
}


function email(){
    $email = new \SendGrid\Mail\Mail(); 
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("ridwan@bareksa.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid(('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'));
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
