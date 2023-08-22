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
				<a href="<?php echo base_url() ?>ps/karyawan/"><button style="margin-bottom: 20px" class="btn btn-success"><i
								class="fa fa-home mr-20"></i>Back</button></a>
<?php foreach ($saldo as $row) {
    //$i=$row->id_cuti_bersama;
    $nik = $row->nik;
    $sisa = $row->saldo_cuti;
}
?>
		<form action="<?php echo base_url() ?>ps/saldocuti/update_saldo" method="POST">
        <label>Nik</label>
        <!-- < -->
        <input class="form-control" name="nik" type="text" value="<?php echo $nik ?>" readonly/><br>
        <label>Sisa Cuti</label>
		<input type="text" name="sisa" value="<?php echo $sisa ?>" class="form-control"/>
            <br>
        <button type="submit" class="btn btn-success" >Submit</button>

		</form>

					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('ps/sniphets/footer')?>