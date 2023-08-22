<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body sm-data-box-1">
						<button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Persetujuan
							Cuti/Izin</button>
						<a href="<?php echo base_url()?>ps/home/export"><button type="submit"
								style="margin-bottom: 20px" class="btn btn-warning"><i
									class="fa fa-print mr-20"></i>Export Data to Excel</button></a>

						<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Filter Data</button>

						<div class="collapse" id="collapseExample">
							<div class="card card-body">
								<form action="<?php echo base_url()?>ps/home/approval" method="GET">
									<select class="form-control" name="filter">
										<option value="Ya">Approved</option>
										<option value="Tidak">Declined</option>
									</select><br>
									<button type="submit" class="btn btn-primary">Search</button>
								</form>

							</div>
						</div>
						<br><br>
						<form action="<?php echo base_url()?>ps/home/approval" method="GET">
						<input placeholder="Search Anything" type="search" name="filter" class="form-control"><br>
						<button type="submit" class="btn btn-primary">Search</button>
						</form>
						<div><?php echo $this->session->flashdata('msg'); ?><div>
								<table class="table">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">NIK</th>
											<th scope="col">Nama Karyawan</th>
											<th scope="col">Jenis</th>
											<th scope="col">Tanggal Mulai</th>
											<th scope="col">Tanggal Berakhir</th>
											<th scope="col">Total Cuti/Izin</th>
											<th scope="col">Diajukan Pada Tanggal</th>
											<th scope="col">Atasan</th>
											<th scope="col">Persetujuan</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($cuti)) { ?>
										<tr>
											<td colspan="8">Data Belum Ada</td>
										</tr>
										<?php
									} else {
									$no = 0;
									foreach ($cuti as $row) {
									$no++;
									?>
										<tr>
											<td>
												<?php echo $no; ?>
											</td>
											<td>
												<?php echo $row->nik ?>
											</td>
											<td>
												<?php echo $row->nama_karyawan ?>
											</td>
											<td>
												<?php echo $row->jenis ?>
											</td>
											<td>
												<?php echo date('d F Y', strtotime($row->tgl_mulai)) ?>
											</td>
											<td>
												<?php echo date('d F Y', strtotime($row->tgl_berakhir)) ?>
											</td>
											<td>
												<?php echo $row->total_hari ?>
											</td>
											
											<td>
												<?php echo date('d F Y', strtotime($row->created_at))?>
												</td>
												<td>
												<?php echo $row->atasan?>
											</td>
											<td>
											
												<?php if ($row->approval=='Ya'){ 
										echo "<button class='btn btn-success'>Telah Disetujui</button>";
									}elseif ($row->approval=='Tidak'){
										echo "<button class='btn btn-warning'>Tidak Disetujui</button>";
									}else{
										echo "<button class='btn btn-danger'>Belum Direspond</button>";
									} 
										?>
											</td>
											<?php } ?>
										</tr>

										<?php }?>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('ps/sniphets/footer')?>