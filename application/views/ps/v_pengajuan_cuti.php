<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>

<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Pengajuan Cuti</h4>
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
										<form autocomplete="off" action="<?php echo base_url()?>ps/karyawan/pengajuan_cuti_do_insert" method="POST">
                                            
                                        <div class="form-group">
												<label>NIK</label>
												<input type="text" class="form-control" name="nik"
													placeholder="NIK">
											</div>
											<div class="form-group">
												<label>Tanggal Mulai</label>
												<input type="text" id="datepicker" class="form-control" name="tgl_mulai"
													placeholder="Tanggal Mulai">
											</div>
											<div class="form-group">
												<label>Tanggal Berakhir</label>
												<input type="text" id="datepicker-1" class="form-control"
													name="tgl_berakhir" placeholder="Tanggal Berakhir">
											</div>
											<div class="form-group">
												<label>Keterangan/Alasan</label><br>
												<textarea rows="4" cols="50" name="keterangan"></textarea>
											</div>
											<div class="form-group">
												<label>Diajukan Kepada</label><br>
												
												<select class="form-control" name="atasan">
										
												<?php foreach ($atasan as $row){
											$atasan=$row->atasan;
								
										?>
												<option value="<?php echo $atasan?>"><?php echo $atasan?></option>
												<?php 		} ?>
												</select>
											
											</div>
											<button type="submit" class="btn btn-primary">Submit</button>
										</form>
							

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('ps/sniphets/footer')?>