<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Karyawan Resign</h4>
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

						<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Upload Data Karyawan Resign</button>

						<?php $error=''; echo $error;?>
						<div class="collapse" id="collapseExample">
							<div class="card card-body">

								<p>Download Template Excel <a
										href='<?php echo base_url()?>excel/karyawan_resign.xls'>disini</a></p><br>
								<?php echo form_open_multipart('ps/home/upload_karyawan_resign/');?>

								<input class="form-control" type="file" name="berkas" />

								<button type="submit" class="btn btn-primary">Upload</button>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('ps/sniphets/footer')?>
