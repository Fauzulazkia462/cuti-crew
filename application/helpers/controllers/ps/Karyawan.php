<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// End load library phpspreadsheet
class Karyawan extends CI_Controller
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
        if ($level != 'ps') {
            redirect('welcome');
        } else {
            $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

    }

    public function index()
    {
        //    $this->load->database();
        // $jumlah_data = $this->m_Data->jumlah_data();
        // $config['full_tag_open'] = '<ul class="pagination pagination-sm m-t-xs m-b-xs">';
        // $config['full_tag_close'] = '</ul>';

        // $config['first_link'] = 'First';
        // $config['first_tag_open'] = '<li>';
        // $config['first_tag_close'] = '</li>';

        // $config['last_link'] = 'Last';
        // $config['last_tag_open'] = '<li>';
        // $config['last_tag_close'] = '</li>';

        // $config['next_link'] = ' <i class="glyphicon glyphicon-menu-right"></i> ';
        // $config['next_tag_open'] = '<li>';
        // $config['next_tag_close'] = '</li>';

        // $config['prev_link'] = ' <i class="glyphicon glyphicon-menu-left"></i> ';
        // $config['prev_tag_open'] = '<li>';
        // $config['prev_tag_close'] = '</li>';

        // $config['cur_tag_open'] = '<li class="active"><a href="#">';
        // $config['cur_tag_close'] = '</a></li>';

        // $config['num_tag_open'] = '<li>';
        // $config['num_tag_close'] = '</li>';

        // $this->load->library('pagination');
        // $config['base_url'] = base_url() . 'ps/karyawan/index/';
        // $config['total_rows'] = $jumlah_data;
        // $config['per_page'] = 10;
        // $from = $this->uri->segment(4);
        // $this->pagination->initialize($config);
        //$data['karyawan'] = $this->m_Data->data($config['per_page'], $from);
       // $data['karyawan']=$this->m_Data->tampil_data('karyawan')->result();
       $data['karyawan']=$this->db->query("select *, saldo_cuti.saldo_cuti as sisa_cuti from karyawan join saldo_cuti on karyawan.nik=saldo_cuti.nik")->result(); 
       $this->load->view('ps/v_karyawan', $data);
    }

    public function do_insert()
    {
        //Perhitungan Masa Kerja dan Pengambilan Saldo Cuti Tahun Sebelumnya
        $yearnow = date('Y');
        $tahun = $this->input->post('tahun');
        $join = $this->db->query("select karyawan.nik,karyawan.tgl_masuk_kerja,saldo_cuti.saldo_cuti,saldo_cuti.tahun,saldo_cuti.saldo_minus from karyawan join saldo_cuti on karyawan.nik=saldo_cuti.nik where saldo_cuti.tahun='$yearnow'")->result();
        foreach ($join as $row) {
            $masukkerja = $row->tgl_masuk_kerja;
            $saldo_cuti = 12 - $row->saldo_minus;
            $start_date = new DateTime($masukkerja);
            $end_date = new DateTime(date("Y-m-d"));
            $interval = $start_date->diff($end_date);
            if ($interval->days > 365) {
                $data = array(
                    array(
                        'nik' => $row->nik,
                        'saldo_cuti' => $saldo_cuti,
                        'cuti_terpakai' => 0,
                        'saldo_minus' => 0,
                        'tahun' => $tahun,
                    ),
                );
                $this->db->insert_batch('saldo_cuti', $data);
            } else {
                $data = array(
                    array(
                        'nik' => $row->nik,
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

    public function cuti_bersama()
    {
        $date = date('Y');
        $data['cuti_bersama'] = $this->db->query("select * from cuti_bersama where year(tanggal)='$date'")->result();
        $this->load->view('ps/v_cuti_bersama', $data);
    }

    public function import_excel()
    {
        $inputFileName = "./excel/data_karyawan.xls";

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
            $tgl_masuk_kerja = $schdeules[$i]['1'];
            $nama_karyawan = $schdeules[$i]['2'];
            $divisi = $schdeules[$i]['3'];
            $departement = $schdeules[$i]['4'];
            $atasan = $schdeules[$i]['5'];
            $email = $schdeules[$i]['6'];
            $gradetitle = $schdeules[$i]['7'];
            $grade = $schdeules[$i]['8'];
            $level = $schdeules[$i]['9'];
            $lokasi_kerja = $schdeules[$i]['10'];
            $tgl_lahir = $schdeules[$i]['11'];
            $no_ktp = $schdeules[$i]['12'];
            $sex = $schdeules[$i]['13'];
            //    echo $nik."<br>";
            $data = array(
                'nik' => $nik,
                'tgl_masuk_kerja' => $tgl_masuk_kerja,
                'nama_karyawan' => $nama_karyawan,
                'divisi' => $divisi,
                'departement' => $departement,
                'atasan' => $atasan,
                'email' => $email,
                'grade_title' => $gradetitle,
                'grade' => $grade,
                'level' => $level,
                'lokasi_kerja' => $lokasi_kerja,
                'tgl_lahir' => $tgl_lahir,
                'no_ktp' => $no_ktp,
                'sex' => $sex,

            );
            $this->m_Data->insert_data($data, 'karyawan');

        }
        //    echo count($schdeules);
    }

    public function buat_user()
    {
        $inputFileName = "./excel/data_karyawan.xls";

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
            $tgl_masuk_kerja = $schdeules[$i]['10'];
            $result = preg_replace("/[^0-9]/", "", $tgl_masuk_kerja);
            $password = date('dmY', strtotime($result));

            $data = array(
                'nik' => $nik,
                'password' => md5($password),
                'level' => 'employee',
            );

            $this->m_Data->insert_data($data, 'users');

            //    return $this->m_Data->insert_data($data,'karyawan');
        }

        //$string='1998-01-02';
        //$result = preg_replace("/[^0-9]/", "", $string);
        //echo $result;
        //echo "<br>";
        //echo date('dmY', strtotime('1994-02-15'));

    }

    public function buat_saldocuti()
    {
        $inputFileName = "./excel/data_karyawan.xls";
        $date = date('Y');

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

            $data = array(
                'nik' => $nik,
                'saldo_cuti' => 0,
                'cuti_terpakai' => 0,
                'saldo_minus' => 0,
                'tahun' => $date,
            );

            $this->m_Data->insert_data($data, 'saldo_cuti');

            //    return $this->m_Data->insert_data($data,'karyawan');
        }
    }
    public function upload_excel()
    {
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xls';
        $config['file_name'] = 'data_karyawan.xls';
        $config['overwrite'] = true;
        //$this->upload->overwrite = true;
        //    $config['max_size']             = 100;
        //    $config['max_width']            = 1024;
        //    $config['max_height']           = 768;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
							<h4>Gagal Upload, Pastikan Format Data Adalah .xlsx</h4>
						</div>');
            redirect('ps/karyawan');
        } else {
            //$data = array('upload_data' => $this->upload->data());
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
               $this->import_excel();
               $this->buat_user();
               $this->buat_saldocuti();
            redirect('ps/karyawan');
            //$this->load->view('ps/v_karyawan', $data);
        }
    }

    public function do_insert_cuti_bersama()
    {
        $tanggal = $this->input->post('tanggal');
        $tanggal1 = date('Y-m-d', strtotime($tanggal));

        $data_cuti = array(
            'tanggal' => $tanggal1,
        );

        if (!empty($tanggal)) {
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
                if ($date == date('Y', strtotime($tanggal1))) {
                    $this->m_Data->update_data($where, $data, 'saldo_cuti');
                }
            }
       }

            // foreach ($db as $db) {
            //     $where = array(
            //         'nik' => $db->nik,

            //     );
            //     if ($db->saldo_cuti==0){
            //         $data = array(
            //             'saldo_cuti' => $db->saldo_cuti,
            //         );
            //     }else{
            //     $data = array(
            //         'saldo_cuti' => $db->saldo_cuti - 1,
            //     );
            // }
            //     if ($date == date('Y', strtotime($tanggal1))) {
            //         $this->m_Data->update_data($where, $data, 'saldo_cuti');
            //     }
            // }

            $this->m_Data->insert_data($data_cuti, 'cuti_bersama');
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');
            redirect('ps/karyawan/cuti_bersama');
        }
        // if (!empty($jumlah)){
        // $this->m_Data->insert_data($data,'cuti_bersama');
        // //Update saldo cuti
        // $date=date('Y');
        // $db=$this->db->query("select * from saldo_cuti where tahun='$date'")->result();
        // foreach ($db as $db){
        //     $where=array(
        //         'nik' => $db->nik,

        //     );
        //     $data=array(
        //         'saldo_cuti' => $db->saldo_cuti-$jumlah,
        //     );
        //     $this->m_Data->update_data($where,$data,'saldo_cuti');
        // }

        // $this->session->set_flashdata('msg',
        // '<div class="alert alert-success">
        //     <h4>Berhasil</h4>
        // </div>');
        // redirect ('ps/karyawan/cuti_bersama');
        // }else{
        //     $this->session->set_flashdata('msg',
        // '<div class="alert alert-warning">
        //     <h4>Isi Jumlah Data</h4>
        // </div>');
        // redirect ('ps/karyawan/cuti_bersama');
        // }

    }

    public function edit_cuti_bersama($id_cuti_bersama)
    {

        $where = array(
            'id_cuti_bersama' => $id_cuti_bersama,
        );
        $data['cuti_bersama'] = $this->m_Data->edit_data($where, 'cuti_bersama')->result();
        $this->load->view('ps/v_edit_cuti_bersama', $data);
    }

    public function update_cuti_bersama()
    {
        $jumlah = $this->input->post('jumlah');
        $where_cuti_bersama = array(
            'id_cuti_bersama' => $this->input->post('id_cuti_bersama'),
        );
        $data_cuti_bersama = array(
            'jumlah' => $jumlah,
        );

        //Update Saldo Cuti
        $date = date('Y');
        $jumlahcuti = $this->db->query("select * from cuti_bersama where tahun='$date'")->result();
        foreach ($jumlahcuti as $row) {
            $jumlahcuti = $row->jumlah;
        }
        $db = $this->db->query("select * from saldo_cuti where tahun='$date'")->result();
        foreach ($db as $db) {
            $where = array(
                'nik' => $db->nik,

            );
            $data = array(
                'saldo_cuti' => $db->saldo_cuti + ($jumlahcuti - $jumlah),
            );

            $this->m_Data->update_data($where, $data, 'saldo_cuti');
        }
        $this->m_Data->update_data($where_cuti_bersama, $data_cuti_bersama, 'cuti_bersama');
        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');
        redirect('ps/karyawan/cuti_bersama');
    }

    public function kurang_jatah_cuti()
    {
        $nik = $this->input->post('nik');
        $jumlah = $this->input->post('jumlah');

        //$ceknik=$this->m_Data->tampil_data('karyawan');
        //Cek Saldo Cuti
        $tahun = date('Y');
        $ceksaldo = $this->db->query("select * from saldo_cuti where nik='$nik' and tahun='$tahun'")->result();
        foreach ($ceksaldo as $row) {
            $saldo_cuti = $row->saldo_cuti;
        }
        //Cek Nik
        $ceknik = $this->db->query("select * from karyawan where nik='$nik'");
        $ceknik = $ceknik->num_rows();
        if ($ceknik < 1) {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-danger">
			<h4>NIK Tidak Ditemukan</h4>
		</div>');
            redirect('ps/karyawan/cuti_bersama');
        } else {
            $where = array(
                'nik' => $nik,
            );
            $data = array(
                'saldo_cuti' => $saldo_cuti + $jumlah,
            );
            $this->m_Data->update_data($where, $data, 'saldo_cuti');
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
			<h4>Berhasil</h4>
		</div>');
            redirect('ps/karyawan/cuti_bersama');
        }
    }

    public function details($id_karyawan)
    {
        $data['karyawan'] = $this->db->query("select * from karyawan where nik='$id_karyawan'")->result();
        $this->load->view('ps/v_karyawan_details', $data);
    }

    public function pengajuan()
    {
        $data['atasan'] = $this->db->query("select nama_supervisor as atasan from supervisor order by nama_supervisor ASC")->result();
        $this->load->view('ps/v_pengajuan_cuti', $data);
    }
    public function pengajuan_cuti_do_insert()
    {
        //Input Form
        $atasan = $this->input->post('atasan');
        $nik = $this->input->post('nik');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_berakhir = $this->input->post('tgl_berakhir');
        $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));
        $tgl_berakhir = date('Y-m-d', strtotime($tgl_berakhir));
        //    $tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
        //    $tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
        $total_hari = $this->input->post('total_hari');
        $keterangan = $this->input->post('keterangan');

        //Hitung Hari Otomatis
        $datetime1 = new DateTime($tgl_mulai);
        $datetime2 = new DateTime($tgl_berakhir);
        $interval = $datetime1->diff($datetime2);
        $woweekends = 1;
        for ($i = 1; $i <= $interval->d; $i++) {
            $datetime1->modify('+1 day');
            $weekday = $datetime1->format('w');

            if ($weekday !== "0" && $weekday !== "7") { // 0 for Sunday and 6 for Saturday
                $woweekends++;
            }

        }
        $holiday = 0;
        $now = date('Y');
        $before = $now - 1;
        $holidays = $this->db->query("select tanggal from holiday where year(tanggal)='$now' or year(tanggal)='$before'")->result();
        foreach ($holidays as $row) {
            $tgl = $row->tanggal;
            if ($tgl_mulai < $tgl and $tgl_berakhir > $tgl) {
                $holiday = $holiday + 1;
            } else {
                $holiday = $holiday + 0;
            }
        }

        //Data Input Cuti Ke DB
        $data = array(
            'nik' => $nik,
            'jenis' => 'cuti',
            'tgl_mulai' => $tgl_mulai,
            'tgl_berakhir' => $tgl_berakhir,
            'total_hari' => $woweekends - $holiday,
            'keterangan' => $keterangan,
            'atasan' => $atasan,
            'created_at' => date('Y-m-d H:i:s'),
        );

        //CEKNIK
        $n = $this->db->query("select * from karyawan where nik='$nik'");
        $row_n = $n->num_rows();

        $nama = $this->db->query("select * from karyawan where nik='$nik'")->result();
        foreach ($nama as $row) {
            $nama = $row->nama_karyawan;
        }
        $mail = $this->db->query("select * from supervisor where nama_supervisor='$atasan'")->result();
        foreach ($mail as $row) {
            $mail = $row->email;

            $nama_supervisor = $row->nama_supervisor;
        }
        $jumlahhari = (string) $woweekends - $holiday;
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("admin@niramasutama.com", "PT Niramas Utama");
        $email->setSubject("Pengajuan Izin - NIK " . $nik);
        $email->addTo((string) $mail, $nama_supervisor);
        // $email->addContent("text/plain", "Pengajuan Izin");
        $email->addContent(
            "text/html", "Berdasarkan Data Dibawah Ini<br>NIK : " . $nik . "<br>Nama : " . $nama . "<br>Jenis Pengajuan : Cuti<br>Tanggal Mulai : " . $tgl_mulai . "<br>Tanggal berahir : " . $tgl_berakhir . "<br>Jumlah Hari : " . $jumlahhari . "<br>Keterangan : " . $keterangan . "<br>Silahkan Login <a href='https://cuticrew.niramasutama.com/'>Disini </a> Untuk Approval"
        );
        //
        $sendgrid = new \SendGrid('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');

        if (!empty($nik) && !empty($tgl_mulai) && !empty($tgl_berakhir) && !empty($keterangan)) {
            if ($row_n < 1) {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger">
                        <h4>Nik tidak terdaftar</h4>
                    </div>');
                redirect('ps/karyawan/pengajuan');
            } else {
                $sendgrid->send($email);
                $this->m_Data->insert_data($data, 'cuti');
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Cuti</h4>
                    </div>');
                redirect('ps/karyawan/pengajuan');
            }
        } else {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-danger">
					<h4>Semua Nilai Harus Diisi</h4>
				</div>');
            redirect('ps/karyawan/pengajuan');
        }
    }

    public function delete($id_karyawan)
    {
        $where = array(
            'nik' => $id_karyawan,
        );
        $this->m_Data->delete_data($where, 'recycle_karyawan');
        $this->m_Data->delete_data($where, 'recycle_saldo_cuti');
        $this->m_Data->delete_data($where, 'recycle_cuti');
        $this->session->set_flashdata('msg',
            '<div class="alert alert-danger">
						  <h4>Delete Data Berhasil</h4>
					  </div>');
        redirect('ps/karyawan/restore');

    }

    public function alfa()
    {
        $jumlah_data = $this->db->query("select * from saldo_cuti")->num_rows();
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-t-xs m-b-xs">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = ' <i class="glyphicon glyphicon-menu-right"></i> ';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = ' <i class="glyphicon glyphicon-menu-left"></i> ';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'ps/karyawan/alfa/index/';
        $config['total_rows'] = $jumlah_data;
        $config['per_page'] = 10;
        $page = $config['per_page'];
        $from = $this->uri->segment(5);
        $this->pagination->initialize($config);
        if ($from == '') {
            $data['saldo_cuti'] = $this->db->query("select *, karyawan.nama_karyawan from saldo_cuti join karyawan on saldo_cuti.nik=karyawan.nik limit $page")->result();
        } else {
            $data['saldo_cuti'] = $this->db->query("select *, karyawan.nama_karyawan from saldo_cuti join karyawan on saldo_cuti.nik=karyawan.nik limit $from, $page")->result();
        }
        //    $data['saldo_cuti']=$this->db->query("select *, karyawan.nama_karyawan from saldo_cuti join karyawan on saldo_cuti.nik=karyawan.nik")->result();
        $this->load->view('ps/v_karyawan_alfa', $data);
    }

    public function import_excel_karyawan_alfa()
    {
        $inputFileName = "./excel/data_karyawan_alfa.xlsx";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        $nik = $this->db->query("select * from saldo_cuti")->result();
        foreach ($nik as $row) {
            $saldo_cuti = $row->saldo_cuti;
            $n = $row->nik;
            for ($i = 1; $i < count($schdeules); $i++) {
                $nik = $schdeules[$i]['0'];
                $jumlah_alfa = $schdeules[$i]['1'];

                $where = array(
                    'nik' => $nik,
                );

                if ($n == $nik) {
                    $data = array(
                        //'nik'=>$nik,
                        'saldo_cuti' => $saldo_cuti - $jumlah_alfa,
                    );
                }
                $this->m_Data->update_data($where, $data, 'saldo_cuti');
            }
        }
    }

    public function upload_karyawan_alfa()
    {
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xlsx';
        $config['file_name'] = 'data_karyawan_alfa.xlsx';
        $config['overwrite'] = true;
        //$this->upload->overwrite = true;
        //    $config['max_size']             = 100;
        //    $config['max_width']            = 1024;
        //    $config['max_height']           = 768;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('berkas')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
							<h4>Gagal Upload, Pastikan Format Data Adalah .xlsx</h4>
						</div>');
            redirect('ps/karyawan/alfa');
        } else {
            //$data = array('upload_data' => $this->upload->data());
            $this->session->set_flashdata('msg',
                '<div class="alert alert-success">
							<h4>Berhasil Upload Data</h4>
						</div>');
            $this->import_excel_karyawan_alfa();
            //$this->buat_user();
            redirect('ps/karyawan/alfa');
            //$this->load->view('ps/v_karyawan', $data);
        }
    }

    public function delete_cuti_bersama($id)
    {

        $date = date('Y');
        $db = $this->db->query("select * from saldo_cuti where tahun='$date'")->result();
        foreach ($db as $db) {
            $where = array(
                'nik' => $db->nik,

            );
            $data = array(
                'saldo_cuti' => $db->saldo_cuti + 1,
            );

            $this->m_Data->update_data($where, $data, 'saldo_cuti');

        }

        $where = array(
            'id_cuti_bersama' => $id,
        );
        $this->m_Data->delete_data($where, 'cuti_bersama');
        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">
							<h4>Berhasil Delete</h4>
						</div>');
        redirect('ps/karyawan/cuti_bersama');

    }

    public function delete_karyawan()
    {

        $data['check'] = $this->input->post('check');
        $this->load->view('ps/v_confirm_delete_karyawan',$data);

//         if (!empty($check)) {
//             foreach ($check as $value) {
               
//                 $cek = $this->db->query("delete from saldo_cuti where nik='$value'");
             
//                 if ($cek) {
//                     $this->db->query("delete from karyawan where nik='$value'");    
//                     $this->db->query("delete from users where nik='$value'");
//                 } else {
//                     redirect('ps/karyawan/');
//                 }

//             }

//             $this->session->set_flashdata('msg',
//             '<div class="alert alert-success">
//     <h4>Berhasil Delete</h4>
// </div>');
//         redirect('ps/karyawan/');
//         }else{
//             $this->session->set_flashdata('msg',
//             '<div class="alert alert-warning">
//     <h4>Silahkan Pilih Data Yang Mau Di Delete</h4>
// </div>');
//             redirect('ps/karyawan/');
//         }
    }

    function delete_confirm(){
        $check=$this->input->post('check');
        if (!empty($check)) {
                        foreach ($check as $value) {
                           

                                //recycle
                                $select_saldo=$this->db->query("select * from saldo_cuti where nik='$value'")->result();
                                foreach ($select_saldo as $row){
                                    $data_saldo=array(
                                        'id_saldo_cuti'=>$row->id_saldo_cuti,
                                        'nik'=>$row->nik,
                                        'saldo_cuti'=>$row->saldo_cuti,
                                        'cuti_terpakai'=>$row->cuti_terpakai,
                                        'saldo_minus'=>$row->saldo_minus,
                                        'tahun'=>$row->saldo_minus,
                                    );
                                
                               $this->m_Data->insert_data($data_saldo,'recycle_saldo_cuti');
                            }
                                $select=$this->db->query("select * from karyawan where nik='$value'")->result();
                    
                               
                                foreach ($select as $row){
                                    $nik=$row->nik;
                                    $tgl_masuk_kerja=$row->tgl_masuk_kerja;
                                    $nama_karyawan=$row->nama_karyawan;
                                    $divisi=$row->divisi;
                                    $departement=$row->departement;
                                    $atasan=$row->atasan;
                                    $email=$row->email;
                                    $grade_title=$row->grade_title;
                                    $grade=$row->grade;
                                    $level=$row->level;
                                    $lokasi_kerja=$row->lokasi_kerja;
                                    $tgl_lahir=$row->tgl_lahir;
                                    $no_ktp=$row->no_ktp;
                                    $sex=$row->sex;
                                }
                                $data_karyawan=array(
                                    'nik' => $nik,
                                    'tgl_masuk_kerja' => $tgl_masuk_kerja,
                                    'nama_karyawan' => $nama_karyawan,
                                    'divisi' => $divisi,
                                    'departement' => $departement,
                                    'atasan' => $atasan,
                                    'email' => $email,
                                    'grade_title' => $grade_title,
                                    'grade' => $grade,
                                    'level' => $level,
                                    'lokasi_kerja' => $lokasi_kerja,
                                    'tgl_lahir' => $tgl_lahir,
                                    'no_ktp' => $no_ktp,
                                    'sex' => $sex,
                    
                                );
                               $this->m_Data->insert_data($data_karyawan,'recycle_karyawan');
                                $select_cuti=$this->db->query("select * from cuti where nik='$value'")->result();
                                foreach ($select_cuti as $row) {
                                    $data_cuti=array(
                                        'id_cuti'=>$row->id_cuti,
                                        'nik'=>$row->nik,
                                        'jenis'=>$row->jenis,
                                        'tgl_mulai'=>$row->tgl_mulai,
                                        'tgl_berakhir'=>$row->tgl_berakhir,
                                        'total_hari'=>$row->total_hari,
                                        'keterangan'=>$row->keterangan,
                                        'atasan'=>$row->atasan,
                                        'approval'=>$row->approval,
                                        'cuti_khusus'=>$row->cuti_khusus,
                                        'created_at'=> $row->created_at,
                                    );

                                $this->m_Data->insert_data($data_cuti,'recycle_cuti');
                                }

                           //Delete

                        //    if($backup_saldo && $backup_karyawan && $backup_cuti){
                            $this->db->query("delete from saldo_cuti where nik='$value'");
                                $this->db->query("delete from karyawan where nik='$value'");  
                                //This is a bug really but I lazy to edit it   
                            //    $this->db->query("delete from users where nik='$value'");
                                $this->db->query("delete from cuti where nik='$value'");
                               
                        //    }    
                            
                        }
            
                        $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                <h4>Berhasil Delete</h4>
            </div>');
                    redirect('ps/karyawan/');
                    }else{
                        $this->session->set_flashdata('msg',
                        '<div class="alert alert-warning">
                <h4>Silahkan Pilih Data Yang Mau Di Delete</h4>
            </div>');
                        redirect('ps/karyawan/');
                    }
    }

    function restore(){
        $data['karyawan']=$this->m_Data->tampil_data('recycle_karyawan')->result();
        $this->load->view('ps/v_karyawan_restore',$data);
    }
    public function edit($id_karyawan)
    {
        $data['karyawan'] = $this->db->query("select * from karyawan where nik='$id_karyawan'")->result();
        $this->load->view('ps/v_karyawan_edit', $data);
    }

    function update_karyawan(){
        $nik=$this->input->post('nik');
        $nama=$this->input->post('nama');
        $tgl_masuk_kerja=$this->input->post('tgl_masuk_kerja');
        $departement=$this->input->post('departement');
        $divisi=$this->input->post('divisi');
        $atasan=$this->input->post('atasan');
        $grade_title=$this->input->post('grade_title');
        $grade=$this->input->post('grade');
        $level=$this->input->post('level');
        $lokasi_kerja=$this->input->post('lokasi_kerja');
        $tgl_lahir=$this->input->post('tgl_lahir');
        $no_ktp=$this->input->post('no_ktp');
        $jenis_kelamin=$this->input->post('jenis_kelamin');

        $data = array(
            'nik' => $nik,
            'tgl_masuk_kerja' => $tgl_masuk_kerja,
            'nama_karyawan' => $nama,
            'divisi' => $divisi,
            'departement' => $departement,
            'atasan' => $atasan,
            'email' => $email,
            'grade_title' => $grade_title,
            'grade' => $grade,
            'level' => $level,
            'lokasi_kerja' => $lokasi_kerja,
            'tgl_lahir' => $tgl_lahir,
            'no_ktp' => $no_ktp,
            'sex' => $jenis_kelamin,
        );
        $where=array(
            'nik' => $nik,
        );
        $this->m_Data->update_data($where,$data,'karyawan');

        $this->session->set_flashdata('msg',
        '<div class="alert alert-success">
<h4>Berhasil Update</h4>
</div>');
    redirect('ps/karyawan/');

    }

    function restore_karyawan(){
        $check=$this->input->post('check');
        if (!empty($check)) {
                        foreach ($check as $value) {
                         
                                //recycle
                                $select=$this->db->query("select * from recycle_karyawan where nik='$value'")->result();
                    
                               
                                foreach ($select as $row){
                                    $nik=$row->nik;
                                    $tgl_masuk_kerja=$row->tgl_masuk_kerja;
                                    $nama_karyawan=$row->nama_karyawan;
                                    $divisi=$row->divisi;
                                    $departement=$row->departement;
                                    $atasan=$row->atasan;
                                    $email=$row->email;
                                    $grade_title=$row->grade_title;
                                    $grade=$row->grade;
                                    $level=$row->level;
                                    $lokasi_kerja=$row->lokasi_kerja;
                                    $tgl_lahir=$row->tgl_lahir;
                                    $no_ktp=$row->no_ktp;
                                    $sex=$row->sex;
                                }
                                $data_karyawan=array(
                                    'nik' => $nik,
                                    'tgl_masuk_kerja' => $tgl_masuk_kerja,
                                    'nama_karyawan' => $nama_karyawan,
                                    'divisi' => $divisi,
                                    'departement' => $departement,
                                    'atasan' => $atasan,
                                    'email' => $email,
                                    'grade_title' => $grade_title,
                                    'grade' => $grade,
                                    'level' => $level,
                                    'lokasi_kerja' => $lokasi_kerja,
                                    'tgl_lahir' => $tgl_lahir,
                                    'no_ktp' => $no_ktp,
                                    'sex' => $sex,
                    
                                );
                                $this->m_Data->insert_data($data_karyawan,'karyawan');

                                $select_saldo=$this->db->query("select * from recycle_saldo_cuti where nik='$value'")->result();
                                foreach ($select_saldo as $row){
                                    $data_saldo=array(
                                        'id_saldo_cuti'=>$row->id_saldo_cuti,
                                        'nik'=>$row->nik,
                                        'saldo_cuti'=>$row->saldo_cuti,
                                        'cuti_terpakai'=>$row->cuti_terpakai,
                                        'saldo_minus'=>$row->saldo_minus,
                                        'tahun'=>$row->saldo_minus,
                                    );
                                
                                $this->m_Data->insert_data($data_saldo,'saldo_cuti');
                            }
                                $select_cuti=$this->db->query("select * from recycle_cuti where nik='$value'")->result();
                                foreach ($select_cuti as $row) {
                                    $data_cuti=array(
                                        'id_cuti'=>$row->id_cuti,
                                        'nik'=>$row->nik,
                                        'jenis'=>$row->jenis,
                                        'tgl_mulai'=>$row->tgl_mulai,
                                        'tgl_berakhir'=>$row->tgl_berakhir,
                                        'total_hari'=>$row->total_hari,
                                        'keterangan'=>$row->keterangan,
                                        'atasan'=>$row->atasan,
                                        'approval'=>$row->approval,
                                        'cuti_khusus'=>$row->cuti_khusus,
                                        'created_at'=> $row->created_at,
                                    );

                                    $this->m_Data->insert_data($data_cuti,'cuti');
                                }

                          //Delete Sampah
                              
                             
                         
                            
                                $this->db->query("delete from recycle_saldo_cuti where nik='$value'");
                                   $this->db->query("delete from recycle_karyawan where nik='$value'");  
                                   //This is a bug really, but I too Lazy to edit,  
                                //   $this->db->query("delete from users where nik='$value'");
                                   $this->db->query("delete from recycle_cuti where nik='$value'");
                                   
                        
                    }
            
                        $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                <h4>Berhasil Restore</h4>
            </div>');
                    redirect('ps/karyawan/restore/');
                    }else{
                        $this->session->set_flashdata('msg',
                        '<div class="alert alert-warning">
                <h4>Silahkan Pilih Data Yang Mau Di Restore</h4>
            </div>');
                        redirect('ps/karyawan/restore/');
                    }
    }
}
