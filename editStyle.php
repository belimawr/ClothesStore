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


/* | style_ID | description | name  | department | type     | thumbnail_link */
$styleID = $db->escape_string($_POST['styleID']);
$desc = $db->escape_string($_POST['desc']);
$type = $db->escape_string($_POST['type']);
$thumbnailLink = $db->escape_string($_POST['thumbnailLink']);
$department = $db->escape_string($_POST['department']);
$name = $db->escape_string($_POST['name']);

$query = <<<SQL
		UPDATE style
		SET description='$desc', type='$type', thumbnail_link='$thumbnailLink', department='$department', name='$name'
		WHERE style_ID = $styleID;
SQL;

$result = $db->query($query);
if($result)
	echo("OK");
else
	echo($db->error);

?>