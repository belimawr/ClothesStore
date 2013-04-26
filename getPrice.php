<?php 

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

$styleID = $_POST['styleID'];
$colour = $_POST['colour'];
$size = $_POST['size'];

$result = $db->query("SELECT price, image_link FROM item WHERE style_ID = $styleID AND colour LIKE '$colour' AND item_size LIKE '$size'");

if(!$result)
{
	echo ("<script type='text/javascript'> alert('Sorry, your request cannot be processed, please try again later.');");
	echo ("window.location.href='index.php' </script>");
	exit();
}

$row = $result->fetch_assoc();
$price = $row['price'];
$img = $row['image_link'];

echo($price . ";" . $img);


?>