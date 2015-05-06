<?php

include 'xmlapi.php';

$ip = 'obee.local';
$account = "staff";
$password = "obee123";
$port="2083";



$xmlapi = new xmlapi($ip);
$xmlapi->password_auth($account,$password);

$xmlapi->set_debug(1);

$call = array("db"=>'database_name');

$createUser = array('dbuser'=>'julian', 'password'=>'aaa');

//$result = $xmlapi->api2_query($account, "MysqlFE", "createdb", $call);

//$result = $xmlapi->api2_query($account,"MysqlFE","listdbs");

$result = $xmlapi->api2_query($account,"MysqlFE","listusers");

//$xmlapi->api2_query($account, "MysqlFE", "createdbuser", $createUser);


//$result =  $xmlapi->api1_query($account, "Mysql", "adduserdb", array(
//              'db_name', 'julian', 'alter select update '
//));

//$flag = true;
//foreach($result as $key => $value)
//{
//
//    if($key == 'error')
//    {
//        $flag = false;
//    }
//}
//
//if($flag)
//{
//    echo 'valid';
//}
//else if(!$flag)
//{
//    echo 'invalid';
//}


//print_r($result);


foreach($result->children() as $r)
{
   foreach($r as $key=>$value)
    {
        if($key == 'user')
        {
            echo $value.'<br>';
        }
    }

}
?>
