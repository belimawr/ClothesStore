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

/*
 * Complete order.
 */
$result = $db->query("UPDATE orders
			SET completed=1
			WHERE order_ID=$orderID") or die(mysql_error());

/*
 * Reduce quantity of item in stock.
 */
$result = $db->query("SELECT item_ID, quantity
				FROM order_item
				WHERE order_ID=$orderID") or die(mysql_error());
while ($row = $result->fetch_assoc())
{
	$quant = $row['quantity'];
	$item = $row['item_ID'];
	$reply = $db->query("UPDATE item
				SET stock=stock-$quant
				WHERE item_ID=$item") or die(mysql_error());
}

/*
 * Delete customer's basket.
 */
$result = $db->query("DELETE FROM basket 
				WHERE customer_ID=$customer_ID") or die(mysql_error());

$db->close();

?>
