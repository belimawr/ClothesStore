<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('buying.phtml');
$parameters = array();

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

$styleID = $_GET['styleID'];
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

$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE style.style_ID = $styleID";

$result = $db->query($query);
/* item_ID | style_ID | colour | item_size | price | stock | image_link | style_ID | description  | name  | department | type  | material | thumbnail_link */
$cloths = array();
while ($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['StyleID'] = $row['style_ID'];
	$tmp['itemID'] = $row['item_ID'];
	$tmp['colour'] = $row['colour'];
	$tmp['item_size'] = $row['item_size'];
	$tmp['price'] = $row['price'];
	$tmp['stock'] = $row['stock'];
	$tmp['image_link'] = $row['image_link'];
	$tmp['desc'] = $row['description'];
	$tmp['name'] = $row['name'];
	$tmp['thumbnail_link'] = $row['thumbnail_link'];
	$cloths[] = $tmp;
}
$parameters['cloths'] = $cloths;

$template->display($parameters);
?>
