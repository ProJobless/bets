
<?php if($bets == 'None') : ?>
   <h4><?php echo $no; ?></h4>
   
   <?php else : ?>
   
    <table>
    <table id="bet-table" class='table table-bordered table-hover'>
		<thead>
			
			<tr>
				<th></th>
				<th>Status</th>
				<th>Title</th>
				<th>Against</th>
				<th>Message</th>
				<th>Actions</th>
			</tr>
			
		</thead>
		<tbody></tbody>
    </table>
		
	
		<script>
		$(document).ready(function() {
			var oTable = $('#bet-table').dataTable({
				  "bProcessing": true,
        "sAjaxSource": "/release-datatables/examples/ajax/sources/objects.txt",
        "aoColumns": [
            {
               "mDataProp": null,
               "sClass": "control center",
               "sDefaultContent": '<img src="'+sImageUrl+'details_open.png'+'">'
            },
            { "mDataProp": "engine" },
            { "mDataProp": "browser" },
            { "mDataProp": "grade" }
        ]
				
				
				
			});
		
		
		
		
		
		
		
		$('tr').hover(function(){
	$(this).find("button").css({'opacity':'1'});

    }, function() {
        jQuery(this).find("button").css({'opacity':'0.3'});
});

	$('button.cancel').addClass('btn-danger');
	$('button.accept').addClass('btn-success');
	$('button.won').addClass('btn-success');
	$('button.lost').addClass('btn-danger');
	$('button.paid').addClass('btn-success');

$('#bet-table').dataTable( {
        
        
    } );
    
   $(function(){
    $('button.cancel').click(function(e){
	    	var id = this.id;
	    	var other;
	    	console.log('cancel click for id ' + id);
	    	
	    				
			var url = 'bets/cancel/' + id;
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('error','Bet Canceled');
					console.log(this);
					 	$.get("bets/get_recent_activity", function(data){
						 	$('#recent').html(data);
						 	});										
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
	    
    });
   });
   
   $('button.accept').click(function(e){
	    	var id = this.id;
	    	var other;
	    	console.log('accept click for id ' + id);
	    	
	   
		    				
				var url = 'bets/accept/' + id;
				$.post(url, function(data) {
				var text = data['result'];
				try{
					if ( text.substring(0,10) != 'Unsuccesful'){
						generateNoty('success','Bet Accept');
						console.log(this);
					 //	$.get("bets/get_recent_activity", function(data){
						 //	$('#recent').html(data);
						 //	});
					}
					else
					{
						generateNoty('error',text);
					}
				 } catch (err) {
					generateNoty('error','Internal Application Error');
				 
				 }
		    
	    });
   });
   
   $('button.vote').click(function(e){
	    	var id = this.id;
	    	var other;
	   
	    	
	    	 	var betid = this.id.split("_");
	    	 	 	console.log('vote click for id ' + betid[1]);
			if($(this).hasClass('won')){
				console.log('voted for me');
				other = betid[0] + '_opp';
			}else{
				console.log('voted for opp');
				other = betid[0] + '_me';
			}
	    				
		
			var url = 'bets/vote/' + betid[0] + '/' + betid[1];
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('success','Vote Submitted');
					
					 //	$.get("bets/get_recent_activity", function(data){
						 //	$('#recent').html(data);
				//});
					
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
   });
   
   });

$('button.paid').click(function(e){
	    	var id = this.id;
	    	var other;
	   
	    	
	    	 
	    console.log('paid click for id ' + id);
		
	    				
		
			var url = 'bets/paid/' + id;
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('success','You got paid!');
					
					$('#'+id).remove('.btn');
					 	$.get("bets/get_recent_activity", function(data){
			$('#recent').html(data);
			});
					
					
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
   });
   
   });

   
   
   });
    
</script>
<?php endif; ?>










