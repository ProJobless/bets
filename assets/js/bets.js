//$('input#userSelector').autocomplete({source:<?php echo json_encode($users);?>});





//generage a notification
function generateNoty(type, msg)
{
			var n = noty({
			
			  type: type,
			  text: msg,
			  timeout: 2500, // delay for closing event. Set false for sticky notifications
			  closeWith: ['click']// ['click', 'button', 'hover']
			  
			});

}
//clear form after submit
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
}

/* attach a submit handler to the form */
  $("#betForm").submit(function(event) {

    /* stop form from submitting normally */
    event.preventDefault(); 

    /* Send the data using post and put the results in a div */
    $.post( 'bets/create', $('form#betForm').serialize(), function(data) {
			var text = data['result'];
			if( text == 'Bet Created')
			{
				generateNoty('success',text);
				resetForm($('#betForm'));
				$('#newBet').modal('hide')
			}
			else{
				generateNoty('error',text);
			}
			
       },
       'json' // I expect a JSON response
    );
  });

//instantiate the global variable for the table
var oTable;

//initialize all the action buttons
function initializeButtons(){
	
	$('button.cancel').addClass('btn-danger');
	  		       		$('button.accept').addClass('btn-success');
	  		       		$('button.won').addClass('btn-success');
						 			$('button.lost').addClass('btn-danger');
						 			$('button.paid').addClass('btn-success');  
						 			
									$('tr').hover(function(){
													$(this).find("button").css({'opacity':'1'});
													},function() {
										        jQuery(this).find("button").css({'opacity':'0.3'});
										});
										
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
							}
//build our table
function buildTable(url){
	//console.log(url);
	oTable = $('#bet-table').dataTable( {
      	"iDisplayLength" : '100',
        "bProcessing": true,
        "sAjaxSource": url,
        "fnDrawCallback": function(){
	  		       		initializeButtons();     
        },
          "aoColumns": [
            {
               "mDataProp": null,
               "sClass": "control center",
               "sDefaultContent": '<span class="icon" id="icon-plus"></span>'
            },
            { "mDataProp": "status" },
            { "mDataProp": "title" },
            { "mDataProp": "opponent_name" },
            { "mDataProp": "message" },
            { "mDataProp": function (source, type, val) {
            				
            				/*
			            		for(i=0;i < source.length;i++){
			            			bet = source['aaData'][i];
				            		if(bet['actions'].length > 0){
				            			for(j=0; j < bet['actions'].length;j++){
           			
				            				
							            		var ret = '<button id="';
							            		ret = ret + bet['actions'][j]['id'];
							            		ret = ret + '" name="';
							            		ret = ret + bet['actions'][j]['name'];
							            		ret = ret +'" class="btn btn-mini ';
							            		ret = ret + bet['actions'][j]['class'].toLowerCase();
							            		ret = ret + ' ';
							            		ret = ret + ((bet['actions'][j]['active'] != undefined ) ? (bet['actions'][j]['active'] ? 'active' : '' ) : '');
							            		ret = ret+'">';
							            		ret = ret+bet['actions'][j]['name'];
							            		ret = ret+'</button>';
					           				}
							           				return ret;
							           				}
					           			return null;
					           		
					           	}
					           	*/
						           	if(source['actions'].length > 0){
								            			for(j=0; j < source['actions'].length;j++){
				           			
								            				
											            		var ret = '<button id="';
											            		ret = ret + source['actions'][j]['id'];
											            		ret = ret + '" name="';
											            		ret = ret + source['actions'][j]['name'];
											            		ret = ret +'" class="btn btn-mini ';
											            		ret = ret + source['actions'][j]['class'].toLowerCase();
											            		ret = ret + ' ';
											            		ret = ret + ((source['actions'][j]['active'] != undefined ) ? (source['actions'][j]['active'] ? 'active' : '' ) : '');
											            		ret = ret+'">';
											            		ret = ret+source['actions'][j]['name'];
											            		ret = ret+'</button>';
									           				}
											           				return ret;
											           				}
									           			return null;
		           	
		           	}
			       
			       }
		       
		           	 		
		          ]
      
        
        
		    } );
	
	
}							
function initializeTable(url){
				
				$.get(url, function(data){
		//				console.log(data);
						if(data['message']){
							//$('#bet-table_wrapper').css('visibility','hidden');
							//$('#message').html(data['message']);
							//console.log(data);
							generateNoty('message',data['message']);
							$('#bet-table').css('visibility','hidden');
						} else {
							$('#bet-table').show();
							$('#bet-table_wrapper').css('visibility','visible');
								$('#message').css('visibility','hidden');
							if(oTable){
								console.log(oTable);
								oTable.fnDestroy();
							}
							buildTable(url);
						}
				});
				
	}
            	


