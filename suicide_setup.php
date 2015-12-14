<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<?php
if($perms_suicEdit != '1'){
	die("You do not have permission to be here.");
}
$searchUsers = array();
// Step 1, set sessionID cookie, set username and score arrays.
if(!isset($_COOKIE['suicideSetupSessionID'])){
	setcookie('suicideSetupSessionID',$_GET['sessid'],(time()+14400));
	setcookie('suicideSetupEventID',$_GET['id'],(time()+14400));
	$_SESSION['suicideSetupScoreMode'] = $_GET['score'];
	$_SESSION['suicideSetupEventID'] = $_GET['id'];
	$eventID = $_GET['id'];
	// Get user characters
	$sql = "SELECT user_char FROM event_signups WHERE event_id='$eventID' AND user_status!='Unavailable'";
	$que = mysqli_query($con,$sql);
	$suicideChars = array();
	while($row = mysqli_fetch_array($que)){
		$sui_userChar = $row['user_char'];
		array_push($suicideChars, $sui_userChar);
	}
	$sqlSuicideChars = implode('\',\'', $suicideChars);
	// Get user positions
	$sql = "SELECT user_name,user_char,pos FROM suicide WHERE user_char IN('$sqlSuicideChars') ORDER BY pos";
	$que = mysqli_query($con,$sql);
	$_SESSION['suicideSetupCharacters'] = array();
	$_SESSION['suicideSetupPositions'] = array();
	$_SESSION['suicideSetupSignups'] = array();
	while($row = mysqli_fetch_array($que)){
		$sui_userChar = $row['user_char'];
		$sui_userPosi = $row['pos'];
		$sui_userName = $row['user_name'];
		array_push($_SESSION['suicideSetupSignups'], $sui_userChar);
		array_push($_SESSION['suicideSetupCharacters'], array("name" => $sui_userName, "char" => $sui_userChar, "posi" => $sui_userPosi));
		array_push($_SESSION['suicideSetupPositions'], $sui_userPosi);
	}
	// Refresh with no URL variables.
	header('Location: '.$_SERVER['PHP_SELF'].'');
	exit();
}
?>
<?php // Do i need this?
function cmp($a, $b) {
	return $a['posi'] - $b['posi'];
}
function searchForId($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['char'] === $id) {
           return true;
       }
   }
   return false;
}
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$method = preg_replace('#[^a-zA-Z]#', '', $_GET['method']);
	if($method == 'setupAdd'){
		if(empty($_POST['user_char'])){
			$_SESSION['error'] = 'Sorry, you tried to add an invisible person.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		if(searchForId($_POST['user_char'], $_SESSION['suicideSetupCharacters'])){
			$_SESSION['error'] = 'Sorry, that user is already in the Setup List.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		$sp_userChar   = preg_replace('#[^a-zA-Z0-9\\-\\040]#', '', $_POST['user_char']);
		// Check if user is in the Suicide List
		$sql = "SELECT user_char,pos FROM suicide WHERE user_char='$sp_userChar'";
		$que = mysqli_query($con,$sql);
		$row = mysqli_fetch_row($que);
		if($row[0] == ''){
			$_SESSION['error'] = 'Sorry, that user is not in the Suicide List. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		array_push($_SESSION['suicideSetupCharacters'], array("char" => $row[0], "posi" => $row[1]));
		array_push($_SESSION['suicideSetupPositions'], $row[1]);
		usort($_SESSION['suicideSetupCharacters'], "cmp");
		sort($_SESSION['suicideSetupPositions']);
		$_SESSION['success'] = 'User '.$sp_userChar.' Added to Setup List';
		header('Location: '.$_SERVER['PHP_SELF'].'');
		exit();
	}
	if($method == 'setupDelete'){
		$max = count($_SESSION['suicideSetupCharacters']);
		for($i=0;$i<$max;$i++){
			if($_POST['userChar'] == $_SESSION['suicideSetupCharacters'][$i]['char']){
				// Unset User Details
				unset($_SESSION['suicideSetupCharacters'][$i]);
				unset($_SESSION['suicideSetupPositions'][$i]);
				// Re-index Array
				$_SESSION['suicideSetupCharacters'] = array_values($_SESSION['suicideSetupCharacters']);
				$_SESSION['suicideSetupPositions'] = array_values($_SESSION['suicideSetupPositions']);
				$_SESSION['success'] = 'User '.$_POST['userChar'].' deleted from Setup List';
				header('Location: '.$_SERVER['PHP_SELF'].'');
				exit();
			}
		}
	}
	if($method == 'setupDeleteWarn'){
		$max = count($_SESSION['suicideSetupCharacters']);
		for($i=0;$i<$max;$i++){
			if($_POST['userChar'] == $_SESSION['suicideSetupCharacters'][$i]['char']){
				// Unset User Details
				unset($_SESSION['suicideSetupCharacters'][$i]);
				unset($_SESSION['suicideSetupPositions'][$i]);
				// Re-index Array
				$_SESSION['suicideSetupCharacters'] = array_values($_SESSION['suicideSetupCharacters']);
				$_SESSION['suicideSetupPositions'] = array_values($_SESSION['suicideSetupPositions']);
				$sql_userChar = $_POST['userChar'];
				$sql = "UPDATE raider_info_final SET warning=warning+1 WHERE username='$sql_userChar'";
				mysqli_query($con,$sql);
				$_SESSION['success'] = 'User '.$_POST['userChar'].' deleted from Setup List and Warned';
				header('Location: '.$_SERVER['PHP_SELF'].'');
				exit();
			}
		}
	}
	if($method == 'setupSuicideComplete'){
		setcookie('suicideSetupSuicideComplete','true',(time()+14400));
		if($_SESSION['suicideSetupScoreMode'] == '1'){
			$suicideSetupAtten = array();
			$max = count($_SESSION['suicideSetupCharacters']);
			for($i=0;$i<$max;$i++){
				$sql_userChar = $_SESSION['suicideSetupCharacters'][$i]['char'];
				array_push($suicideSetupAtten, $sql_userChar);
				$sql = "UPDATE raider_info_final SET attended=attended+1 WHERE username='$sql_userChar' AND attended<10";
				mysqli_query($con,$sql);
			}
			$sqlSuicideChars = implode('\',\'', $_SESSION['suicideSetupSignups']);
			$sqlSuicideAttns = implode('\',\'', $suicideSetupAtten);
			$sql = "UPDATE raider_info_final SET attended=attended-1 WHERE username NOT IN ('$sqlSuicideAttns') AND username IN('$sqlSuicideChars') AND attended>0";
			mysqli_query($con,$sql);
		}
		header('Location: '.$_SERVER['PHP_SELF'].'');
		exit();
	}
	if($method == 'warnUser'){
		$sp_userChar = $_POST['userChar'];
		$sql = "UPDATE raider_info_final SET warning=warning+1 WHERE username='$sp_userChar'";
		if(mysqli_query($con,$sql)){
			$_SESSION['success'] = 'User '.$_POST['userChar'].' warned.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'User '.$_POST['userChar'].' warning failed, please try again.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'suicideUser'){
		$successVariable = 0;
		$errorVariable = 0;
		$sp_userChar = $_POST['userChar'];
		$sp_userTo   = $_POST['userTo'];
		$sp_userPos  = $_POST['userPos'];
		$sp_logDate  = date('Y-m-d H:i:s');
		$max = count($_SESSION['suicideSetupCharacters']);
		for($i = 0;$i<$max;$i++){
			if($sp_userChar == $_SESSION['suicideSetupCharacters'][$i]['char']){
				$_SESSION['suicideSetupCharacters'] = moveValueByIndex($_SESSION['suicideSetupCharacters'], $i);
				$_SESSION['suicideSetupCharacters'] = array_values($_SESSION['suicideSetupCharacters']);
			}
		}
		$max = count($_SESSION['suicideSetupCharacters']);
		for($x = 0;$x<$max;$x++){
			$in_userChar = $_SESSION['suicideSetupCharacters'][$x]['char'];
			$in_userName = $_SESSION['suicideSetupCharacters'][$x]['name'];
			$in_userPos  = $_SESSION['suicideSetupPositions'][$x];
			$sql = "UPDATE suicide SET user_name='$in_userName',user_char='$in_userChar' WHERE pos='$in_userPos'";
			if(mysqli_query($con,$sql)){
				$successVariable ++;
			} else {
				$errorVariable ++;
			}
		}
		$sql = "INSERT INTO suicide_log (admin_name,user_name,log_type,log_direction,log_date)
				VALUES ('$userName','$sp_userChar','Suicided','$sp_userPos -> $sp_userTo','$sp_logDate')";
		if(mysqli_query($con,$sql)){
			$successVariable ++;
		} else {
			$errorVariable ++;
		}
		$_SESSION['success'] = 'User '.$sp_userChar.' suicided. Successful Queries: '.($successVariable-1).' - Errors: '.$errorVariable.' (If Errors > 0, please contact Jade with a screenshot of this page.)';
		header('Location: '.$_SERVER['PHP_SELF'].'');
		exit();
	}
	if($method == 'search'){
		$searchUsers = preg_split("/[\s,]+/", $_POST['charnames']);
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CCA Testing</title>
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
<div class="container well well-small">
	<div class="row-fluid">
		<div class="span12">
			<?php
			if($_SESSION['error'] != ''){
				echo '<div class="alert alert-danger">';
				echo '<strong>Error: </strong> ';
				echo $_SESSION['error'];
				echo '</div>';
				$_SESSION['error'] = '';
			}
			if($_SESSION['success'] != ''){
				echo '<div class="alert alert-success">';
				echo '<strong>Success: </strong> ';
				echo $_SESSION['success'];
				echo '</div>';
				$_SESSION['success'] = '';
			}
			?>
			<?php if(!isset($_COOKIE['suicideSetupSuicideComplete'])){ ?>
			<h5>Add user to Setup List</h5>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=setupAdd" class="form-horizontal" style="margin-bottom: 0; text-align: center;" method="post">
			<input type="text" name="user_char" placeholder="Character Name..." id="suicideCharInput" class="input-medium" style="width: 300px;">
			<button type="submit" class="btn btn-success">Add User</button>
			</form>
			<br />
			<div class="clearfix"></div>
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th style="text-align: center;">#</th>
						<th>Character Name</th>
						<th colspan="2" style="text-align: center;">Admin</th>
					</tr>
				</thead>
				<tbody style="color: #fff;">
					<?php
					$max = count($_SESSION['suicideSetupCharacters']);
					for($i = 0;$i<$max;$i++){
						echo '<tr>';
						echo '<td style="text-align: center;">'.$_SESSION['suicideSetupPositions'][$i].'</td>';
						echo '<td>'.$_SESSION['suicideSetupCharacters'][$i]['char'].'</td>';
						// Delete
						echo '<td title="Delete '.$_SESSION['suicideSetupCharacters'][$i]['char'].'" style="text-align: center; cursor: pointer;" onClick="postform(\'deleteForm_'.$i.'\',\''.$_SESSION['suicideSetupCharacters'][$i]['char'].'\',\''.$i.'\')" class="deleteForm"><i class="icon-trash"></i></td>';
						echo '<form action="'.$_SERVER['PHP_SELF'].'?method=setupDelete" method="post" class="hidden" id="deleteForm_'.$i.'">';
						echo '<input type="hidden" name="userPos" value="'.$_SESSION['suicideSetupPositions'][$i].'">';
						echo '<input type="hidden" name="userChar" value="'.$_SESSION['suicideSetupCharacters'][$i]['char'].'">';
						echo '</form>';
						// Warn and Delete
						echo '<td title="Warn &amp; Delete '.$_SESSION['suicideSetupCharacters'][$i]['char'].'" style="text-align: center; cursor: pointer;" onClick="postform(\'deleteWarnForm_'.$i.'\',\''.$_SESSION['suicideSetupCharacters'][$i]['char'].'\',\''.$i.'\')" class="deleteForm"><i class="icon-warning-sign"></i></td>';
						echo '<form action="'.$_SERVER['PHP_SELF'].'?method=setupDeleteWarn" method="post" class="hidden" id="deleteWarnForm_'.$i.'">';
						echo '<input type="hidden" name="userPos" value="'.$_SESSION['suicideSetupPositions'][$i].'">';
						echo '<input type="hidden" name="userChar" value="'.$_SESSION['suicideSetupCharacters'][$i]['char'].'">';
						echo '</form>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=setupSuicideComplete" method="post" style="margin-bottom: 0; text-align: center;">
			<button type="submit" class="btn btn-primary" style="width: 400px;" onClick="return confirm('Are you sure you want to complete Setup? THIS ACTION CAN NOT BE UNDONE.')">Complete Setup</button>
			</form>
			<?php } ?>
			<?php if(isset($_COOKIE['suicideSetupSuicideComplete'])){ ?>
			Page will refresh in: <span id="timer">60</span> seconds to keep the session alive.
			<br />
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th style="text-align: center;">#</th>
						<th>Character Name</th>
						<th colspan="2" style="text-align: center;">Admin</th>
					</tr>
				</thead>
				<tbody style="color: #fff;">
					<?php
					$max = count($_SESSION['suicideSetupCharacters']);
						for($i = 0;$i<$max;$i++){
							if(in_array($_SESSION['suicideSetupCharacters'][$i]['char'],$searchUsers)){
								echo '<tr style="background: #347C17 !important;">';
							} else {
								echo '<tr>';
							}
							echo '<td style="text-align: center;">'.$_SESSION['suicideSetupPositions'][$i].'</td>';
							echo '<td>'.$_SESSION['suicideSetupCharacters'][$i]['char'].'</td>';
							// Suicide
							echo '<td title="Suicide '.$_SESSION['suicideSetupCharacters'][$i]['char'].'" style="text-align: center; cursor: pointer;" onClick="postform(\'suicideForm_'.$i.'\',\''.$_SESSION['suicideSetupCharacters'][$i]['char'].'\',\''.$i.'\')" class="deleteForm"><i class="icon-ambulance"></i></td>';
							echo '<form action="'.$_SERVER['PHP_SELF'].'?method=suicideUser" method="post" class="hidden" id="suicideForm_'.$i.'">';
							echo '<input type="hidden" name="userPos" value="'.$_SESSION['suicideSetupPositions'][$i].'">';
							echo '<input type="hidden" name="userChar" value="'.$_SESSION['suicideSetupCharacters'][$i]['char'].'">';
							echo '<input type="hidden" name="userTo" value="'.$_SESSION['suicideSetupPositions'][$max-1].'">';
							echo '</form>';
							// Warn
							echo '<td title="Warn '.$_SESSION['suicideSetupCharacters'][$i]['char'].'" style="text-align: center; cursor: pointer;" onClick="postform(\'warnForm_'.$i.'\',\''.$_SESSION['suicideSetupCharacters'][$i]['char'].'\',\''.$i.'\')" class="deleteForm"><i class="icon-warning-sign"></i></td>';
							echo '<form action="'.$_SERVER['PHP_SELF'].'?method=warnUser" method="post" class="hidden" id="warnForm_'.$i.'">';
							echo '<input type="hidden" name="userChar" value="'.$_SESSION['suicideSetupCharacters'][$i]['char'].'">';
							echo '</form>';
							echo '</tr>';
						}
					?>
				</tbody>
			</table>
			<hr />
			<center>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=search" method="post" style="margin: 0;">
				Please separate names with a space.
				<br />
				<textarea name="charnames" placeholder="Enter some usernames Ex: Prilla Neekasa Nalon" style="width: 90%;"></textarea>
				<br />
				<button type="submit" class="btn btn-success pull-right">Search</button>
			</form>
			</center>
			<div class="clearfix"></div>
			<hr />
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Admin Name</th>
						<th>Mode</th>
						<th>User Name</th>
						<th>Info</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sql = "SELECT * FROM suicide_log ORDER BY log_date DESC LIMIT 10";
					$que = mysqli_query($con,$sql);
					while($row = mysqli_fetch_array($que)){
						echo '<tr>';
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
			<script>
				var count=60;
				var myVar=setInterval(function(){myTimer()},1000);
				function myTimer(){
					count=count-1;
					if(count <= 0)
					{
						location.reload();
						return;
					}
					document.getElementById("timer").innerHTML=count;
				}
			</script>
			<?php } ?>
		</div>
	</div>
</div>
<script language="JavaScript" type="text/javascript">
function postform(formName,userName,userID)
{
	if(formName == 'deleteForm_'+userID){
		if(confirm('Are you sure you want to delete ' + userName + ' from the Suicide List?')){
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