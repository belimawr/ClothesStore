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

// item_ID | style_ID | colour | item_size | price  | stock | image_link
$style_ID = $db->escape_string($_POST['style_ID']);
$colour = $db->escape_string($_POST['colour']);
$item_size = $db->escape_string($_POST['item_size']);
$price = $db->escape_string($_POST['price']);
$stock = $db->escape_string($_POST['stock']);
$image_link = $db->escape_string($_POST['image_link']);

$query = <<<SQL
		INSERT INTO item (item_ID, style_ID, colour, item_size, price, stock, image_link)
		VALUES (null, '$style_ID', '$colour', '$item_size', '$price', '$stock', '$image_link')
SQL;

$result = $db->query($query);

if($result)
{
	$parameters['success'] = 1;
}
else
{
	$parameters['success'] = 0;
}
$db->close();
$template->display($parameters);

?>