<?php

require_once 'functions.php';
//create_database("db_name","obee123","local.obee.com.au","");
$user = create_assign_user_privileges("db_name", "obee123", "local.obee.com.au","db_name");

$db_name = "db_name";
$cpanel_password = "obee123";
$cpanel_host = "local.obee.com";
$cpanel_user = "staff";

database_structure_sql($cpanel_user, $cpanel_password, $cpanel_host,$db_name);

//echo "std user = {$user['user_name']}, pass = {$user['user_pass']}<br />";
//echo "admin user = {$user['admin_name']}, pass = {$user['admin_pass']}<br />";

?>