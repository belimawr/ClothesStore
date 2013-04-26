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

$styleID = $_GET['style_ID'];

$sql = "SELECT * FROM style WHERE style_ID = $styleID;";

$result = $db->query($sql);


/* | style_ID | description | name  | department | type     | thumbnail_link */
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<style>\n";
while($row = $result->fetch_array())
{
	$xml = $xml . "<item>\n";
	$xml = $xml . "<style_ID>" . $row['style_ID'] . "</style_ID>\n";
	$xml = $xml . "<description>" . $row['description'] . "</description>\n";
	$xml = $xml . "<name>" . $row['name'] . "</name>\n";
	$xml = $xml . "<department>" . $row['department'] . "</department>\n";
	$xml = $xml . "<type>" . $row['type'] . "</type>\n";
	$xml = $xml . "<thumbnail_link>" . $row['thumbnail_link'] . "</thumbnail_link>\n";
	$xml = $xml . "</item>\n";
}
$xml = $xml . "</style>";

echo($xml);

$db->close();


?>