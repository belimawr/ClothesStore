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

$result = $db->query("SELECT colour, item_size FROM item WHERE style_ID = $styleID");

if(!$result)
{
	echo ("<script type='text/javascript'> alert('Sorry, your request cannot be processed, please try again later.');");
	echo ("window.location.href='index.php' </script>");
	exit();
}

$colour = "";
$size = "";
while($row = $result->fetch_assoc())
{
	if(strlen($colour) == 0)
	{
		$colour =$row['colour'];
		$size = $row['item_size'];
	}
	else
	{
		$colour = $colour . "," . $row['colour'];
		$size = $size . "," . $row['item_size'];
	}
}

echo($colour . ";" . $size);


?>