<?php

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('index2.phtml');

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
$sql = "SELECT * FROM index_data";
$messages = $db->query($sql);
$parameters = array();
while ($row = $messages->fetch_assoc())
{
	$parameters[$row['msg_name']] = $row['msg'];
	$parameters[$row['img_name']] = $row['img'];
}

/* Query the images to the index slideShow */
$messages = $db->query("SELECT img, img_name FROM index_data WHERE img_name like 'slideShow'");
$images = array();
$i = 0;
while ($row = $messages->fetch_assoc())
{
	$images[$i++] = $row['img'];
}
$parameters['slideShowImages'] = $images;
$parameters['imgCount'] = $i;

//Using others variables to store data in a array
//It will be useful to read from the database and add
//the data to the array
// $vecKey = 'img4';
// $vecEl = "source images/Womens Jeans/wj3.jpg";

// $parameters[$vecKey] = $vecEl;
// print_r($parameters);
$template->display($parameters);
?>
