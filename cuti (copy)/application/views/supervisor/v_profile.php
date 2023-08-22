<?php $this->load->view('supervisor/sniphets/header')?>
<?php $this->load->view('supervisor/sniphets/menu')?>
<div class="page-wrapper">
	<div class="container-fluid pt-25">
        <div class="row">
				<div class="panel panel-default card-view">
					<div class="panel-wrapper collapse in">
						<div class="panel-body sm-data-box-1">
  <div><?php echo $this->session->flashdata('msg'); ?><div>
<center> <button class="btn btn-primary" type="button">Ganti Password</button></center>
<form action="<?php echo base_url()?>supervisor/home/ganti_password" method="POST">
<label>Masukan Password Baru</label>
<input type="text" class="form-control"  name="change_password">
<br>	
<input type="submit" class="btn btn-success" name="submit" value="Update Your Password"/>
</form>
</div>
<?php $this->load->view('employee/sniphets/footer')?>