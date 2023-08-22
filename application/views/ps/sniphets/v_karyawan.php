<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>

<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Data Karyawan</h4>
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
								class="fa fa-search mr-20"></i>Upload Data Karyawan</button>
<br>
<?php $error=''; echo $error;?>
								<div class="collapse" id="collapseExample">
							<div class="card card-body">
						
 <p>Download Template Excel <a href='<?php echo base_url()?>excel/karyawan.xls'>disini</a></p><br>
 <?php echo form_open_multipart('ps/karyawan/upload_excel/');?>

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
									<th scope="col">Atasan</th>
									<th scope="col">Divisi</th>
									<th scope="col">Delete/Details</th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($karyawan)) { ?>
								<tr>
									<td colspan="8">Data Belum Ada</td>
								</tr>
								<?php
									} else {
									$no = 0;
									foreach ($karyawan as $row) {
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
										<?php echo $row->atasan ?>
									</td>
									<td>
										<?php echo $row->divisi ?>
									</td>
									<td>
									<a class="btn btn-sm btn-danger" href="<?php echo base_url() ?>ps/karyawan/delete/<?php echo $row->nik?>"><i class="fa fa-trash"></i> Delete</a>
									<a class="btn btn-sm btn-warning" href="<?php echo base_url() ?>ps/karyawan/details/<?php echo $row->nik?>"><i class="fa fa-edit"></i> Details</a>
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