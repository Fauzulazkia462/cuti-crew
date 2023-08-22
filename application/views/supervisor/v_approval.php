<?php $this->load->view('supervisor/sniphets/header')?>
<?php $this->load->view('supervisor/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Persetujuan</h4>
				<?php echo $this->session->flashdata('msg'); ?>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="col-md-1 col-sm-4 col-xs-6 pull-right">
                              
                            </div>


<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">NIK</th> 
									<th scope="col">Nama</th> 
									<th scope="col">Jenis</th>
									<th scope="col">Tanggal Mulai Cuti</th>
									<th scope="col">Tanggal Berakhir Cuti</th>
									<th scope="col">Jam Mulai Izin</th>
									<th scope="col">Jam Berakhir Izin</th>
									<th scope="col">Saldo Cuti Sebelum Pengajuan</th>
									<th scope="col">Jumlah Pengajuan (Hari)</th>
									<th scope="col">Sisa Cuti Setelah Pengajuan</th>
		
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
										<?php echo $row->jam_mulai ?>
									</td>
									<td>
										<?php echo $row->jam_akhir ?>
									</td>

									<td>
										<?php 
										if ($row->jenis=='cuti'){
											if ($row->approval=='Tidak' || $row->approval=='Belum' ){
												echo $row->saldo_cuti;
											}else{
												echo $row->saldo_cuti+$row->total_hari;
											}
										}else{
											echo $row->saldo_cuti;
										}
										?>
									</td>
									<td>
										<?php echo $row->total_hari ?>
									</td>
									
									<td>
										<?php 
										if ($row->approval=='Belum' ){
										echo '-';
									}else{
										echo $row->saldo_cuti;
									}
										?>
									</td>
									<td>
										<?php 
										if ($row->jenis != 'cuti khusus'){
										echo $row->keterangan;
									}else{
										echo $row->cuti_khusus;
									}
										?>
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
	<?php $this->load->view('supervisor/sniphets/footer')?>