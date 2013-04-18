<?php 

require_once dirname(__FILE__).'/../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);

$template = $twig->loadTemplate('searchCloths.phtml');

$parameters = array();

$size_list = "";
if(isset($_POST['size_list']))
{
	foreach($_POST['size_list'] as $check)
	{
		$parameters[$check] = 1;
		if(strlen($size_list) == 0)
		{
			$size_list = "(item_size like '$check') ";
		}
		else
		{
			$size_list = $size_list . " or (item_size like '$check') ";
		}
	}
}
$colour_list = "";
if(isset($_POST['colour_list']))
{
	foreach($_POST['colour_list'] as $check)
	{
		$parameters[$check] = 1;
		if(strlen($colour_list) == 0)
		{
			$colour_list = "(colour like '$check') ";
		}
		else
		{
			$colour_list = $colour_list . " or (colour like '$check') ";
		}
	}
}

$dep_query = $_POST['dep_query'];
$type_query = $_POST['type_query'];

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

/* Create the query */
if($dep_query == "all")
	$string_Dept = " true";
else
{
	if($type_query != "any")
		$string_Dept = "((department like '$dep_query') and (type like '$type_query'))";
	else
		$string_Dept = "(department like '$dep_query')";
}

if( (strlen($colour_list) > 0) && (strlen($size_list) > 0))
{
	$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE ($colour_list or $size_list) and $string_Dept";
}
elseif(strlen($colour_list) > 0)
{
	$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE ($colour_list) and $string_Dept";
}
elseif(strlen($size_list) > 0)
{
	$query = "SELECT * FROM item JOIN style on item.style_ID = style.style_ID WHERE ($size_list) and $string_Dept";
}

/* Execute the query */
if(isset($query))
{
	$result = $db->query($query);

	/* item_ID | style_ID | colour | item_size | price | stock | image_link | style_ID | description  | name  | department | type  | material | thumbnail_link */
	while ($row = $result->fetch_assoc())
	{
		$tmp = array();
		$tmp['id'] = $row['style_ID'];
		$tmp['desc'] = $row['description'];
		$tmp['src'] = $row['image_link'];
		$tmp['price'] = $row['price'];
		$tmp['name'] = $row['name'];
		$tmp['size'] = $row['item_size'];
		$tmp['material'] = $row['material'];
		$tmp['thumbnail'] = $row['thumbnail_link'];
		$tmp['colour'] = $row['colour'];
		$wcloths[] = $tmp;
	}
}
if(isset($wcloths))
	$parameters['womensCloths'] = $wcloths;

$parameters['department'] = $_POST['department'];
$parameters['dep_query'] = $dep_query;
$parameters['type_query'] = $type_query;

$db->close();

$template->display($parameters);


?>