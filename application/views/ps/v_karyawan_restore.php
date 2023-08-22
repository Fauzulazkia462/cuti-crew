<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>


<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Restore Data Karyawan</h4>
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

                    <form action="<?php echo base_url()?>ps/karyawan/restore_karyawan" method="POST">
							<button style="margin-bottom: 20px" type="submit"
						
								class="btn btn-success">
								</i>Restore Data Karyawan</button>
							<br>



							<br>


							<table id="example" class="table table-bordered table-striped" style="width:100%">
								<thead>
									<tr>
										<th>NIK</th>
										<th>Nama Karyawan</th>
										<th>Atasan</th>
										<th>Divisi</th>
                                        <th>Delete Permanently</th>
										<th>Restore</th>
									</tr>
								</thead>
								<tbody>
									<?php
								
									$i=1;
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

										<a  onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm btn-danger" href="<?php echo base_url() ?>ps/karyawan/delete/<?php echo $row->nik?>"><i
                                                     class="fa fa-trash"></i> Delete Permanently</a>
										</td>
							
										<td>
											<input type="checkbox"  name="check[<?php echo $i?>]"
												value="<?php echo $row->nik?>">
											<?php $i++?>
										</td>
									</tr>

									<?php } ?>
							</table>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('ps/sniphets/footer')?>