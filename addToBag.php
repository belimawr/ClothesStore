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

/*
 * Connect to database.
*/
$db = new mysqli($host, $user, $pass, $database);

/* Query the messages to the index page */
// $sql = ;
// $messages = $db->query($sql);
// while ($row = $messages->fetch_assoc())
// {
// }

if(!isset($_SESSION['customer_ID']))
{
	echo("You're not logged in, please login.");
}
else
{
	$size = $_POST['size'];
	$colour = $_POST['colour'];
	$styleID = $_POST['styleID'];

	/* Get the item_ID */
	$result = $db->query("SELECT item_ID FROM item WHERE colour='$colour' AND item_size='$size' AND style_ID='$styleID'");
	$row =  $result->fetch_assoc();
	$itemID = $row['item_ID'];

	/*
	 * Check if this item is in the basket,
	 * if true, update the quantity,
	 * otherwise insert in the table.
	 */
	$sql = "SELECT quantity FROM basket WHERE item_ID=$itemID AND customer_ID=$customer_ID";
	$result = $db->query($sql);
	if($result->num_rows == 0)
	{
		$sql = <<<SQL
		INSERT INTO basket (ID, customer_ID, item_ID, quantity) VALUES(null, $customer_ID, $itemID, 1)
SQL;
	}
	else
	{
		$r = $result->fetch_assoc();
		$quantity = $r['quantity'] + 1;
		$sql = <<<SQL
		UPDATE basket SET quantity=$quantity
		WHERE item_ID = $itemID AND customer_ID = $customer_ID
SQL;
	}

	if($result = $db->query($sql))
		echo($_POST['name'] . " added to basket!");
	else
		echo("An error occurred, please try again");
}

?>