<?php

class M_Data extends CI_Model {

 	function tampil_data($table){
		return $this->db->get($table);
    }
    
    function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}
	function insert_data($data,$table){
		$this->db->insert($table,$data);
	}

	function delete_data($where,$table){
		$this->db->where($where);
		$this->db->delete($table);
	}

	function edit_data($where,$table){		
		return $this->db->get_where($table,$where);
		}

	function data($number,$offset){
		return $query = $this->db->get('karyawan',$number,$offset)->result();		
	}
 
	function jumlah_data(){
		return $this->db->get('karyawan')->num_rows();
	}

	public function view(){
		return $this->db->get('izin')->result(); // Tampilkan semua data yang ada di tabel siswa
	  }
	  
	  // Fungsi untuk melakukan proses upload file
	  public function upload_file($filename){
		$this->load->library('upload'); // Load librari upload
		
		$config['upload_path'] = './excel/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']  = '2048';
		$config['overwrite'] = true;
		$config['file_name'] = $filename;
	  
		$this->upload->initialize($config); // Load konfigurasi uploadnya
		if($this->upload->do_upload('file')){ // Lakukan upload dan Cek jika proses upload berhasil
		  // Jika berhasil :
		  $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
		  return $return;
		}else{
		  // Jika gagal :
		  $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
		  return $return;
		}
	  }
	  
	  // Buat sebuah fungsi untuk melakukan insert lebih dari 1 data
	  public function insert_multiple($data){
		$this->db->insert_batch('izin', $data);
	  }
	


}