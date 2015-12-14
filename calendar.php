<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Event Calendar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css">
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	if("<?php echo $_SESSION['userTimeZone']; ?>".length==0){
		var visitortime = new Date();
		var visitortimezone = "" + -visitortime.getTimezoneOffset()*60;
		$.ajax({
			type: "GET",
			url: "timezone.php",
			data: 'time='+ visitortimezone,
			success: function(){
				location.reload();
			}
		});
	}
});
</script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/backstretch.js"></script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="/">Crimson Crusade Alliance</a>
			<?php if ($userName == 'Anonymous') { ?>
			<div class="pull-right">
				<ul class="nav pull-right">
					<li><a href="#">Sign Up</a></li>
					<li class="divider-vertical"></li>
					<li class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
						<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0;">
							<form method="post" action="../forum/ucp.php?mode=login" accept-charset="UTF-8" style="margin-bottom: 15px;">
								<input style="margin-bottom: 15px;" type="text" placeholder="Username" id="username" name="username">
								<input style="margin-bottom: 15px;" type="password" placeholder="Password" id="password" name="password">
								<label class="checkbox">
									<input type="checkbox" value="1" name="remember">
									 Remember Me
								</label>
								<input type="hidden" name="redirect" value="<?php echo $_SERVER['PHP_SELF'].$extra_url; ?>">
								<input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In" name="login">
							</form>
						</div>
					</li>
				</ul>
			</div>
			<?php } else { ?>
			<div class="pull-right">
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo $userName; ?><b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php include('inc/dropdownlinks.php'); ?>
						</ul><!--/dropdown-menu-->
					</li><!--/dropdown-->
				</ul><!--/nav pull-right-->
			</div><!--/pull-right-->
			<?php } ?>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<?php include('./inc/nav_links.php'); ?>
				</ul>
			</div><!--/nav-collapse collapse-->
		</div><!--/container-fluid-->
	</div><!--/navbar-inner-->
</div><!--/navbar navbar-fixed-top-->

<div class="container well well-small">
	<div class="row-fluid">
		<div class="span12">
			<?php
			if(!isset($_POST['month'])) { $month = date('n',time()+$userTimeZone); } else { $month = $_POST['month']; };
			if(!isset($_POST['year']))  { $year  = date('Y',time()+$userTimeZone); } else { $year = $_POST['year'];  };
			if($month == 13) { $month = 1;  $year = $year+1; }
			if($month == 0)  { $month = 12; $year = $year-1; }
			$prev_month = date('Y-m-',mktime(0,0,0,$month-1,1,$year));
			$this_month = date('Y-m-',mktime(0,0,0,$month,1,$year));
			$next_month = date('Y-m-',mktime(0,0,0,$month+1,1,$year));
			$date_tod   = date('j',time()+$userTimeZone);
			
			try {
				$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpasswd);
				$sql = "SELECT * FROM events_final WHERE date LIKE '{$prev_month}%' OR date LIKE '{$this_month}%' OR date LIKE '{$next_month}%'";
				$sth = $dbh->prepare($sql);
				$sth->execute();
				$result = $sth->fetchAll();
			}  catch (PDOException $e){
				echo $e->getMessage();
			}
			
			for($x = 0; $x < count($result); $x++){
				$datetime = strtotime($result[$x]['datetime']);
				$result[$x]['date'] = date('d-m-Y', ($datetime + $userTimeZone));
				$result[$x]['time'] = date('g:i a', ($datetime + $userTimeZone));
			}
			?>
			<div style="width: 100%; height: 25px; margin-bottom: 5px;">
				<span style="width: 15%; text-align: left; height: 25px;" class="pull-left">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="month" value="<?php echo ($month-1); ?>">
				<input type="hidden" name="year" value="<?php echo ($year); ?>">
				<input type="submit" class="btn btn-default" value="Previous Month">
				</form>
				</span>
				<span style="width: 15%; text-align: left; height: 25px;" class="pull-left">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="month" value="<?php echo date('n',time()+$userTimeZone); ?>">
				<input type="submit" class="btn btn-default" value="Current Month">
				</form>
				</span>
				<span style="width: 40%; margin: 0 auto; text-align: center; height: 25px; padding-top: 7px;" class="pull-left">
				<strong style="font-size: 25px; line-height: 25px;"><?php echo date('F Y',mktime(0,0,0,$month,1,$year)); ?></strong>
				</span>
				<span style="width: 30%; text-align: right; height: 25px;" class="pull-right">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="month" value="<?php echo ($month+1); ?>">
				<input type="hidden" name="year" value="<?php echo ($year); ?>">
				<input type="submit" class="btn btn-default" value="Next Month">
				</form>
				</span>
			</div>
			<div class="clearfix"></div>
			<br />
			<?php echo draw_calendar($month,$year,$result,$admin_user,$date_tod); ?>
		</div>
	</div><!--/row-fluid-->
</div><!--/container-fluid-->

<div class="navbar navbar-inverse navbar-fixed-bottom">
	<div class="navbar-inner">
		<div class="container-fluid">
			<?php include('inc/footer.php'); ?>
		</div><!--/container-fluid-->
	</div><!--/navbar-inner-->
</div><!--/navbar-fixed-bottom-->
<!-- JAVASCRIPT -->
<script type="text/javascript">
$.backstretch("./images/temp_bg.jpg");
</script>
</body>
</html>