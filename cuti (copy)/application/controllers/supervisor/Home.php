<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //Cek Session
        $nik=$this->session->nama;
        $cek_level=$this->db->query("select level from users where nik='$nik'")->result();
		foreach ($cek_level as $row){
			$level=$row->level;
		}
        if ($level!='supervisor'){
            redirect ('welcome');
        }else{
        $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
	
	}

    function index() {
        $this->load->view('supervisor/v_home');
    }

    function approval() {
        $session=$this->session->nama;
        $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan from cuti join karyawan on cuti.nik=karyawan.nik where cuti.atasan='$session'")->result();
        $this->load->view('supervisor/v_approval',$data);
    }

    function approved($id_cuti) {
        //Select ID CUTI
        $cuti=$this->db->query("select * from cuti where id_cuti='$id_cuti'")->result();
        foreach ($cuti as $row){
            $nik=$row->nik;
            $total_cuti=$row->total_hari;
            $jenis=$row->jenis;
        }

        //Taking Saldo Cuti
        $saldo_cuti=$this->db->query("select saldo_cuti from saldo_cuti where nik='$nik'")->result();
        foreach ($saldo_cuti as $row){
            $saldo=$row->saldo_cuti;
        }
        $saldo_cuti=$saldo-$total_cuti;
       // $data = array(
         //   'saldo_cuti' =>$saldo_cuti,
        //);
        $data_cuti = array(
            'approval' =>'Ya',
        );
        $where = array('id_cuti' => $id_cuti);
        $where_nik = array('nik' => $nik);

        //Update Sisa Cuti
        $cutiterpakai=$this->db->query("select cuti_terpakai from saldo_cuti where nik='$nik'")->result();
        foreach ($cutiterpakai as $row){
            $cutiterpakai=$row->cuti_terpakai;
        }
        $data_saldo_cuti=array(
            'saldo_cuti' => $saldo_cuti,
            'cuti_terpakai' => $cutiterpakai+$total_cuti,
        );

        $data_cuti_mines=array(
            'saldo_minus' => $total_cuti-$saldo,
            'saldo_cuti' => $saldo-$total_cuti,
            'cuti_terpakai' => $cutiterpakai+$total_cuti,
        );
        
        if ($jenis == 'cuti khusus'){
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
            redirect('supervisor/home/approval');
        }elseif ($jenis == 'cuti'){
            if ($saldo<=$total_cuti){
                $this->m_Data->update_data($where_nik,$data_cuti_mines,'saldo_cuti');
                $this->m_Data->update_data($where,$data_cuti,'cuti');
                $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
                redirect('supervisor/home/approval');
            }else{
            $this->m_Data->update_data($where_nik,$data_saldo_cuti,'saldo_cuti');
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
            redirect('supervisor/home/approval');
            }
        }
        elseif ($jenis == 'izin'){
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                  '<div class="alert alert-success">
                      <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                  </div>');  
            redirect('supervisor/home/approval');
        }
    }

    function declined($id_cuti) {
        //Select ID CUTI
        $cuti=$this->db->query("select * from cuti where id_cuti='$id_cuti'")->result();
        foreach ($cuti as $row){
           $nik=$row->nik;
        }
        $where = array('id_cuti' => $id_cuti);

       $data_cuti = array(
           'approval' =>'Tidak',
       );

        $this->m_Data->update_data($where,$data_cuti,'cuti');
        $this->session->set_flashdata('msg', 
        '<div class="alert alert-warning">
            <h4>Terima Kasih Telah Declined Cuti/Izin</h4>
        </div>');  
        redirect('supervisor/home/approval');
    }

    function delete($id_cuti){
        $where=array(
            'id_cuti' => $id_cuti,
        );
        $this->m_Data->delete_data($where,'cuti');
        $this->session->set_flashdata('msg', 
                  '<div class="alert alert-danger">
                      <h4>Delete Data Berhasil</h4>
                  </div>');  
        redirect('supervisor/home/approval');
    }

    function ganti_password(){
        $username=$this->session->nama;
        $newpassword=$this->input->post('change_password');
        $newpassword=md5($newpassword);

        $data = array(
            'password' =>$newpassword,
        );
    
        $where = array(
            'nik' => $username
        );
     
        $this->m_Data->update_data($where,$data,'users');
        $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Berhasil Ganti Password</h4>
                    </div>');  
        redirect('supervisor/home/profile');
    }

    function profile() {
        $this->load->view('supervisor/v_profile');
    }
}