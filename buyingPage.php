<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('buying.phtml');
$parameters = array();

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";

$db = new mysqli($host, $user, $pass, $database);

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	$customer_ID = $_SESSION['customer_ID'];
	$sql = "SELECT SUM(quantity) as num_items FROM basket WHERE customer_ID = $customer_ID";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$num_items = $row['num_items'];
	if($num_items == NULL)
		$parameters['num_items'] = 0;
	else
		$parameters['num_items'] = $num_items;
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

$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE style.style_ID = $styleID";

$result = $db->query($query);

/* item_ID | style_ID | colour | item_size | price | stock | image_link | style_ID | description  | name  | department | type  | material | thumbnail_link */
$cloths = array();
while ($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['styleID'] = $row['style_ID'];
	$tmp['itemID'] = $row['item_ID'];
	$tmp['colour'] = $row['colour'];
	$tmp['item_size'] = $row['item_size'];
	$tmp['price'] = $row['price'];
	$tmp['stock'] = $row['stock'];
	$tmp['image_link'] = $row['image_link'];
	$tmp['desc'] = $row['description'];
	$tmp['name'] = $row['name'];
	$tmp['size'] = $row['item_size'];
	$tmp['thumbnail_link'] = $row['thumbnail_link'];
	$cloths[] = $tmp;
}
$parameters['item'] = $cloths[0];
$parameters['style_ID'] = $tmp['styleID'];

$db->close();


$colour_size = array();
$size_colour = array();

foreach($cloths as $item)
{
// 	print_r($item);
// 	echo("<BR><BR>");
	if(isset($colour_size[$item['colour']]))
	{
		if(!in_array($item['size'], $colour_size[$item['colour']]))
		{
			$colour_size[$item['colour']][] = $item['size'];
		}
	}
	else
	{
		$colour_size[$item['colour']] = array();
		$colour_size[$item['colour']][] = $item['size'];
	}

	if(isset($size_colour[$item['size']]))
	{
		if(!in_array($item['colour'], $size_colour[$item['size']]))
		{
			$size_colour[$item['size']][] = $item['colour'];
		}
	}
	else
	{
		$size_colour[$item['size']] = array();
		$size_colour[$item['size']][] = $item['colour'];
	}
}
// exit();

$parameters['colour_size'] = $colour_size;
$parameters['size_colour'] = $size_colour;

$template->display($parameters);
?>
