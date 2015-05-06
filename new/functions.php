<?php


/*** 
 * SQL CHANGES
 * using POST now not get
 * 
$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/execute/Mysql/set_privileges_on_database" . $add_auth_url;
   print "\nAdd User Auth: " . $url . "\n";
   
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$db_name&database=$db_name&privileges=ALL");
   
   $result = curl_exec($curl);
 */

/*** Add SQL structure to database ***/
function database_structure_sql($cpanel_user, $cpanel_password, $cpanel_host,$db_name)
{
	$output = array('success'=>false,'reason'=>'');
	
	// 1. Create a new user with CREATE and INSERT privileges
	$download_url = "https://".$cpanel_host.":2083/login/";
	
	// get the cpanel security token.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_URL, $download_url);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$cpanel_user&pass=$cpanel_password");
	$result = curl_exec($curl);
	curl_close($curl);
	
	$parts = explode( 'URL=', $result);
	$session_parts = explode( '/frontend/', $parts[1]);
	$token = $session_parts[0];
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	
	/*** Temporary user and permissions ***/
	$add_user_url = "/sql/adduser.html?user=addSQL&pass=ran325dom";
	$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_user_url;
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);
	$errorCount = 0;
	
		if ( str_replace("Sorry, that username is invalid", "", $result) != $result)
		{
			print "Sorry, can't create user to add sql structure ($db_name)<br />";
			$errorCount++;	
		}

		if ( str_replace("No username given", "", $result) != $result)
		{
			print "No username given for the sql structure part ($db_name)<br />";
			$errorCount++;
		}
		
		if ( str_replace("exists in the database!", "", $result) != $result)
		{
			print "The add sql user already exists (should not be an issue though)<br />";
		}
		
		// Add user permissions
		if ( $errorCount == 0 )
		{


			$add_auth_url = "/sql/addusertodb.html?user=addSQL&db={$db_name}&CREATE=CREATE&INSERT=INSERT";
			$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_auth_url;
			curl_setopt($curl, CURLOPT_URL, $url);
			$result = curl_exec($curl);
			if ( str_replace("You do not own the user", "", $result) != $result)
			{
				print "Adding the user priviliges for sql structure failed :(<br />";
				$errorCount++;
			}
		}
	
	// 2. Add SQL to database (with use_version)
	
		
	// 3. Insert Settings and Settings_questions
	// --> This step may be removed in the future
	// --> e.g. Login to account with no settings row, give the user a setup wizard
	//$venue_name_sql = $mysqli->real_escape_string($venue_name);
	
	// 4. Delete the new user which was only setup to create the database structure
	
	
	// success create user and priviliges?
	if( $errorCount == 0 )
		$output['success'] = true;

	return $output;
	
}

// http://ramui.com/articles/random-password-generator.html
function generate_password($length='')
{
         $str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_?/:(){}[]0123456789';
         $max=strlen($str);
         $length=@round($length);
         if(empty($length)){$length=rand(8,12);}
         $password='';
         for($i=0; $i<$length; $i++){$password.=$str{rand(0,$max-1)};}
         return $password;
}

/*** Create database user ***/
function create_assign_user_privileges($cpanel_user, $cpanel_password, $cpanel_host,$db_name)
{
	$output = array('success'=>false,'user_name'=>'','user_pass'=>'','admin_name'=>'','admin_pass'=>'');
	
	$download_url = "https://".$cpanel_host.":2083/login/";
	
	// get the cpanel security token.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_URL, $download_url);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$cpanel_user&pass=$cpanel_password");
	$result = curl_exec($curl);
	curl_close($curl);
	
	$parts = explode( 'URL=', $result);
	$session_parts = explode( '/frontend/', $parts[1]);
	$token = $session_parts[0];
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	
	/*** Standard user and permissions ***/
	// generate passwords, urls and run curl
	$db_userpass = generate_password(12);
	$add_user_url = "/sql/adduser.html?user={$db_name}&pass={$db_userpass}";
	$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_user_url;
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);
	$errorCount = 0;
	
		if ( str_replace("Sorry, that username is invalid", "", $result) != $result)
		{
			print "Sorry, that username is invalid ($db_name)<br />";
			$errorCount++;	
		}

		if ( str_replace("No username given", "", $result) != $result)
		{
			print "No username given ($db_name)<br />";
			$errorCount++;
		}
	
		// Add user permissions
		if ( $errorCount == 0 )
		{
			$add_auth_url = "/sql/addusertodb.html?user={$db_name}&db={$db_name}&INSERT=INSERT&SELECT=SELECT&UPDATE=UPDATE";
			$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_auth_url;
			curl_setopt($curl, CURLOPT_URL, $url);
			$result = curl_exec($curl);
			
			if ( str_replace("You do not own the user", "", $result) != $result)
			{
				print "Adding the standard user priviliges failed :(<br />";
				$errorCount++;
			}
			
			// No errors, save stuff
			if ( $errorCount == 0 )
			{
				$output['user_name'] = $db_name;
				$output['user_pass'] = $db_userpass;
			}
		}
		
	/*** Admin user and permissions ***/
	$db_adminpass = generate_password(12);
	$add_admin_url = "/sql/adduser.html?user={$db_name}a&pass={$db_adminpass}";
	$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_admin_url;
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);
	$adminErrorCount = 0;
	
		if ( str_replace("Sorry, that username is invalid", "", $result) != $result)
		{
			print "Sorry, that username is invalid ({$db_name}a)<br />";
			$adminErrorCount++;	
		}

		if ( str_replace("No username given", "", $result) != $result)
		{
			print "No username given ({$db_name}a)<br />";
			$adminErrorCount++;
		}
	
		// Add user permissions
		if ( $adminErrorCount == 0 )
		{
			$add_auth_url = "/sql/addusertodb.html?user={$db_name}a&db={$db_name}&ALTER=ALTER&DELETE=DELETE&INSERT=INSERT&SELECT=SELECT&UPDATE=UPDATE";
			$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_auth_url;
			curl_setopt($curl, CURLOPT_URL, $url);
			$result = curl_exec($curl);
			
			if ( str_replace("You do not own the user", "", $result) != $result)
			{
				print "Adding the admin user priviliges failed :(<br />";
				$adminErrorCount++;
			}
			
			// No errors, save stuff
			if ( $adminErrorCount == 0 )
			{
				$output['admin_name'] = $db_name."a";
				$output['admin_pass'] = $db_adminpass;
			}
		}

	// success create user and priviliges?
	if( $errorCount == 0 && $adminErrorCount == 0 )
		$output['success'] = true;


	return $output;
	
}

