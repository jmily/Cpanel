<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/10/2014
 * Time: 12:18 PM
 */


$file = "/var/sql/db.sql";

$sql_contents = file_get_contents($file);
$sql_contents = explode(";",$sql_contents);

$link = new mysqli('localhost','root','root','db_name');
 if(mysqli_connect_errno())
 {
     printf("Connect failed: %s\n",mysqli_connect_error());
 }

foreach($sql_contents as $query)
{
    $result = $link->query($query);
}