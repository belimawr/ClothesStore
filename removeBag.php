<?php
/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	$customer_ID = $_SESSION['customer_ID'];
}
else
{
	$parameters['logged'] = 0;
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	$_SESSION = array();
}
$itemid = $_POST['itemID'];

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);
$result = $db->query("DELETE FROM basket
					  WHERE item_ID = $itemid 
					  AND customer_ID= $customer_ID");
echo($db->error);
?>