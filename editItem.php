<?php 

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	if($_SESSION['security_level'] != "administrator")
	{
		header('Location: messagesPage.php?link=index.php&message=Please%20login&linkmessage=Go%20Back');
	}
}
else
{
	$parameters['logged'] = 0;
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	$_SESSION = array();
	header('Location: messagesPage.php?link=index.php&message=Please%20login&linkmessage=Go%20Back');
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


// item_ID | style_ID | colour | item_size | price  | stock | image_link
$itemID = $db->escape_string($_POST['itemID']);
$colour = $db->escape_string($_POST['colour']);
$item_size = $db->escape_string($_POST['item_size']);
$price = $db->escape_string($_POST['price']);
$stock = $db->escape_string($_POST['stock']);
$image_link = $db->escape_string($_POST['image_link']);

$query = <<<SQL
		UPDATE item
		SET colour='$colour', item_size='$item_size', price=$price, stock=$stock, image_link='$image_link'
		WHERE item_ID = $itemID
SQL;

$result = $db->query($query);
if($result)
	echo("OK");
else
	echo($db->error);

?>