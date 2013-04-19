<?php 

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$email = $_POST['email'];

$db = new mysqli($host, $user, $pass, $database);

$query = "SELECT email FROM $table WHERE email like '" . $db->real_escape_string($email) . "'";

$result = $db->query($query);

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	echo("emailError");
}
else
{
	if($result->num_rows == 0)
		echo("ok");
	else
		echo("error");
}

$db->close();

?>