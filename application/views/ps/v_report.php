<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Report Sisa Cuti Karyawan</h4>
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

						<a href="<?php echo base_url() ?>ps/home/export_report"><button type="submit"
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
										<!-- <th>Sisa Cuti</th> -->
										<th>History Cuti</th>
										
									</tr>
								</thead>
								<tbody>

								<?php $i = 1; foreach ($report as $row) { $nik = $row->nik ?>
									
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
										<!-- <td>
											<?php echo $row->sisa_cuti ?>
										</td> -->
										<td>
											<input type="hidden" name="nikinput" value="<?php echo $nik ?>">
											<input type="button" role="" name="view" value="History Cuti" id="<?php echo $row->nik; ?>" class="btn btn-warning view_data">
										</td>
										
									</tr>

									<?php }?>
								</tbody>
							</table>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal_tableHMS" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                         Data Cuti Karyawan
                </div>
            </div>
                <div class="modal-body no-padding">
                    <div id="hms_result"></div>

					<!-- <table id="example" class="table table-bordered table-striped" style="width:100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama</th>
										<th>Jenis</th>
										<th>Tanggal</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>

								<?php $i = 1; foreach ($list as $row) {?>

									<tr>
										<td>

										</td>
										<td>
											<?php echo $row->nama_karyawan ?>
										</td>
										<td>
											<?php echo $row->jenis ?>
										</td>
										<td>
											<?php echo $row->tgl_mulai ?> sd <?php echo $row->tgl_berakhir ?> 
										</td>
										<td>
											<?php echo $row->total_hari ?>
										</td>
										
									</tr>

								<?php }?>
								</tbody>
							</table> -->
                </div>
                <div class="modal-footer no-margin-top">
                    <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal-table -->
<script type="text/javascript">
$(document).ready(function(){
    $('.view_data').click(function(){
        var nik = $(this).attr('id');

        $.ajax({

            url: "<?php echo base_url() ?>ps/Karyawan/history",
            method: "POST",
            data: {nik:nik},
            success: function(data){
                $('#hms_result').html(data);
                $('#modal_tableHMS').modal('show');
            }
      });
   });

	// $('.view_data').click(function(){

	// 	$.ajax({
	// 		success:function(){
	// 			$('#modal_tableHMS').modal('show');
	// 		}
	// 	});
	// })
});
</script>
<?php $this->load->view('ps/sniphets/footer')?>