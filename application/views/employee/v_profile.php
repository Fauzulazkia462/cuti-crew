<?php $this->load->view('employee/sniphets/header')?>
<?php $this->load->view('employee/sniphets/menu')?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                <h4 class="page-title">Ganti Password</h4>
				<?php echo $this->session->flashdata('msg'); ?>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                              
                            </div>

<center> <button class="btn btn-primary" type="button">Ganti Password</button></center>
<form action="<?php echo base_url()?>employee/profile/ganti_password" method="POST">
<label>Masukan Password Baru</label>
<input type="text" class="form-control"  name="change_password">
<br>	
<input type="submit" class="btn btn-success" name="submit" value="Update Your Password"/>
</form>
</div>
                        </div>
                    </div>
                </div>

<?php $this->load->view('employee/sniphets/footer')?>