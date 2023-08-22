<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Data Tanggal Libur</h4>
		
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
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-warning"><i
								class="fa fa-search mr-20"></i>Upload Data Libur</button>

						<?php $error=''; echo $error;?>
						<div class="collapse" id="collapseExample">
							<div class="card card-body">

								<p>Download Template Excel <a
										href='<?php echo base_url()?>excel/holiday.xls'>disini</a></p><br>
								<?php echo form_open_multipart('ps/holiday/upload_holiday/');?>

								<input class="form-control" type="file" name="berkas" />

								<button type="submit" class="btn btn-info">Upload</button>

								</form>
							</div>
						</div>

								<button style="margin-bottom: 20px" data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i
								class="fa fa-search mr-20"></i>Input Data Libur</button>
<br>
								<div class="collapse" id="collapseExample">
							<div class="card card-body">
						
                            <form autocomplete="off" action="<?php echo base_url()?>ps/holiday/do_insert" method="POST">
                                            
                                            <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="text" id="datepicker" class="form-control" name="tanggal"
                                                        placeholder="Tanggal"><br>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>

</form>

							</div></div>
						</div>
								<br>
                            <div><?php echo $this->session->flashdata('msg'); ?><div>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Tanggal</th> 
									<th scope="col">Event</th> 
									<th scope="col">Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($holiday)) { ?>
								<tr>
									<td colspan="8">Data Belum Ada</td>
								</tr>
								<?php
									} else {
									$no = 0;
									foreach ($holiday as $row) {
									$no++;
									?>
								<tr>
									<td> 
										<?php echo $no; ?>
									</td>
									<td>
										<?php  echo date('d F Y', strtotime($row->tanggal)) ?>
									</td>
									<td>
										<?php  echo $row->event ?>
									</td>
									<td>
									<a class="btn btn-sm btn-danger" href="<?php echo base_url() ?>ps/holiday/delete/<?php echo $row->id_holiday?>"><i class="fa fa-trash"></i> Delete</a>
									</td>
								</tr>

								<?php }}?>
							</tbody>
						</table>
                

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>