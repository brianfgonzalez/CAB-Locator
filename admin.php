<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CAB Locator Admin Page</title>
  <meta name="description" content="Site to simplify the process of locating the proper CAB file.">
  <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.4/lumen/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/main.css" media="all">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
if (isset($_POST["password"])) {
  if ($_POST["password"] === "P@ssw0rd") {
    $password = true;
  } else {
    $password = false;
  }
} else {
  $password = false;
}
?>
</head>
<body>
<script>
$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "xml/cabs.xml",
    dataType: "xml",
    success: function (data) {
      window.xml = data;
      xmlParser();
    }
  });
});

function xmlParser() {
  var cabNames = [];
  $(window.xml).find('cab').each(function () {
    if ($(this).attr('name').length > 0) {
      cabNames.push($(this).attr('name'));
    }
  });
  cabNames.sort();
  var oldCabNamesDropDown = '<select name="oldCabName" id="oldCabName" onchange="PopulateCabNameForm();">';

  for (i = 0; i < cabNames.length; i++) {
	  oldCabNamesDropDown = oldCabNamesDropDown + '<option value="' + cabNames[i] + '">' + cabNames[i] + '</option>';
  }
  oldCabNamesDropDown = oldCabNamesDropDown + '</select>';
  $('#load').fadeOut();
  $("#oldCabNamesSpan").html(oldCabNamesDropDown);
}

function PopulateCabNameForm() {
  $(window.xml).find('cab[name="' + $('#oldCabName').val() + '"]').each(function () {

 	  $('#cabForm').append('<input type="hidden" name="oldCabName" value="' + $('#oldCabName').val() + '" />');
  	$('#cabName').val($('#oldCabName').val());

    var oldCabModel = '';
    $(this).find('model').each(function () {
    	oldCabModel = oldCabModel + $(this).text() + ',';
    });
    oldCabModel = oldCabModel.substring(0, oldCabModel.length - 1);
    $('#cabModel').val(oldCabModel);

    $(this).find('googlelink').each(function () {
      $('#cabGoogleLink').val($(this).text());
    });

    $(this).find('ftplink').each(function () {
        $('#cabFtpLink').val($(this).text());
      });

    switch ($(this).find('os').first().text()) {
      case '7x86':
    	  ClearActivesExcept('7x86');
        break;
      case '7x64':
    	  ClearActivesExcept('7x64');
        break;
      case '8.1x64':
        ClearActivesExcept('81x64');
        break;
      case '10x64':
    	  ClearActivesExcept('10x64');
        break;
     }
    
  });
}

// OS button CSS -> based on dropdown select
function ClearActivesExcept(desiredName) {
  $('#7x86').removeClass('active');
  $('#7x64').removeClass('active');
  $('#81x64').removeClass('active');
  $('#10x64').removeClass('active');
  $('#'+desiredName).addClass('active');
  $('#osRadio'+desiredName).prop('checked', true);
}

// Validate 
function CheckForEmpty(idName) {
  if ($('#' + idName).val() === '') {
    $('#' + idName + 'div').addClass('has-error');
  }
  else {
    $('#' + idName + 'div').removeClass('has-error');
  }
}

// submit button function for add/update form
$(function () {
  $('#submitButton').click(function () {
    // check if any * fields are empty
    check('cabModel');
    check('cabGoogleLink');
    check('cabName');
    if (
     $('#cabModel').val() !== '' &&
     $('#cabGoogleLink').val() !== '' &&
     $('#cabName').val() !== '') {
      $('#cabForm').submit();
    }
  });
});

// submit button function for delete form 
$(function () {
    $('#deleteButton').click(function () {
      // check if any * fields are empty
      check('cabModel');
      check('cabGoogleLink');
      check('cabName');
      if (
       $('#cabModel').val() !== '' &&
       $('#cabGoogleLink').val() !== '' &&
       $('#cabName').val() !== '') {
        $('#cabForm').submit();
      }
    });
  });
