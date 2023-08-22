<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Data Sisa Cuti Karyawan </h4>
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
								<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Upload Data Karyawan Alfa</button>
<br>

								<div class="collapse" id="collapseExample">
							<div class="card card-body">
						
 <p>Download Template Excel <a href='<?php echo base_url()?>excel/karyawan_alfa.xlsx'>disini</a></p><br>
 <?php echo form_open_multipart('ps/karyawan/upload_karyawan_alfa/');?>

 <input class="form-control" type="file" name="berkas" />

 <button type="submit" class="btn btn-primary">Upload</button>

</form>

							</div>
						</div>
								<br>
                            <div><?php echo $this->session->flashdata('msg'); ?><div>
                            <table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">NIK</th> 
									<th scope="col">Nama Karyawan</th>
									<th scope="col">Sisa Cuti</th>
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
										<?php echo $no; ?>
									</td>
									<td>
										<?php echo $row->nik ?>
									</td>
									<td>
										<?php echo $row->nama_karyawan ?>
									</td>
									<td>
										<?php echo $row->saldo_cuti ?>
									</td>
								</tr>

								<?php }}?>
							</tbody>
						</table>
                      <nav aria-label="Page navigation">
					  <ul class="pagination">
                    <?php echo $this->pagination->create_links(); ?>
               </nav>
						

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>