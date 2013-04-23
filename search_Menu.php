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
	$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID";
else
{
	if($type != "any")
		$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE department like '$depart' and type like '$type'";
	else
		$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE department like '$depart'";
}

$result = $db->query($query);

$parameters = array();
$wcloths = array();
/* item_ID | style_ID | colour | item_size | price | stock | image_link | style_ID | description  | name  | department | type  | material | thumbnail_link */
while ($row = $result->fetch_assoc())
{
		$tmp = array();
		$tmp['style_ID'] = $row['style_ID'];
		$tmo['item_ID'] = $row['item_ID']; 
		$tmp['desc'] = $row['description'];
		$tmp['src'] = $row['image_link'];
		$tmp['price'] = $row['price'];
		$tmp['name'] = $row['name'];
		$tmp['size'] = $row['item_size'];
		$tmp['material'] = $row['material'];
		$tmp['thumbnail'] = $row['image_link'];
		$tmp['colour'] = $row['colour'];
		$wcloths[] = $tmp;
}
$parameters['womensCloths'] = $wcloths;
$parameters['dep_query'] = $depart;
$parameters['type_query'] = $type;
$parameters['department'] = $_GET['department'];

// $colour_size = array();
// $size_colour = array();

// foreach($wcloths as $item)
// {
// 	if(isset($colour_size[$item['colour']]))
// 	{
// 		if(!in_array($item['size'], $colour_size[$item['colour']]))
// 		{
// 			$colour_size[$item['colour']][] = $item['size'];
// 		}
// 	}
// 	else
// 	{
// 		$colour_size[$item['colour']] = array();
// 		$colour_size[$item['colour']][] = $item['size'];
// 	}
	
// 	if(isset($size_colour[$item['size']]))
// 	{
// 		if(!in_array($item['colour'], $size_colour[$item['size']]))
// 		{
// 			$size_colour[$item['size']][] = $item['colour'];
// 		}
// 	}
// 	else
// 	{
// 		$size_colour[$item['size']] = array();
// 		$size_colour[$item['size']][] = $item['colour'];
// 	}
// }


// $parameters['size_colour'] = $colour_size;
// $parameters['colour_size'] = $size_colour;

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
