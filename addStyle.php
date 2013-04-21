<?php 

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('addStyle.phtml');
$parameters = array();

/* Checks if exists a session */
session_start();
if(isset($_SESSION['uid']))
{
	$parameters['logged'] = 1;
	$parameters['username'] = $_SESSION['username'];
}
else
{
	$parameters['logged'] = 0;
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	$_SESSION = array();
}


$host = "s.tiago.eti.br";
$database = "StrathWEB_Store";
$user = "StrathWEB";
$pass = "2013StrathWEB";
$table = "style";

$db = new mysqli($host, $user, $pass, $database);

$name = $db->escape_string($_POST['name']);
$description = $db->escape_string($_POST['description']);
$department = $db->escape_string($_POST['department']);
$type = $db->escape_string($_POST['type']);
$material = $db->escape_string($_POST['material']);
$thumbnail_link = $db->escape_string($_POST['thumbnail']);

$query = <<<SQL
		INSERT INTO $table (style_ID, description, name, department, type, material, thumbnail_link)
		VALUES (null, '$description', '$name', '$department', '$type', '$material', '$thumbnail_link')
SQL;

$result = $db->query($query);

if($result)
{
	$parameters['success'] = 1;
	$parameters['name'] = $name;
}
else
{
	$parameters['success'] = 0;
}
$db->close();
$template->display($parameters);

?>