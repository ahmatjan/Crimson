<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Raiders List</title>
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
			<table class="table table-striped table-hover table-bordered raider-table" id="raidersTable" style="width: 100%; max-width: 100%;">
				<thead>
					<tr>
						<th style="width: 15%;">Character</th>
						<th style="width: 5%;">Class</th>
						<th style="width: 16%; text-align: center;" colspan="4">Tier 1 - 20 Man</th>
						<th style="width: 16%; text-align: center;" colspan="4">Tier 2 - 10 Man</th>
						<th style="width: 16%; text-align: center;" colspan="4">Tier 2 - 20 Man</th>
						<th style="width: 16%; text-align: center;" colspan="4">Tier 3 - 10 Man</th>
						<th style="width: 16%; text-align: center;" colspan="4">Tier 3 - 20 Man</th>
					</tr>
					<tr>
						<th style="width: 15%">&nbsp;</th>
						<th style="width: 10%;">&nbsp;</th>
						<th style="width: 4%;">Tank</th>
						<th style="width: 4%;">Heal</th>
						<th style="width: 4%;">Dps</th>
						<th style="width: 4%;">Sup</th>
						<th style="width: 4%;">Tank</th>
						<th style="width: 4%;">Heal</th>
						<th style="width: 4%;">Dps</th>
						<th style="width: 4%;">Sup</th>
						<th style="width: 4%;">Tank</th>
						<th style="width: 4%;">Heal</th>
						<th style="width: 4%;">Dps</th>
						<th style="width: 4%;">Sup</th>
						<th style="width: 4%;">Tank</th>
						<th style="width: 4%;">Heal</th>
						<th style="width: 4%;">Dps</th>
						<th style="width: 4%;">Sup</th>
						<th style="width: 4%;">Tank</th>
						<th style="width: 4%;">Heal</th>
						<th style="width: 4%;">Dps</th>
						<th style="width: 4%;">Sup</th>
				</thead>
				<tbody style="color: #FFF;">
				<?php
				$sql = "SELECT username,T1_20,T2_10,T2_20,T3_10,T3_20 FROM raider_info_final ORDER BY username ASC";
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($res)){
					$username = $row['username'];
					$username_clean = strtolower($username);
					$t1_20    = $row['T1_20'];
					$t2_10    = $row['T2_10'];
					$t2_20    = $row['T2_20'];
					$t3_10    = $row['T3_10'];
					$t3_20    = $row['T3_20'];
					// Get Class
					$inside_sql = "SELECT u.user_id FROM phpbb_users u INNER JOIN phpbb_profile_fields_data pf ON u.user_id = pf.user_id WHERE pf.pf_main_char_name='$username'";
					$inside_que = mysqli_query($con,$inside_sql);
					$inside_row = mysqli_fetch_row($inside_que);
					$inside_UID1 = $inside_row[0];
					
					$inside_sql = "SELECT user_id FROM phpbb_users WHERE username='$username' OR username_clean='$username_clean'";
					$inside_que = mysqli_query($con,$inside_sql);
					$inside_row = mysqli_fetch_row($inside_que);
					$inside_UID2 = $inside_row[0];
					
					$inside_sql = "SELECT pf_main_class FROM phpbb_profile_fields_data WHERE user_id='$inside_UID1' OR user_id='$inside_UID2'";
					$inside_que = mysqli_query($con,$inside_sql);
					$inside_row = mysqli_fetch_row($inside_que);
					$inside_class = $inside_row[0];
					if($inside_class == '1'){ $inside_class = 'Warrior'; };
					if($inside_class == '2'){ $inside_class = 'Cleric'; };
					if($inside_class == '3'){ $inside_class = 'Mage'; };
					if($inside_class == '4'){ $inside_class = 'Rogue'; };
					//
					if(in_array($username,$userCharacters)){
						echo '<tr class="success">';
					} else {
						echo '<tr>';
					};
					$background = array('#282828','#303030','#383838','#404040','#484848');
					$Tiers      = array('Tier 1 - 20 Man','Tier 2 - 10 Man','Tier 2 - 20 Man','Tier 3 - 10 Man','Tier 3 - 20 Man');
					echo '<td>'.$username.'</td>';
					echo '<td>'.$inside_class.'</td>';
					for($i=0; $i <= 4; $i++){
						if ($i == 0){ $tier = $t1_20; }
						if ($i == 1){ $tier = $t2_10; }
						if ($i == 2){ $tier = $t2_20; }
						if ($i == 3){ $tier = $t3_10; }
						if ($i == 4){ $tier = $t3_20; }
						echo '<td style="text-align: center; background-color: '.$background[$i].';">';
							if(strlen(strstr($tier,'T'))>0){
								echo '<div title="'.$Tiers[$i].' - Tank">';
								echo '<i class="icon-check" style="color: #66CC00;"></i>';
								echo '</div>';
							} else {
								echo '<div title="'.$Tiers[$i].' - Tank">';
								echo '<i class="icon-check-empty" style="color: #990000;"></i>';
								echo '</div>';
							}
						echo '</td>';
						echo '<td style="text-align: center; background-color: '.$background[$i].';">';
							if(strlen(strstr($tier,'H'))>0){
								echo '<div title="'.$Tiers[$i].' - Healer">';
								echo '<i class="icon-check" style="color: #66CC00;"></i>';
								echo '</div>';
							} else {
								echo '<div title="'.$Tiers[$i].' - Healer">';
								echo '<i class="icon-check-empty" style="color: #990000;"></i>';
								echo '</div>';
							}
	
						echo '</td>';
						echo '<td style="text-align: center; background-color: '.$background[$i].';">';
							if(strlen(strstr($tier,'D'))>0){
								echo '<div title="'.$Tiers[$i].' - Damage">';
								echo '<i class="icon-check" style="color: #66CC00;"></i>';
								echo '</div>';
							} else {
								echo '<div title="'.$Tiers[$i].' - Damage">';
								echo '<i class="icon-check-empty" style="color: #990000;"></i>';
								echo '</div>';
							}
	
						echo '</td>';
						echo '<td style="text-align: center; background-color: '.$background[$i].';">';
							if(strlen(strstr($tier,'S'))>0){
								echo '<div title="'.$Tiers[$i].' - Support">';
								echo '<i class="icon-check" style="color: #66CC00;"></i>';
								echo '</div>';
							} else {
								echo '<div title="'.$Tiers[$i].' - Support">';
								echo '<i class="icon-check-empty" style="color: #990000;"></i>';
								echo '</div>';
							}
	
						echo '</td>';
					};
				echo '</tr>';
				}
				mysqli_close($con);
				?>
				</tbody>
			</table>
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
<script>
jQuery('#raidersTable tbody td div[title]').tooltip();
</script>
</body>
</html>