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

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

if($_GET['action']=="add") {		
	// First delete the old entry from cabs.xml
	$file = 'xml/cabs.xml';
	if (file_exists($file)) {
		$xmlObj = simplexml_load_file($file);
	} else {
		exit('Failed to open cabs.xml.');
	}
	//Use XPath to find target node for removal
	$target = $xmlObj->xpath('//cab[@name="'.$_POST['newpdpname'].'"]');

	//If target exist
	if($target) {
		//Import simpleXml reference into Dom & do removal (removal occurs in simpleXML object)
		$domRef = dom_import_simplexml($target[0]); //Select position 0 in XPath array
		$domRef->parentNode->removeChild($domRef);		
	}
	//Format XML to save indented tree rather than one line and save
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xmlObj->asXML());
	$dom->save($file);


	$file = 'xml/cabs.xml';
	if (file_exists($file)) {
		$xmlObj = simplexml_load_file($file);
	} else {
		exit('Failed to open cabs.xml.');
	}
	$config = array(
							'indent'=> true,
							'output-xml'=> true,
							'add-xml-space'=> true,
							'input-xml'=> true);

	// Tidy
	$tidy = new tidy();


	$cab = $xmlObj->addChild('cab');
	$cab->addAttribute('name', xml_entities($_POST['newpdpname']));
	if(!is_bool(strpos($_POST['newmodel'], ','))) {
		$modelarray = explode(',',$_POST['newmodel']);
		foreach($modelarray as $model) {
			$cab->addChild('model', xml_entities($model));			
		}
	} else {
		$cab->addChild('model', xml_entities($_POST['newmodel']));		
	}
	$cab->addChild('os', xml_entities($_POST['newos']));
	$link = $cab->addChild('link', xml_entities($_POST['newlink1']));
	$link->addAttribute('type', xml_entities($_POST['newlinktype1']));
	if( isset($_POST['newlink2']) ) {
			$link2 = $cab->addChild('link', xml_entities($_POST['newlink2']));
			$link2->addAttribute('type', xml_entities($_POST['newlinktype2']));
			} 
	if( isset($_POST['newlink3']) ) {
			$link3 = $cab->addChild('link', xml_entities($_POST['newlink3']));
			$link3->addAttribute('type', xml_entities($_POST['newlinktype3']));
			}
	if( isset($_POST['newlink4']) ) {
			$link4 = $cab->addChild('link', xml_entities($_POST['newlink4']));
			$link4->addAttribute('type', xml_entities($_POST['newlinktype4']));
			} 
	if( isset($_POST['newlink5']) ) {
			$link5 = $cab->addChild('link', xml_entities($_POST['newlink5']));
			$link5->addAttribute('type', xml_entities($_POST['newlinktype5']));
			} 
	$tidy->repairString($xmlObj, $config, 'utf8');
	$tidy->cleanRepair();
	echo tidy_get_output($tidy);
	$xmlObj->asXML($file);
	print '
	<div class="alert alert-success">
		<strong>Success!</strong>, the record was added, <a href="index.html">now return</a> to see your handy work.
	</div>';
} elseif ($_GET['action']=="delete") {
	$file = 'xml/cabs.xml';
	if (file_exists($file)) {
		$xmlObj = simplexml_load_file($file);
	} else {
		exit('Failed to open cabs.xml.');
	}
	//Use XPath to find target node for removal
	$target = $xmlObj->xpath('//cab[@name="'.$_POST['delcabname'].'"]');

	//If target does not exist (already deleted by someone/thing else), halt
	if(!$target)
	return; //Returns null

	//Import simpleXml reference into Dom & do removal (removal occurs in simpleXML object)
	$domRef = dom_import_simplexml($target[0]); //Select position 0 in XPath array
	$domRef->parentNode->removeChild($domRef);

	//Format XML to save indented tree rather than one line and save
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xmlObj->asXML());
	$dom->save($file);
	print '
	<div class="alert alert-success">
		<strong>Success!</strong>, the record was deleted, <a href="index.html">now return</a> to see your handy work.
	</div>';
} elseif ($_GET['action']=="update") {
	// First delete the old entry from cabs.xml
	$file = 'xml/cabs.xml';
	if (file_exists($file)) {
		$xmlObj = simplexml_load_file($file);
	} else {
		exit('Failed to open cabs.xml.');
	}
	//Use XPath to find target node for removal
	$target = $xmlObj->xpath('//cab[@name="'.$_POST['origcabname'].'"]');

	//If target does not exist (already deleted by someone/thing else), halt
	if(!$target)
	return; //Returns null

	//Import simpleXml reference into Dom & do removal (removal occurs in simpleXML object)
	$domRef = dom_import_simplexml($target[0]); //Select position 0 in XPath array
	$domRef->parentNode->removeChild($domRef);

	//Format XML to save indented tree rather than one line and save
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xmlObj->asXML());
	$dom->save($file);

	$file = 'xml/cabs.xml';
	if (file_exists($file)) {
		$xmlObj = simplexml_load_file($file);
	} else {
		exit('Failed to open cabs.xml.');
	}		
	$cab = $xmlObj->addChild('cab');
	$cab->addAttribute('name', xml_entities($_POST['updpdpname']));
	if(!is_bool(strpos($_POST['updmodel'], ','))) {
		$modelarray = explode(',', $_POST['updmodel']);
		foreach($modelarray as $model) {
			$cab->addChild('model', xml_entities($model));			
		}
	} else {
		$cab->addChild('model', xml_entities($_POST['updmodel']));		
	}
	$cab->addChild('os', xml_entities($_POST['updos']));
	$link = $cab->addChild('link', xml_entities($_POST['updlink1']));
	$link->addAttribute('type', xml_entities($_POST['updlinktype1']));
	if( isset($_POST['updlink2']) ) {
			$link2 = $cab->addChild('link', xml_entities($_POST['updlink2']));
			$link2->addAttribute('type', xml_entities($_POST['updlinktype2']));
			}
	if( isset($_POST['updlink3']) ) {
			$link3 = $cab->addChild('link', xml_entities($_POST['updlink3']));
			$link3->addAttribute('type', xml_entities($_POST['updlinktype3']));
			}
	if( isset($_POST['updlink4']) ) {
			$link4 = $cab->addChild('link', xml_entities($_POST['updlink4']));
			$link4->addAttribute('type', xml_entities($_POST['updlinktype4']));
			} 
	if( isset($_POST['updlink5']) ) {
			$link5 = $cab->addChild('link', xml_entities($_POST['updlink5']));
			$link5->addAttribute('type', xml_entities($_POST['updlinktype5']));
			}
	$config = array(
							'indent'=> true,
							'output-xml'=> true,
							'add-xml-space'=> true,
							'input-xml'=> true);

	// Tidy
	$tidy = new tidy();
	$tidy->repairString($xmlObj, $config, 'utf8');
	$tidy->cleanRepair();
	echo tidy_get_output($tidy);
	$xmlObj->asXML($file);
	print '
	<div class="alert alert-success">
		<strong>Success!</strong>, the record was updated, <a href="index.html">now return</a> to see your handy work.
	</div>';
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