</script>
<?php
if ($password) {
?>
<div class="container drop-shadow main">
<div class="jumbotron">

  <form id="cabForm" action="submit.php?action=update" method="post" enctype="multipart/form-data">
    <a href="../"><img src="images/logo.png" width="65"></a>
    <div class="input-group">
      <p>Use the dropdown if you want to change an existing CAB entry:</p>
      <span id="oldCabNamesSpan"></span>&nbsp;&nbsp;&nbsp;<button id="deleteButton" type="button" class="btn btn-danger btn-xs" tabindex="2"><-- Delete this CAB entry</button>
    </div>
    <br />
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">PDP Name*:</span>
      <input id="cabName" name="cabName" type="text" class="form-control" placeholder="PDP_CF-20mk1_Win7x64_V6.00L10M00" onchange='CheckForEmpty("cabName");' onkeyup='CheckForEmpty("cabName");' oninput='CheckForEmpty("cabName");' tabindex="1" />
    </div>
    <br />
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">Google Link*:</span>
	    <input name="cabGoogleLink" id="cabGoogleLink" type="text" class="form-control" placeholder="https://drive.google.com/open?id=0B_636cnOwn0jZ09FSFdoNDhNVGM" onchange='CheckForEmpty("cabGoogleLink");' oninput='CheckForEmpty("cabGoogleLink");' onkeyup='CheckForEmpty("cabGoogleLink");' tabindex="2" />
    </div>
    <br />
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">FTP Link:</span>
	    <input name="cabFtpLink" id="cabFtpLink" type="text" class="form-control" placeholder="ftp://ftp.panasonic.com/computer/cab/pdp_cf-31mk5_win10x64_v4.02l13m00.cab" tabindex="3" />
    </div>
    <br />
    <div class="input-group">
      <span class="input-group-addon" >Models Supported*:</span>
      <input name="cabModel" id="cabModel" type="text" class="form-control" placeholder="cf-c2c (mk2), cf31w (mk4), cf195 (mk6)" onchange='CheckForEmpty("cabModel");' onkeyup='CheckForEmpty("cabModel");' oninput='CheckForEmpty("cabModel");' tabindex="4" />
    </div>
    <br />
	  <div class="btn-group" data-toggle="buttons">
  	  <label id="7x86" class="btn btn-default"><input type="radio" name="os" id="osRadio7x86" value="7x86"  tabindex="5" /> 7x86</label>
    	<label id="7x64" class="btn btn-default"><input type="radio" name="os" id="osRadio7x64" value="7x64" tabindex="6" /> 7x64</label>
    	<label id="81x64" class="btn btn-default"><input type="radio" name="os" id="osRadio8x64" value="81x64" tabindex="7" /> 81x64</label>
    	<label id="10x64" class="btn btn-default active"><input type="radio" name="os" id="osRadio10x64" value="10x64" checked tabindex="8" /> 10x64</label>
  	</div>
    &nbsp;&nbsp;&nbsp;<button id="submitButton" type="button" class="btn btn-warning btn-sm" tabindex="9">Add/Update CAB Entry</button>
    <br />
  </form>
  <br /><br />
  <p class="emailtext"><span class="glyphicon glyphicon-envelope"></span> Go back to the <a href="../">Home Page.</a></p>
  <a href="../" class="btn btn-primary btn-xs">Back to Home</a> <br/>
  <br /><br />
  <p class="emailtext"><span class="glyphicon glyphicon-envelope"></span> Contact <a
    href="mailto:imaging@us.panasonic.com">Imaging Support</a> for any further assistance.</p>

<?php
} else {
?>

<div class="jumbotron">
  <div class="alert alert-danger">
    <strong>Error!</strong>, Entered password is not correct. <a href="../">Go back</a> and try again.
  </div>
</div>

<?php
}
?>

</div>
</div>
</body>
</html>