<?php
$this->load->view('supervisor/sniphets/header');
$this->load->view('supervisor/sniphets/menu');
?>
<!-- Main Content -->
<div class="page-wrapper">
			<div class="container-fluid pt-25">	
						<div class="panel panel-default card-view">
							<div class="panel-wrapper collapse in">
								<div class="panel-body sm-data-box-1">
									<div class="cus-sat-stat weight-500 txt-success text-center mt-5">
										<span class="text">Welcome <?php echo $this->session->nama;?></span><br>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
       
          
<?php
$this->load->view('supervisor/sniphets/footer');
//$this->load->view('sniphets/menu');
?>