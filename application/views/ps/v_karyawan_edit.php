<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Data Karyawan</h4>
				<?php echo $this->session->flashdata('msg'); ?>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-6 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="col-md-2 col-sm-8 col-xs-8 pull-right">
                              
                            </div>


<div class="table-responsive">
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
    $no_ktp=$row->no_ktp;
}
?>
		<form action="<?php echo base_url()?>ps/karyawan/update_karyawan" method="POST">
        <label>Nik</label>
        <!-- < -->
        <input class="form-control" name="nik" type="text" value="<?php echo $nik?>"/>
        <label>Nama</label>
		<input type="text" name="nama" value="<?php echo $nama_karyawan?>" class="form-control"/>
        <label>Tanggal masuk Kerja</label>
		<input type="text" name="tgl_masuk_kerja" value="<?php echo $tgl_masuk_kerja?>" class="form-control"/>
        <label>Divisi</label>
		<input type="text" name="divisi" value="<?php echo $divisi?>" class="form-control"/>
        <label>Departement</label>
		<input type="text" name="departement" value="<?php echo $departement?>" class="form-control"/>
        <label>Atasan</label>
		<input type="text" name="atasan" value="<?php echo $atasan?>" class="form-control"/>
        <label>Grade Title</label>
		<input type="text" name="grade_title" value="<?php echo $grade_title?>" class="form-control"/>
        <label>Grade</label>
		<input type="text" name="grade" value="<?php echo $grade?>" class="form-control"/>
        <label>Level</label>
		<input type="text" name="level" value="<?php echo $level?>" class="form-control"/>
        <label>Lokasi Kerja</label>
		<input type="text" name="lokasi_kerja" value="<?php echo $lokasi_kerja?>" class="form-control"/>
        <label>Tanggal Lahir</label>
		<input type="text" name="tgl_lahir" value="<?php echo $tgl_lahir?>" class="form-control"/>
        <label>NO KTP</label>
		<input type="text" name="no_ktp" value="<?php echo $no_ktp?>" class="form-control"/>
        <label>Jenis Kelamin</label>
		<input type="text" name="jenis_kelamin" value="<?php echo $sex?>" class="form-control"/>
            <br>
        <button type="submit" class="btn btn-success" >Submit</button>

		</form>
		
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>