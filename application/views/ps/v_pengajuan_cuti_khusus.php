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
						<!-- <div><?php echo $this->session->flashdata('msg'); ?><div> -->
								
								
										<form autocomplete="off" action="<?php echo base_url()?>ps/karyawan/pengajuan_cuti_khusus_do_insert" method="POST"> 
											<div class="form-group">
											<label>NIK / NAMA</label>
												<select class="form-control" name="nik">
												<?php foreach ($karyawan as $row){
													$nik=$row->nik;
													$nama=$row->nama_karyawan;
												?>
												<option hidden value="">--</option>
												<option value="<?php echo $nik?>"><?php echo $nik .' ['.$nama.']' ?></option>
												<?php 		} ?>
												</select>
											</div>
											<div class="form-group">
												<label>Jenis Ketidakhadiran</label>
												<select name="jenis" class="form-control">
													<option hidden value="">--</option>
													<option value="cuti">Cuti Tahunan</option>
													<!-- <option value="izin">Izin Tidak Masuk Kerja</option> -->
													<option value="cuti khusus">Cuti Khusus</option>
												</select>
											</div>
											<div class="form-group" id="category" name="category" style="display:none">
												<label>Kategori Cuti Khusus</label>
												<select name="kategori" class="form-control">
													<option value="Menikah">Menikah</option>
													<option value="Baptis Anak">Baptis Anak</option>
													<option value="Khitan Anak">Khitan Anak</option>
													<option value="Orang Tua Meninggal">Orang Tua Meninggal / Mertua Meninggal</option>
													<option value="Keluarga 1 Rumah Meninggal">Keluarga 1 Rumah Meninggal</option>
													<option value="Melahirkan">Melahirkan</option>
													<option value="Menikahkan Anak">Menikahkan Anak</option>
													<option value="Istri Melahirkan">Istri Melahirkan / Keguguran</option>
													<option value="Suami/Istri Meninggal Dunia">Suami/Istri Meninggal Dunia</option>
													<option value="Anak/menantu meninggal dunia">Anak/menantu meninggal dunia</option>
												</select>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('select[name=jenis]').on('change', function(){
			if($(this).val() == 'cuti khusus'){
                $("#category").show();
			}else{
			    $("#category").hide();
			}
		});
	});
</script>
			<?php $this->load->view('ps/sniphets/footer')?>
