<?php 

	$check_account_url = "../../../".$_POST['path'];
		
	// Step 1. Check that folder path doesn't already exist
	if( !is_dir($check_account_url) )
	{
		echo "ok";
	}
	else
	{
		echo "in use";
	}

?>