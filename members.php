<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Members List</title>
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
			<div class="pull-right">
				<?php 
				if(isset($_GET['search'])){
					echo '<button class="btn btn-warning" type="button" onClick="window.location=\''.$_SERVER['PHP_SELF'].'\'">Clear Search</button>';
				} else {
					?>
					<form class="form-horizontal" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="text" name="search" placeholder="Search users..." id="user_search" class="form-control" style="width: 150px;">
					<button class="btn btn-success" type="submit">Search</button>
					</form>
				<?php 
				}
				?>
			</div>
			<?php
			if(isset($_GET['search'])){
				$search = preg_replace('#[^a-zA-Z0-9]#', '', $_GET['search']);
				$sql = "SELECT * FROM phpbb_users WHERE username LIKE '%$search%' AND group_id!='6'";
				$query = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
					$username = $row['username'];
					$user_id  = $row['user_id'];
					$user_ava = $row['user_avatar'];
					$list .= '<tr>';
					$list .= '<td style="width: 30px;">';
					if($user_ava == ''){
						$list .= '<img src="../forum/styles/art_ultra_blue/theme/images/avatar.png" style="border: 1px solid #333; width: 30px; height: 30px;">';
					} else {
						$list .= '<img src="../forum/download/file.php?avatar='.$user_ava.'" style="border: 1px solid #333; width: 30px; height: 30px;">';
					}
					$list .= '</td>';
					$list .= '<td onClick="window.location=\'http://crimson-alliance.com/forum/memberlist.php?mode=viewprofile&u='.$user_id.'\'" style="cursor: pointer;">'.$username.'</td>';
					$list .= '</tr>';
				}
				mysqli_close($con);	
			} else {
				$sql = "SELECT COUNT(user_id) FROM phpbb_user_group WHERE group_id='10'";
				$query = mysqli_query($con,$sql);
				$row = mysqli_fetch_row($query);
				// Here we have the total row count
				$rows = $row[0];
				// This is the number of results we want displayed per page
				$page_rows = 15;
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
				$sql = "SELECT user_id FROM phpbb_user_group WHERE group_id='10'";
				$query = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($query)){
					$memberIDS[] = $row['user_id'];
				}
				$memberIDS = implode('\',\'', $memberIDS);
				// This sets the range of rows to query for the chosen $pagenum
				$limit = 'LIMIT '.($pagenum - 1) * $page_rows .',' .$page_rows;
				// This is your query again, it is for grabbing just one page worth of rows by applying $limit
				$sql = "SELECT * FROM phpbb_users WHERE user_id IN ('$memberIDS') ORDER BY username ASC $limit";
				$query = mysqli_query($con,$sql);
				// This shows the user what page they are on, and the totle number of pages
				// $textline1 = "News (<b>$rows</b>)";
				// $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
				// Establish the paginationCtrls variable
				$paginationCtrls = '';
				// if there is more than 1 page worth of results
				if($last != 1) {
					$paginationCtrls .= '<div class="pagination pagination-centered" style="margin: 0;">';
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
					$username = $row['username'];
					$user_id  = $row['user_id'];
					$user_ava = $row['user_avatar'];
					$list .= '<tr>';
					$list .= '<td style="width: 30px;">';
					if($user_ava == ''){
						$list .= '<img src="../forum/styles/art_ultra_blue/theme/images/avatar.png" style="border: 1px solid #333; width: 30px; height: 30px;">';
					} else {
						$list .= '<img src="../forum/download/file.php?avatar='.$user_ava.'" style="border: 1px solid #333; width: 30px; height: 30px;">';
					}
					$list .= '</td>';
					$list .= '<td onClick="window.location=\'http://crimson-alliance.com/forum/memberlist.php?mode=viewprofile&u='.$user_id.'\'" style="cursor: pointer;">'.$username.'</td>';
					$list .= '</tr>';
				}
				mysqli_close($con);
			}
			?>
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th colspan="2">Members</th>
					</tr>
				</thead>
				<tbody style="color: #FFF;">
			<?php echo $list; ?>
				</tbody>
			</table>
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
$.backstretch("./images/temp_bg.jpg");
</script>
<script language="JavaScript" type="text/javascript">
function postform(formName)
{
  document.getElementById(formName).submit() ;
}
</script>
</body>
</html>