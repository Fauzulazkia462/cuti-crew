<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
				<div class="container-fluid pt-25">
				
				<!-- Row -->
				<button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Cuti Bersama</button>
				<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-warning"><i
								class="fa fa-search mr-20"></i>Kurangi Jatah Cuti Bersama Karyawan</button>

						<div class="collapse" id="collapseExample">
							<div class="card card-body">
								<form action="<?php echo base_url()?>ps/karyawan/kurang_jatah_cuti" method="POST">
									<label>Masukan Nik Karyawan</label><br>
									<input type="text" name="nik" class="form-control">
									<label>Masukan Jumlah Cuti Bersama Yang Ingin Dikurangi</label>
									<input type="text" name="jumlah" class="form-control">
									<button type="submit" class="btn btn-primary">Submit</button>
								</form>

							</div>
							</div>
				<div><?php echo $this->session->flashdata('msg'); ?><div>
				<!-- /Row -->
				<br><br>
		<form action="<?php echo base_url()?>ps/karyawan/do_insert_cuti_bersama" method="POST">
		<label>Input Jumlah Cuti Bersama Tahun <?php echo date('Y')?></label>
		<input type="text" name="jumlah" class="form-control"/>
		<br>
		<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<!--Table-->
		<table class="table table-striped">
											<thead>
	
												<tr>
													<th scope="col">Jumlah</th>
													<th scope="col">Tahun</th>
													<th scope="col">Update</th>
												</tr>
											</thead>
											<tbody>
											<?php if (empty($cuti_bersama)){ ?>
												<tr>
												<td>Data Belum Ada</td>
												</tr>
												<?php }else { 
													foreach ($cuti_bersama as $row){
												?>
												<tr>
													<td><?php echo $row->jumlah?></td>
													<td><?php echo $row->tahun?></td>
		
												<td>
									<a class="btn btn-sm btn-warning" href="<?php echo base_url() ?>ps/karyawan/edit_cuti_bersama/<?php echo $row->id_cuti_bersama?>"><i class="fa fa-edit"></i> Update</a>
									</td>
							
												</tr>
												
											</tbody>
											<?php } } ?>
										</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>