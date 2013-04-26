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

$result = $db->query("SELECT item_size FROM item WHERE style_ID = $styleID AND colour LIKE '$colour'");

if(!$result)
{
	echo ("<script type='text/javascript'> alert('Sorry, your request cannot be processed, please try again later.');");
	echo ("window.location.href='index.php' </script>");
	exit();
}

$size = "";
while($row = $result->fetch_assoc())
{
	if(strlen($size) == 0)
	{
		$size = $row['item_size'];
	}
	else
	{
		$size = $size . "," . $row['item_size'];
	}
}

echo($size);


?>