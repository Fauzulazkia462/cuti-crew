<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>


<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Report Sisa Cuti Karyawan Resign</h4>
			<?php echo $this->session->flashdata('msg'); ?>
			<!-- /.col-lg-12 -->
		</div>

		<!-- ============================================================== -->
		<!-- table -->
		<!-- ============================================================== -->
		<div class="row">
			<div class="col-md-6 col-lg-12 col-sm-12">
				<div class="white-box">
					<div class="col-md-2 col-sm-8 col-xs-8 pull-right">

					</div>


					<div class="table-responsive">

						<a href="<?php echo base_url() ?>ps/home/export_report_resign"><button type="submit"
								style="margin-bottom: 20px" class="btn btn-success"><i
									class="fa fa-file-excel-o"></i>Export Data to Excel</button></a>
					

						<?php $error = '';
echo $error;?>
						<form action="" method="POST">
							<br>


							<table id="example" class="table table-bordered table-striped" style="width:100%">
								<thead>
									<tr>
										<th>NIK</th>
										<th>Nama Karyawan</th>
										<th>Tahun</th>
										<th>Saldo Cuti</th>
										<th>Terpakai</th>
										<th>Sisa Cuti</th>
									</tr>
								</thead>
								<tbody>
									<?php

$i = 1;
foreach ($report as $row) {
    // $no++;
    ?>
									<tr>
										<td>
											<?php echo $row->nik ?>
										</td>
										<td>
											<?php echo $row->nama_karyawan ?>
										</td>
										<td>
											<?php echo $row->tahun ?>
										</td>
										<td>
											<?php echo $row->saldo_cuti ?>
										</td>
										<td>
											<?php echo $row->cuti_terpakai ?>
										</td>
										<td>
											<?php echo ($row->saldo_cuti - $row->cuti_terpakai) ?>
										</td>
									</tr>

									<?php }?>
							</table>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('ps/sniphets/footer')?>