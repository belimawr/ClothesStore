<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('paymentDetails.phtml');
$parameters = array();

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
	$customerID = $_SESSION['customer_ID'];
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

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);

$sql = <<<SQL
	SELECT * FROM customer JOIN address on address.address_ID = customer.address_ID
	WHERE customer.customer_ID = $customerID;	
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
