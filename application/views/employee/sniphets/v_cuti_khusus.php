<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title">Pengajuan Cuti Khusus</h4>
				<?php echo $this->session->flashdata('msg'); ?>
			</div>

		</div>
		<!-- /.row -->
		<!-- .row -->
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="white-box">
					<form autocomplete="off" action="<?php echo base_url()?>employee/cuti_khusus/do_insert" method="POST">
						<div class="form-group">
							<label>Kategori</label>
							<select name="kategori" class="form-control">
								<option value="Menikah">Menikah</option>
								<option value="Baptis Anak">Baptis Anak</option>
								<option value="Khitan Anak">Khitan Anak</option>
								<option value="Orang Tua Meninggal">Orang Tua Meninggal / Mertua Meninggal</option>
								<option value="Keluarga 1 Rumah Meninggal">Keluarga 1 Rumah Meninggal</option>
								<option value="Melahirkan">Melahirkan</option>
								<option value="Menikahkan Anak">Menikahkan Anak</option>
								<option value="Istri Melahirkan">Istri Melahirkan / Keguguran</option>
								<option value="Suami/Istri Meninggal Dunia">Suami/Istri Meninggal Dunia</option>
								<option value="Anak/menantu meninggal dunia">Anak/menantu meninggal dunia</option>
								
							</select>
						</div>
						<div class="form-group">
							<label>Tanggal Mulai</label>
							<input type="text" id="datepicker" class="form-control" name="tgl_mulai"
								placeholder="Tanggal Mulai">
						</div>
						<div class="form-group">
							<label>Tanggal Berakhir</label>
							<input type="text" id="datepicker-1" class="form-control" name="tgl_berakhir"
								placeholder="Tanggal Berakhir">
						</div>
						
					
						<div class="form-group">
							<label>Diajukan Kepada</label><br>
							<select class="form-control" name="atasan">

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

					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col" colspan="4"><button class="btn btn-success">Kategori Cuti
										Khusus</button></th>
							</tr>
							<tr>
								<th scope="col">Kategori</th>
								<th scope="col">Kuota Hari</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Menikah</td>
								<td>3 Hari</td>
							</tr>
							<tr>
								<td>Baptis Anak</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Khitan Anak</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Orang/Mertua Tua Meninggal</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Keluarga 1 Rumah Meninggal</td>
								<td>1 Hari</td>
							</tr>
							<tr>
								<td>Melahirkan</td>
								<td>90 Hari</td>
							</tr>
							<tr>
								<td>Menikahkan Anak</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Istri Melahirkan/Keguguran</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Suami/Istri Meninggal Dunia</td>
								<td>2 Hari</td>
							</tr>
							<tr>
								<td>Anak/Menantu Meninggal Dunia</td>
								<td>2 Hari</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /.row -->

		<?php $this->load->view('employee/sniphets/footer')?>