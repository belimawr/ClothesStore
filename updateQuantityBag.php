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

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);

$itemid = $db->escape_string($_POST['itemID']);
$quantity = $db->escape_string($_POST['quantity']);

$result = $db->query("UPDATE basket
					  SET quantity=$quantity
					  WHERE item_ID = $itemid 
					  AND customer_ID= $customer_ID");
echo($db->error);

$query = "SELECT price FROM item WHERE item_ID = $itemid";

$result = $db->query($query);

$row = $result->fetch_assoc();

echo($row['price']);


$db->close();
?>