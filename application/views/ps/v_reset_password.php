<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Reset Password User</h4>
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
}
?>
		<form onSubmit="return validate()" action="<?php echo base_url()?>ps/karyawan/update_password" method="POST">
        <label>Nik</label>
        <!-- < -->
        <input class="form-control" name="nik" type="text" value="<?php echo $nik?>"/>
        <label>Nama</label>
		<input type="text" name="nama" value="<?php echo $nama_karyawan?>" class="form-control"/>
        <label>Password Baru</label>
		<input type="password" name="password" id="password" value="" class="form-control"/>
        <label>Konfirmasi Password</label>
		<input type="password" name="confirm_password" value="" id="confirm_password" class="form-control"/>
            <br>
        <button type="submit" class="btn btn-success" >Submit</button>

		</form>
		
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
        function validate(){

            var a = document.getElementById("password").value;
            var b = document.getElementById("confirm_password").value;
            if (a!=b) {
               alert("Passwords do no match");
               return false;
            }
        }
     </script>
	<?php $this->load->view('ps/sniphets/footer')?>