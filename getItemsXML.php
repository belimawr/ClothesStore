<?php 
/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	if($_SESSION['security_level'] != "administrator")
	{
// 		header('Location: messagesPage.php?link=index.php&message=Please%20login&linkmessage=Go%20Back');
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
// 	header('Location: messagesPage.php?link=index.php&message=Please%20login&linkmessage=Go%20Back');
}

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);

$itemID = $_GET['item_ID'];

$sql = "SELECT * FROM item JOIN style ON style.style_ID = item.style_ID WHERE item_ID = $itemID ORDER BY name;";

$result = $db->query($sql);


/* | item_ID | style_ID | colour | item_size | price  | stock | image_link  | style_ID | description  | name | department | type  | thumbnail_link */
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<clothes>\n";
while($row = $result->fetch_array())
{
	$xml = $xml . "<item>\n";
	$xml = $xml . "<item_ID>" . $row['item_ID'] . "</item_ID>\n";
	$xml = $xml . "<item_size>" . $row['item_size'] . "</item_size>\n";
	$xml = $xml . "<price>" . $row['price'] . "</price>\n";
	$xml = $xml . "<stock>" . $row['stock'] . "</stock>\n";
	$xml = $xml . "<image_link>" . $row['image_link'] . "</image_link>\n";
	$xml = $xml . "<colour>" . $row['colour'] . "</colour>\n";
	$xml = $xml . "<name>" . $row['name'] . "</name>\n";
	$xml = $xml . "</item>\n";
}
$xml = $xml . "</clothes>";

echo($xml);

$db->close();


?>