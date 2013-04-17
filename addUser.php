<?php 

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);

$password = $db->escape_string($_POST['password']);
$Fname = $db->escape_string($_POST['Fname']);
$Lanme = $db->escape_string($_POST['Lname']);
$address1 = $db->escape_string($_POST['address1']);
$address2 = $db->escape_string($_POST['address2']);
$city = $db->escape_string($_POST['city']);
$postcode = $db->escape_string($_POST['postcode']);
$phone = $db->escape_string($_POST['phone']);
$mobile = $db->escape_string($_POST['mobile']);
$email = $db->escape_string($_POST['email']);

if($db->connect_errno > 0)
{
	die('Unable to connect to the database $database at $host, error: [' . $db->connect_error . ']');
}

$myquery = <<<SQL
		 INSERT INTO
		 $table (customer_ID, password, name_f, name_l, address_1, address_2, city, postcode, phone, mobile, email)
		 VALUES
		 (null, '$password', '$Fname' , '$Lanme', '$address1', '$address2', '$city', '$postcode', '$phone', '$mobile', '$email')
SQL;
$result = null;
if(!$result = $db->query($myquery))
{
	die('There was an error running the query [' . $db->error . ']');
}

echo("Your username is: " . $email . " and your password is: " . $password);
$db->close();
?>