<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
// End load library phpspreadsheet
class Holiday extends CI_Controller {
	

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

    function index(){
        $data['holiday']=$this->m_Data->tampil_data('holiday')->result();
        $this->load->view('ps/v_holiday',$data);
    }

    function do_insert(){

            $tanggal=$this->input->post('tanggal');
            $data = array( 
                  'tanggal'  =>  $tanggal,
            );

			if (!empty($tanggal)){				
                $this->m_Data->insert_data($data,'holiday');
                $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Submit Tanggal Liburan</h4>
                    </div>');  
		redirect('ps/holiday');
			}else {
				$this->session->set_flashdata('msg', 
                    '<div class="alert alert-danger">
                        <h4>Gagal Submit Tanggal Liburan</h4>
                    </div>');  
		redirect('ps/holiday');
			}
        }

        function delete($id){
            $where=array(
                'id_holiday' => $id,
            );
           $this->m_Data->delete_data($where,'holiday');
           $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil</h4>
                    </div>');  
		redirect('ps/holiday');
        }
    
}