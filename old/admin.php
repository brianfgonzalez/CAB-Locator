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

// load xml
$(document).ready(function() {
	$.ajax({
		type: "GET",
		url: "xml/cabs.xml",
		dataType: "xml",
		success : function(data) {
				window.xml=data;
				xmlParser();
		}
	});
});

// populate delete cab dropdown code
function xmlParser() {
	var cabnames = [];
	$(window.xml).find('cab').each(function() {
		if ($(this).attr('name').length > 0)
		{
			cabnames.push($(this).attr('name'));
		}
	});
	cabnames.sort();
	var delCabNamesDropDowntxt = '<select name="delcabname" id="delcabname">';
	var updCabNamesDropDowntxt = '<select name="updcabname" id="updcabname" onchange="popUpdateForm();">';
	
	for	(i=0;i<cabnames.length;i++) {
		delCabNamesDropDowntxt = delCabNamesDropDowntxt + '<option value="' +
        cabnames[i]+'">'+cabnames[i]+'</option>';
		
		updCabNamesDropDowntxt = updCabNamesDropDowntxt + '<option value="' +
        cabnames[i]+'">'+cabnames[i]+'</option>';
	}
	delCabNamesDropDowntxt = delCabNamesDropDowntxt + '</select>';
	updCabNamesDropDowntxt = updCabNamesDropDowntxt + '</select>';
	$('#load').fadeOut();
	$("#delcabnames").html(delCabNamesDropDowntxt);
	$("#updcabnames").html(updCabNamesDropDowntxt);
	$('#updateform').hide();
}


// onchange check function call
function check(name) {
		if ($("#"+name).val() === "")
		{ $("#"+name+"div").addClass("has-error"); }
		else
		{ $("#"+name+"div").removeClass("has-error"); }
}

// populate update form
function popUpdateForm() {
	$(window.xml).find('cab[name="'+$('#updcabname').val()+'"]').each(function() {
		$('#updpdpname').val($('#updcabname').val());
		$('#updateform').append('<input type="hidden" name="origcabname" value="'+$('#updcabname').val()+'" />');
		var newupdmodel = '';
		$(this).find('model').each(function() {
			newupdmodel = newupdmodel + $(this).text() + ',';
		});
		newupdmodel = newupdmodel.substring(0, newupdmodel.length-1);
		$('#updmodel').val(newupdmodel);
		$('#updlinks').html('');
		$('#updaddtlinks').html('');
		i = 1; updlinkstxt = '';
		$(this).find('link').each(function() {
			$('#updlinks').append('<div class="input-group" id="updlink'+i+'div">' +
			'<span class="input-group-addon" id="basic-addon1">Link '+i+':</span>' +
			'<div class="input-group-btn" data-toggle="buttons">' +
				'<label id="updlinkcabtypeshade'+i+'" class="btn btn-default">' +
					'<input id="updlinkcabtypebutton'+i+'" type="radio" name="updlinktype'+i+'" id="option1" checked value="CAB" />CAB' +
				'</label>' +
				'<label id="updlinkocbtypeshade'+i+'" class="btn btn-default">' +
					'<input id="updlinkocbtypebutton'+i+'" type="radio" name="updlinktype'+i+'" id="option2" value="OCB" />OCB' +
				'</label>' +
				'<label id="updlinktxttypeshade'+i+'" class="btn btn-default">' +
					'<input id="updlinktxttypebutton'+i+'" type="radio" name="updlinktype'+i+'" id="option3" value="TXT" />TXT' +
				'</label>' +
			'</div>' +
			'<input name="updlink'+i+'" id="updlink'+i+'" type="text" class="form-control" aria-describedby="basic-addon1" autocomplete="off" ' +
			'onchange=\'check("updlink'+i+'");\' onkeyup=\'check("updlink'+i+'");\' onpaste=\'check("updlink'+i+'");\' oninput=\'check("updlink'+i+'");\'' +
			'></label></div> <!--- /input group --->');
			switch( $(this).attr('type') ) {
			case 'CAB':
				$('#updlinkcabtypeshade'+i).addClass('active');
				$('#updlinkcabtypebutton'+i).prop("checked", true);
				break;
			case 'OCB':
				$('#updlinkocbtypeshade'+i).addClass('active');
				$('#updlinkocbtypebutton'+i).prop("checked", true);
				break;
			case 'TXT':
				$('#updlinktxttypeshade'+i).addClass('active');
				$('#updlinktxttypebutton'+i).prop("checked", true);
				break;
			default:
				$('#updlinkcabtypeshade'+i).addClass('active');
				$('#updlinkcabtypebutton'+i).prop("checked", true);
			}
			$('#updlink'+i+'').val($(this).text());
			i++;
		});
		switch( $(this).find('os').first().text() ) {
		case 'Windows 7 x86':
			$('#7x86').addClass('active');
			$("#updosradio7x86").prop("checked", true);
			$('#7x64').removeClass('active');
			$('#8x64').removeClass('active');
			$('#81x64').removeClass('active');
			$('#10x64').removeClass('active');
			break;
		case 'Windows 7 x64':
			$('#7x86').removeClass('active');
			$('#7x64').addClass('active');
			$("#updosradio7x64").prop("checked", true);
			$('#8x64').removeClass('active');
			$('#81x64').removeClass('active');
			$('#10x64').removeClass('active');
			break;
		case 'Windows 8 x64':
			$('#7x86').removeClass('active');
			$('#7x64').removeClass('active');
			$('#8x64').addClass('active');
			$("#updosradio8x64").prop("checked", true);
			$('#81x64').removeClass('active');
			$('#10x64').removeClass('active');
			break;
		case 'Windows 8.1 x64':
			$('#7x86').removeClass('active');
			$('#7x64').removeClass('active');
			$('#8x64').removeClass('active');
			$('#81x64').addClass('active');
			$("#updosradio81x64").prop("checked", true);
			$('#10x64').removeClass('active');
			break;
		case 'Windows 10 x64':
			$('#7x86').removeClass('active');
			$('#7x64').removeClass('active');
			$('#8x64').removeClass('active');
			$('#81x64').removeClass('active');
			$('#10x64').addClass('active');
			$("#updosradio10x64").prop("checked", true);
			break;
		}
	});
	$( "#updateform" ).show();
}

