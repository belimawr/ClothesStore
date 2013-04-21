<?php
header('Content-Type: text/html; charset=utf-8');

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('messages.phtml');
$parameters = array();

function validadeHashedPassword($pass, $hashed)
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

	/* Put the salt at the beggining, so I can find it later */
	$hash = $salt . $hash;

	/* Return if it's equal or not to the stored password */
	return $hash == $hashed;
}

$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "customer";

$db = new mysqli($host, $user, $pass, $database);

$email = $_POST['usr'];
$password = $db->escape_string($_POST['password']);
$admin = FALSE;

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	$query = "SELECT * FROM admin WHERE username like '$email'";
	$admin = TRUE;
}
else
{
	$query = "SELECT * FROM $table WHERE email like '$email'";
}

$result = $db->query($query);

if($result->num_rows == 0) //Erro! User not registered.
{
	$parameters['message'] = "Invalid email/password.";
	$parameters['link'] = "index.php";
	$parameters['linkMessage'] = "Go back";
	$template->display($parameters);
}
else
{
	$row = $result->fetch_assoc();
	/* Check password */
	if(validadeHashedPassword($password, $row['password']))
	{
		if($admin == TRUE)
		{
			session_start();
			session_regenerate_id(true);
			$_SESSION['uid'] = session_id();
			$_SESSION['username'] = $row['username'];
			header( 'Location: admin.php' );
		}
		else 
		{
			session_start();
			session_regenerate_id(true);
			$_SESSION['uid'] = session_id();
			$_SESSION['username'] = $row['name_f'] . " " . $row['name_l'];
			header( 'Location: index.php' );
		}
	}
	else
	{
		$parameters['message'] = "Invalid email/password.";
		$parameters['link'] = "index.php";
		$parameters['linkMessage'] = "Go back";
		$template->display($parameters);
	}
}
?>
