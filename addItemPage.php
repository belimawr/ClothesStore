<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('addItem.phtml');
$parameters = array();

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

$query = "SELECT * FROM style ORDER BY name";
$result = $db->query($query);

$styleID = array();
while ($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['id'] = $row['style_ID'];
	$tmp['name'] = $row['name'];
	$tmp['thumbnail_link'] = $row['thumbnail_link'];
	$styleID[] = $tmp;
}

$parameters['styleID'] = $styleID;

$db->close();

$template->display($parameters);
?>