<?php 

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$password = $_POST['password'];
$Fname = $_POST['Fname'];
$Lanme = $_POST['Lname'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$postcode = $_POST['postcode'];
$phone = $_POST['phone'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];


mysql_connect($host, $user, $pass);
mysql_select_db($database) or die(mysql_error());

// $db = new mysqli($host, $user, $pass, $database);

// if($db->connect_errno > 0
// {
// 	die('Unable to connect to database [' . $db->connect_error . ']');
// }

mysql_query(
		"insert into $table (customer_ID, password, name_f, name_l, address_1, address_2, city, postcode, phone, mobile, email)
		values(null, '$password', '$Fname' , '$Lanme', '$address1', '$address2', '$city', '$postcode', '$phone', '$mobile', '$email')") or die(mysql_error());

echo("Your username is: " . $email . " and your password is: " . $password);

?>