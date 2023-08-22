<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        $data['saldo_cuti'] = $this->db->query("select * from saldo_cuti where nik='$nik'")->result();
        $data['atasan'] = $this->db->query("select distinct atasan from karyawan")->result();
        $this->load->view('employee/v_cuti_khusus', $data);
    }

    public function do_insert()
    {
        //Value Total Hari Cuti Khusus
        //   $kategori=$this->input->post('kategori');
        // if ($kategori=='Menikah'){
        //   $value=3;
        //}elseif ($kategori=='Baptis Anak'){
        //  $value=2;
        //}elseif ($kategori=='Khitan Anak'){
        //  $value=2;
        //}elseif ($kategori=='Orang Tua Meninggal'){
        //  $value=2;
        //}else{
        //  $value=1;
        // }

        //Perhitungan Masa Kerja
        $session = $this->session->nama;
        $karyawan = $this->db->query("select * from karyawan where nik='$session'")->result();
        foreach ($karyawan as $row) {
            $masukkerja = $row->tgl_masuk_kerja;
            $atasan = $row->atasan;
        }

        //Input Form
        $nik = $session;
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_berakhir = $this->input->post('tgl_berakhir');
      //  $tgl_mulai=date('Y-m-d', strtotime($tgl_mulai));
		//$tgl_berakhir=date('Y-m-d', strtotime($tgl_berakhir));
        $keterangan = $this->input->post('keterangan');
        $total_hari = $this->input->post('total_hari');
        $kategori = $this->input->post('kategori');

        //Data Input Cuti Ke DB
        $data = array(
            'nik' => $nik,
            'jenis' => 'cuti khusus',
            'tgl_mulai' => $tgl_mulai,
            'tgl_berakhir' => $tgl_berakhir,
            'total_hari' => $total_hari,
            'keterangan' => $keterangan,
            'atasan' => $atasan,
            'cuti_khusus' => $total_hari,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($tgl_mulai) && !empty($tgl_berakhir) && !empty($keterangan)) {
            if ($kategori == 'Melahirkan') {
                if ($total_hari > 90) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                    <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                </div>');
                    redirect('employee/cuti_khusus');
                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');
                }
            } elseif ($kategori == 'Keguguran') {
                if ($total_hari > 45) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            
            }  elseif ($kategori == 'Menikah') {
                if ($total_hari != 3) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Tidak Sesuai</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            }elseif ($kategori == 'Baptis Anak') {
                if ($total_hari != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            }elseif ($kategori == 'Khitan Anak') {
                if ($total_hari != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            }elseif ($kategori == 'Orang Tua Meninggal') {
                if ($total_hari != 2) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            }elseif ($kategori == 'Keluarga 1 Rumah Meninggal') {
                if ($total_hari != 1) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">
                            <h4>Total Hari Cuti Anda Melebihi Kuota</h4>
                        </div>');
                    redirect('employee/cuti_khusus');

                } else {
                    $this->m_Data->insert_data($data, 'cuti');
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">
                             <h4>Berhasil Submit Cuti</h4>
                         </div>');
                    redirect('employee/cuti_khusus');

                }
            }else {
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
