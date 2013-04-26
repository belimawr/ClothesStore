<?php

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	$customerID = $_SESSION['customer_ID'];
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

$cardType = $db->escape_string($_POST['cardType']);
$cardNumber = $db->escape_string($_POST['cardnumber']);
$cardHolder = $db->escape_string($_POST['name']);
$cardSecCode = $db->escape_string($_POST['securitycode']);
$cardMonth = $db->escape_string($_POST['month']);
$cardYear = $db->escape_string($_POST['year']);

$address1 = $db->escape_string($_POST['address1']);
$address2 = $db->escape_string($_POST['address2']);
$city = $db->escape_string($_POST['city']);
$postcode = $db->escape_string($_POST['postcode']);
$userAddress = $db->escape_string($_POST['userAddress']);

/* Search the card, if it does not exist, add it */
$sql = <<<SQL
	SELECT * FROM card WHERE card_no='$cardNumber'
SQL;

$result = $db->query($sql);

if($result->num_rows != 0) /* There is a card */
{
	$row = $result->fetch_assoc();
	$cardID = $row['card_ID'];
}
else
{
	$sql = <<<SQL
		INSERT INTO card VALUES (null, '$cardType', '$cardNumber', '$cardSecCode', '$cardYear-$cardMonth-01', '$cardHolder', $customerID)
SQL;
	$result = $db->query($sql);
	if($result)
		$cardID = $db->insert_id;
	else
	{
		echo("mySQL error: " . $db->error);
	}
}

if($userAddress == "1" ) /* Use User's address */
{
	$sql = <<<SQL
			SELECT * FROM customer  WHERE customer_ID = $customerID
SQL;
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$addressID = $row['address_ID'];
}
else /* Add a new one */
{
	/* Search for the address in the database */
	$sql = <<<SQL
			SELECT * FROM address WHERE city LIKE '$city' AND address_1 LIKE '$address1' AND address_2 LIKE '$address2' AND postcode LIKE '$postcode'
SQL;
	$result = $db->query($sql);
	if($result->num_rows == 0) /* If there isn't a addres, add it*/
	{
		$sql = <<<SQL
		 INSERT INTO address
		 VALUES(null, '$address1', '$address2', '$city', '$postcode');
SQL;
		$db->query($sql);
		$addressID = $db->insert_id;
	}
	else
	{
		$row = $result->fetch_assoc();
		$addressID = $row['address_ID'];
	}
}
/*
 * Get all items from the basket:
 *  1 Calculate the total price;
 *  2 Create the order;
 *  3 Add them to the order_item;
 *  4 Delete all items from basket.
 */

/* Get all items */
$sql = <<<SQL
		SELECT * FROM basket JOIN item ON item.item_ID = basket.item_ID WHERE customer_ID = $customerID
SQL;

$result = $db->query($sql);

unset($row);

$items = array();
$total = 0.0;
/* | ID | customer_ID | item_ID | quantity | */
while($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['customer_ID'] = $row['customer_ID'];
	$tmp['item_ID'] = $row['item_ID'];
	$tmp['quantity'] = $row['quantity'];
	$tmp['price'] = $row['price'];
	$total += $tmp['price']*$tmp['quantity'];
	$items[] = $tmp;
}

/* Create an order */
$sql = <<<SQL
	INSERT INTO orders
	VALUES(null, null, 0, $total, $customerID, $cardID, $addressID)
SQL;
$result = $db->query($sql);
$orderID = $db->insert_id;

/* Add all items to order_items */
foreach($items as $item)
{
	$itID = $item['item_ID'];
	$itQuant = $item['quantity'];
	$sql = "INSERT INTO order_item VALUES ($orderID, $itID, $itQuant)";
	$result = $db->query($sql);
	if(!$result)
	{
		echo("mySQL error: " . $db->error);
		exit();
	}
}

/* Delete all items from basket */
// $sql = "DELETE FROM basket WHERE customer_ID = $customerID";
// $result = $db->query($sql);
// if(!$result)
// {
// 	echo("mySQL error: " . $db->error);
// 	exit();
// }

$db->close();

header("Location: reviewOrderPage.php?orderID=$orderID");
?>
