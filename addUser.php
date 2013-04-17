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

$password = hashPassword($password, $email);

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

// echo("<BR><BR>");

// echo(hashPassword($password, $email));

// echo("<BR<BR>");

// echo(validadeHashedPassword($password, $email, hashPassword($password, $email)));
$db->close();

function hashPassword($pass, $user)
{
	/* Generetes a random and secure 256 bit (64 characteres) */
	$salt = hash('sha256', uniqid(mt_rand(), true) . 'one random part here' . strtolower($user));
	
	/* prefix the hash with the salt */
	$hash = $salt . $pass;
	
	/* Hash the password a bunch of times */
	for($i = 0; $i < 10000; $i++)
	{
		$hash = hash('sha256', $hash);
	}
	
	/* Put the salt at the beggining, so I can find it later */
	$hash = $salt . $hash;
	
	return $hash;
}

function validadeHashedPassword($pass, $user, $hashed)
{
	/* Get the salt */
	$salt = substr($hashed, 0, 64);
	
	/* prefix the hash with the salt */
	$hash = $salt . $pass;
	
	/* Hash the password a bunch of times */
	for($i = 0; $i < 10000; $i++)
	{
	$hash = hash('sha256', $hash);
	}
	
	/* put the salt at the beggining again */
	$hash = $salt . $hash;
	
	/* Return if it's equal or not to the stored password */
	return $hash == $hashed;
}
?>