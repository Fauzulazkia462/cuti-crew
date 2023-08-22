<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="<?php echo base_url()?>supervisor/home">
                        <!-- Logo icon image, you can use font-icon also -->
                        <b>
                            <!--This is dark logo icon-->
                            <img src="<?php echo base_url()?>assets/plugins/images/admin-logo.png" alt="home" class="dark-logo" />
                            <!--This is light logo icon-->
                                    </b>
                        <!-- Logo text image you can use text also -->
                        <span class="hidden-xs">
                            <!--This is dark logo text-->
                            <img src="<?php echo base_url()?>assets/plugins/images/admin-text.png" alt="home" class="dark-logo" />
                            <!--This is light logo text-->
                            <img width="90" height="39" src="<?php echo base_url()?>assets/images/inaco.jpg" alt="home" class="light-logo" />
                        </span> 
                    </a>
                </div>
                <!-- /Logo -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="nav-toggler open-close waves-effect waves-light hidden-md hidden-lg" href="javascript:void(0)"><i class="fa fa-bars"></i></a>
                    </li>
                    <li>
                       
                    </li>
                    <li>
                        <a class="profile-pic" href="#"> <img src="<?php echo base_url()?>assets/images/inaco.jpg" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">
                        <?php 
                        $session=$this->session->nama;
                         $db=$this->db->query("select * from supervisor where nik='$session'")->result();
                        foreach ($db as $row){
                            $data=$row->nama_supervisor;
                        }
                        echo $data;
                        ?>
                        </b></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="<?php echo base_url()?>supervisor/home" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url()?>supervisor/home/approval" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Approval</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url()?>supervisor/home/profile" class="waves-effect"><i class="fa fa-table fa-fw" aria-hidden="true"></i>Ganti Password</a>
                    </li>
    
                    <li>
                        <a href="<?php echo base_url()?>welcome/logout" class="waves-effect"><i class="fa fa-info-circle fa-fw"
                                aria-hidden="true"></i>Logout</a>
                    </li>

                </ul>
               
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        
        <!-- Page Content -->
        <!-- ============================================================== -->