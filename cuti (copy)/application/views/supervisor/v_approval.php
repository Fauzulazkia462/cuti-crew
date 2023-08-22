<?php $this->load->view('supervisor/sniphets/header')?>
<?php $this->load->view('supervisor/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body sm-data-box-1">
						<button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Persetujuan
							Cuti/Izin</button><br>
                            <div><?php echo $this->session->flashdata('msg'); ?><div>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">NIK</th> 
									<th scope="col">Nama</th> 
									<th scope="col">Jenis</th>
									<th scope="col">Tanggal Mulai</th>
									<th scope="col">Tanggal Berakhir</th>
									<th scope="col">Total Cuti/Izin</th>
									<th scope="col">Keterangan</th>
									<th scope="col">Diajukan tanggal</th>
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
										<?php echo $row->keterangan ?>
									</td>
									<td>
										<?php echo $row->created_at?>
									</td>
									<?php if ($row->approval=='Belum'){
										?>
									<td>
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url() ?>supervisor/home/approved/<?php echo $row->id_cuti ?>"><i class="fa fa-edit"></i> Disetujui</a>
                                    <a class="btn btn-sm btn-warning" href="<?php echo base_url() ?>supervisor/home/declined/<?php echo $row->id_cuti?>"><i class="fa fa-edit"></i> Ditolak</a>
									</td>
									<?php }else { ?>
									<td>
										<?php if ($row->approval=='Ya'){ 
										echo "<button class='btn btn-success'>Telah Disetujui</button>";
									}else{
										echo "<button class='btn btn-warning'>Tidak Disetujui</button>";
									}  
										?>
									</td>
									<?php } ?>
								
								</tr>

								<?php }}?>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('supervisor/sniphets/footer')?>