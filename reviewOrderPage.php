<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('reviewOrder.phtml');
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

$orderID = $_GET['orderID'];
$customerID = $_SESSION['customer_ID'];
$result = $db->query("SELECT colour, item_size,price,name,quantity
			FROM item,style,order_item,orders
			WHERE orders.order_ID=$orderID
			AND item.style_ID = style.style_ID
			AND order_item.item_ID= item.item_ID
			AND order_item.order_ID = orders.order_ID");

$items = array();

while ($row = $result->fetch_assoc())
{
	$item = array();
	$item['colour'] = $row['colour'];
	$item['size'] = $row['item_size'];
	$item['price']= $row['price'];
	$item['name']= $row['name'];
	$item['quantity']= $row['quantity'];
	$items[] = $item;
}

$parameters['items'] = $items;

$deliveryResult = $db->query("SELECT name_f, name_l, address.address_1, address.address_2, address.city, address.postcode
                             FROM customer,address
			     WHERE customer.customer_ID=$customerID
				AND customer.address_ID=address.address_ID");

$deliveryAddress = array();
$row = $deliveryResult->fetch_assoc();
$deliveryAddress['line1'] = $row['address_1'];
$deliveryAddress['line2'] = $row['address_2'];
$deliveryAddress['city'] = $row['city'];
$deliveryAddress['postcode'] = $row['postcode'];

$parameters['deliveryAddress'] = $deliveryAddress;

$paymentResult= $db->query("SELECT card.card_ID,card_type,sec_code,expiry,card_no 
				FROM card, orders
				WHERE orders.order_ID=$orderID
				AND card.card_ID=orders.card_ID");

$payment= array();
$row = $paymentResult->fetch_assoc();
$payment['card_ID'] = $row['card_ID'];
$payment['card_no'] = $row['card_no'];
$payment['sec_code']= $row['sec_code'];
$payment['expiry']= $row['expiry'];
$payment['card_type']= $row['card_type'];

$parameters['payment'] = $payment;

$total=0.00;
foreach($items as $item){
	$total = $total + ($item['price'] * $item['quantity']);
}

$parameters['total'] = $total;

$template->display($parameters);


?>
