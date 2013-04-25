<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('paymentDetails.phtml');
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
	SELECT * FROM customer JOIN address on address.address_ID = customer.address_ID
	WHERE customer.customer_ID = $customer_ID;	
SQL;

$result = $db->query($sql);

$row = $result->fetch_assoc();

$parameters['address1'] = $row['address_1'];
$parameters['address2'] = $row['address_2'];
$parameters['city'] = $row['city'];
$parameters['postcode'] = $row['postcode'];

$db->close();
$template->display($parameters);
?>
