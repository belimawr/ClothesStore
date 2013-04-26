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
$size = $_POST['size'];

$result = $db->query("SELECT colour FROM item WHERE style_ID = $styleID AND item_size LIKE '$size'");

if(!$result)
{
	echo ("<script type='text/javascript'> alert('Sorry, your request cannot be processed, please try again later.');");
	echo ("window.location.href='index.php' </script>");
	exit();
}

$colour = "";
while($row = $result->fetch_assoc())
{
	if(strlen($colour) == 0)
	{
		$colour = $row['colour'];
	}
	else
	{
		$colour = $size . "," . $row['colour'];
	}
}

echo($colour);


?>