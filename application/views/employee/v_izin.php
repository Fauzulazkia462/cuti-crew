<?php
$this->load->view('employee/sniphets/header');
$this->load->view('employee/sniphets/menu');
?>
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Pengajuan Izin</h4>
				<?php echo $this->session->flashdata('msg'); ?>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->


<div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="white-box">
	<form autocomplete="off" action="<?php echo base_url()?>employee/izin/do_insert" method="POST">



		<div class="form-group">
			<label>Jenis Ketidakhadiran</label>
			<select name="jenis" onchange="showMe(this.value);" class="form-control">
				<option hidden value="">--</option>
				<option value="izin">Izin Tidak Masuk Kerja</option>
				<option value="izin setengah">Izin Setengah Hari</option>
			</select>
		</div>
		<div class="form-group">
			<label>Tanggal Mulai</label>
			<input type="text" id="datepicker" class="form-control" name="tgl_mulai" placeholder="Tanggal Mulai">
		</div>
		<div class="form-group">
			<label>Tanggal Berakhir</label>
			<input type="text" id="datepicker-1" class="form-control" name="tgl_berakhir"
				placeholder="Tanggal Berakhir">
		</div>
		
		<div class="showIzinHalf" id="showIzinHalf" style="display:none;">
			<div class="form-group inputJam">
				<label>Dari Jam</label>
				<input type="text" class="jam jam1 form-control" placeholder="00:00" name="jam_mulai">
				<label>Sampai Jam</label>
				<input type="text" class="jam form-control" placeholder="00:00" name="jam_akhir">
			</div>

			<div class="form-group noteJam">
				<label>Note :</label>
				<ul class="">
					<li>Datang terlambat, tiba di kantor jam 11, maka diisi 08:00 dan 11:00</li>
					<li>Pulang lebih awal, contoh pulang jam 13:00, maka diisi 13:00 dan 17:00</li>
					<li>Pengisian tidak menggunakan titik dua(:)</li>
				</ul>
			</div>
		</div>

		<div class="form-group">
			<label>Keterangan/Alasan</label><br>
			<textarea rows="4" cols="50" name="keterangan"></textarea>
		</div>
		<div class="form-group">
			<label>Diajukan Kepada</label><br>

			<select class="form-control" name="atasan">
			<option value="NULL"></option> 
				<?php foreach ($atasan as $row){
											$atasan=$row->atasan;
								
										?>
				<option value="<?php echo $atasan?>"><?php echo $atasan?></option>
				<?php 		} ?>
			</select>

		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>


</div>
                        </div>
						<div class="col-md-6 col-xs-12">
                        <div class="white-box">
						<?php $this->load->view('employee/sniphets/v_riwayat_izin')?>
                        </div>
                    </div>
                    </div>
					
                </div>

			<script>
			$(document).ready(function($){
				$(".jam").mask("99:99");
			});
			</script>
			
			<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
			<script src="<?php echo base_url()?>assets/js/jquery.mask.min.js"></script>


			<script type="text/javascript">

				function showMe(value){
					if(value=="izin setengah"){
						document.getElementById('showIzinHalf').style.display="block";
					} else {
						document.getElementById('showIzinHalf').style.display="none";
					}
				}

			</script>

	<?php
$this->load->view('employee/sniphets/footer');
//$this->load->view('sniphets/menu');
?>