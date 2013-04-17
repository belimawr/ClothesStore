<?php 

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$email = $_POST['email'];

$db = new mysqli($host, $user, $pass, $database);

$query = "SELECT email FROM $table WHERE email like '$email'";

$result = $db->query($query);

if($result->num_rows == 0)
	echo("ok");
else
	echo("error");

$db->close();

?>