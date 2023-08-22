<table class="table table-striped">
<?php foreach ($cuti_bersama as $row) {
	$cuti_bersama=$row->jumlah;
}?>
											<thead>
												<tr>
													<th scope="col" colspan="4"><button class="btn btn-success">Hak Cuti
															Anda Di Tahun <?php echo date('Y')?></button></th>
												</tr>
												<tr>
													<th scope="col">Kuota</th>
													<th scope="col">Cuti Tahunan Terpakai</th>
													<th scope="col">Sisa Cuti</th>
													<th scope="col">Izin Minus</th>
													<th scope="col">Cuti Bersama</th>
												</tr>
											</thead>
											<tbody>
												<?php if (empty($saldo_cuti)) { ?>
												<tr>
													<td colspan="8">Data Belum Ada</td>
												</tr>
												<?php
													} else {
													$no = 0;
													foreach ($saldo_cuti as $row) {
													$no++;
													?>
												<tr>
													<td>
														<center><?php echo "12" ?></center>
													</td>
													<td>
														<center><?php echo $row->cuti_terpakai ?></center>
													</td>
													<td>
														<center><?php echo $row->saldo_cuti ?></center>
													</td>
													<td>
														<center><?php echo $row->saldo_minus ?></center>
													</td>
													<td>
														<center><?php if (empty($cuti_bersama)){
															echo "0";
														}else{
																echo $cuti_bersama;
															} 
																?></center>
													</td>
												</tr>
												<?php }} ?>
											</tbody>
										</table>