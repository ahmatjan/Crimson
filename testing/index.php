<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="cache-control" content="no-cache">
<title>Crimson Alliance</title>
<!-- Stylesheets -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link href="css/offcanvas.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
<![endif]-->
</head>

<body>
<div class="leftcontent">
	<div class="container" style="padding: 0 17px;">
		<img src="images/logo.png" width="160" height="44" style="margin: 5px 0;">
		<form class="form-horizontal" id="loginForm" style="margin-top: 10px;" novalidate>
			<div class="input-group input-group-sm" style="margin-bottom: 2px;">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" class="form-control" id="inputUsername" placeholder="Username" data-required="true" data-minlength="3" data-error-message="<b>Error:</b> Invalid Username" data-error-container="#error">
			</div>
			<div class="input-group input-group-sm" style="margin-bottom: 2px;">
				<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
				<input type="password" class="form-control" id="inputPassword" placeholder="Password" data-required="true" data-minlength="3" data-error-message="<b>Error:</b> Invalid Password" data-error-container="#error">
			</div>
			<div class="input-group input-group-sm pull-right">
			<button type="button" class="btn btn-default btn-sm">Register</button>&nbsp;<input type="submit" class="btn btn-success btn-sm" value="Login">
			<br />
			<small><a href="#">Forgot Password?</a></small>
			</div>
		</form>
		<div class="clearfix"></div>
		<hr style="margin: 10px 0;" />
		<ul class="nav nav-pills nav-stacked leftnav">
			<li id="homepage">
				<a href="homepage.php" class="hash">Home</a>
			</li>
			<li id="forum">
				<a href="forum.php" class="hash">Forum</a>
			</li>
			<li id="testpage">
				<a href="testpage.php" class="hash">Link 3</a>
			</li>
		</ul>
	</div>
</div>
<div class="rightcontent">
	<div class="container" style="padding: 0; max-width: none;">
		<span id="error"></span>
		<div id="responseArea"></div>
	</div>
</div>
<div class="clearfix"></div>
<!-- Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="./js/jquery-ui-1.10.3.custom.js"></script>
<script src="./js/bootstrap.js"></script>
<script src="./js/parsley.min.js"></script>
<!-- jQuery ScrollTo Plugin -->
<script src="js/jquery-scrollto.js"></script>
<!-- History.js -->
<script src="js/jquery.history.js"></script>
<script>
$('#loginForm').parsley( {
	inputs: 'input, textarea, select',
	excluded: 'input[type=hidden], input[type=submit]',
	successClass: 'has-success',
	errorClass: 'has-error',
	errors: {
		errorsWrapper: '<div class="alert alert-dismissable alert-danger" style="width: 100%; padding: 5px; padding-right: 35px; border-radius: 0; margin: 0; border: none;"></div>',
		errorElem: '<span></span>'
	}
});
</script>
<script>
$('a.hash').bind('click', function(e) {           
  var url = $(this).attr('href');
  $('div#responseArea').load(url); // load the html response into a DOM element
  e.preventDefault(); // stop the browser from following the link
  var hash = url.replace(".php","");
  History.pushState({state:1}, "Crimson Crusade Alliance - "+hash, "?state="+url);
  $('li.active').removeClass("active");
  $('#'+hash).addClass("active");
});
</script>
<script>
$(document).ready(function() {
	var url = "<?php if($_GET['state'] != ''){ echo $_GET['state']; } else { echo 'homepage.php'; } ?>";
	<?php if($_GET['state'] == ''){ echo '$(\'#homepage\').addClass("active");'; } ?>
	var hash = url.replace(".php", "");
	$('#'+hash).addClass("active");
	$('div#responseArea').load(url);
	// Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
    });
});
</script>
</body>
</html>