$(document).ready(function() {

	// Show url whilst typing	
	$('#account-url').keyup(function() {
		var string = $(this).val().replace(' ','-');
		string.replace('_','-');
		$('#example-url').html( string );
		
		// Check if path is ok using ajax
		if( string.length > 0 )
		{
			$.ajax({
				type: "POST",
				url: "ajax/validate_path.php",
				data: { path: string }
				}).done(function( msg ) {
					console.log( "Msg: " + msg );
					if( msg == "ok" )
						$('#example-url-ok').html(' <img src="imgs/tick.png" />');
					else
						$('#example-url-ok').html(' <img src="imgs/cross.png" /> already in use');
			});
		}
		else
		{
			$('#example-url-ok').html('');
		}
		
	});
	$('#account-url').trigger('keyup');						// Trigger onload
	

	// Simple error checking before submitting
	$('.create-new').click(function() {
		
		console.log('Simple error checking...');
		
		// Check account name
		if( $('#venue-name').val() == "" )
		{
			alert('Please enter the venue name.');
			return false;
		}
		
		// Venue email
		if( $('#venue-email').val() == "" )
		{
			alert('Please enter the venue\'s email address.');
			return false;
		}
		// Validate email address
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if( !emailReg.test( $('#venue-email').val() )) 
		{
			alert('Email address invalid.');
			return false;
		}
			
		// Check there is an account url
		if( $('#example-url-ok').html().length == 0 ) 
		{
			alert('Please enter the account url.');
			return false;
		}
		
		// URL already in use
		//console.log('index of = '+$('#example-url-ok').html().indexOf('tick') );
		if( $('#example-url-ok').html().indexOf('tick') == -1  )
		{
			alert('Cannot create account - url already in use.');
			return false;
		}
		
		// Check timezone is selected
		//console.log( 'timezone val = '+$('#timezone').val() );  
		if( $('#timezone').val() == "" )
		{
			alert('Please select a timezone.');
			return false;
		}
		
		// Referral name
		if( $('#referral-name').val() == "" )
		{
			alert('Please enter your name.');
			return false;
		}
		
		// Referral email
		if( $('#referral-email').val() == "" )
		{
			alert('Please enter your email address.');
			return false;
		}
		
		console.log('Error checking completed, all seems ok');
		
	});
	
});
