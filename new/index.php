<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>    
    
<?php 

	/* Functions and Settings */
	require 'functions.php';
	require_once '../../../z/cpanel-config.php';
	
	// What is the latest version to setup?
	$use_version = "";
	// Get list of available versions
	if( is_dir('versions') )
	{
		$versions_available = scandir('versions');
		foreach( $versions_available as $key => $value )
		{
			if( $value != "." || $value != ".." )
			{
				$version1 = ltrim($use_version,'v');
				$version2 = ltrim($value,'v');
				if( version_compare($version2, $version1) > 0 )
					$use_version = $value;
			}
		}
	}

	/*** GET AND POST ***/
	if( isset($_POST['create']) )
	{
		echo "Creating account...<br />";
		echo "going to use version $use_version<br />";

		$use_account_url = "../../".$_POST['account-url'];
		
		// Step 1. Check that folder path doesn't already exist
		if( !is_dir($use_account_url) )
		{
			echo "account path: obee.com.au/{$_POST['account-url']} is OK, continuing...<br />";
			
			// Step 2. Create database
			$db = create_database_prefix($cpanel_user, $cpanel_password, $cpanel_host,'zy');

			// If the database was created (and we have the name)
			echo "db created = {$db['created']}, name = {$db['name']}<br />";
			if( $db['created'] && !empty($db['name']) )
			{			
				// Step 3. Create users, add to database and set permissions
				$user = create_assign_user_privileges($cpanel_user, $cpanel_password, $cpanel_host,$db['name']);
				
				echo "std user = {$user['user_name']}, pass = {$user['user_pass']}<br />";
				echo "admin user = {$user['admin_name']}, pass = {$user['admin_pass']}<br />";
				if( $user['success'] )
				{
					// Step 4. Add SQL Structure to database
					$struc = database_structure_sql($cpanel_user, $cpanel_password, $cpanel_host,$db['name'],$use_version,$_POST['venue-name']);
					
					echo "struc success = {$struc['success']}<br />";
					echo "struc reason = {$struc['reason']}<br />";
					
					// Step 5. Unzip apps folder
					
					// Step 6. Unzip public_html folder
					
					// Step 7. generate and upload userconfig.php and adminconfig.php to apps folder
					
					// Step 8. generate and upload .htaccess files
					
					// Step 9. Add htpasswd and htgroup to .htpasswds folder
					
					// Step 10. Add new database to restaurants.php (cron, smsgloblal and monthly report)
					
					
				}
				else
				{
					echo "Sorry, trouble creating user and assiging privileges<br />";
				}
			}
			else 
			{
				echo "Sorry, no database could be created<br />";
			}
		}
		else
		{
			echo "Sorry, obee.com.au/{$_POST['account-url']} is already in use. STOPPED<br />";
		}

	}
	
	
	/*************** need to email some stuff to support@obee.com.au (or owen@obee.com.@author Owner
	/*************** e.g the database usernames/passwords etc to put on file
	
	/*** END GET AND POST ***/

?>    
    
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

		<div id="content">
	
			<form method="post" name="new-account" action="?">
	
		        <h1>Create a new obee account</h1>
		
				<table>
					<tbody>
						<tr>
							<th>Venue Name</th>
							<td><input type="text" name="venue-name" id="venue-name" autocomplete="off" /></td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Venue Email</th>
							<td><input type="text" name="venue-email" id="venue-email" autocomplete="off" /></td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Account Url</th>
							<td><input type="text" name="account-url" id="account-url" autocomplete="off" /></td>
							<td class="hint">obee.com.au/<span id="example-url"></span><span id="example-url-ok"></span></td>
						</tr>
						<tr>
							<th>Timezone</th>
							<td>
								<select name="timezone" id="timezone">
									<option value="">Please select</option>
									<option value="Australia/ACT">ACT</option>	
									<option value="Australia/North">Northern Territory</option>
								 	<option value="Australia/NSW">NSW</option>
									<option value="Australia/Queensland">Queensland</option>
									<option value="Australia/South">South Australia</option>
									<option value="Australia/Tasmania">Tasmania</option>
									<option value="Australia/Victoria">Victoria</option> 	
									<option value="Australia/West">Western Australia</option>
								</select>
							</td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Logo</th>
							<td><input type="file" name="file" id="file"></td>
							<td class="hint">280 x 115px</td>
						</tr>
					</tbody>
				</table>
				
				<br />
					
				<h3>Referral</h3>
				
				<table>
					<tbody>
						<tr>
							<th>Your name</th>
							<td><input type="text" name="referral-name" id="referral-name" /></td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Your email</th>
							<td><input type="text" name="referral-email" id="referral-email" /></td>
							<td class="hint">you will be cc'd in to the restaurants welcome email</td>
						</tr>
					</tbody>
				</table>
	
				<br />
	
				<input type="submit" value="Create Account" name="create"  class="green-top-down create-new" />
	
				
	
				<h3>Optional <span class="tip">(any fields left blank will be randomly generated)</span></h3>
				
				
				
				<h4>Managers login details</h4>
				
				<table>
					<tbody>
						<tr>
							<th>Username</th>
							<td><input type="text" name="venue-name" /></td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Password</th>
							<td><input type="text" name="venue-name" /></td>
							<td class="hint"></td>
						</tr>
					</tbody>
				</table>
				
				<br />
				
				<h4>Staff login details</h4>
				
				<table>
					<tbody>
						<tr>
							<th>Username</th>
							<td><input type="text" name="venue-name" /></td>
							<td class="hint"></td>
						</tr>
						<tr>
							<th>Password</th>
							<td><input type="text" name="venue-name" /></td>
							<td class="hint"></td>
						</tr>
					</tbody>
				</table>
			
				
				
				<br />
				<input type="submit" value="Create Account" name="create" class="green-top-down create-new" />													
			
			</form>

		</div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-7021908-4'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
