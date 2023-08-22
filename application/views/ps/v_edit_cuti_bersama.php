<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title">Cuti Bersama</h4>
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
				
<?php foreach ($cuti_bersama as $row){
    $id_cuti_bersama=$row->id_cuti_bersama;
    $jumlah=$row->jumlah;
    $tahun=$row->tahun;
}
?>
		<form action="<?php echo base_url()?>ps/karyawan/update_cuti_bersama" method="POST">
        <input type="hidden" value="<?php echo $id_cuti_bersama?>" name="id_cuti_bersama"/>
		<label>Masukan Jumlah Cuti Bersama Tahun <?php echo date('Y')?></label>
		<input type="text" name="jumlah" value="<?php echo $jumlah?>" class="form-control"/>
        <label>Tahun</label><br>
        <input type="text" class="form-control" value="<?php echo $tahun?>" name="tahun" disabled/> 
		<br>
		<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>