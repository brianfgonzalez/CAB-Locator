<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>CAB Locator</title>
<meta name="description" content="Site to simplify the process of locating the proper CAB file.">
<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.4/lumen/bootstrap.min.css" rel="stylesheet">
<!--- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" media="all"> --->
<link rel="stylesheet" type="text/css" href="css/main.css" media="all">
<script src="js/jquery-1.11.3.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container drop-shadow main">
	<div class="jumbotron">
	
<?php 
/*
print '
Name: '.$_POST['pdpname'].'<br />
URL: '.$_POST['googlelink'].'<br />
Model: '.$_POST['model'].'<br />
OS: '.$_POST['os'].'<br />
';
*/
$file = 'xml/cabs.xml';
$xmlObj = simplexml_load_file($file);
if ( is_object($xmlObj) && method_exists($xmlObj, 'children') )
{
		//print 'The CAB was there and I sucked it up!';
		//$cab = $xmlObj->command->create->children()->create->addChild('cab');
		$cab = $xmlObj->addChild('cab');
		$cab->addChild('name', xml_entities($_POST['pdpname']));
		$cab->addChild('model', xml_entities($_POST['model']));
		$cab->addChild('os', xml_entities($_POST['os']));
		$cab->addAttribute('link', xml_entities($_POST['googlelink']));
		$config = array(
								'indent'         => true,
								'output-xml'     => true,
								'input-xml'     => true);

		// Tidy
		$tidy = new tidy();
		$tidy->parseString($xmlObj, $config, 'utf8');
		$tidy->cleanRepair();
		echo tidy_get_output($tidy);
		$xmlObj->asXML($file);
		print '
<div class="alert alert-success">
	<strong>Success!</strong>, the record was added, <a href="index.html">now return</a> to see your handy work.
</div>
		';
}
else 
{
    		print '
<div class="alert alert-danger">
	<strong>Error!</strong>, the cabs.xml was not found, <a href="add.html">Go Back</a> and try again.
</div>
		';
}

function xml_entities($string) {
		$newstring = trim($string);
    return strtr(
        $newstring, 
        array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            "&" => "&amp;",
        )
    );
}
?>
	
	</div>
	<p class="emailtext"><span class="glyphicon glyphicon-envelope"></span>  Contact  <a href="mailto:imaging@us.panasonic.com">Imaging Support</a> for any further assistance.</p>
</div>
</body>
</html>