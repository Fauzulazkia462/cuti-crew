<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body sm-data-box-1">
						<button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Pengajuan
							Cuti</button><br>
							<button style="margin-bottom: 20px" class="btn btn-warning">Pengajuan Cuti Maximal 7 Hari Sebelum Tanggal Mulai Cuti
							</button>
						<?php if ($interval->days <= 365 ){ ?>
						<button style="margin-bottom: 20px" class="btn btn-danger">Masa Kerja Anda Belum 1 Tahun, Jika Mengajukan Cuti Akan Dihitung Untuk Cuti Tahun Berikutnya
							</button>
						<?php } ?>
						<div><?php echo $this->session->flashdata('msg'); ?><div>
								<div class="grid-container">
									<div class="grid-item">
										<form action="<?php echo base_url()?>employee/cuti/do_insert" method="POST">
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
									<div class="grid-item">
										<?php $this->load->view('employee/sniphets/v_cuti')?>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('employee/sniphets/footer')?>