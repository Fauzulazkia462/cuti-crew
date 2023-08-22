<body>
	<!-- Preloader -->
	<div class="preloader-it">
		<div class="la-anim-1"></div>
	</div>
	<!-- /Preloader -->
	<div class="wrapper theme-1-active pimary-color-red">
		<!-- Top Menu Items -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="mobile-only-brand pull-left">
				<div class="nav-header pull-left">
					<div class="logo-wrap">
						<span class="brand-text">PT Niramas Utama</span>
					</div>
				</div>	
				<a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);">
					<i class="zmdi zmdi-menu"></i>
				</a>
				<a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);">
				</a>
				<a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
			</div>
			<div id="mobile_only_nav" class="mobile-only-nav pull-right">
				<ul class="nav navbar-right top-nav pull-right">			
					<li class="dropdown auth-drp">
						<a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown">
						Welcome <?php echo $this->session->nama?>
							<img src="<?php echo base_url()?>assets/images/inaco.jpg" alt="user_auth" class="user-auth-img img-circle"/>
						</a>
						<ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
							<li>
								<a href="<?php echo base_url()?>welcome/logout"><i class="zmdi zmdi-power"></i><span>Log Out</span></a>
							</li>
						</ul>
					</li>
				</ul>
			</div>	
		</nav>
		<!-- /Top Menu Items -->
		
		<!-- Left Sidebar Menu -->
		<div class="fixed-sidebar-left">
			<ul class="nav navbar-nav side-nav nicescroll-bar">
				<li class="navigation-header">
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/home">
						<div class="pull-left">
							<i class="fa fa-home mr-20"></i>
							<span class="right-nav-text">Dashboard</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/izin">
						<div class="pull-left">
							<i class="fa fa-blind mr-20"></i>
							<span class="right-nav-text">Pengajuan Izin</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/cuti">
						<div class="pull-left">
							<i class="fa fa-beer mr-20"></i>
							<span class="right-nav-text">Pengajuan Cuti</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/cuti_khusus">
						<div class="pull-left">
							<i class="fa fa-bullseye mr-20"></i>
							<span class="right-nav-text">Pengajuan Cuti Khusus</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/profile/approval">
						<div class="pull-left">
							<i class="fa fa-wpforms mr-20"></i>
							<span class="right-nav-text">Approval</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url()?>employee/profile">
						<div class="pull-left">
							<i class="fa fa-cloud mr-20"></i>
							<span class="right-nav-text">Ganti Password</span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				
			</ul>
		</div>
		<div class="right-sidebar-backdrop"></div>
		<!-- /Right Sidebar Backdrop -->