/*** Create a database with the prefix supplied ***/
// If zy0001 and zy0002 exist loop through until success
function create_database_prefix($cpanel_user,$cpanel_password,$cpanel_host,$prefix)
{
	$output = array('created'=>false,'name'=>'');
	
	$download_url = "https://".$cpanel_host.":2083/login/";
	
	// get the cpanel security token.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_URL, $download_url);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$cpanel_user&pass=$cpanel_password");
	$result = curl_exec($curl);
	curl_close($curl);
	
	$parts = explode( 'URL=', $result);
	$session_parts = explode( '/frontend/', $parts[1]);
	$token = $session_parts[0];
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		
	// Loop through and try to create databases 
	for( $i = 1 ; $i <= 9999 ; $i++ )
	{
		$errorCount = 0;						// Initialise for this loop
		$db_num = sprintf('%04d',$i);			// Padded to a length of four, 0 -> 9999
		
		//echo "--------<br />Trying $db_num<br />";
		
		$add_db_url = "/sql/addb.html?db={$prefix}$db_num";
		$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_db_url;
		curl_setopt($curl, CURLOPT_URL, $url);
		$result = curl_exec($curl);
			
		if ( str_replace("That database name already exists", "", $result) != $result)
		{
			//print "database already exits (".$cpanel_user."_".$prefix.$db_num.")<br />";
			$errorCount++;
		}
		
		if ( str_replace("is an invalid database name", "", $result) != $result)
		{
			//print "is an invalid database name (".$cpanel_user."_".$prefix.$db_num.")<br />";
			$errorCount++;
		}
		
		if ( $errorCount == 0 )
		{
			curl_close($curl);
			//print $result;
			//echo "database created";
			$output['created'] = true;
			$output['name'] = $cpanel_user."_".$prefix.$db_num;
			break;					// Database created, exit for loop now
		}
		
	}
	
	return $output;
	
}

/*** Create database with a supplied name ***/
function create_database($cpanel_user,$cpanel_password,$cpanel_host,$db_name)
{
	$download_url = "https://".$cpanel_host.":2083/login/";
	
	// get the cpanel security token.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_URL, $download_url);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$cpanel_user&pass=$cpanel_password");
	$result = curl_exec($curl);
	curl_close($curl);
	
	$parts = explode( 'URL=', $result);
	$session_parts = explode( '/frontend/', $parts[1]);
	$token = $session_parts[0];
	
	$add_db_url = "/sql/addb.html?db={$db_name}";

	$errorCount = 0;

	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_HEADER,1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		
	$url =  "https://$cpanel_user:$cpanel_password@$cpanel_host:2083$token/frontend/x3" . $add_db_url;
		
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);
		
//	echo "Result = $result<br />";
	
		if ( str_replace("That database name already exists", "", $result) != $result){
			print "database already exits";
			$errorCount++;
		}
				
		if ( str_replace("is an invalid database name", "", $result) != $result){
			print "is an invalid database name";
			$errorCount++;

		}
			

	curl_close($curl);
	//print $result;
	
	if ( $errorCount == 0 ) {
		echo "database created";
	}
}

?>