j=2;
// add add link button for new cab form initiation code
$(function() {
	$("#newaddtlinksbutton").click(function() {
		$("#newaddtlinks").append(
		'<div class="input-group" id="newlink'+j+'div">' +
			'<span class="input-group-addon" id="basic-addon1">Link '+j+':</span>' +
			'<div class="input-group-btn" data-toggle="buttons">' +
				'<label class="btn btn-default active">' +
					'<input type="radio" name="newlinktype'+j+'" id="option1" checked value="CAB" />CAB' +
				'</label>' +
				'<label class="btn btn-default">' +
					'<input type="radio" name="newlinktype'+j+'" id="option2" value="OCB" />OCB' +
				'</label>' +
				'<label class="btn btn-default">' +
					'<input type="radio" name="newlinktype'+j+'" id="option3" value="TXT" />TXT' +
				'</label>' +
			'</div>' +
			'<input name="newlink'+j+'" id="newlink'+j+'" type="text" class="form-control"' +
			'placeholder="https://drive.google.com/open?id=0B_636cnOwn0jZ09FSFdoNDhNVGM"' +
			'aria-describedby="basic-addon1" autocomplete=\'off\' onchange=\'check("newlink'+j+'");\' onkeyup=\'check("newlink'+j+'");\'' +
			'onpaste=\'check("newlink'+j+'");\' oninput=\'check("newlink'+j+'");\' />' +
		'</div> <!--- /input group --->');
		j++;
	});
});

// remove a link button for new cab form initiation code
$(function() {
	$("#newremlinksbutton").click(function() {
		if (j!=2)
		{
		j--;
		$('#newlink'+j+'div').remove();
		}
	});
});

// add button initiation code
$(function() {
	$("#addbutton").click(function() {
		check("newmodel");check("newlink1");check("newpdpname");
		if (
		$("#newmodel").val() !== "" &&
		$("#newlink1").val() !== "" &&
		$("#newpdpname").val() !== "" )
		{
			$("#addform").submit();
		}
		else
		{
			//alert("errors!");
		}
	});
});

// add a link button for update cab form initiation code
$(function() {
	$("#updaddtlinksbutton").click(function() {
		$("#updaddtlinks").append(
		'<div class="input-group" id="updlink'+i+'div">' +
			'<span class="input-group-addon" id="basic-addon1">Link '+i+':</span>' +
			'<div class="input-group-btn" data-toggle="buttons">' +
				'<label class="btn btn-default active">' +
					'<input type="radio" name="updlinktype'+i+'" id="option1" checked value="CAB" />CAB' +
				'</label>' +
				'<label class="btn btn-default">' +
					'<input type="radio" name="updlinktype'+i+'" id="option2" value="OCB" />OCB' +
				'</label>' +
				'<label class="btn btn-default">' +
					'<input type="radio" name="updlinktype'+i+'" id="option3" value="TXT" />TXT' +
				'</label>' +
			'</div>' +
			'<input name="updlink'+i+'" id="updlink'+i+'" type="text" class="form-control"' +
			'placeholder="https://drive.google.com/open?id=0B_636cnOwn0jZ09FSFdoNDhNVGM"' +
			'aria-describedby="basic-addon1" autocomplete=\'off\' onchange=\'check("updlinkdiv'+i+'");\' onkeyup=\'check("updlinkdiv'+i+'");\'' +
			'onpaste=\'check("updlinkdiv'+i+'");\' oninput=\'check("updlinkdiv'+i+'");\' />' +
		'</div> <!--- /input group --->');
		i++;
	});
});

