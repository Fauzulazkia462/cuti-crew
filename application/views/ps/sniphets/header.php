<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url()?>assets/plugins/images/favicon.png">
    <title>PT Niramas Utama</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url()?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="<?php echo base_url()?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
     <!-- morris CSS -->
    <link href="<?php echo base_url()?>assets/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="<?php echo base_url()?>assets/plugins/bower_components/chartist-js/dist/chartist.min.css" rel="stylesheet">
	<!--
    <link href="<?php echo base_url()?>assets/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
	-->
    <!-- animation CSS -->
    <link href="<?php echo base_url()?>assets/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?php echo base_url()?>assets/css/colors/default.css" id="theme" rel="stylesheet">
    
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
	 
	 <!--
	 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	 -->
	 
	 <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>


     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
     
<!-- 
     <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
$(document).ready(function() {
    $('#example').DataTable({
      "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      "order" : []
    });
} );


</script>
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto;

  padding: 5px;
}
.grid-item {
 
  padding: 0px;
  /* font-size: 30px; */
  text-align: center;
}

.noteJam{
  color:red;
}

.jam1{
  margin-bottom:20px;
}

</style>
</head>