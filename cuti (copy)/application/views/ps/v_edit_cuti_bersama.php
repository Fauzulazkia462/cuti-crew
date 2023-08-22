<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
		<div class="row">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
				<div class="container-fluid pt-25">
				
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