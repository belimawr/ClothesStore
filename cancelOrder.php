<?php
$parameters = array();

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

/*
 * Database data
*/
$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";

$orderID = $_POST['orderID'];

/*
 * Connect to database.
*/
$db = new mysqli($host, $user, $pass, $database);

$sql = "DELETE FROM order_item WHERE order_item.order_ID = $orderID";
$sql1 = "DELETE FROM orders WHERE orders.order_ID = $orderID;";
$sql2 = "DELETE FROM basket WHERE basket.customer_ID = $customer_ID;";

$result = $db->query($sql);
echo ($db->error);

$result = $db->query($sql2);
echo ($db->error);
$result = $db->query($sql1);

echo ($db->error);
$db->close();


?>
