<?php
$this->load->view('employee/sniphets/header');
$this->load->view('employee/sniphets/menu');
?>

<!-- Page Content -->
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
				<h4 class="page-title"></h4>
                    <!-- /.col-lg-12 -->
                </div>
               
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                          <center><h1>Welcome <?php foreach ($karyawan as $row){
												$nama=$row->nama_karyawan;
                                            } 
                                            echo $nama;
                                            ?>
                                            </h1></center>

						</div>
                        </div>
                    </div>
                </div>
               
                   
          
<?php
$this->load->view('employee/sniphets/footer');
//$this->load->view('sniphets/menu');
?>