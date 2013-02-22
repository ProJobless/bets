<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>Welcome </title>
</head>
<body>
	<div id='login_form'>
		<form action='<?php echo base_url();?>user/login' method='post' name='process'>
			<h2>Login</h2>
			<br />			
			 <?php //if(! is_null($msg)) echo $msg;?>    
			<label for='username'>Username</label>
			<input type='text' name='username' id='username' size='25' /><br />
		
			<label for='password'>Password</label>
			<input type='password' name='password' id='password' size='25' /><br />							
		
			<input type='Submit' value='Login' />			
		</form>
	</div>
		<div id='reg_form'>
		<form action='<?php echo base_url();?>landing/register' method='post' name='process'>
			<h2>Register</h2>
			<br />			
			<label for='username'>Username</label>
			<input type='text' name='username' id='username' size='25' /><br />
			<label for='username'>Email</label>
			<input type='text' name='email' id='email' size='25' /><br />
			<label for='password'>Password</label>
			<input type='password' name='password' id='password' size='25' /><br />	
			<label for='password'>Confirm Password</label>
			<input type='password' name='cpassword' id='cpassword' size='25' /><br />				
		
			<input type='Submit' value='Register' />			
		</form>
	</div>
	<?php if(isset($message)): ?>
		<script>
		function generateNoty(type, msg)
		{
			var n = noty({
			
			  type: type,
			  text: msg,
			  timeout: 2500, // delay for closing event. Set false for sticky notifications
			  closeWith: ['click']// ['click', 'button', 'hover']
			  
			});

			}
			
			generateNoty('error', '<?php echo $message;?>');
			</script>
	<?php endif;?>
		
		
	

</body>
</html>