<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
class Cuti_khusus extends CI_Controller
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
        $nik = $this->session->nama;
        $data['karyawan'] = $this->db->query("select * from karyawan where nik='$nik'")->result();
        foreach ($data['karyawan'] as $row){
            $divisi=$row->divisi;
        }
        $data['saldo_cuti'] = $this->db->query("select * from saldo_cuti where nik='$nik'")->result();
        if ($divisi=="Product Development"){
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where nama_supervisor='Christ Wina Dyah Ayu Maharani'")->result();
		}else{
			$data['atasan']=$this->db->query("select nama_supervisor as atasan from supervisor where divisi='$divisi' order by nama_supervisor ASC")->result();
        }
            $this->load->view('employee/v_cuti_khusus', $data);
    }

    public function do_insert()
    {
        //Value Total Hari Cuti Khusus
           $kategori=$this->input->post('kategori');
        if ($kategori=='Menikah'){
          $value=3;
        }elseif ($kategori=='Baptis Anak' || $kategori == 'Suami/Istri Meninggal Dunia' || $kategori=='Anak/menantu meninggal dunia'){
         $value=2;
        }elseif ($kategori=='Khitan Anak'){
         $value=2;
        }elseif ($kategori=='Orang Tua Meninggal'){
         $value=2;
        }elseif ($kategori=='Keluarga 1 Rumah Meninggal'){
         $value=1;
        }elseif ($kategori=='Menikahkan Anak'){
            $value=2;
        }elseif ($kategori=='Istri Melahirkan'){
            $value=2;
        }else{
            $value=90;
        }

        //Perhitungan Masa Kerja
        $session = $this->session->nama;
        // $karyawan = $this->db->query("select * from karyawan where nik='$session'")->result();
        // foreach ($karyawan as $row) {
        //     $masukkerja = $row->tgl_masuk_kerja;
        //     $atasan = $row->atasan;
        // }

        //Input Form
        $nik = $session;
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_berakhir = $this->input->post('tgl_berakhir');
        $tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
        $tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
        //  $tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
        //$tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
        $keterangan = $this->input->post('keterangan');
        $total_hari = $this->input->post('total_hari');
        $kategori = $this->input->post('kategori');
        $atasan=$this->input->post('atasan');

        if ($atasan=='NULL'){
            $this->session->set_flashdata('msg',
            '<div class="alert alert-danger">
                    <h4>Silahkan Isi Kolom Atasan</h4>
                </div>');
        redirect('employee/izin');
        }

        //Data Input Cuti Ke DB
        $data = array(
            'nik' => $nik,
            'jenis' => 'cuti khusus',
            'tgl_mulai' => $tgl_mulai,
            'tgl_berakhir' => $tgl_berakhir,
            'total_hari' => $value,
            'keterangan' => $keterangan,
            'atasan' => $atasan,
            'cuti_khusus' => $kategori,
            'created_at' => date('Y-m-d H:i:s'),
        );

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
          $value=$woweekends-$holiday;

        //Initial Send Email
        $nama = $this->db->query("select * from karyawan where nik='$nik'")->result();
        foreach ($nama as $row) {
            $nama = $row->nama_karyawan;
        }
        $mail = $this->db->query("select * from supervisor where nama_supervisor='$atasan'")->result();
        foreach ($mail as $row) {
            $mail = $row->email;

            $nama_supervisor = $row->nama_supervisor;
        }
        // $jumlahhari=(string)$woweekends-$holiday;
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("admin@niramasutama.com", "PT Niramas Utama");
        $email->setSubject("Pengajuan Izin - NIK " . $nik);
        $email->addTo((string) $mail, $nama_supervisor);
        // $email->addContent("text/plain", "Pengajuan Izin");
        $email->addContent(
            "text/html", "Berdasarkan Data Dibawah Ini<br>NIK : " . $nik . "<br>Nama : " . $nama . "<br>Jenis Pengajuan : Cuti Khusus<br>Kategori : " . $kategori . "<br>Tanggal Mulai : " . $tgl_mulai . "<br>Tanggal berahir : " . $tgl_berakhir . "<br>Jumlah Hari : " . $value. "<br>Keterangan : " . $keterangan."<br>Silahkan Login <a href='https://cuticrew.niramasutama.com/'>Disini </a> Untuk Approval"
        );
        //
        // $sendgrid = new \SendGrid('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');
        //  $sendgrid = new \SendGrid('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');
        $sendgrid = new \SendGrid('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');
        if (!empty($tgl_mulai) && !empty($tgl_berakhir)) {
            if ($kategori == 'Melahirkan') {
                if ($value > 90) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                    <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                </div>');
                    redirect('employee/cuti_khusus');
                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                   
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');
                }
            } elseif ($kategori == 'Keguguran') {
                if ($value > 45) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }

            } elseif ($kategori == 'Menikah') {
                if ($value != 3) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            } elseif ($kategori == 'Baptis Anak' || $kategori='Menikahkan Anak' || $kategori=='Istri Melahirkan') {
                if ($value != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            } elseif ($kategori == 'Khitan Anak') {
                if ($value != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            } elseif ($kategori == 'Orang Tua Meninggal') {
                if ($value != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            } elseif ($kategori == 'Keluarga 1 Rumah Meninggal') {
                if ($value != 1) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $sendgrid->send($email);
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            } else {
                $sendgrid->send($email);
                $this->m_Data->insert_data($data, 'cuti');
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Cuti</h4>
                    </div>');
                redirect('employee/cuti_khusus');
            }
        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-danger">
                        <h4>Semua Nilai Harus Diisi</h4>
                    </div>');
            redirect('employee/cuti_khusus');
        }
    }
}
