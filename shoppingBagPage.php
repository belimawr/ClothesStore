<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('shoppingBag.phtml');
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

$sql = <<<SQL
	SELECT * FROM basket B JOIN item I JOIN style S ON I.item_ID = B.item_ID AND I.style_ID = S.style_ID
	WHERE B.customer_ID = $customer_ID
SQL;


$result = $db->query($sql);
$items = array();
/* | ID | customer_ID | item_ID | quantity | item_ID | style_ID | colour | item_size | price | stock | image_link  | style_ID | description | 
 * name | department | type | material | thumbnail_link*/
while($r = $result->fetch_assoc())
{
	$tmp = array();
	$tmp['itemID'] = $r['item_ID'];
	$tmp['quantity'] = $r['quantity'];
	$tmp['name'] = $r['name'];
	$tmp['price'] = $r['price']*$tmp['quantity'];
// 	$tmp['stock'] = $r['stock'];
	$tmp['image_link'] = $r['image_link'];
	$tmp['size'] = $r['item_size'];
	$tmp['colour'] = $r['colour'];
	$tmp['desc'] = $r['description'];

	if($r['stock'] > $r['quantity'])
		$tmp['aval'] = 1;
	else
		$tmp['aval'] = 0;
	$items[] = $tmp;
}

$parameters['items'] = $items;

$db->close();
$template->display($parameters);
?>
