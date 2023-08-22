
<footer class="footer text-center"> <?php echo date('Y') ?> &copy; PT Niramas Utama </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
	<script>
  function myFunction(val){    
   //alert(val)
   document.getElementById('output').value = val;
   //document.getElementById( "demo").innerHTML = x; 
}
    </script>
<script>
        $('#datepicker').datepicker({
			//format: 'yyyy-mm-dd',
            format : 'dd-mm-yyyy',
            uiLibrary: 'bootstrap',
            autoclose: true
        });

		$('#datepicker-1').datepicker({
			//format: 'yyyy-mm-dd',
            format : 'dd-mm-yyyy',
            uiLibrary: 'bootstrap',
            autoclose: true

        });
</script>
    <script src="<?php echo base_url()?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url()?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?php echo base_url()?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="<?php echo base_url()?>assets/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url()?>assets/js/waves.js"></script>
    <!--Counter js -->
    <script src="<?php echo base_url()?>assets/plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!-- chartist chart -->
    <script src="<?php echo base_url()?>assets/plugins/bower_components/chartist-js/dist/chartist.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="<?php echo base_url()?>assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url()?>assets/js/custom.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/dashboard1.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
</body>

</html>