<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Persetujuan</h4>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                              
                            </div>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Jenis</th>
									<th scope="col">Tanggal Mulai</th>
									<th scope="col">Tanggal Berakhir</th>
									<th scope="col">Total Cuti/Izin</th>
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
										<?php echo $row->approval ?>
									</td>
								</tr>

								<?php }}?>
							</tbody>
						</table>

						</div>
                        </div>
                    </div>
                </div>

	<?php $this->load->view('employee/sniphets/footer')?>