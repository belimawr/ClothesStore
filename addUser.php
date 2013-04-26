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

if($password == "" || $Fname == "" || $Lanme == "" || $address1 == "" || $address2 == "" || $city == "" || $postcode == "" || $phone == "" || $mobile == "" || $email == "")
{
	echo ("<script type='text/javascript'> alert('All fields are required!');");
	echo ("window.location.href='addUserPage.php' </script>");
	exit();
}

$emailCheckSQL = "SELECT * FROM $table where email = '$email'";

$emailCheck = $db->query($emailCheckSQL);

if ($row = $emailCheck->fetch_assoc())
{
	echo ("<script type='text/javascript'> alert('This email is already registered');");
	echo ("window.location.href='addUserPage.php' </script>");
	exit();
}

$db->autocommit(false);

$addAdd = <<<SQL
		 INSERT INTO address
		 VALUES(null, '$address1', '$address2', '$city', '$postcode');
SQL;

$adduser = <<<SQL
		 INSERT INTO
		 $table
		 VALUES
		 (null, '$password', '$Fname' , '$Lanme', '$phone', '$mobile', '$email', LAST_INSERT_ID())
SQL;
$result = null;
if(!$result = $db->query($addAdd))
{
	echo($addAdd."<BR><BR>");
	die('There was an error running the query [' . $db->error . ']');
}

$result = null;
if(!$result = $db->query($adduser))
{
	echo($adduser."<BR><BR>");
	die('There was an error running the query [' . $db->error . ']');
}

$db->commit();

 echo ("<script type='text/javascript'> alert('User $email successfuly registred!');");
 echo ("window.location.href='index.php' </script>");

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

?>
