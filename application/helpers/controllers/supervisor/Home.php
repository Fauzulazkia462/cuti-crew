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
        $session=$this->session->nama;
        $data['jumlahkaryawan']=$this->db->query("select * from karyawan")->num_rows();
        $date=date('Y');
        $data['cutibersama']=$this->db->query("select * from cuti_bersama where year(tanggal)='$date'")->num_rows();
        $date=date('Y');
        $data['harilibur']=$this->db->query("select * from holiday where year(tanggal)='$date'")->num_rows();
        $data['karyawan']=$this->db->query("select * from karyawan where nik='$session'")->result();

        $this->load->view('supervisor/v_home',$data);
    }

    function approval() {
        $nik=$this->session->nama;
        $nama=$this->db->query("select * from supervisor where nik='$nik'")->result();
        foreach ($nama as $row){
            $session=$row->nama_supervisor;
            // echo $session;
        }

        $data['cuti']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where cuti.atasan='$session' order by created_at desc")->result();
        $data['app']=$this->db->query("select *,karyawan.nama_karyawan,saldo_cuti.saldo_cuti from cuti join karyawan on cuti.nik=karyawan.nik join saldo_cuti on cuti.nik=saldo_cuti.nik where cuti.atasan='$session' AND cuti.approval='Belum'")->result();
       
        foreach ($data['app'] as $row){
            $where=array(
                'id_cuti'=>$row->id_cuti,
            );
            $data_cuti=array(
                'approval' => 'Tidak',
            );
             $created=new Datetime (substr($row->created_at,1,10));
             $now= new Datetime (date('Y-m-d'));
             $interval=$created->diff($now);
 
             if ($interval->days>3){
                 $this->m_Data->update_data($where,$data_cuti,'cuti');
             }
            
           // $this->m_Data->insert_data($data,'cuti');
        }
         $this->load->view('supervisor/v_approval',$data);
    }

    function approved($id_cuti) {
        //Select ID CUTI
        $cuti=$this->db->query("select * from cuti where id_cuti='$id_cuti'")->result();
        foreach ($cuti as $row){
            $nik=$row->nik;
            $total_cuti=$row->total_hari;
            $jenis=$row->jenis;
            $tgl_mulai=$row->tgl_mulai;
            $tgl_berakhir=$row->tgl_berakhir;
        }

        //Taking Saldo Cuti
        $saldo_cuti=$this->db->query("select saldo_cuti from saldo_cuti where nik='$nik'")->result();
        foreach ($saldo_cuti as $row){
            $saldo=$row->saldo_cuti;
        }
        //Saldo nya 0
        if ($saldo==0){
            $total_cuti=0;
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
        //Send Telegram
        $karyawan=$this->db->query("select * from karyawan where nik='$nik'")->result();
        foreach ($karyawan as $row){
            $nama_karyawan=$row->nama_karyawan;
            $divisi=$row->divisi;
            $departement=$row->departement;
        }
        $session=$this->session->nama;
        $supervisor=$this->db->query("select * from supervisor where nik='$session'")->result();
        foreach ($supervisor as $row){
            $nama_supervisor=$row->nama_supervisor;
        }
        $token = '1087542049:AAFwH2WFfGady-KjaEPzMq-BHrd3KSPJxJk';
        $chatID = '-329271102';

      //  echo "sending message to " . $chatID . "\n";
        $messaggio = "Informasi\nPengajuan ".$jenis.' a/n '.$nama_karyawan.'/'.$nik.' Departement '.$departement.' Telah disetujui. Terhitung sejak '.$tgl_mulai.' sampai dengan '.$tgl_berakhir.'.';   
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($messaggio);
        $ch = curl_init();
        $optArray = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($jenis == 'cuti khusus'){
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
            $result;
            redirect('supervisor/home/approval');
        }elseif ($jenis == 'cuti'){
            if ($saldo<=$total_cuti){
                $this->m_Data->update_data($where_nik,$data_cuti_mines,'saldo_cuti');
                $this->m_Data->update_data($where,$data_cuti,'cuti');
                $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
                $result;
                redirect('supervisor/home/approval');
            }else{
            $this->m_Data->update_data($where_nik,$data_saldo_cuti,'saldo_cuti');
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                    '<div class="alert alert-success">
                        <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                    </div>');  
                    $result;
            redirect('supervisor/home/approval');
            }
        }
        elseif ($jenis == 'izin'){
            $this->m_Data->update_data($where,$data_cuti,'cuti');
            $this->session->set_flashdata('msg', 
                  '<div class="alert alert-success">
                      <h4>Terima Kasih Telah Approve Cuti/Izin</h4>
                  </div>'); 
                  $result; 
            redirect('supervisor/home/approval');
        }
    }

    function declined($id_cuti) {
        //Select ID CUTI
        $cuti=$this->db->query("select * from cuti where id_cuti='$id_cuti'")->result();
        foreach ($cuti as $row){
           $nik=$row->nik;
           $tgl_mulai=$row->tgl_mulai;
           $tgl_berakhir=$row->tgl_berakhir;
        }
        $where = array('id_cuti' => $id_cuti);

       $data_cuti = array(
           'approval' =>'Tidak',
       );

       //Send Telegram
       $karyawan=$this->db->query("select * from karyawan where nik='$nik'")->result();
       foreach ($karyawan as $row){
           $nama_karyawan=$row->nama_karyawan;
           $divisi=$row->divisi;
           $departement=$row->departement;
       }
       $session=$this->session->nama;
       $supervisor=$this->db->query("select * from supervisor where nik='$session'")->result();
       foreach ($supervisor as $row){
           $nama_supervisor=$row->nama_supervisor;
       }
       $token = '1087542049:AAFwH2WFfGady-KjaEPzMq-BHrd3KSPJxJk';
       $chatID = '-329271102';
     //  echo "sending message to " . $chatID . "\n";
     $messaggio = "Informasi\nPengajuan ".$jenis.' a/n '.$nama_karyawan.'/'.$nik.' Departement '.$departement. ' tidak disetujui. Terhitung sejak '.$tgl_mulai.' sampai dengan '.$tgl_berakhir.'.';   
       $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
       $url = $url . "&text=" . urlencode($messaggio);
       $ch = curl_init();
       $optArray = array(
               CURLOPT_URL => $url,
               CURLOPT_RETURNTRANSFER => true
       );
       curl_setopt_array($ch, $optArray);
       $result = curl_exec($ch);
       curl_close($ch);

        $this->m_Data->update_data($where,$data_cuti,'cuti');
        $this->session->set_flashdata('msg', 
        '<div class="alert alert-warning">
            <h4>Terima Kasih Telah Declined Cuti/Izin</h4>
        </div>');  
        $result;
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

    private function sendMessage() {
        $token = '1087542049:AAFwH2WFfGady-KjaEPzMq-BHrd3KSPJxJk';
        $chatID = '-329271102';
        echo "sending message to " . $chatID . "\n";
        $messaggio = 'Error';
    
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($messaggio);
        $ch = curl_init();
        $optArray = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}