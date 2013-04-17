<?php 

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$email = $_POST['email'];

mysql_connect($host, $user, $pass);
mysql_select_db($database) or die(mysql_error());

$query = mysql_query("SELECT email FROM $table WHERE email like '$email'") or die(mysql_error());

if(mysql_num_rows($query) == 0)
	echo("ok");
else
	echo("error");

?>