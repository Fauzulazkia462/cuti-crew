<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Data Atasan</h4>
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
								class="fa fa-search mr-20"></i>Input Data Atasan</button>
<br>
						    <div class="collapse" id="collapseExample">
							<div class="card card-body">
						
                            <form autocomplete="off" action="<?php echo base_url()?>ps/home/do_insert" method="POST">
                                            
                                            <div class="form-group">
                                                    <label>NIK</label>
                                                    <input type="text" class="form-control" name="nik" 
                                                        placeholder="NIK"><br>
													<label>Nama Atasan</label>
													<input type="text" class="form-control" name="nama_supervisor"
                                                        placeholder="Nama Atasan"><br>
													<label>Email</label>
													<input type="text" class="form-control" name="email"
                                                        placeholder="Email"><br>
													<label>Divisi</label>
													<input type="text" class="form-control" name="divisi"
                                                        placeholder="Divisi"><br>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>

							</form>

							</div></div>
						</div>
								<br>
						<table id="example" class="table table-bordered table-striped" style="width:100%">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">NIK</th> 
									<th scope="col">Nama Atasan</th>
									<th scope="col">Email</th> 
									<th scope="col">Divisi</th> 									
									<th scope="col">Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($supervisor)) { ?>
								<tr>
									<td colspan="8">Data Belum Ada</td>
								</tr>
								<?php
									} else {
									$no = 0;
									foreach ($supervisor as $row) {
									$no++;
									?>
								<tr>
									<td> 
										<?php echo $no; ?>
									</td>
									<td>
										<?php  echo $row->nik ?>
									</td>
									<td>
										<?php  echo $row->nama_supervisor ?>
									</td>
									<td>
										<?php  echo $row->email ?>
									</td>
									<td>
										<?php  echo $row->divisi ?>
									</td>
									<td>
									<a class="btn btn-sm btn-danger" href="<?php echo base_url() ?>ps/home/delete_spv/<?php echo $row->id_supervisor?>"><i class="fa fa-trash"></i> Delete</a>
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