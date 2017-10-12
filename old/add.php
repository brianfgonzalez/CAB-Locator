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
<?php
	if (isset($_POST["password"]))
	{
		if ($_POST["password"] === "P@ssw0rd")
		{
			$password = true;
		}	else {
			$password = false;
		}
	} else {
	$password = false;
	}
?>
</head>
<body>
<script>
$(document).ready(function() {
	$.ajax({
		type: "GET",
		url: "xml/cabs.xml",
		dataType: "xml",
		success : function(data) {
				window.xml=data;
		}
	});
});
function check(name) {
		if ($("#"+name).val() === "")
		{ $("#"+name+"div").addClass("has-error"); }
		else
		{ $("#"+name+"div").removeClass("has-error"); }
}
$(function() {
	$("#submitbutton").click(function() {
		check("model");check("googlelink");check("altlink");check("pdpname");
		if (
		$("#model").val() !== "" &&
		$("#googlelink").val() !== "" &&
		//$("#altlink").val() !== "" &&
		$("#pdpname").val() !== "" )
		{
			$("#cabform").submit();
		}
		else
		{
			//alert("errors!");
		}
	});
});
</script>
<?php
if ($password) {
print '
<div class="container drop-shadow main">
<div class="jumbotron">
<center>
	<a href="http://www.panasonic.com/toughbook/support">
	<img src = "images/SolutionsConsultingLogo.png" alt="Panasonic Solutions Consulting">
	</a>
</center>
	<form id="cabform" action="submit.php" method="post" enctype="multipart/form-data">
	<div class="cabform">
		<h1>Add A New CAB Form:</h1>
		<hr />
			<div class="input-group" id="pdpnamediv">
				<span class="input-group-addon" id="basic-addon1">PDP Name:</span>
				<input id="pdpname" name="pdpname" type="text" class="form-control"
					placeholder="PDP_G1mk3_Win8x64_V4.01L10M00" aria-describedby="basic-addon1"
					autocomplete=""off" onchange=\'check("pdpname");\' onkeyup=\'check("pdpname");\'
					onpaste=\'check("pdpname");\' oninput=\'check("pdpname");\' tabindex="1" />
			</div> <!--- /input group --->
			<br />
			<div class="input-group" id="googlelinkdiv">
				<span class="input-group-addon" id="basic-addon1">Google Link:</span>
				<input name="googlelink" id="googlelink" type="text" class="form-control"
					placeholder="https://drive.google.com/open?id=0B_636cnOwn0jZ09FSFdoNDhNVGM"
					aria-describedby="basic-addon1"
					autocomplete=\'off\' onchange=\'check("googlelink");\' onkeyup=\'check("googlelink");\'
					onpaste=\'check("googlelink");\' oninput=\'check("googlelink");\' tabindex="2" />
			</div> <!--- /input group --->
			<br />
			<div class="input-group" id="altlinkdiv">
				<span class="input-group-addon" id="basic-addon1">Alternate Link:</span>
				<input name="altlink" id="altlink" type="text" class="form-control"
					placeholder="ftp://ftp.panasonic.com/computer/cab/PDP_FZ-G1mk3_Win10x64_V4.02L10M00.cab"
					aria-describedby="basic-addon1" autocomplete=\'off\' tabindex="3" />
			</div> <!--- /input group --->
			<br />
			<div class="input-group" id="modeldiv">
				<span class="input-group-addon" id="basic-addon1">Models Supported:</span>
				<input name="model" id="model" type="text" class="form-control"
					placeholder="CFC2-2, CF31-4, CF19-6"
					aria-describedby="basic-addon1"
					autocomplete=\'off\' onchange=\'check("model");\' onkeyup=\'check("model");\'
					onpaste=\'check("model");\' oninput=\'check("model");\' tabindex="4" />
			</div> <!--- /input group --->
			<br />
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-default active">
					<input type="radio" name="os" id="option2" value="Windows 7 x64" autocomplete="off" checked tabindex="5" /> 7x64
				</label>
				<label class="btn btn-default">
					<input type="radio" name="os" value="Windows 7 x86" id="option1" autocomplete="off" tabindex="6" /> 7x86
				</label>
				<label class="btn btn-default">
					<input type="radio" name="os" id="option3" value="Windows 8 x64" autocomplete="off" tabindex="7" />  8x64
				</label>
				<label class="btn btn-default">
					<input type="radio" name="os" id="option4" value="Windows 8.1 x64" autocomplete="off" tabindex="8" /> 8.1x64
				</label>
				<label class="btn btn-default">
					<input type="radio" name="os" id="option5" value="Windows 10 x64" autocomplete="off" tabindex="9" /> 10x64
				</label>
			</div> <!--- /btn group --->
			<br />
			<center>
				<button id="submitbutton" type="button" class="btn btn-success btn-lg" tabindex="9">Submit</button>
			</center>
			<br />
			<br />
	</div>
</div>
</div>';
} else {
print '
<div class="container drop-shadow main">
	<div class="jumbotron">
	
		<div class="alert alert-danger">
			<strong>Error!</strong>, Password is not correct.  <a href="index.html">Go back</a> and try again.
		</div>
		
	</div>
</div>';
}

?>
</body>
</html>
