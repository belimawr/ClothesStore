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
	$query = "SELECT style_ID, description, department, type, thumbnail_link FROM style";
else
{
	if($type != "any")
		$query = "SELECT style_ID, description, department, type, thumbnail_link FROM style WHERE department like '$depart' and type like '$type'";
	else
		$query = "SELECT style_ID, description, department, type, thumbnail_link FROM style WHERE department like '$depart'";
}

$result = $db->query($query);

$parameters = array();
$wcloths = array();
while ($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['id'] = $row['style_ID'];
	$tmp['desc'] = $row['description'];
	$tmp['src'] = $row['thumbnail_link'];
	$tmp['price'] = 100;
	$wcloths[] = $tmp;
}
$parameters['womensCloths'] = $wcloths;
$parameters['dep_query'] = $depart;
$parameters['type_query'] = $type;
$parameters['department'] = $_GET['department'];

$db->close();

$template->display($parameters);
?>
