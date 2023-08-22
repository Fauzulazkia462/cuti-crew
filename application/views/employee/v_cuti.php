<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Pengajuan Cuti</h4>
						<?php echo $this->session->flashdata('msg'); ?>
                    </div>
                    
                </div>
                <!-- /.row -->
  <!-- .row -->
  <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="white-box">
						<form autocomplete="off" action="<?php echo base_url()?>employee/cuti/do_insert" method="POST">
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
												<option value="NULL"></option> 
										
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
                    <div class="col-md-6 col-xs-12">
                        <div class="white-box">
						<?php $this->load->view('employee/sniphets/v_cuti')?>
                        </div>
                    </div>
					<div class="col-md-6 col-xs-12">
                        <div class="white-box">
						<?php $this->load->view('employee/sniphets/v_riwayat_cuti')?>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

<?php $this->load->view('employee/sniphets/footer')?>