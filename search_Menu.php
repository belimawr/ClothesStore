<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('search_Menu.phtml');

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
$depart = $_GET['depart'];
$type = $_GET['type'];

if($depart == "all")
	$query = "SELECT * FROM style";
else
{
	if($type != "any")
		$query = "SELECT * FROM style WHERE department like '$depart' and type like '$type'";
	else
		$query = "SELECT * FROM style WHERE department like '$depart'";
}

$result = $db->query($query);

$parameters = array();
$wcloths = array();
while ($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['id'] = $row['style_ID'];
	$tmp['desc'] = $row['name'];
	$tmp['src'] = $row['thumbnail_link'];
	$wcloths[] = $tmp;
}
$parameters['womensCloths'] = $wcloths;
$parameters['dep_query'] = $depart;
$parameters['type_query'] = $type;
$parameters['department'] = $_GET['department'];

$db->close();

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
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

$template->display($parameters);
?>
