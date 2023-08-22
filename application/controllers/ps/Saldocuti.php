<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

// End load library phpspreadsheet
class Saldocuti extends CI_Controller
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

    public function edit($id)
    {
        $data['saldo'] = $this->db->query("select * from saldo_cuti where nik='$id'")->result();
        $this->load->view('ps/v_saldo_cuti_edit', $data);
    }

    public function update_saldo()
    {
        $nik = $this->input->post('nik');
        $sisa = $this->input->post('sisa');

        $data = array(
            'nik' => $nik,
            'saldo_cuti' => $sisa,
        );
        $where = array(
            'nik' => $nik,
        );
        $this->m_Data->update_data($where, $data, 'saldo_cuti');

        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">
<h4>Berhasil Update</h4>
</div>');
        redirect('ps/karyawan/');

    }

}
