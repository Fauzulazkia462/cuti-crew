<table class="table table-striped">

											<thead>
												<tr>
													<th scope="col" colspan="4"><button class="btn btn-danger">History
															Izin Anda Di Tahun <?php echo date('Y')?></button></th>
												</tr>
												<tr>
													<th scope="col">Tgl Awal</th>
													<th scope="col">Tgl Akhir</th>
													<th scope="col">Total Hari</th>
													<th scope="col">Keterangan</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($riwayat_izin)) { ?>
												<tr>
													<td colspan="8">Data Belum Ada</td>
												</tr>
												<?php
													} else {
													$no = 0;
													foreach ($riwayat_izin as $row) {
													$no++;
													?>
												<tr>
													<td>
														<center><?php echo $row->tgl_mulai ?></center>
													</td>
													<td>
														<center><?php echo $row->tgl_berakhir ?></center>
													</td>
													<td>
														<center><?php echo $row->total_hari ?></center>
													</td>
													
													<td>
														<center><?php echo $row->keterangan ?></center>
													</td>
												</tr>
												<?php }} ?>
											</tbody>
										</table>