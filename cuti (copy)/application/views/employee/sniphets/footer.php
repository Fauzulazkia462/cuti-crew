<script>
  function myFunction(val){    
   //alert(val)
   document.getElementById('output').value = val;
   //document.getElementById( "demo").innerHTML = x; 
}
    </script>
<script>
        $('#datepicker').datepicker({
			format: 'yyyy/mm/dd',
            uiLibrary: 'bootstrap'
        });

		$('#datepicker-1').datepicker({
			format: 'yyyy/mm/dd',
            uiLibrary: 'bootstrap'
        });
</script>

<script>
$(document).ready(function(){
   $('[data-toggle="offcanvas"]').click(function(){
       $("#navigation").toggleClass("hidden-xs");
   });
});
</script>
 <script>
    $(function(){
      // bind change event to select
      $('#dynamic_select').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });
   </script>
   

			<!-- /Footer -->
			
		</div>
		<!-- /Main Content -->

	</div>
	<!-- /#wrapper -->
	
	<!-- JavaScript -->
	
	<!-- jQuery -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/jquery/dist/jquery.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Data table JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
	
	<!-- Slimscroll JavaScript -->
	<script src="<?php echo base_url()?>assets/dist/js/jquery.slimscroll.js"></script>
	
	<!-- simpleWeather JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/moment/min/moment.min.js"></script>
	<script src="<?php echo base_url()?>assets/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
	<script src="<?php echo base_url()?>assets/dist/js/simpleweather-data.js"></script>
	
	<!-- Progressbar Animation JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/waypoints/lib/jquery.waypoints.min.js"></script>
	<script src="<?php echo base_url()?>assets/vendors/bower_components/jquery.counterup/jquery.counterup.min.js"></script>
	
	<!-- Fancy Dropdown JS -->
	<script src="<?php echo base_url()?>assets/dist/js/dropdown-bootstrap-extended.js"></script>
	
	<!-- Sparkline JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/jquery.sparkline/dist/jquery.sparkline.min.js"></script>
	
	<!-- Owl JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/owl.carousel/dist/owl.carousel.min.js"></script>
	
	<!-- ChartJS JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/chart.js/Chart.min.js"></script>
	
	<!-- Morris Charts JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/raphael/raphael.min.js"></script>
	<script src="<?php echo base_url()?>assets/vendors/bower_components/morris.js/morris.min.js"></script>
	<script src="<?php echo base_url()?>assets/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
	
	<!-- Switchery JavaScript -->
	<script src="<?php echo base_url()?>assets/vendors/bower_components/switchery/dist/switchery.min.js"></script>
	
	<!-- Init JavaScript -->
	<script src="<?php echo base_url()?>assets/dist/js/init.js"></script>
	<script src="<?php echo base_url()?>assets/dist/js/dashboard-data.js"></script>
</body>


<!-- Mirrored from hencework.com/theme/hound/full-width-light/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jul 2017 05:28:46 GMT -->
</html>