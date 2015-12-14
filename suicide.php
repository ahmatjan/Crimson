<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$method = preg_replace('#[^a-zA-Z]#', '', $_GET['method']);
	if($method == 'add'){
		$add_userChar = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['userName']);
		$userPos  = preg_replace('#[^0-9]#', '', $_POST['userPos']);
		// Get user ID from profile_fields_data
		$sql = "SELECT user_id FROM phpbb_profile_fields_data WHERE pf_main_char_name='$add_userChar'";
		$que = mysqli_query($con,$sql);
		$row = mysqli_fetch_row($que);
		if(count($row) > 0){
			// Get username from phpbb_users
			$userID = $row[0];
			$sql = "SELECT username FROM phpbb_users WHERE user_id='$userID'";
			$que = mysqli_query($con,$sql);
			$row = mysqli_fetch_row($que);
			$add_userName = $row[0];
			// Check if user in Suicide List
			$sql = "SELECT COUNT(id) FROM suicide WHERE user_char='$add_userChar' OR user_name='$add_userName'";
			$que = mysqli_query($con,$sql);
			$row = mysqli_fetch_row($que);
			if($row[0] > 0){
				$_SESSION['error'] = 'Sorry, that user is already in the Suicide List';
				header('Location: '.$_SERVER['PHP_SELF'].'');
				exit();
			} else {
				// Continue
				// User Pos Set
				// Check if User Pos Set > MAX(pos)
				$sql = "SELECT MAX(pos) FROM suicide";
				$que = mysqli_query($con,$sql);
				$row = mysqli_fetch_row($que);
				if (($userPos > $row[0]+1) || ($userPos == '') || ($userPos == '0')){
					// $userPos must be MAX(pos)+1 or less, so make $userPos MAX(pos)+1
					$userPos = $row[0]+1;
				}
				// Continue
				mysqli_query($con,"START TRANSACTION");
				$sql1 = mysqli_query($con,"UPDATE suicide SET pos=pos+1 WHERE pos>='$userPos'");
				$sql2 = mysqli_query($con,"INSERT INTO suicide (user_name,user_char,pos) VALUES ('$add_userName','$add_userChar','$userPos')");
				if($sql1 and $sql2){
					$logDate = date('Y-m-d H:i:s');
					$sql = "INSERT INTO suicide_log (admin_name,user_name,log_type,log_direction,log_date) VALUES ('$userName','$add_userName - $add_userChar','Added','Pos: $userPos','$logDate')";
					mysqli_query($con,$sql);
					$_SESSION['success'] = 'User added to the Suicide List Successfully';
					header('Location: '.$_SERVER['PHP_SELF'].'');
					exit();
				} else {
					$_SESSION['error'] = 'Sorry, we could not add that user to the database, please try again. '.mysqli_error($con).'';
					header('Location: '.$_SERVER['PHP_SELF'].'');
					exit();
				}
			}
		} else {
			$_SESSION['error'] = 'Sorry, we could not find that character in the database. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'userUp'){
		$userID  = preg_replace('#[^0-9]#', '', $_POST['userID']);
		$move_userName = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userName']);
		$move_userChar = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userChar']);
		$sql = "UPDATE
				  suicide INNER JOIN (SELECT pos FROM suicide WHERE id='$userID') curr
				  ON suicide.pos IN (curr.pos, curr.pos-1)
				SET
				  suicide.pos = CASE WHEN suicide.pos=curr.pos
									   THEN curr.pos-1 ELSE curr.pos END;
				";
		$que = mysqli_query($con,$sql);
		if($que){
			$logDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO suicide_log (admin_name,user_name,log_type,log_direction,log_date) VALUES('$userName','$move_userName - $move_userChar','Moved','Up','$logDate')";
			mysqli_query($con,$sql);
			$_SESSION['success'] = 'User '.$move_userChar.' moved up.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Sorry, we could not move that user up. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'userDown'){
		$userID  = preg_replace('#[^0-9]#', '', $_POST['userID']);
		$move_userName = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userName']);
		$move_userChar = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userChar']);
		$sql = "UPDATE
				  suicide INNER JOIN (SELECT pos FROM suicide WHERE id='$userID') curr
				  ON suicide.pos IN (curr.pos, curr.pos+1)
				SET
				  suicide.pos = CASE WHEN suicide.pos=curr.pos
									   THEN curr.pos+1 ELSE curr.pos END;
				";
		$que = mysqli_query($con,$sql);
		if($que){
			$logDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO suicide_log (admin_name,user_name,log_type,log_direction,log_date) VALUES('$userName','$move_userName - $move_userChar','Moved','Down','$logDate')";
			mysqli_query($con,$sql);
			$_SESSION['success'] = 'User '.$move_userChar.' moved down.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Sorry, we could not move that user down. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'userDele'){
		$userID  = preg_replace('#[^0-9]#', '', $_POST['userID']);
		if($userID == ''){
			$_SESSION['error'] = 'You tried to delete all users... Shtawp it!';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		$userPos  = preg_replace('#[^0-9]#', '', $_POST['userPos']);
		$dele_userName = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userName']);
		$dele_userChar = preg_replace('#[^a-zA-Z0-9\\-\\040\\_]#', '', $_POST['userChar']);
		$sql = "DELETE FROM suicide WHERE id='$userID'";
		$que = mysqli_query($con,$sql);
		if($que){
			$sql = "UPDATE suicide SET pos=pos-1 WHERE pos>'$userPos'";
			mysqli_query($con,$sql);
			$logDate = date('Y-m-d H:i:s');
			$sql = "INSERT INTO suicide_log (admin_name,user_name,log_type,log_direction,log_date) VALUES('$userName','$dele_userName - $dele_userChar','Deleted','Pos: $userPos','$logDate')";
			mysqli_query($con,$sql);
			$_SESSION['success'] = 'User '.$dele_userChar.' deleted.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Sorry, we could not delete that user. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>[CCA] - Suicide List</title>
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
			if($_SESSION['error'] != ''){
				echo '<br />';
				echo '<div class="alert alert-danger">';
				echo '<strong>Error: </strong> ';
				echo $_SESSION['error'];
				echo '</div>';
				$_SESSION['error'] = '';
			}
			if($_SESSION['success'] != ''){
				echo '<br />';
				echo '<div class="alert alert-success">';
				echo '<strong>Success: </strong> ';
				echo $_SESSION['success'];
				echo '</div>';
				$_SESSION['success'] = '';
			}
			?>
			<?php if($perms_suicAdd == '1'){ ?>
			<form action="?method=add" method="post" style="margin: 0;">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th style="width: 67px; text-align: center;">#</th>
						<th>Character</th>
						<th style="width: 100px;">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="number" class="input-mini" name="userPos" style="margin-bottom: 0; width: 67px;" placeholder="0"></td>
						<td><input type="text" class="input-xlarge" name="userName" style="margin-bottom: 0;" id="suicideCharInput"></td>
						<td style="text-align: center;"><button type="submit" class="btn btn-success" style="margin-bottom: 0;">Add User</button></td>
					</tr>
				</tbody>
			</table>
			</form>
			<?php } ?>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th style="width: 40px; text-align: center;">#</th>
						<th style="width: 200px;">Character</th>
						<th style="width: 200px;">Class</th>
					<?php
						if($perms_suicAdmin == '1'){
							if($perms_suicEdit == '1'){
								echo '<th width="46px" style="text-align: center;">Move Up</th>';
								echo '<th width="46px" style="text-align: center;">Move Down</th>';
							}
							if($perms_suicDele == '1'){
								echo '<th width="46px" style="text-align: center;">Delete</th>';
							}
						}
					?>
					</tr>
				</thead>
				<tbody style="color: #FFF;">
			<?php
				$sql = "SELECT * FROM suicide ORDER BY pos ASC";
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($res)){
					$sql_username = $row['user_name'];
					$sql_userchar = $row['user_char'];
					$sql_inside = "SELECT user_id FROM phpbb_users WHERE username='$sql_username' OR username='$sql_userchar'";
					$que_inside = mysqli_query($con,$sql_inside);
					$res_inside = mysqli_fetch_row($que_inside);
					$user_id    = $res_inside[0];
					
					$sql_inside = "SELECT pf_main_class,user_id FROM phpbb_profile_fields_data WHERE pf_main_class!='NULL' AND user_id='$user_id' OR pf_main_char_name='$sql_username' OR pf_main_char_name='$sql_userchar'";
					$que_inside = mysqli_query($con,$sql_inside);
					$res_inside = mysqli_fetch_row($que_inside);
					$user_class = $res_inside[0];
					$user_id    = $res_inside[1];
					
					if ($user_class == '1'){
						$user_class = 'Warrior';
					} else if ($user_class == '2'){
						$user_class = 'Cleric';
					} else if ($user_class == '3'){
						$user_class = 'Mage';
					} else if ($user_class == '4'){
						$user_class = 'Rogue';
					}
					
					if($row['user_name'] == $userName){
						echo '<tr class="success">';
					} else {
						echo '<tr>';
					}
					echo '<td style="text-align: center;">'.$row['pos'].'</td>';
					echo '<td style="cursor: pointer;" onClick="window.location=\'http://crimson-alliance.com/forum/memberlist.php?mode=viewprofile&u='.$user_id.'\'" >'.$row['user_char'].'</td>';
					echo '<td>'.$user_class.'</td>';
					if($perms_suicAdmin == '1'){
						if($perms_suicEdit == '1'){
							echo '<td style="text-align: center; cursor: pointer;" onClick="postform(\'upForm_'.$row['id'].'\')" class="upForm"><i class="icon-arrow-up"></i></td>';
							echo '<form action="'.$_SERVER['PHP_SELF'].'?method=userUp" method="post" class="hidden" id="upForm_'.$row['id'].'">';
							echo '<input type="hidden" name="userID" value="'.$row['id'].'">';
							echo '<input type="hidden" name="userName" value="'.$row['user_name'].'">';
							echo '<input type="hidden" name="userChar" value="'.$row['user_char'].'">';
							echo '</form>';
							echo '<td style="text-align: center; cursor: pointer;" onClick="postform(\'downForm_'.$row['id'].'\')" class="downForm"><i class="icon-arrow-down"></i></td>';
							echo '<form action="'.$_SERVER['PHP_SELF'].'?method=userDown" method="post" class="hidden" id="downForm_'.$row['id'].'">';
							echo '<input type="hidden" name="userID" value="'.$row['id'].'">';
							echo '<input type="hidden" name="userName" value="'.$row['user_name'].'">';
							echo '<input type="hidden" name="userChar" value="'.$row['user_char'].'">';
							echo '</form>';
						}
						if($perms_suicDele == '1'){
							echo '<td style="text-align: center; cursor: pointer;" onClick="postform(\'deleteForm_'.$row['id'].'\',\''.$row['user_char'].'\',\''.$row['id'].'\')" class="deleteForm"><i class="icon-trash"></i></td>';
							echo '<form action="'.$_SERVER['PHP_SELF'].'?method=userDele" method="post" class="hidden" id="deleteForm_'.$row['id'].'">';
							echo '<input type="hidden" name="userID" value="'.$row['id'].'">';
							echo '<input type="hidden" name="userPos" value="'.$row['pos'].'">';
							echo '<input type="hidden" name="userName" value="'.$row['user_name'].'">';
							echo '<input type="hidden" name="userChar" value="'.$row['user_char'].'">';
							echo '</form>';
						}
					}
					echo '</tr>';
				}
			?>
				</tbody>
			</table>
			<hr />
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th width="20%" style="text-align: center;">Time</th>
						<th width="20%">Admin Name</th>
						<th width="10%">Mode</th>
						<th width="40%">User Name</th>
						<th width="10%">Info</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sql = "SELECT * FROM suicide_log ORDER BY log_date DESC LIMIT 10";
					$que = mysqli_query($con,$sql);
					while($row = mysqli_fetch_array($que)){
						echo '<tr>';
						$datetime = strtotime($row['log_date']);
						echo '<td style="text-align: center; font-size: 12px;">'.date('d-m-Y H:i:s',abs($datetime + $userTimeZone)).'</td>';
						echo '<td>'.$row['admin_name'].'</td>';
						echo '<td>'.$row['log_type'].'</td>';
						echo '<td>'.$row['user_name'].'</td>';
						if($row['log_direction'] == 'null'){
							echo '<td>&nbsp;</td>';
						} else {
							echo '<td>'.$row['log_direction'].'</td>';
						};
						echo '</tr>';
					};
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
<script language="JavaScript" type="text/javascript">
function postform(formName,userName,userID)
{
	if(formName == 'deleteForm_'+userID){
		if(confirm('Are you sure you want to delete ' + userName + ' ID: ' + userID + ' from the Suicide List?')){
			document.getElementById(formName).submit();
			return true;
		} else {
			return false;
		}
	} else {
		document.getElementById(formName).submit();
	}
}
</script>
<script type="text/javascript">
	$(function() {
	var data = [<?php
		$sql = "SELECT pf_main_char_name FROM phpbb_profile_fields_data WHERE pf_main_char_name NOT LIKE 'null'";
		$que = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($que)){
			if($row['pf_main_char_name'] != '') { echo '"'.$row['pf_main_char_name'].'",'; };
		};
		?>];
		$( "#suicideCharInput" ).autocomplete({
		  source: data
		});
	});
	</script>
</body>
</html>