// remove a link button for update cab form initiation code
$(function() {
	$("#updremlinksbutton").click(function() {
		if (i!=2)
		{
		i--;
		$('#updlink'+i+'div').remove();
		}
	});
});

// add button initiation code
$(function() {
	$("#updatebutton").click(function() {
		check("updmodel");check("updlink1");check("updpdpname");
		if (
		$("#updmodel").val() !== "" &&
		$("#updlink1").val() !== "" &&
		$("#updpdpname").val() !== "" )
		{
			$("#updateform").submit();
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
	<a href="http://www.panasonicdeployment.com">
	<img src = "images/SolutionsConsultingLogo.png" alt="Panasonic Solutions Consulting" width="150">
	</a>

	<!--- Beginning of Accordian --->
	<div class="accordion" id="accordion2">
	
	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- Deleting a CAB section --->
	<div class="panel-group accordion-group" role="tablist">
		<div class="panel panel-default">
			
			<div class="panel-heading" role="tab" id="headingOne">
			  <h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" aria-controls="collapseOne" aria-expanded="false">
				  - Removing CAB?
				</a>
			  </h4>
			</div>
			
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<div class="input-group">
						<form id="deleteform" action="submit.php?action=delete" method="post" class="form-inline">
						<span id="delcabnames"></span>
						&nbsp;<button type="submit" class="btn btn-danger btn-xs">Delete CAB</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- Adding a new CAB section --->
	<div class="panel-group accordion-group" role="tablist">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
			  <h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-controls="collapseTwo" aria-expanded="false">
				  - Adding CAB?
				</a>
			  </h4>
			</div><!--- /panel-heading group --->
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					<form id="addform" action="submit.php?action=add" method="post" enctype="multipart/form-data">
					<div class="cabform">
						<div class="input-group" id="newpdpnamediv">
							<span class="input-group-addon" id="basic-addon1">PDP Name*:</span>
							<input id="newpdpname" name="newpdpname" type="text" class="form-control"
								placeholder="PDP_G1mk3_Win8x64_V4.01L10M00" aria-describedby="basic-addon1"
								autocomplete=""off" onchange=\'check("newpdpname");\' onkeyup=\'check("newpdpname");\'
								onpaste=\'check("newpdpname");\' oninput=\'check("newpdpname");\' tabindex="1" />
						</div> <!--- /input group --->
						<br />
						<div class="input-group" id="newlink1div">
							<span class="input-group-addon" id="basic-addon1">Link 1*:</span>
							<div class="input-group-btn" data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="newlinktype1" id="option1" checked value="CAB" autocomplete="off" />CAB
								</label>
								<label class="btn btn-default">
									<input type="radio" name="newlinktype1" id="option2" value="ocb" autocomplete="off" />OCB
								</label>
								<label class="btn btn-default">
									<input type="radio" name="newlinktype1" id="option2" value="txt" autocomplete="off" />TXT
								</label>
							</div>
							<input name="newlink1" id="newlink1" type="text" class="form-control"
								placeholder="https://drive.google.com/open?id=0B_63..."
								aria-describedby="basic-addon1"
								autocomplete=\'off\' onchange=\'check("newlink1");\' onkeyup=\'check("newlink1");\'
								onpaste=\'check("newlink1");\' oninput=\'check("newlink1");\' tabindex="2" />
						</div> <!--- /input group --->
						<div id="newaddtlinks"></div>
						<button id="newaddtlinksbutton" type="button" class="btn btn-success btn-xs">Add More Links</button>
						<button id="newremlinksbutton" type="button" class="btn btn-danger btn-xs">Remove a Link</button>					
						<br />
						<br />
						<div class="input-group" id="newmodeldiv">
							<span class="input-group-addon" id="basic-addon1">Models Supported*:</span>
							<input name="newmodel" id="newmodel" type="text" class="form-control"
								placeholder="CF-C2A (mk1),CF-195 (mk7),CF-54A (mk1)"
								aria-describedby="basic-addon1"
								autocomplete=\'off\' onchange=\'check("newmodel");\' onkeyup=\'check("newmodel");\'
								onpaste=\'check("newmodel");\' oninput=\'check("newmodel");\' tabindex="4" />
						</div> <!--- /input group --->
						<br />
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-default active">
								<input type="radio" name="newos" id="option2" checked value="Windows 7 x64" autocomplete="off" tabindex="5" /> 7x64
							</label>
							<label class="btn btn-default">
								<input type="radio" name="newos" value="Windows 7 x86" id="option1" autocomplete="off" tabindex="6" /> 7x86
							</label>
							<label class="btn btn-default">
								<input type="radio" name="newos" id="option3" value="Windows 8 x64" autocomplete="off" tabindex="7" />  8x64
							</label>
							<label class="btn btn-default">
								<input type="radio" name="newos" id="option4" value="Windows 8.1 x64" autocomplete="off" tabindex="8" /> 8.1x64
							</label>
							<label class="btn btn-default">
								<input type="radio" name="newos" id="option5" value="Windows 10 x64" autocomplete="off" tabindex="9" /> 10x64
							</label>
						</div> <!--- /btn group --->
						<br />
						<br />
						<button id="addbutton" type="button" class="btn btn-success btn-sm" tabindex="9">Add CAB</button>
						<br />
					</div> <!--- /cabform group --->
					</form>
				</div><!--- /panel-body group --->
			</div><!--- /collaspeTwo group --->
		</div>
	</div>
	
	
	
	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --->
	<!--- Updating a CAB section --->
	<div class="panel-group ccordion-group" role="tablist">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingThree">
			  <h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree" aria-controls="collapseThree" aria-expanded="false">
				  - Updating a CAB?
				</a>
			  </h4>
			</div><!--- /panel-heading group --->
			<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
				<div class="panel-body">
					<span id="updcabnames"></span>
					<br /><br />
					<form id="updateform" action="submit.php?action=update" method="post" enctype="multipart/form-data">
					<div class="cabform">
						<div class="input-group" id="pdpnamediv">
							<span class="input-group-addon" id="basic-addon1">PDP Name*:</span>
							<input id="updpdpname" name="updpdpname" type="text" class="form-control"
								aria-describedby="basic-addon1" autocomplete=""off" onchange=\'check("uppdpname");\' onkeyup=\'check("uppdpname");\'
								onpaste=\'check("uppdpname");\' oninput=\'check("uppdpname");\' tabindex="1" />
						</div> <!--- /input group --->
						<br />
						<span id="updlinks"></span>
						<div id="updaddtlinks"></div>
						<button id="updaddtlinksbutton" type="button" class="btn btn-success btn-xs">Add More Links</button>
						<button id="updremlinksbutton" type="button" class="btn btn-danger btn-xs">Remove a link</button>
						<br />
						<br />
						<div class="input-group" id="modeldiv">
							<span class="input-group-addon" id="basic-addon1">Models Supported*:</span>
							<input name="updmodel" id="updmodel" type="text" class="form-control"
								placeholder="CFC2-2, CF31-4, CF19-6"
								aria-describedby="basic-addon1"
								autocomplete=\'off\' onchange=\'check("updmodel");\' onkeyup=\'check("updmodel");\'
								onpaste=\'check("updmodel");\' oninput=\'check("updmodel");\' tabindex="4" />
						</div> <!--- /input group --->
						<br />
						<div class="btn-group" data-toggle="buttons">
							<label id="7x86" class="btn btn-default">
								<input type="radio" name="updos" id="updosradio7x86" checked value="Windows 7 x86" /> 7x86
							</label>
							<label id="7x64" class="btn btn-default">
								<input type="radio" name="updos" id="updosradio7x64" value="Windows 7 x64" /> 7x64
							</label>
							<label id="8x64" class="btn btn-default">
								<input type="radio" name="updos" id="updosradio8x64" value="Windows 8 x64" />  8x64
							</label>
							<label id="81x64" class="btn btn-default">
								<input type="radio" name="updos" id="updosradio81x64" value="Windows 8.1 x64" /> 8.1x64
							</label>
							<label id="10x64" class="btn btn-default">
								<input type="radio" name="updos" id="updosradio10x64" value="Windows 10 x64" /> 10x64
							</label>
						</div> <!--- /btn group --->
						<br />
						<br />
						<button id="updatebutton" type="button" class="btn btn-warning btn-sm" tabindex="9">Update CAB</button>
						<br />
					</div> <!--- /cabform group --->
					</form>
				</div><!--- /panel-body group --->
			</div><!--- /collaspeTwo group --->
		</div>
	</div>
	
	<!--- Ending of Accordian --->
	</div>
<a href="http://panasonicdeployment.com" class="btn btn-primary btn-xs dropdown-toggle">Back to Home</a>
</div>
	<p class="emailtext"><span class="glyphicon glyphicon-envelope"></span>  Contact  <a href="mailto:imaging@us.panasonic.com">Imaging Support</a> for any further assistance.</p>
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