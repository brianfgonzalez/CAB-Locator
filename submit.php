<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>CAB Locator XML Controller</title>
<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.4/lumen/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/main.css" media="all">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
</head>
<body>
<div class="container drop-shadow main">
	<div class="jumbotron">
	
<?php

//Load XML into simpleXML object
$file = 'xml/cabs.xml';
$xmlObj = simplexml_load_file($file);

//Configure tidy php module configuration
$tidy = new tidy();
$config = array(
  'indent'=> true,
  'output-xml'=> true,
  'add-xml-space'=> true,
  'input-xml'=> true,
  'quote-ampersand' => true,
  'quote-marks' => true,
  'quote-nbsp' => true,
  'indent-spaces' => 2
);


if($_GET['action']=="add") {		
  //Delete any matching cab XMLs
  $xpath = '//cab[contains(@name,"'.$_POST['newpdpname'].'")]';
  $currentCabs = $xmlObj->xpath($xpath);
  foreach ($currentCabs as $currentCab)
  {
    $cab = dom_import_simplexml($currentCab);
    $cab->parentNode->removeChild($cab);
  }

  //Add the new cab node
  $cab = $xmlObj->addChild('cab');
  //Add the new name attribute
  $cab->addAttribute('name', $_POST['newpdpname']);
  //Add new model node/nodes
  if(!is_bool(strpos($_POST['newmodel'], ','))) {
    //More than one model was specified
    $modelarray = explode(',', $_POST['newmodel']);
    foreach($modelarray as $model) {
      $cab->addChild('model', CleanXML($model));
    }
  } else {
    //Only one model was specified
    $cab->addChild('model', CleanXML($_POST['newmodel']));
  }
  //Add new links
  foreach($_POST['cabLinks'] as $cabLink)
  {
    $l = $cab->addChild('link', CleanXML($cabLink['link']));
    $l->addAttribute('type', CleanXML($cabLink['type']));
  }
  //Add cab OS
  $cab->addChild('os', CleanXML($_POST['newos']));
  //Tidy XML into string
  $clean = $tidy->repairString($xmlObj->asXML(), $config, 'utf8');
  //Open and write new XML
  $cabsXML = fopen($file, "w");
  fwrite($cabsXML, $clean);
	print '
	<div class="alert alert-success">
		<strong>Success!</strong>, the record was deleted, <a href="..\">now return</a> to see your handy work.
	</div>';
} elseif ($_GET['action']=="delete") {
	//Use XPath to find target node for removal
  $xpath = '//cab[contains(@name,"'.$_POST['delcabname'].'")]';
  $currentCabs = $xmlObj->xpath($xpath);
  foreach ($currentCabs as $currentCab)
  {
    $cab = dom_import_simplexml($currentCab);
    $cab->parentNode->removeChild($cab);
  }

  //Tidy XML into string
  $clean = $tidy->repairString($xmlObj->asXML(), $config, 'utf8');
  //Open and write new XML
  $cabsXML = fopen($file, "w");
  fwrite($cabsXML, $clean);
	print '
	<div class="alert alert-success">
		<strong>Success!</strong>, the record was deleted, <a href="..\">now return</a> to see your handy work.
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



//Function to cleanup XML content
function CleanXML($string) {
  $newstring = strtoupper(trim($string));
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