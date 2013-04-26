<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('editStyle.phtml');
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

$sql = "SELECT * FROM style ORDER BY name";

$result = $db->query($sql);

$items = array();
/* | style_ID | description | name  | department | type     | thumbnail_link */
while($row = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['id'] = $row['style_ID'];
	$tmp['name'] = $row['name'];
	$tmp['department'] = $row['department'];
	$tmp['type'] = $row['type'];
	$tmp['thumbnail_link'] = $row['thumbnail_link'];
	$items[] = $tmp;
}

$parameters['items'] = $items;

$db->close();
$template->display($parameters);
?>
