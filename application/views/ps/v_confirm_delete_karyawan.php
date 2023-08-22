<?php $this->load->view('ps/sniphets/header')?>
<?php $this->load->view('ps/sniphets/menu')?>


<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row bg-title">
			<h4 class="page-title">Confirm Delete Data Karyawan</h4>
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

                    <?php 
                    
                    if (!empty($check)){
                        ?>
                    <form action="<?php echo base_url()?>ps/karyawan/delete_confirm" method="POST">
                    <p>Apakah Anda Yakin Menghapus Data Karyawan Berikut</p>
                        <?php
                    foreach ($check as $row){
                        $db=$this->db->query("select * from karyawan where nik='$row'")->result();
                        foreach ($db as $db){
                            $nama=$db->nama_karyawan;
                        }
                        ?>
                        <div class="grid-container">
                    <div class="grid-item">
                    <label>NIK</label>
                    <input type="text" class="form-control" value="<?php echo $row?>" name="check[]"/>
                    </div>
                    <div class="grid-item">
                    <label>Nama</label>
                    <input type="text" class="form-control" value="<?php echo $nama?>" name=""/>
                    </div>
</div>   
                <?php
                    }
                    ?>
                    
                    <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
                    <?php
                }else{
                    echo "data belum ada";
                }
                    ?>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('ps/sniphets/footer')?>