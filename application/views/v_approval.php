<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Persetujuan Izin/Cuti</h4>
			<?php echo $this->session->flashdata('msg'); ?>
			<!-- /.col-lg-12 -->
		</div>

		<!-- ============================================================== -->
		<!-- table -->
		<!-- ============================================================== -->
		<div class="row">
			<div class="col-md-6 col-lg-12 col-sm-12">
				<div class="white-box">
					<div class="col-md-1 col-sm-8 col-xs-8 pull-right">

					</div>


					<div class="table-responsive">
						<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample1"
							aria-expanded="false" aria-controls="collapseExample1" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Export Data To Excel</button>

						



						<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Filter Data</button>

								<div class="collapse" id="collapseExample1">
							<div class="card card-body">
								<form action="<?php echo base_url()?>ps/home/export" method="POST">
								<label>Bulan</label>
									<select name="bulan" class="form-control">
										<option>--PILIH--</option>
										<option value="1">Januari</option>
										<option value="2">Februari</option>
										<option value="3">Maret</option>
										<option value="4">April</option>
										<option value="5">Mei</option>
										<option value="6">Juni</option>
										<option value="7">Juli</option>
										<option value="8">Agustus</option>
										<option value="9">September</option>
										<option value="10">Oktober</option>
										<option value="11">November</option>
										<option value="12">Desember</option>
									</select>
									<?php
									$db=$this->db->query("select distinct year(created_at)  as year from cuti")->result();
									?>
									<label>Tahun</label>
									<select name="tahun" class="form-control">
										<option>--PILIH--</option>
										<?php 
										foreach ($db as $row){ 
										
										?>
										<option value="<?php echo $row->year?>"><?php echo $row->year?></option>
									<?php } ?>
									</select><br>
									<br>
									<button type="submit" class="btn btn-primary">Submit</button>
								</form>

							</div>
						</div>

						<div class="collapse" id="collapseExample">
							<div class="card card-body">
								<form action="<?php echo base_url()?>ps/home/approval" method="GET">
									<select class="form-control" name="filter">
										<option value="Ya">Approved</option>
										<option value="Tidak">Declined</option>
									</select><br>
									<button type="submit" class="btn btn-primary">Search</button>
								</form>
<br>
								<form action="<?php echo base_url()?>ps/home/jenis_cuti" method="GET">
									<select class="form-control" name="filter">
										<option value="cuti khusus">Cuti Khusus</option>
										<option value="cuti">Cuti</option>
										<option value="izin">Izin</option>
									</select><br>
									<button type="submit" class="btn btn-primary">Search</button>
								</form>

							</div>
						</div>
						<br><br>

					
								<table id="example" class="table table-bordered table-striped" style="width:100%">
									<thead>
										<tr>
											<th>NIK</th>
											<th>Nama Karyawan</th>
											<th>Jenis</th>
											<th>Tanggal Mulai</th>
											<th>Tanggal Berakhir</th>
											<th>Total Pengajuan</th>
											<th>Keterangan</th>
											<th>Diajukan Pada Tanggal</th>
											<th>Atasan</th>
											<th>Sisa Cuti</th>
											<th>Persetujuan</th>
										</tr>
									</thead>
									<tbody>
										<?php
								
								$no = 0;
								foreach ($cuti as $row) {
								$no++;
								?>
										<tr>

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
										<?php
											if ($row->jenis!='cuti khusus'){
												echo $row->keterangan;
											}else{
												echo $row->cuti_khusus;
											}
										?>
									</td>
											<td>
												<?php echo date('d F Y', strtotime($row->created_at))?>
											</td>
											<td>
												<?php echo $row->atasan?>
											</td>
											<td>
												<?php echo $row->saldo_cuti?>
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


								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('ps/sniphets/footer')?>