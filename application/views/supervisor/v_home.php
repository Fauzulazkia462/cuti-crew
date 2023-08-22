<?php
$this->load->view('supervisor/sniphets/header');
$this->load->view('supervisor/sniphets/menu');
?>
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
                          <center><h1>Welcome <?php 
                        $session=$this->session->nama;
                         $db=$this->db->query("select * from supervisor where nik='$session'")->result();
                        foreach ($db as $row){
                            $data=$row->nama_supervisor;
                        }
                        echo $data;
                        ?>
                                            </h1></center>

						</div>
                        </div>
                    </div>
                </div>
       
          
<?php
$this->load->view('supervisor/sniphets/footer');
//$this->load->view('sniphets/menu');
?>