<?php //$this->load->view('sidebar');?>

	 <div class="span9" id="content">
	

  <div class="row" id="list">
  	<div><h3 id="message"></h3></div>
  	<?php $this->load->view('bet_table'); ?>

		

  
  
</div>


</div>

 
<script>


            

  //load data on page load
  $(document).ready(function() {
  
		$('.hide').css('visibility','hidden');
		
		  /* Initialise the DataTable */
		 initializeTable("bets/get/6/json");
		  
  
		 
		 
		  
		   
			});
			
			
	

  </script>
