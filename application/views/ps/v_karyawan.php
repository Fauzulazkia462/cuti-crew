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

						<a href="<?php echo base_url() ?>ps/home/export_karyawan"><button type="submit"
								style="margin-bottom: 20px" class="btn btn-warning"><i
									class="fa fa-print mr-20"></i>Export Data to Excel</button></a><br>
						<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Upload Data Karyawan</button>

						<?php $error = '';
echo $error;?>
						<div class="collapse" id="collapseExample">
							<div class="card card-body">

								<p>Download Template Excel <a href='<?php echo base_url() ?>excel/karyawan.xls'>disini</a></p><br>
										
								<?php echo form_open_multipart('ps/karyawan/upload_excel/'); ?>
								<input class="form-control" type="file" name="berkas" />

								<button type="submit" class="btn btn-primary">Upload</button>

								</form>

							</div>
						</div>

						<form action="<?php echo base_url() ?>ps/karyawan/delete_karyawan" method="POST">
							<button style="margin-bottom: 20px" type="submit"

								class="btn btn-success">
								</i>Delete Data Karyawan</button>
							<br>



							<br>


							<table id="example" class="table table-bordered table-striped" style="width:100%">
								<thead>
									<tr>
										<th>NIK</th>
										<th>Nama Karyawan</th>
										<th>Atasan</th>
										<th>Divisi</th>
										<th>Sisa Cuti</th>
										<th>Details</th>
										<th>Edit</th>
										<th>Reset Password</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
									<?php

$i = 1;
foreach ($karyawan as $row) {
    // $no++;
    ?>
									<tr>

										<td>
											<?php echo $row->nik ?>
										</td>
										<td>
											<?php echo $row->nama_karyawan ?>
										</td>
										<td>
											<?php echo $row->atasan ?>
										</td>
										<td>
											<?php echo $row->divisi ?>
										</td>
										<td>
											<?php echo ($row->saldo - $row->cutter) ?>
										</td>
										<td>

											<a class="btn btn-sm btn-warning"
												href="<?php echo base_url() ?>ps/karyawan/details/<?php echo $row->nik ?>"><i
													class="fa fa-edit"></i> Details</a>
										</td>
										<td>

											<a class="btn btn-sm btn-primary"
												href="<?php echo base_url() ?>ps/karyawan/edit/<?php echo $row->nik ?>"><i
													class="fa fa-edit"></i> Edit</a>
										</td>
										<td>

											<a class="btn btn-sm btn-info"
												href="<?php echo base_url() ?>ps/karyawan/reset_password/<?php echo $row->nik ?>"><i
													class="fa fa-edit"></i>Reset Password</a>
										</td>
										<td>
											<input type="checkbox"  name="check[<?php echo $i ?>]"
												value="<?php echo $row->nik ?>">
											<?php $i++?>
										</td>
									</tr>

									<?php }?>
							</table>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('ps/sniphets/footer')?>