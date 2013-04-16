<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('Womenjacket.phtml');

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

mysql_connect($host, $user, $pass);
mysql_select_db($database) or die(mysql_error());

/* Query the messages to the index page */
$depart = $_GET['depart'];
$type = $_GET['type'];
$cloths = mysql_query("SELECT style_ID, description, department, type, thumbnail_link FROM style WHERE department like '$depart' and type like '$type'") or die(mysql_error());
$parameters = array();
$wcloths = array();
while ($row = mysql_fetch_array($cloths))
{
	$tmp = array();
	$tmp['id'] = $row['style_ID'];
	$tmp['desc'] = $row['description'];
	$tmp['src'] = $row['thumbnail_link'];
	$tmp['price'] = 100;
	$wcloths[] = $tmp;
}
$parameters['womensCloths'] = $wcloths;
// $parameters['department'] = "Coats & Jackets";
$parameters['department'] = $_GET['department'];


$template->display($parameters);
?>
