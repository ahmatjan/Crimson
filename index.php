<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Crimson Crusade Alliance</title>
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
	
<div class="container well well-small hidden-phone">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<?php include('inc/mini-cal.php'); ?>
			</div><!--/well-->
		</div><!--/span12-->
	</div><!--/row-fluid-->
</div><!--/container-->
<div class="container well well-small">
	<div class="row-fluid">
		<div class="span4">
			<h5>Server Status</h5>
			<!-- START SERVER STATUS -->
			<?php
			$xml=simplexml_load_file("eu-status.xml");
			foreach($xml->children() as $server){
				if($server['name'] == 'Gelidra'){
					echo '<div style="background: none; padding: 0 0 0 10px;">';
						echo '<h4 style="color: #fff;">';
						echo ucfirst($server['name']);
						echo '</h4>';
						echo '<div class="pull-left">';
						echo $server['language'].' ';
						if (($server['pvp'] == 'False') && ($server['rp'] == 'False')) { echo 'PvE'; }
						echo '</div>';
						echo '<div class="pull-right">';
						if (($server['online'] == 'True') && ($server['locked'] !== 'False')) {
							echo '<span class="label label-success">Online</span>';
						} else if (($server['online'] == 'True') && ($server['locked'] == 'False')) {
							echo '<span class="label label-warning">Locked</span>';
						} else if (($server['online'] == 'False')) {
							echo '<span class="label label-important">Offline</span>';
						};
						if(($server['online'] == 'True')){
							echo '&nbsp;';
							if (($server['population'] == 'low')) {
								echo '<span class="label label-success">Low</span>';
							} else if (($server['population'] == 'medium')) {
								echo '<span class="label label-warning">Medium</span>';
							} else if (($server['population'] == 'high')) {
								echo '<span class="label label-warning">High</span>';
							} else if (($server['population'] == 'full')) {
								echo '<span class="label label-important">Full</span>';
							};
						};
						if(($server['online'] == 'True')){
							echo '&nbsp;';
							if (($server['queued'] == 0)) {
								echo '<span class="label label-success">No Queue</span>';
							} else if (($server['queued'] <= 10)) {
								echo '<span class="label label-success">Queue of '.$server['queued'].'</span>';
							} else if (($server['queued'] <= 100)) {
								echo '<span class="label label-warning">Queue of '.$server['queued'].'</span>';
							} else if (($server['queued'] > 100)) {
								echo '<span class="label label-important">Queue of '.$server['queued'].'</span>';
							};
						};
						echo '</div>';
					echo '</div>';
				};
			};
			?>
			<div class="clearfix"></div>
			<!-- SERVER STATUS END -->
			<hr />
			<h5>Next Raid</h5>
			<?php
				$date_today = date("Y-m-d H:i:s",time()+$userTimeZone);
				$sql = "SELECT * FROM events_final WHERE datetime>='$date_today' ORDER BY datetime LIMIT 1";
				$que = mysqli_query($con,$sql);
				$res = array();
				while($row = mysqli_fetch_array($que)){
					$res[] = $row;
				};
				$cou = count($res);
				for($x=0; $x < $cou; $x++) {
					if ($cou < 1){
						echo '<h4 style="color: #b42121; font-weight: bold; margin-bottom: 0;">';
						echo 'No Raids Scheduled';
						echo '</h4>';
					} else {
						$event_ts = abs(strtotime($res[$x]['datetime']) + $userTimeZone);
						$event_date = date('l \t\h\e jS', $event_ts);
						$event_check = date('d-m-Y', $event_ts);
						$event_time = date('g:i a', $event_ts);
						$user_today = date('d-m-Y', time()+$userTimeZone);
						$user_day    = date('d', time()+$userTimeZone);
						$user_tomor  = date('d-m-Y', ((time()+$userTimeZone)+86400));
						
						echo '<div style="background: none; padding: 0 0 0 10px;">';
								echo '<h4><a href="./calendar_signup.php?id='.$res[$x]['id'].'">'.$res[$x]['title'].'</a></h4>';
							echo '<div class="pull-left">';
								if($event_check == $user_today) { echo 'Today'; } else if ($event_check == $user_tomor) { echo 'Tomorrow'; } else { echo $event_date; };
							echo '</div>';
							echo '<div class="pull-right">';
								echo '<span class="label label-info">'.$res[$x]['people'].' Man</span>&nbsp;';
								echo '<span class="label label-success">Tier '.$res[$x]['tier'].'</span>&nbsp;';
								echo '<span class="label label">'.$res[$x]['hit'].' Hit Required</span>';
							echo '</div>';
						echo '</div>';
					};
				};
			?>
			<div class="clearfix"></div>
		</div>
		<div class="span8">
			<h5>Alliance News</h5>
			<?php
			$sql = "SELECT COUNT(id) FROM news";
			$query = mysqli_query($con,$sql);
			$row = mysqli_fetch_row($query);
			// Here we have the total row count
			$rows = $row[0];
			// This is the number of results we want displayed per page
			$page_rows = 5;
			// This tells us the page number of our last page
			$last = ceil($rows/$page_rows);
			// This makes sure $last cannot be less than 1
			if($last < 1){
				$last = 1;
			}
			// Establish the $pagenum variable
			$pagenum = 1;
			// Get pagenum from URL vars if it is present, else it is = 1
			if(isset($_GET['pn'])){
				$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
			}
			// This makes sure the page number isn't below 1, or more than our $last page
			if ($pagenum < 1) { 
				$pagenum = 1;
			} else if ($pagenum > $last) {
				$pagenum = $last;
			}
			// This sets the range of rows to query for the chosen $pagenum
			$limit = 'LIMIT '.($pagenum - 1) * $page_rows .',' .$page_rows;
			// This is your query again, it is for grabbing just one page worth of rows by applying $limit
			$sql = "SELECT * FROM news ORDER BY news_time DESC $limit";
			$query = mysqli_query($con,$sql);
			// This shows the user what page they are on, and the totle number of pages
			$textline1 = "News (<b>$rows</b>)";
			$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
			// Establish the paginationCtrls variable
			$paginationCtrls = '';
			// if there is more than 1 page worth of results
			if($last != 1) {
				$paginationCtrls .= '<div class="pagination" style="margin: 0;">';
				$paginationCtrls .= '<ul>';
				if($pagenum > 1) {
					$previous = $pagenum - 1;
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">&larr;</a></li>';
					// Render clickable number links that should appear on the left of the target page number
					for($i=$pagenum-4; $i < $pagenum; $i++){
						if($i > 0){
							$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
						}
					}
				}
				// Render the target page number, but without it being a link
				$paginationCtrls .= '<li class="active"><a href="#">'.$pagenum.'</a></li>';
				// Render clickable number links that should appear on the right of the page number
				for($i = $pagenum+1; $i <= $last; $i++){
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
					if($i >= $pagenum+4){
						break;
					}
				}
				// This does the same as above, only checking if we are on the last page
				if ($pagenum != $last) {
					$next = $pagenum + 1;
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">&rarr;</a></li>';
				}
				$paginationCtrls .= '</ul>';
				$paginationCtrls .= '</div>';
			}
			$list = '';
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				$news_title   = $row['news_title'];
				$news_content = $row['news_content'];
				$news_user    = $row['news_user'];
				$news_time    = $row['news_time'];
				$news_user_id = $row['news_user_id'];
				$news_id      = $row['id'];
				$list .= '<div class="pull-left"><h5 style="color: #fff; margin-top: 0;">'.$news_title.'</h5></div><div class="pull-right">by <a href="../forum/memberlist.php?mode=viewprofile&amp;u='.$news_user_id.'">'.$news_user.'</a>&nbsp;'.$news_time.'</div>';
				$list .= '<div class="clearfix"></div>';
				$list .= '<div class="newscont">'.$news_content.'</div>';
				$list .= '<hr />';
			}
			mysqli_close($con);
			?>
			<p><?php echo $list; ?></p>
			<div><?php echo $paginationCtrls; ?></div>
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
<script type="text/javascript">
$.backstretch("./images/temp_bg.jpg");
</script>
</body>
</html>