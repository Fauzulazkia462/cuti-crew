<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// End load library phpspreadsheet
class Approval extends CI_Controller
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

    public function delete($id)
    {
        $where = array(
            'id_cuti' => $id,
        );
        $this->m_Data->delete_data($where, 'cuti');
        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">
							<h4>Berhasil Delete</h4>
						</div>');
        redirect('ps/home/approval');


    }
}
