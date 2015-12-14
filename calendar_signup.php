<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<?php
$extra_url = '?id='.$_GET['id'];
// Is Request?
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$method = preg_replace('#[^a-z]#', '', $_GET['method']);
	if($method == 'signup'){
		$scoreEnabled = preg_replace('#[^a-z]#', '', $_GET['scoreEnabled']);
		if($scoreEnabled == 'false'){
			// Verify all posted information
			$sp_userName   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userName']);
			$sp_userChar   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userChar']);
			$sp_userClass  = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userClass']);
			$sp_userRole   = preg_replace('#[^a-zA-Z0-9\\-\\040\\(\\)]#', '', $_POST['userRole']);
			$sp_userInfo   = preg_replace('#[^a-zA-Z0-9\\-\\040\\(\\)\\/\\\\]#', '', $_POST['userInfo']);
			$sp_userStatus = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userStatus']);
			$sp_eventID    = preg_replace('#[^0-9]#', '', $_POST['eventID']);
			$sp_userTime   = date('Y-m-d H:i:s');
			// Check if user already signed up, just incase.
			$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$sp_eventID' AND user_name='$sp_userName'";
			$que = mysqli_query($con,$sql);
			$cou = mysqli_fetch_row($que);
			$row = $cou[0];
			if ($row > 0){
				$_SESSION['error'] = 'You have tried to signup twice, please look for your name below.';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			};
			// Continue, user isn't signed up.
			if($stmt = $con->prepare("INSERT INTO event_signups (event_id,user_name,user_char,user_status,user_role,user_info,user_class,user_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
				// Bind the Variables
				$stmt->bind_param("ssssssss", $sp_eventID, $sp_userName, $sp_userChar, $sp_userStatus, $sp_userRole, $sp_userInfo, $sp_userClass, $sp_userTime);
				$stmt->execute();
				$stmt->close();
				$_SESSION['success'] = 'Signup Successful';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			} else {
				$_SESSION['error'] = 'Signup has failed. '.mysqli_error($con).'';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			};
		} else if ($scoreEnabled == 'true'){
			// Verify all posted information
			$sp_userName   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userName']);
			$sp_userChar   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userChar']);
			$sp_userClass  = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userClass']);
			$sp_userRole   = preg_replace('#[^a-zA-Z0-9\\-\\040\\(\\)]#', '', $_POST['userRole']);
			$sp_userInfo   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userInfo']);
			$sp_userStatus = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userStatus']);
			$sp_eventID    = preg_replace('#[^0-9]#', '', $_POST['eventID']);
			$sp_eventDate  = preg_replace('#[^a-zA-Z0-9\\-\\040\\_\\\\]#', '',$_POST['event_date']);
			$sp_userTime   = date('Y-m-d H:i:s');
			// Check if user already signed up, just incase.
			$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$sp_eventID' AND user_name='$sp_userName'";
			$que = mysqli_query($con,$sql);
			$cou = mysqli_fetch_row($que);
			$row = $cou[0];
			if ($row > 0){
				$_SESSION['error'] = 'You have tried to signup twice, please look for your name below.';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			};
			// Continue, user isn't signed up.
			if($stmt = $con->prepare("INSERT INTO event_signups (event_id,user_name,user_char,user_status,user_role,user_info,user_class,user_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
				// Bind the Variables
				$stmt->bind_param("ssssssss", $sp_eventID, $sp_userName, $sp_userChar, $sp_userStatus, $sp_userRole, $sp_userInfo, $sp_userClass, $sp_userTime);
				$stmt->execute();
				$stmt->close();
			} else {
				$_SESSION['error'] = 'Signup has failed. '.mysqli_error($con).'';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			};
			if($sp_userStatus != 'Unavailable'){
				mysqli_query($con,"START TRANSACTION");
				$sql  = mysqli_query($con,"UPDATE raider_info_final SET signups=signups+1 WHERE (username='$sp_userName' OR username='$sp_userChar') AND (signups<10 AND attended>0)");
				$sql2 = mysqli_query($con,"UPDATE raider_info_final SET attended=attended-1 WHERE (username='$sp_userName' OR username='$sp_userChar') AND (signups=10 AND attended>0)");
				if($sql and $sql2){
					$_SESSION['success'] = 'Signup Successful.';
					header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
					exit();
				} else {
					$_SESSION['error'] = 'Signup unsuccessful '.mysqli_error($con).'';
					header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
					exit();
				}
			} else {
				$_SESSION['success'] = 'Signup Successful.';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$sp_eventID.'');
				exit();
			}
		}
	}
	if($method == 'status'){
		// Verify Information
		$st_eventID = preg_replace('#[^0-9]#', '', $_GET['id']);
		$st_userID = preg_replace('#[^0-9]#', '', $_POST['su_userID']);
		$st_userNO = preg_replace('#[^0-9]#', '', $_POST['su_userNO']);
		$st_scorEN = $_POST['su_scorEN'];
		$st_userST = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['su_userST_'.$st_userNO.'']);
		$st_userDate = date('Y-m-d H:i:s',time());
		// Verify user is in the signups.. just incase.
		$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$st_eventID' AND id='$st_userID'";
		$que = mysqli_query($con,$sql);
		$cou = mysqli_fetch_row($que);
		$row = $cou[0];
		if($row > 0){
			$sql = "SELECT user_status FROM event_signups WHERE event_id='$st_eventID' AND id='$st_userID'";
			$que = mysqli_query($con,$sql);
			$cou = mysqli_fetch_row($que);
			$st_userST_OLD = $cou[0];
			if($st_userST == 'Unavailable'){
				$sql = "UPDATE event_signups SET user_status='$st_userST', user_time='$st_userDate' WHERE id='$st_userID'";
				if(mysqli_query($con,$sql)){
					if($st_scorEN == '1'){
						mysqli_query($con,"START TRANSACTION");
						$sql  = mysqli_query($con,"UPDATE raider_info_final SET signups=signups-1 WHERE (username='$st_userName' OR username='$st_userChar') AND (signups<10 AND attended>0) AND (signups>1)");
						$sql2 = mysqli_query($con,"UPDATE raider_info_final SET attended=attended+1 WHERE (username='$st_userName' OR username='$st_userChar') AND (signups=10 AND attended>0)");
						if($sql and $sql2){
							$_SESSION['success'] = 'Status edited successfully.';
							header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
							exit();
						} else {
							$_SESSION['error'] = 'Status edit unsuccessful '.mysqli_error($con).'';
							header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
							exit();
						}
					} else {
						$_SESSION['success'] = 'Status edited successfully.';
						header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
						exit();
					}
				} else {
					$_SESSION['error'] = 'Status edit unsuccessful. '.mysqli_error($con).'';
					header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
					exit();
				}
			} else {
				if($st_userST_OLD == 'Unavailable'){
					$sql = "UPDATE event_signups SET user_status='$st_userST', user_time='$st_userDate' WHERE id='$st_userID'";
					if(mysqli_query($con,$sql)){
						if($st_scorEN == '1'){
							mysqli_query($con,"START TRANSACTION");
							$sql  = mysqli_query($con,"UPDATE raider_info_final SET signups=signups+1 WHERE (username='$st_userName' OR username='$st_userChar') AND (signups<10 AND attended>0)");
							$sql2 = mysqli_query($con,"UPDATE raider_info_final SET attended=attended-1 WHERE (username='$st_userName' OR username='$st_userChar') AND (signups=10 AND attended>0)");
							if($sql and $sql2){
								$_SESSION['success'] = 'Signup Successful.';
								header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
								exit();
							} else {
								$_SESSION['error'] = 'Signup unsuccessful '.mysqli_error($con).'';
								header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
								exit();
							}
						} else {
							$_SESSION['success'] = 'Status edited successfully.';
							header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
							exit();
						}
					} else {
						$_SESSION['error'] = 'Status edit unsuccessful. '.mysqli_error($con).'';
						header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
						exit();
					}
				} else {
					$sql = "UPDATE event_signups SET user_status='$st_userST' WHERE id='$st_userID'";
					if(mysqli_query($con,$sql)){
						$_SESSION['success'] = 'Status edited successfully.';
						header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
						exit();
					} else {
						$_SESSION['error'] = 'Status edit unsuccessful. '.mysqli_error($con).'';
						header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
						exit();
					}
				}
			}
		} else {
			$_SESSION['error'] = 'Editing your Status has failed, you don\'t seem to be signed up...';
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$st_eventID.'');
			exit();
		}
	}
	if($method == 'role'){
		// Verify Information
		$ro_userID = preg_replace('#[^0-9]#', '', $_POST['su_userID']);
		$ro_userRO = preg_replace('#[^^a-zA-Z0-9\\-\\040\\(\\)]#', '', $_POST['userRole']);
		$ro_eventID = preg_replace('#[^0-9]#', '', $_GET['id']);
		// Verify user is in the signups.. just incase.
		$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$ro_eventID' AND id='$ro_userID'";
		$que = mysqli_query($con,$sql);
		$cou = mysqli_fetch_row($que);
		$row = $cou[0];
		if($row > 0){
			$sql = "UPDATE event_signups SET user_role='$ro_userRO' WHERE id='$ro_userID'";
			if(mysqli_query($con,$sql)){
				$_SESSION['success'] = 'Role edited successfully.';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ro_eventID.'');
				exit();
			} else {
				$_SESSION['error'] = 'Role edit failed.'.mysqli_error($con).'';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ro_eventID.'');
				exit();
			}
		} else {
			$_SESSION['error'] = 'Editing your Role has failed, you don\'t seem to be signed up...';
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ro_eventID.'');
			exit();
		}
	}
	if($method == 'character'){
		$ch_userID = preg_replace('#[^0-9]#', '', $_POST['su_userID']);
		$ch_userCH = preg_replace('#[^0-9A-Za-z]#', '', $_POST['userChar']);
		$ch_eventID = preg_replace('#[^0-9]#' ,'', $_GET['id']);
		if ($ch_userCH == $userChar1){ $ch_userCL = $userMainClass; };
		if ($ch_userCH == $userChar2){ $ch_userCL = $userAlt1Class; };
		if ($ch_userCH == $userChar3){ $ch_userCL = $userAlt2Class; };
		if ($ch_userCH == $userChar4){ $ch_userCL = $userAlt3Class; };
		if ($ch_userCL == '1'){ $ch_userCL = 'Warrior'; };
		if ($ch_userCL == '2'){ $ch_userCL = 'Cleric'; };
		if ($ch_userCL == '3'){ $ch_userCL = 'Mage'; };
		if ($ch_userCL == '4'){ $ch_userCL = 'Rogue'; };
		// Verify user is in the signups.. just incase.
		$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$ch_eventID' AND id='$ch_userID'";
		$que = mysqli_query($con,$sql);
		$cou = mysqli_fetch_row($que);
		$row = $cou[0];
		if($row > 0){
			$sql = "UPDATE event_signups SET user_char='$ch_userCH', user_class='$ch_userCL' WHERE id='$ch_userID'";
			if(mysqli_query($con,$sql)){
				$_SESSION['success'] = 'Character changed successfully.';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ch_eventID.'');
				exit();
			} else {
				$_SESSION['error'] = 'Character change failed. '.mysqli_error($con).'';
				header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ch_eventID.'');
				exit();
			}
		} else {
			$_SESSION['error'] = 'Editing your Role has failed, you don\'t seem to be signed up...';
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$ch_eventID.'');
			exit();
		}
	}
	if($method == 'comment'){
		$co_userNA = preg_replace('#[^0-9A-Za-z]#', '', $_POST['user_name']);
		$co_userCO = preg_replace('#[^0-9A-Za-z]#', '', $_POST['user_color']);
		$co_userID = preg_replace('#[^0-9]#', '', $_POST['user_id']);
		$co_userCM = $_POST['user_comment'];
		$co_eventID = preg_replace('#[^0-9]#' ,'', $_GET['id']);
		$co_userTI = date('Y-m-d H:i:s');
		$sql = "INSERT INTO event_comments (event_id,user_name,user_comment,user_time,user_colour,user_id) VALUES ('$co_eventID','$co_userNA','$co_userCM','$co_userTI','$co_userCO','$co_userID')";
		if(mysqli_query($con,$sql)){
			$_SESSION['success'] = 'Comment added successfully.';
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$co_eventID.'');
			exit();
		} else {
			$_SESSION['error'] = 'Comment addition failed. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$co_eventID.'');
			exit();
		}
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Event Signup</title>
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
<?php
// Get Admin Variables
$sql = "SELECT * FROM admin_variables";
$que = mysqli_query($con,$sql);
$row = mysqli_fetch_row($que);
$stat_changes = $row[1];
$sign_changes = $row[2];
$comm_changes = $row[3];
$admi_changes = $row[4];
?>
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
					<li class="active">
						<a href="<?php echo ''.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].''; ?>">Calendar Signup</a>
					</li>
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
		<div class="span12">
			<?php //  ?>
			<?php
		if(!isset($_GET['id'])){
			echo '<div class="alert alert-error">';
			echo '<strong>Error</strong> ';
			echo 'You have not selected an event... please select one above.';
			echo '</div>';
			} else {
				$eventID = preg_replace('#[^0-9]#', '', $_GET['id']);
				$sql = "SELECT count(id) FROM events_final WHERE id=$eventID";
				$que = mysqli_query($con,$sql);
				$row = mysqli_fetch_array($que);
				$count = $row[0];
				if ($count < 1){
					echo '<div class="alert alert-error">';
					echo '<strong>Error</strong> ';
					echo 'You have selected an invalid event ID, please select an event from above.';
					echo '</div>';
				} else {
					$sql = "SELECT * FROM events_final WHERE id=$eventID";
					$que = mysqli_query($con,$sql);
					$row = mysqli_fetch_array($que);
					$eventName = $row['title'];
					$eventDate = $row['datetime'];
					$eventDesc = $row['description'];
					$eventScor = $row['enable_score'];
					$eventRaid = $row['enable_raiders'];
					$eventTier = $row['tier'];
					$eventPpl  = $row['people'];
					$eventHit  = $row['hit'];
					$eventAdmi = $row['adminName'];
					
					// Begin Output of Event Details
					echo '<div class="pull-left" style="width: 30%;">';
					echo '<h3 style="margin-bottom: 0;">'.$eventName.'</h3>';
					echo '<div>';
						echo date('l \t\h\e jS \o\f F \a\t g:ia',strtotime($eventDate)+$userTimeZone);
					echo '</div>';
					echo '<div>';
						echo '<span class="label label-info">'.$eventPpl.' Man</span>&nbsp;';
						echo '<span class="label label-success">Tier '.$eventTier.'</span>&nbsp;';
						echo '<span class="label label">'.$eventHit.' Hit Required</span>&nbsp;';
						if($eventScor == '1'){
							echo '<span class="label label-inverse">Score On</span>';
						} else {
							echo '<span class="label label">Score Off</span>';
						}
					echo '</div>';
					echo '<div>';
						echo '<small>Event created by: '.$eventAdmi.'</small>';
					echo '</div>';
					echo '<div>';
					if(strtotime($eventDate)+$admi_changes > time()){
						if($perms_raidStat == '1'){
							echo '<button type="button" onClick="showHideAdminControls()" class="btn btn-warning">Admin Controls</button>&nbsp;';
						};
						if(($perms_suicEdit == '1') && (($eventScor == '1'))){
							echo '<button type="button" onClick="setupSuicide()" class="btn btn-danger">Setup Suicide</button>';
						};
					};
					echo '<br />';
					$eventStariCal = date('Ymd\THis',abs(strtotime($eventDate)+$userTimeZone));
					$eventStopiCal = date('Ymd\THis',abs(strtotime($eventDate)+$userTimeZone+10800));
					$datestart   = $eventStariCal;
					$dateend     = $eventStopiCal;
					$address     = "Rift";
					$uri         = $_SERVER['PHP_SELF'].$extra_url;
					$description = $eventName;
					$filename    = "iCal_".$extra_url;
					echo '<a href="./iCalendar.php?summary='.$eventName.'&datestart='.$datestart.'&dateend='.$dateend.'&address='.$address.'&uri='.$uri.'&description='.$description.'&filename='.$filename.'" target="_blank">';
					echo 'Export to Outlook';
					echo '</a>';
					echo '</div>';
					echo '</div>';
					echo '<div class="pull-right" style="width: 70%;">';
					echo '<div style="background: #070809; border: 1px solid #000000; padding: 5px; overflow-y: auto; height: 130px; max-height: 130px; min-height: 130px;">';
					echo $eventDesc;
					echo '</div>';
					echo '</div>';
					echo '<div class="clearfix"></div>';
				}
			?>
			<?php
				// Begin output of Signup Form
				// Check if user is enabled for signups for the teir, if score mode is enabled
			if ($userName != 'Anonymous') {
				// Begin Output of Errors
				if($_SESSION['error'] != ''){
					echo '<br />';
					echo '<div class="alert alert-danger" style="margin-bottom: -20px;">';
					echo '<strong>Error: </strong> ';
					echo $_SESSION['error'];
					echo '</div>';
					$_SESSION['error'] = '';
				}
				if($_SESSION['success'] != ''){
					echo '<br />';
					echo '<div class="alert alert-success" style="margin-bottom: -20px;">';
					echo '<strong>Success: </strong> ';
					echo $_SESSION['success'];
					echo '</div>';
					$_SESSION['success'] = '';
				}
				//
				if(strtotime($eventDate)+$sign_changes < time()){
					echo '<br />';
					echo '<div class="alert alert-info">';
					echo '<strong>Info: </strong> ';
					echo 'Signups for this event have closed.';
					echo '</div>';
				} else {
					$sql = "SELECT COUNT(id) FROM event_signups WHERE event_id='$eventID' AND user_name='$userName'";
					$query = mysqli_query($con,$sql);
					$row = mysqli_fetch_row($query);
					$cou = $row[0];
					if($cou == 0){
						if (($eventScor == '1') || ($eventRaid == '1')){
							$tierCheck = 't'.$eventTier.'_'.$eventPpl;
							// $userName = 'DerpyDerp';
							// $userChar1 = 'DerpyDerp';
							$sql = "SELECT $tierCheck FROM raider_info_final WHERE username='$userName' OR username='$userChar1'";
							$que = mysqli_query($con,$sql);
							$res = mysqli_fetch_row($que);
							$uT  = $res[0];
							if(($uT == '----') || ($uT == '')){
								echo '<br />';
								echo '<div class="alert alert-info">';
								echo '<strong>Info: </strong> ';
								echo 'You are not permitted to sign up to this tier of raiding. Please check the <a href="./raiders.php" style="color: #0F0;">Raiders List</a> for your status, and talk to your Guild Master about joining that tier.';
								echo '</div>';
							} else { ?>
							<h5>Signup</h5>
							<div>
								<?php if($eventScor == '1'){ ?>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $eventID; ?>&method=signup&scoreEnabled=true" method="post" class="noBM">
								<?php } else { ?>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $eventID; ?>&method=signup&scoreEnabled=false" method="post" class="noBM">
								<?php } ?>
									<?php
									$userStatus = array('Available','Tentative','Standby','Unavailable');
									$userStatusInfo = array('Able to make it.','Real Life may interfere.','Real Life has a high chance of interfering.','Not able to make it.');
									$uT = str_replace('-','',$uT);
									$userRoles = array('Any ('.$uT.')');
									if(strlen(strstr($uT,'T'))>0){ array_push($userRoles,'Tank'); };
									if(strlen(strstr($uT,'H'))>0){ array_push($userRoles,'Healer'); };
									if(strlen(strstr($uT,'D'))>0){ array_push($userRoles,'Damage'); };
									if(strlen(strstr($uT,'S'))>0){ array_push($userRoles,'Support'); };
									$userClass = array('Warrior','Cleric','Mage','Rogue');
									?>
									<input type="hidden" value="<?php echo $userName; ?>" name="userName">
									<!-- START CHARACTER SELECTION -->
									<select name="userChar" onChange="return checkClass();" id="userChar">
									<?php if($userChar1 != ''){ ?>
									<option value="<?php echo $userChar1; ?>"><?php echo $userChar1; ?></option>
									<?php } ?>
									</select>
									<!-- START CLASS SELECTION -->
									<select name="userClass">
									<?php
									for($i=1; $i <= 4; $i++){
										if($userMainClass == $i){ echo '<option value="'.$userClass[$i-1].'" id="su_userClass_'.$userClass[$i-1].'" selected>'.$userClass[$i-1].'</option>'; };
									};
									?>
									</select>
									<!-- START USER ROLE SELECTION -->
									<select name="userRole">
									<?php
									foreach($userRoles as $userRoles){
										echo '<option value="'.$userRoles.'">'.$userRoles.'</option>';
									}
									?>
									</select>
									<!-- START USER INFO SELECTION -->
									<input type="text" placeholder="Info" value="<?php echo $user->profile_fields['pf_hit_toughness']; ?>" name="userInfo">
									<!-- START USER STATUS SELECTION -->
									<select name="userStatus">
									<?php
									for($i=0;$i<count($userStatus);$i++){
										echo '<option value="'.$userStatus[$i].'" title="'.$userStatusInfo[$i].'">'.$userStatus[$i].'</option>';
									}
									?>
									</select>
									<input type="hidden" name="eventID" value="<?php echo $eventID; ?>">
									<input type="hidden" name="event_date" value="<?php echo $eventDate; ?>">
									
									<button type="submit" class="btn btn-success">Signup</button>
								</form>
							<?php 
							}
						} else if(in_array('10',$userGroupIDs)) {
							?>
						<h5>Signup</h5>
						<div>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $eventID; ?>&method=signup&scoreEnabled=false" method="post" class="noBM">
								<?php
								$userStatus = array('Available','Tentative','Standby','Unavailable');
								$userStatusInfo = array('Able to make it.','Real Life may interfere.','Real Life has a high chance of interfering.','Not able to make it.');
								$userRoles  = array('Any','DPS','Healer','Main Tank','Off Tank','Support');
								$userClass = array('Warrior','Cleric','Mage','Rogue');
								?>
								<input type="hidden" value="<?php echo $userName; ?>" name="userName">
								<!-- START CHARACTER SELECTION -->
								<select name="userChar" onChange="return checkClass();" id="su_userChar">
								<?php if($userChar1 != ''){ ?>
								<option value="<?php echo $userChar1; ?>"><?php echo $userChar1; ?></option>
								<?php } ?>
								<?php if($userChar2 != ''){ ?>
								<option value="<?php echo $userChar2; ?>"><?php echo $userChar2; ?></option>
								<?php } ?>
								<?php if($userChar3 != ''){ ?>
								<option value="<?php echo $userChar3; ?>"><?php echo $userChar3; ?></option>
								<?php } ?>
								<?php if($userChar4 != ''){ ?>
								<option value="<?php echo $userChar4; ?>"><?php echo $userChar4; ?></option>
								<?php } ?>
								</select>
								<!-- START CLASS SELECTION -->
								<select name="userClass">
								<?php
								for($i=1; $i <= 4; $i++){
									echo '<option value="'.$userClass[$i-1].'" id="su_userClass_'.$i.'"';
									if ($userMainClass == $i){ echo 'selected'; };
									echo '>'.$userClass[$i-1].'</option>';
								};
								?>
								</select>
								<!-- START USER ROLE SELECTION -->
								<select name="userRole">
								<?php
								foreach($userRoles as $userRole){
									echo '<option value="'.$userRole.'">'.$userRole.'</option>';
								}
								?>
								</select>
								<!-- START USER INFO SELECTION -->
								<input type="text" placeholder="Info" value="<?php echo $user->profile_fields['pf_hit_toughness']; ?>" name="userInfo">
								<!-- START USER STATUS SELECTION -->
								<select name="userStatus">
								<?php
								for($i=0;$i<count($userStatus);$i++){
									echo '<option value="'.$userStatus[$i].'" title="'.$userStatusInfo[$i].'">'.$userStatus[$i].'</option>';
								}
								?>
								</select>
								<input type="hidden" name="eventID" value="<?php echo $eventID; ?>">
								
								<button type="submit" class="btn btn-success">Signup</button>
							</form>
						<?php } ?>
						</div>
					<?php 
					} else {
						echo '<br />';
						echo '<div class="alert alert-info">';
						echo '<strong>Info: </strong> ';
						echo 'You are already signed up for this raid. To change your information, please look below.';
						echo '</div>';
					}?>
				<?php 
				}
			}
			?>
			<hr />
			<table class="table table-hover table-custom">
				<thead>
					<tr>
						<th width="5%" style="text-align: center;">#</th>
						<!-- <th width="13%">Name</th> -->
						<th width="20%">Character</th>
						<th width="10%">Class</th>
						<th width="12%">Role</th>
						<th width="20%">Info</th>
						<th width="10%">Status</th>
						<th width="10%">Time</th>
					</tr>
				</thead>
				<tbody style="color: #FFF; font-size: 13px;">
					<?php
					// $eventScor = '0';
					if ($eventScor == '1'){
						if(strtotime($eventDate)+$stat_changes <= time()){
							$sql = "SELECT COUNT(id) FROM final_event_signups WHERE event_id='$eventID'";
							$res = mysqli_query($con,$sql);
							$cou = mysqli_fetch_row($res);
							if ($cou[0] < 1){
								$sql = "INSERT INTO final_event_signups 
										SELECT * FROM event_signups t2 
										WHERE t2.event_id='$eventID'";
								if(mysqli_query($con,$sql)){
								$sql = "UPDATE final_event_signups es, raider_info_final rif
										SET es.user_score = (rif.attended / rif.signups)
										WHERE (es.user_name = rif.username OR es.user_char = rif.username) AND es.event_id='$eventID'";
									if(mysqli_query($con,$sql)){
										
									} else {
										echo mysqli_error($con);
									}
								} else {
									echo mysqli_error($con);
								}
							}
						$sql = "SELECT * FROM final_event_signups WHERE event_id='$eventID' ORDER BY FIELD(user_status,'Unavailable') ASC, user_score ASC, user_time";
						$que = mysqli_query($con,$sql);
						} else {
							$sql = "SELECT DISTINCT event_signups.* FROM event_signups
									INNER JOIN raider_info_final ON event_signups.user_char = raider_info_final.username OR event_signups.user_name = raider_info_final.username
									WHERE event_signups.event_id='$eventID'
									GROUP BY event_signups.user_name
									ORDER BY FIELD(event_signups.user_status,'Unavailable') ASC, (raider_info_final.attended / raider_info_final.signups) ASC, event_signups.user_time";
							$que = mysqli_query($con,$sql);
						}
						$i = 0;
						while ($row = mysqli_fetch_array($que)){
							$i ++;
							$su_userID   = $row['id'];
							$su_userName = $row['user_name'];
							$su_userStat = $row['user_status'];
							$su_userChar = $row['user_char'];
							$su_userRole = $row['user_role'];
							$su_userInfo = $row['user_info'];
							$su_userClas = $row['user_class'];
							$su_userTime = date('d-m H:i',strtotime($row['user_time'])+$userTimeZone);
							
							if($userName == $su_userName){
								echo '<tr style="background: #60187D !important;">';
							} else if($su_userStat == 'Available'){
								echo '<tr style="background: #347C17 !important;">';
							} else if($su_userStat == 'Tentative'){
								echo '<tr style="background: #EE9A4D !important;">';
							} else if($su_userStat == 'Standby'){
								echo '<tr style="background: #CD7F32 !important;">';
							} else if($su_userStat == 'Unavailable'){
								echo '<tr style="background: #800517 !important;">';
							} else {
								echo '<tr>';
							}
							
							echo '<td style="text-align: center;">'.$i.'</td>';
							// echo '<td>'.$su_userName.'</td>';
							echo '<td>'.$su_userChar.'</td>';
							echo '<td>'.$su_userClas.'</td>';
							echo '<td>'.$su_userRole.'</td>';
							echo '<td>'.$su_userInfo.'</td>';
							// Setup User Status
							echo '<td>';
							if(strtotime($eventDate)+$stat_changes > time()){
								if($userName == $su_userName){
									// User Change Status
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=status" method="post" style="margin: 0 0 -4px 0 !important;">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<input type="hidden" name="su_userNO" value="'.$i.'">';
									echo '<input type="hidden" name="su_scorEN" value="'.$eventScor.'">';
									echo '<select name="su_userST_'.$i.'" onChange="disp_confirm(this);" style="margin: 0 !important; width: 80%;">';
									echo '<option value="Available"';
									if($su_userStat == 'Available'){ echo 'selected'; };
									echo '>Available</option>';
									echo '<option value="Tentative"';
									if($su_userStat == 'Tentative'){ echo 'selected'; };
									echo '>Tentative</option>';
									echo '<option value="Standby"';
									if($su_userStat == 'Standby'){ echo 'selected'; };
									echo '>Standby</option>';
									echo '<option value="Unavailable"';
									if($su_userStat == 'Unavailable'){ echo 'selected'; };
									echo '>Unavailable</option>';
									echo '</select>';
									
									echo '</form>';
								} else if($perms_raidStat == '1'){
									// Admin Change Status
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=status" method="post" style="margin: 0 !important; display: none;" class="su_admin_Form">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<input type="hidden" name="su_userNO" value="'.$i.'">';
									echo '<input type="hidden" name="su_scorEN" value="'.$eventScor.'">';
									echo '<select name="su_userST_'.$i.'" onChange="disp_confirm(this);" style="margin: 0 0 -4px 0 !important; width: 80%;">';
									echo '<option value="Available"';
									if($su_userStat == 'Available'){ echo 'selected'; };
									echo '>Available</option>';
									echo '<option value="Tentative"';
									if($su_userStat == 'Tentative'){ echo 'selected'; };
									echo '>Tentative</option>';
									echo '<option value="Standby"';
									if($su_userStat == 'Standby'){ echo 'selected'; };
									echo '>Standby</option>';
									echo '<option value="Unavailable"';
									if($su_userStat == 'Unavailable'){ echo 'selected'; };
									echo '>Unavailable</option>';
									echo '</select>';
									
									echo '</form>';
									echo '<div class="su_admin_Text" style="display: block;">';
									echo $su_userStat;
									echo '</div>';
								} else {
									echo $su_userStat;
								}
							} else {
								echo $su_userStat;
							}
							echo '</td>';
							//
							echo '<td style="font-size: 11px;">'.$su_userTime.'</td>';
							
							echo '</tr>';
						}
					} else {
						$sql = "SELECT * FROM event_signups WHERE event_id=$eventID ORDER BY FIELD(user_status,'Unavailable') ASC, user_time";
						$que = mysqli_query($con,$sql);
						$i = 0;
						while ($row = mysqli_fetch_array($que)){
							$i ++;
							$su_userID   = $row['id'];
							$su_userName = $row['user_name'];
							$su_userStat = $row['user_status'];
							$su_userChar = $row['user_char'];
							$su_userRole = $row['user_role'];
							$su_userInfo = $row['user_info'];
							$su_userClas = $row['user_class'];
							$su_userTime = date('d-m H:i',strtotime($row['user_time'])+$userTimeZone);
							
							if($userName == $su_userName){
								echo '<tr style="background: #60187D !important;">';
							} else if($su_userStat == 'Available'){
								echo '<tr style="background: #347C17 !important;">';
							} else if($su_userStat == 'Tentative'){
								echo '<tr style="background: #EE9A4D !important;">';
							} else if($su_userStat == 'Standby'){
								echo '<tr style="background: #CD7F32 !important;">';
							} else if($su_userStat == 'Unavailable'){
								echo '<tr style="background: #800517 !important;">';
							} else {
								echo '<tr>';
							}
							
							echo '<td style="text-align: center;">'.$i.'</td>';
							// echo '<td>'.$su_userName.'</td>';
							// Setup User Character
							echo '<td>';
							if(strtotime($eventDate)+$stat_changes > time()){
								if($userName == $su_userName){
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=character" method="post" style="margin: 0 !important;">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<select name="userChar" onChange="disp_confirm(this);" id="userChar" style="margin: 0 0 -4px 0 !important;">';
									if($userChar1 != ''){
										echo '<option value="'.$userChar1.'"';
										if($su_userChar == $userChar1){ echo 'selected'; };
										echo '>'.$userChar1.'</option>';
									}
									if($userChar2 != ''){
										echo '<option value="'.$userChar2.'"';
										if($su_userChar == $userChar2){ echo 'selected'; };
										echo '>'.$userChar2.'</option>';
									}
									if($userChar3 != ''){
										echo '<option value="'.$userChar3.'"';
										if($su_userChar == $userChar3){ echo 'selected'; };
										echo '>'.$userChar3.'</option>';
									}
									if($userChar4 != ''){
										echo '<option value="'.$userChar4.'"';
										if($su_userChar == $userChar4){ echo 'selected'; };
										echo '>'.$userChar4.'</option>';
									}
									echo '</select>';
									
									echo '</form>';
								} else {
									echo $su_userChar;
								}
							} else {
								echo $su_userChar;
							}
							echo '</td>';
							//
							echo '<td>'.$su_userClas.'</td>';
							// Setup Role Selection
							echo '<td>';
							if(strtotime($eventDate)+$stat_changes > time()){
								if($userName == $su_userName){
									$userRoles  = array('Any','DPS','Healer','Main Tank','Off Tank','Support');
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=role" method="post" style="margin: 0 !important;">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<select name="userRole" onChange="disp_confirm(this);" id="userRole" style="margin: 0 0 -4px 0 !important;">';
									foreach($userRoles as $userRole){
										echo '<option value="'.$userRole.'"';
										if($userRole == $su_userRole){ echo 'selected'; };
										echo '>'.$userRole.'</option>';
									}
									echo '</select>';
									
									echo '</form>';
								} else {
									echo $su_userRole;
								}
							} else {
								echo $su_userRole;
							}
							echo '</td>';
							//
							echo '<td>'.$su_userInfo.'</td>';
							// Setup User Status
							echo '<td>';
							if(strtotime($eventDate)+$stat_changes > time()){
								if($userName == $su_userName){
									// User Change Status
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=status" method="post" style="margin: 0 0 -4px 0 !important;">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<input type="hidden" name="su_userNO" value="'.$i.'">';
									echo '<select name="su_userST_'.$i.'" onChange="disp_confirm(this);" style="margin: 0 !important; width: 80%;">';
									echo '<option value="Available"';
									if($su_userStat == 'Available'){ echo 'selected'; };
									echo '>Available</option>';
									echo '<option value="Tentative"';
									if($su_userStat == 'Tentative'){ echo 'selected'; };
									echo '>Tentative</option>';
									echo '<option value="Standby"';
									if($su_userStat == 'Standby'){ echo 'selected'; };
									echo '>Standby</option>';
									echo '<option value="Unavailable"';
									if($su_userStat == 'Unavailable'){ echo 'selected'; };
									echo '>Unavailable</option>';
									echo '</select>';
									
									echo '</form>';
								} else if($perms_raidStat == '1'){
									// Admin Change Status
									echo '<form action="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&method=status" method="post" style="margin: 0 !important; display: none;" class="su_admin_Form">';
									echo '<input type="hidden" name="su_userID" value="'.$su_userID.'">';
									echo '<input type="hidden" name="su_userNO" value="'.$i.'">';
									echo '<select name="su_userST_'.$i.'" onChange="disp_confirm(this);" style="margin: 0 0 -4px 0 !important; width: 80%;">';
									echo '<option value="Available"';
									if($su_userStat == 'Available'){ echo 'selected'; };
									echo '>Available</option>';
									echo '<option value="Tentative"';
									if($su_userStat == 'Tentative'){ echo 'selected'; };
									echo '>Tentative</option>';
									echo '<option value="Standby"';
									if($su_userStat == 'Standby'){ echo 'selected'; };
									echo '>Standby</option>';
									echo '<option value="Unavailable"';
									if($su_userStat == 'Unavailable'){ echo 'selected'; };
									echo '>Unavailable</option>';
									echo '</select>';
									
									echo '</form>';
									echo '<div class="su_admin_Text" style="display: block;">';
									echo $su_userStat;
									echo '</div>';
								} else {
									echo $su_userStat;
								}
							} else {
								echo $su_userStat;
							}
							echo '</td>';
							//
							echo '<td style="font-size: 11px;">'.$su_userTime.'</td>';
							
							echo '</tr>';
						}
					}
					?>
				</tbody>
			</table>
			<div id="comments"></div>
			<!-- START COMMENTS -->
			<?php
			$sql = "SELECT COUNT(id) FROM event_comments WHERE event_id=$eventID";
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
			$sql = "SELECT * FROM event_comments WHERE event_id=$eventID ORDER BY user_time DESC $limit";
			$query = mysqli_query($con,$sql);
			// This shows the user what page they are on, and the totle number of pages
			// $textline1 = "News (<b>$rows</b>)";
			// $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
			// Establish the paginationCtrls variable
			$paginationCtrls = '';
			// if there is more than 1 page worth of results
			if($last != 1) {
				$paginationCtrls .= '<div class="pagination" style="margin: 0;">';
				$paginationCtrls .= '<ul>';
				if($pagenum > 1) {
					$previous = $pagenum - 1;
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&amp;pn='.$previous.'#comments">&larr;</a></li>';
					// Render clickable number links that should appear on the left of the target page number
					for($i=$pagenum-4; $i < $pagenum; $i++){
						if($i > 0){
							$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&amp;pn='.$i.'#comments">'.$i.'</a></li>';
						}
					}
				}
				// Render the target page number, but without it being a link
				$paginationCtrls .= '<li class="active"><a href="#">'.$pagenum.'</a></li>';
				// Render clickable number links that should appear on the right of the page number
				for($i = $pagenum+1; $i <= $last; $i++){
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&amp;pn='.$i.'#comments">'.$i.'</a></li>';
					if($i >= $pagenum+4){
						break;
					}
				}
				// This does the same as above, only checking if we are on the last page
				if ($pagenum != $last) {
					$next = $pagenum + 1;
					$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?id='.$eventID.'&amp;pn='.$next.'#comments">&rarr;</a></li>';
				}
				$paginationCtrls .= '</ul>';
				$paginationCtrls .= '</div>';
			}
			$list = '';
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				$com_userName = $row['user_name'];
				$com_userCom  = $row['user_comment'];
				$com_userTime = date('d-m-Y H:i:s',strtotime($row['user_time'])+$userTimeZone);
				$com_userColr = $row['user_colour'];
				$com_userID   = $row['user_id'];
				
				$list .= '<div style="background: #070809; border: 1px solid #000000; padding: 5px;">'.$com_userCom.'</div>';
				$list .= '<div class="pull-right">by <a style="color: #'.$com_userColr.';" href="../forum/memberlist.php?mode=viewprofile&amp;u='.$com_userID.'">'.$com_userName.'</a>&nbsp;at '.$com_userTime.'</div>';
				$list .= '<div class="clearfix"></div>';
				$list .= '<hr />';
			}
			mysqli_close($con);
			?>
			<p><?php echo $list; ?></p>
			<div><?php echo $paginationCtrls; ?></div>
			<?php
			if(strtotime($eventDate)+$comm_changes > time()){
				if($userName != 'Anonymous'){
				?>
			<hr />
			
			<form action="<?php echo ''.$_SERVER['PHP_SELF'].'?id='.$eventID.''?>&method=comment" method="post" style="margin: 0;">
			<input type="hidden" name="user_name" value="<?php echo $user->data['username']; ?>">
			<input type="hidden" name="user_color" value="<?php echo $user->data['user_colour']; ?>">
			<input type="hidden" name="user_id" value="<?php echo $user->data['user_id']; ?>">
			<textarea id="user_comment" name="user_comment" style="width: 100%; height: 60px;"></textarea>
			<button type="submit" class="btn btn-success pull-right">Post Comment</button>
			</form>
			<div class="clearfix"></div>
				<?php
				}
			} 
			?>
	<?php } ?>
		</div><!--/span12-->
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
<script src="./ckeditor_min/ckeditor.js"></script>
<script type="text/javascript">
function disp_confirm(formField){
	if ( $(formField).val() == 'Unavailable' ){
		var r = confirm("You are changing to \"Unavailable\".\n-> Your signup time will be reset. <-\nContinue?");
		if (r == true){
			$(formField).closest('form').submit();
		} else {
			var origVal = $(formField).attr('data-originalValue');
			$(formField).val(origVal);
		};
	} else {
		$(formField).closest('form').submit();
	};
};
function submitForm(formField){
	$(formField).closest('form').submit();
};
</script>
<script type="text/javascript">
$(document).ready(function() {
	CKEDITOR.replace( 'user_comment', {
		autoGrow_minHeight: 100,
		autoGrow_maxHeight: 100,
		height: 100
	});
	$('select').each(function(){
		$(this).attr('data-originalValue',$(this).val());
	});
});
</script>
<script type="text/javascript">
function checkClass(){
	var name_1 = '<?php echo $userChar1; ?>';
	var name_2 = '<?php echo $userChar2; ?>';
	var name_3 = '<?php echo $userChar3; ?>';
	var name_4 = '<?php echo $userChar4; ?>';
	
	if($('#su_userChar').val() == name_1){
		document.getElementById("su_userClass_<?php echo $userMainClass; ?>").selected=true;
		return false;
	} else 
	if($('#su_userChar').val() == name_2){
		document.getElementById("su_userClass_<?php echo $userAlt1Class; ?>").selected=true;
		return false;
	} else 
	if($('#su_userChar').val() == name_3){
		document.getElementById("su_userClass_<?php echo $userAlt2Class; ?>").selected=true;
		return false;
	} else 
	if($('#su_userChar').val() == name_4){
		document.getElementById("su_userClass_<?php echo $userAlt3Class; ?>").selected=true;
		return false;
	}
	return true;
};
</script>
<script>
function setupSuicide(){
	var eventID = '<?php echo $eventID; ?>'; 
	var eventSU = '<?php echo $eventScor; ?>';
	var sessionID = '<?php echo uniqid(); ?>';
	window.open('suicide_setup.php?id='+eventID+'&score='+eventSU+'&sessid='+sessionID+'','Suicide Setup','resizable=0, scrollbars=0, titlebar=0, status=0, width: 700, height: 900');
}
</script>
<script type="text/javascript">
function target_popup(form) {
	window.open('', 'formpopup', 'resizable=no, scrollbars=no, titlebar=no, status=0, width=700, height=900');
	form.target = 'formpopup';
}
</script>
<script type="text/javascript">
function showHideAdminControls(){
	$('.su_admin_Form').toggle();
	$('.su_admin_Text').toggle();
}
</script>
</body>
</html>