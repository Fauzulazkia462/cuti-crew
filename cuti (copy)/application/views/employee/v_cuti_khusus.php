<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body sm-data-box-1">
						<button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Pengajuan
							Cuti Khusus</button><br>
						<div><?php echo $this->session->flashdata('msg'); ?><div>
								<div class="grid-container">
									<div class="grid-item">
										<form action="<?php echo base_url()?>employee/cuti_khusus/do_insert" method="POST">
                                        <div class="form-group">
												<label>Kategori</label>
                                                <select name="kategori" class="form-control">
                                                <option value="Menikah">Menikah</option>
                                                <option value="Baptis Anak">Baptis Anak</option>
                                                <option value="Khitan Anak">Khitan Anak</option>
                                                <option value="Orang Tua Meninggal">Orang Tua Meninggal</option>
                                                <option value="Keluarga 1 Rumah Meninggal">Keluarga 1 Rumah Meninggal</option>
                                                </select>
											</div>
											<div class="form-group">
												<label>Tanggal Mulai</label>
												<input type="text" id="datepicker" class="form-control" name="tgl_mulai"
													placeholder="Tanggal Mulai">
											</div>
											<div class="form-group">
												<label>Tanggal Berakhir</label>
												<input type="text" id="datepicker-1" class="form-control"
													name="tgl_berakhir" placeholder="Tanggal Berakhir">
											</div>
											<div class="form-group">
												<label>Total Hari</label>
												<input type="text" class="form-control" name="total_hari"
													placeholder="Total Hari">
											</div>
											<div class="form-group">
												<label>Keterangan/Alasan</label><br>
												<textarea rows="4" cols="50" name="keterangan"></textarea>
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
									<div class="grid-item">
									
										<table class="table table-striped">
											<thead>
												<tr>
													<th scope="col" colspan="4"><button class="btn btn-success">Kategori Cuti Khusus</button></th>
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
													<td>Orang Tua Meninggal</td>
													<td>2 Hari</td>
												</tr>
												<tr>
													<td>Keluarga 1 Rumah Meninggal</td>
													<td>1 Hari</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('employee/sniphets/footer')?>
