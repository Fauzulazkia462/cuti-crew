<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
				<div class="container-fluid pt-25">
				<a href="<?php echo base_url()?>ps/karyawan/"><button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Back</button></a>
<?php foreach ($karyawan as $row){
    //$i=$row->id_cuti_bersama;
    $nik=$row->nik;
    $nama_karyawan=$row->nama_karyawan;
    $tgl_masuk_kerja=$row->tgl_masuk_kerja;
    $divisi=$row->divisi;
    $departement=$row->departement;
    $atasan=$row->atasan;
    $email=$row->email;
    $grade=$row->grade;
    $level=$row->level;
    $lokasi_kerja=$row->lokasi_kerja;
    $tgl_lahir=$row->tgl_lahir;
    $no_ktp=$row->no_ktp;
    $grade_title=$row->grade_title;
    $sex=$row->sex;
}
?>
		<form>
        <label>Nik</label>
        <input class="form-control" type="text" value="<?php echo $nik?>"/>
        <label>Nama</label>
		<input type="text" value="<?php echo $nama_karyawan?>" class="form-control"/>
        <label>Tanggal masuk Kerja</label>
		<input type="text" value="<?php echo $tgl_masuk_kerja?>" class="form-control"/>
        <label>Divisi</label>
		<input type="text" value="<?php echo $divisi?>" class="form-control"/>
        <label>Departement</label>
		<input type="text" value="<?php echo $departement?>" class="form-control"/>
        <label>Atasan</label>
		<input type="text" value="<?php echo $atasan?>" class="form-control"/>
        <label>Grade Title</label>
		<input type="text" value="<?php echo $grade_title?>" class="form-control"/>
        <label>Grade</label>
		<input type="text" value="<?php echo $grade?>" class="form-control"/>
        <label>Level</label>
		<input type="text" value="<?php echo $level?>" class="form-control"/>
        <label>Lokasi Kerja</label>
		<input type="text" value="<?php echo $lokasi_kerja?>" class="form-control"/>
        <label>Tanggal Lahir</label>
		<input type="text" value="<?php echo $tgl_lahir?>" class="form-control"/>
       
        <label>Jenis Kelamin</label>
		<input type="text" value="<?php echo $sex?>" class="form-control"/>
            <br>

		</form>
		
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>