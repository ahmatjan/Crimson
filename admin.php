<?php include('testing_global.php'); ?>
<?php include('inc/functions.php'); ?>
<?php
function sendphpbbpm($pmmessage,$groupid,$pmsubject) {
	include_once('forum/includes/functions_privmsgs.php');
	
	$message = utf8_normalize_nfc($pmmessage);
	$uid = $bitfield = $options = '';
	$allow_bbcode = $allow_smilies = true;
	$allow_urls = true;
	generate_text_for_storage($message, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$pm_data = array (
	'from_user_id' => 2,
	'from_user_ip' => "127.0.0.1",
	'from_username' => "Raid Admin",
	'enable_sig'     => false,
	'enable_bbcode'  => true,
	'enable_smilies' => true,
	'enable_urls'    => false,
	'icon_id'        => 0,
	'bbcode_bitfield' => $bitfield,
	'bbcode_uid'      => $uid,
	'message'         => $message,
	'address_list'    => array('g' => array($groupid => 'to')),
	);
	
	submit_pm('post', $pmsubject, $pm_data, false);
};
function sendphpbbfp($raidid,$raidname,$raidtime,$raiddate,$raiddesc){
	include_once('./forum/includes/functions_posting.php');
	// note that multibyte support is enabled here 
$my_subject = 'New Raid Posted';
$my_text    = utf8_normalize_nfc('[color=#BF00BF][size=150][b]'.$raidname.'[/b][/size][/color]
			
			[b]Date:[/b] '.$raiddate.'
			[b]Time:[/b] '.$raidtime.' UTC (GMT)
			
			[b][url=http://www.crimson-alliance.com/calendar_signup.php?id='.$raidid.']Click here to sign up.[/url][/b]
			
			[b]Description:[/b]
			'.$raiddesc.'');

// variables to hold the parameters for submit_post
$poll = $uid = $bitfield = $options = ''; 

generate_text_for_storage($my_subject, $uid, $bitfield, $options, false, false, false);
generate_text_for_storage($my_text, $uid, $bitfield, $options, true, true, true);

$data = array( 
    'forum_id'      => 24,
	'topic_id'      => 7,
    'icon_id'       => false,

    'enable_bbcode'     => true,
    'enable_smilies'    => true,
    'enable_urls'       => true,
    'enable_sig'        => false,

    'message'       => $my_text,
    'message_md5'   => md5($my_text),
                
    'bbcode_bitfield'   => $bitfield,
    'bbcode_uid'        => $uid,

    'post_edit_locked'  => 0,
    'topic_title'       => $my_subject,
    'notify_set'        => false,
    'notify'            => false,
    'post_time'         => 0,
    'forum_name'        => 'Raid Postings',
    'enable_indexing'   => true,
	'force_approved_state' => true,
);

submit_post('reply', $my_subject, '', POST_NORMAL, $poll, $data, $update_message = true);
};
?>
<?php
// Is Request?
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$method = preg_replace('#[^a-zA-Z]#', '', $_GET['method']);
	if($method == 'adminVariables'){
		$statChanges = preg_replace('#[^0-9\\-]#', '', $_POST['stat_changes'])*60;
		$signChanges = preg_replace('#[^0-9\\-]#', '', $_POST['sign_changes'])*60;
		$commChanges = preg_replace('#[^0-9\\-]#', '', $_POST['comm_changes'])*60;
		$admiChanges = preg_replace('#[^0-9\\-]#', '', $_POST['admi_changes'])*60;
		$sql = "UPDATE admin_variables SET stat_changes='$statChanges', sign_changes='$signChanges', comm_changes='$commChanges', admi_changes='$admiChanges'";
		$que = mysqli_query($con,$sql);
		if ($que){
			$_SESSION['success'] = 'Admin Variables successfully changed.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not change the Admin Variables. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'addEvent'){
		$eventName = preg_replace('#[^0-9A-Za-z\\-\\\\\_\\040\\(\\)]#', '', $_POST['eventName']);
		$eventDateTime = date('Y-m-d H:i:s', abs(strtotime($_POST['eventTime'])-$userTimeZone));
		$eventTime = date('H:i:s', strtotime($eventDateTime));
		$eventDate = date('Y-m-d', strtotime($eventDateTime));
		$groupID   = $_POST['eventPM'];
		if(($groupID != '10') || ($groupID != '15')){ $groupID = ''; } 
		if($_POST['eventScore'] == 'on'){ $eventScore = '1'; } else { $eventScore = '0'; }
		if(($_POST['eventRaiders'] == 'on') && ($eventScore == '0')){ $eventRaiders = '1'; } else { $eventRaiders = '0'; }
		$eventDescription = $_POST['eventDescription'];
		$tier = '0'; $people = '0'; $hit = '0';
		if($eventName == 'Triumph of the Dragon Queen'){ $tier = '1'; $people = '10'; $hit = '400'; }
		if($eventName == 'Endless Eclipse'){ $tier = '1'; $people = '20'; $hit = '400'; }
		if($eventName == 'Frozen Tempest'){ $tier = '1'; $people = '20'; $hit = '400'; }
		if($eventName == 'Grim Awakening'){ $tier = '2'; $people = '10'; $hit = '500'; }
		
		$sql = "INSERT INTO events_final (title,time,date,datetime,description,enable_score,enable_raiders,tier,people,hit,adminName) VALUES
				('$eventName','$eventTime','$eventDate','$eventDateTime','$eventDescription','$eventScore','$eventRaiders','$tier','$people','$hit','$userName')";
		$que = mysqli_query($con,$sql);
		if ($que){
			// Send PM and Forum Post
			$sql = "SELECT * FROM events_final ORDER BY id DESC LIMIT 1";
			$que = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($que);
			$raiddate = date('d-m-Y',strtotime($row['datetime']));
			$raidtime = date('g:i a',strtotime($row['datetime']));
			$raidid   = $row['id'];
			$raidname = $row['title'];
			$raiddesc = $row['description'];
			$groupID   = $_POST['eventPM'];
			
			sendphpbbfp($raidid,$raidname,$raidtime,$raiddate,$raiddesc);
			
			$pmsubject = 'New Raid Added - '.$raidname.'';
			$pmmessage = '[b]There is a new raid posted for you![/b]
			
			[color=#BF00BF][size=150][b]'.$raidname.'[/b][/size][/color]
			
			[b]Date:[/b] '.$raiddate.'
			[b]Time:[/b] '.$raidtime.' UTC (GMT)
			
			[b][url=http://www.crimson-alliance.com/calendar_signup.php?id='.$raidid.']Click here to sign up.[/url][/b]
			
			[b]Description:[/b]
			'.$raiddesc.'';
			if($groupID != ''){
				sendphpbbpm($pmmessage,$groupID,$pmsubject);
			}
			// End
			$_SESSION['success'] = 'Event '.$eventName.' successfully added.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not add event. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'editEvent'){
		$eventID = preg_replace('#[^0-9]#', '', $_POST['eventID']);
		$eventName = preg_replace('#[^0-9A-Za-z\\-\\\\\_\\040\\(\\)]#', '', $_POST['eventName']);
		$eventDateTime = date('Y-m-d H:i:s', abs(strtotime($_POST['eventTime'])-$userTimeZone));
		$eventTime = date('H:i:s', strtotime($eventDateTime));
		$eventDate = date('Y-m-d', strtotime($eventDateTime));
		if($_POST['eventScore'] == 'on'){ $eventScore = '1'; } else { $eventScore = '0'; }
		if(($_POST['eventRaiders'] == 'on') && ($eventScore == '0')){ $eventRaiders = '1'; } else { $eventRaiders = '0'; }
		$eventDescription = $_POST['eventDescription'];
		$tier = '0'; $people = '0'; $hit = '0';
		if($eventName == 'Triumph of the Dragon Queen'){ $tier = '1'; $people = '10'; $hit = '400'; }
		if($eventName == 'Endless Eclipse'){ $tier = '1'; $people = '20'; $hit = '400'; }
		if($eventName == 'Frozen Tempest'){ $tier = '1'; $people = '20'; $hit = '400'; }
		if($eventName == 'Grim Awakening'){ $tier = '2'; $people = '10'; $hit = '500'; }
		$sql = "UPDATE events_final SET title='$eventName', time='$eventTime', date='$eventDate', datetime='$eventDateTime', description='$eventDescription', enable_score='$eventScore', enable_raiders='$eventRaiders',
				tier='$tier', people='$people', hit='$hit' WHERE id='$eventID'";
		$que = mysqli_query($con,$sql);
		if ($que){
			$_SESSION['success'] = 'Event '.$eventName.' successfully edited.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit event. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'eventDelete'){
		$eventID = preg_replace('#[^0-9]#', '', $_POST['id']);
		if($eventID == ''){
			$_SESSION['error'] = 'You tried to delete all events... Shtawp it!';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		$sql = "DELETE FROM events_final WHERE id='$eventID'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Event successfully deleted.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not delete event. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'addNews'){
		$newsTitle = preg_replace('#[^0-9A-Za-z\\-\\\\\_\\040\\(\\)]#', '', $_POST['newsTitle']);
		$newsDescription = $_POST['newsDescription'];
		$newsTime = date('Y-m-d H:i:s');
		$sql = "INSERT INTO news (news_title,news_content,news_user,news_time,news_user_id) VALUES ('$newsTitle','$newsDescription','$userName','$newsTime','$userID')";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'News Successfully Added.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not add news. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'editNews'){
		$newsTitle = preg_replace('#[^0-9A-Za-z\\-\\\\\_\\040\\(\\)]#', '', $_POST['newsTitle']);
		$newsDescription = $_POST['newsDescription'];
		$sql = "INSERT INTO news (news_title,news_content) VALUES ('$newsTitle','$newsDescription')";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'News Successfully edited.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit news. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'deleteNews'){
		$newsID = preg_replace('#[^0-9]#', '', $_POST['id']);
		if($newsID == ''){
			$_SESSION['error'] = 'You tried to delete all news... Shtawp it!';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		$sql = "DELETE FROM news WHERE id='$raiderID'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'News successfully deleted.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not delete news. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}	
	if($method == 'addRaider'){
		$add_userName = preg_replace('#[^0-9A-Za-z]#', '', $_POST['userName']);
		$t1_20 = '';
		$t2_10 = '';
		$t2_20 = '';
		$t3_10 = '';
		$t3_20 = '';
		if($_POST['t1_20_t'] == 'on'){ $t1_20 .= 'T'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_h'] == 'on'){ $t1_20 .= 'H'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_d'] == 'on'){ $t1_20 .= 'D'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_s'] == 'on'){ $t1_20 .= 'S'; } else { $t1_20 .= '-'; }
		//
		if($_POST['t2_10_t'] == 'on'){ $t2_10 .= 'T'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_h'] == 'on'){ $t2_10 .= 'H'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_d'] == 'on'){ $t2_10 .= 'D'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_s'] == 'on'){ $t2_10 .= 'S'; } else { $t2_10 .= '-'; }
		//
		if($_POST['t2_20_t'] == 'on'){ $t2_20 .= 'T'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_h'] == 'on'){ $t2_20 .= 'H'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_d'] == 'on'){ $t2_20 .= 'D'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_s'] == 'on'){ $t2_20 .= 'S'; } else { $t2_20 .= '-'; }
		//
		if($_POST['t3_10_t'] == 'on'){ $t3_10 .= 'T'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_h'] == 'on'){ $t3_10 .= 'H'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_d'] == 'on'){ $t3_10 .= 'D'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_s'] == 'on'){ $t3_10 .= 'S'; } else { $t3_10 .= '-'; }
		//
		if($_POST['t3_20_t'] == 'on'){ $t3_20 .= 'T'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_h'] == 'on'){ $t3_20 .= 'H'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_d'] == 'on'){ $t3_20 .= 'D'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_s'] == 'on'){ $t3_20 .= 'S'; } else { $t3_20 .= '-'; }
		//
		$sql = "INSERT INTO raider_info_final (username,T1_20,T2_10,T2_20,T3_10,T3_20,signups,attended,warning) VALUES
				('$add_userName','$t1_20','$t2_10','$t2_20','$t3_10','$t3_20','1','0','0')";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Raider added successfully.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not add raider. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'editRaider'){
		$add_userName = preg_replace('#[^0-9A-Za-z]#', '', $_POST['userName']);
		$add_userID = preg_replace('#[^0-9]#', '',$_POST['userID']);
		$t1_20 = '';
		$t2_10 = '';
		$t2_20 = '';
		$t3_10 = '';
		$t3_20 = '';
		if($_POST['t1_20_t'] == 'on'){ $t1_20 .= 'T'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_h'] == 'on'){ $t1_20 .= 'H'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_d'] == 'on'){ $t1_20 .= 'D'; } else { $t1_20 .= '-'; }
		if($_POST['t1_20_s'] == 'on'){ $t1_20 .= 'S'; } else { $t1_20 .= '-'; }
		//
		if($_POST['t2_10_t'] == 'on'){ $t2_10 .= 'T'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_h'] == 'on'){ $t2_10 .= 'H'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_d'] == 'on'){ $t2_10 .= 'D'; } else { $t2_10 .= '-'; }
		if($_POST['t2_10_s'] == 'on'){ $t2_10 .= 'S'; } else { $t2_10 .= '-'; }
		//
		if($_POST['t2_20_t'] == 'on'){ $t2_20 .= 'T'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_h'] == 'on'){ $t2_20 .= 'H'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_d'] == 'on'){ $t2_20 .= 'D'; } else { $t2_20 .= '-'; }
		if($_POST['t2_20_s'] == 'on'){ $t2_20 .= 'S'; } else { $t2_20 .= '-'; }
		//
		if($_POST['t3_10_t'] == 'on'){ $t3_10 .= 'T'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_h'] == 'on'){ $t3_10 .= 'H'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_d'] == 'on'){ $t3_10 .= 'D'; } else { $t3_10 .= '-'; }
		if($_POST['t3_10_s'] == 'on'){ $t3_10 .= 'S'; } else { $t3_10 .= '-'; }
		//
		if($_POST['t3_20_t'] == 'on'){ $t3_20 .= 'T'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_h'] == 'on'){ $t3_20 .= 'H'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_d'] == 'on'){ $t3_20 .= 'D'; } else { $t3_20 .= '-'; }
		if($_POST['t3_20_s'] == 'on'){ $t3_20 .= 'S'; } else { $t3_20 .= '-'; }
		//
		$sql = "UPDATE raider_info_final SET username='$add_userName', T1_20='$t1_20', T2_10='$t2_10', T2_20='$t2_20', T3_10='$t3_10', T3_20='$t3_20', warning='$warning' WHERE id='$add_userID'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Raider edited successfully.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit raider. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'deleRaid'){
		$raiderID = preg_replace('#[^0-9]#', '', $_POST['id']);
		if($raiderID == ''){
			$_SESSION['error'] = 'You tried to delete all raiders... Shtawp it!';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
		$sql = "DELETE FROM raider_info_final WHERE id='$raiderID'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Raider successfully deleted.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not delete raider. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'ALPerms'){
		$newsAdmin = '';
		$newsAdmin .= $_POST['AL_newsAdmin'];
		if($newsAdmin == '1'){
			$newsAdmin .= $_POST['AL_newsAdd'];
			$newsAdmin .= $_POST['AL_newsEdit'];
			$newsAdmin .= $_POST['AL_newsDel'];
		} else {
			$newsAdmin .= '000';
		}
		//
		$eventAdmin = '';
		$eventAdmin .= $_POST['AL_eventAdmin'];
		if($eventAdmin == '1'){
			$eventAdmin .= $_POST['AL_eventAdd'];
			$eventAdmin .= $_POST['AL_eventEdit'];
			$eventAdmin .= $_POST['AL_eventDel'];
			$eventAdmin .= $_POST['AL_eventScore'];
			$eventAdmin .= $_POST['AL_eventPM'];
			$eventAdmin .= $_POST['AL_eventStat'];
		} else {
			$eventAdmin .= '000000';
		}
		//
		$raidersAdmin = '';
		$raidersAdmin .= $_POST['AL_raidersAdmin'];
		if($raidersAdmin == '1'){
			$raidersAdmin .= $_POST['AL_raidersAdd'];
			$raidersAdmin .= $_POST['AL_raidersEdit'];
			$raidersAdmin .= $_POST['AL_raidersDel'];
		} else {
			$raidersAdmin .= '000';
		}
		//
		$permAdmin = $_POST['AL_permAdmin'];
		//
		$suicideAdmin = '';
		$suicideAdmin .= $_POST['AL_suicideAdmin'];
		if($suicideAdmin == '1'){
			$suicideAdmin .= $_POST['AL_suicideAdd'];
			$suicideAdmin .= $_POST['AL_suicideEdit'];
			$suicideAdmin .= $_POST['AL_suicideDel'];
		} else {
			$suicideAdmin .= '000';
		}
		//
		$permissions = $newsAdmin.$eventAdmin.$raidersAdmin.$permAdmin.$suicideAdmin;
		$sql = "UPDATE admin_perms SET group_perms='$permissions' WHERE group_id='8'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Permissions successfully edited.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit Permissions. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'OFPerms'){
		$newsAdmin = '';
		$newsAdmin .= $_POST['AL_newsAdmin'];
		if($newsAdmin == '1'){
			$newsAdmin .= $_POST['AL_newsAdd'];
			$newsAdmin .= $_POST['AL_newsEdit'];
			$newsAdmin .= $_POST['AL_newsDel'];
		} else {
			$newsAdmin .= '000';
		}
		//
		$eventAdmin = '';
		$eventAdmin .= $_POST['AL_eventAdmin'];
		if($eventAdmin == '1'){
			$eventAdmin .= $_POST['AL_eventAdd'];
			$eventAdmin .= $_POST['AL_eventEdit'];
			$eventAdmin .= $_POST['AL_eventDel'];
			$eventAdmin .= $_POST['AL_eventScore'];
			$eventAdmin .= $_POST['AL_eventPM'];
			$eventAdmin .= $_POST['AL_eventStat'];
		} else {
			$eventAdmin .= '000000';
		}
		//
		$raidersAdmin = '';
		$raidersAdmin .= $_POST['AL_raidersAdmin'];
		if($raidersAdmin == '1'){
			$raidersAdmin .= $_POST['AL_raidersAdd'];
			$raidersAdmin .= $_POST['AL_raidersEdit'];
			$raidersAdmin .= $_POST['AL_raidersDel'];
		} else {
			$raidersAdmin .= '000';
		}
		//
		$permAdmin = $_POST['AL_permAdmin'];
		//
		$suicideAdmin = '';
		$suicideAdmin .= $_POST['AL_suicideAdmin'];
		if($suicideAdmin == '1'){
			$suicideAdmin .= $_POST['AL_suicideAdd'];
			$suicideAdmin .= $_POST['AL_suicideEdit'];
			$suicideAdmin .= $_POST['AL_suicideDel'];
		} else {
			$suicideAdmin .= '000';
		}
		//
		$permissions = $newsAdmin.$eventAdmin.$raidersAdmin.$permAdmin.$suicideAdmin;
		$sql = "UPDATE admin_perms SET group_perms='$permissions' WHERE group_id='9'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Permissions successfully edited.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit Permissions. '.mysqli_error($con).'';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		}
	}
	if($method == 'EMPerms'){
		$newsAdmin = '';
		$newsAdmin .= $_POST['AL_newsAdmin'];
		if($newsAdmin == '1'){
			$newsAdmin .= $_POST['AL_newsAdd'];
			$newsAdmin .= $_POST['AL_newsEdit'];
			$newsAdmin .= $_POST['AL_newsDel'];
		} else {
			$newsAdmin .= '000';
		}
		//
		$eventAdmin = '';
		$eventAdmin .= $_POST['AL_eventAdmin'];
		if($eventAdmin == '1'){
			$eventAdmin .= $_POST['AL_eventAdd'];
			$eventAdmin .= $_POST['AL_eventEdit'];
			$eventAdmin .= $_POST['AL_eventDel'];
			$eventAdmin .= $_POST['AL_eventScore'];
			$eventAdmin .= $_POST['AL_eventPM'];
			$eventAdmin .= $_POST['AL_eventStat'];
		} else {
			$eventAdmin .= '000000';
		}
		//
		$raidersAdmin = '';
		$raidersAdmin .= $_POST['AL_raidersAdmin'];
		if($raidersAdmin == '1'){
			$raidersAdmin .= $_POST['AL_raidersAdd'];
			$raidersAdmin .= $_POST['AL_raidersEdit'];
			$raidersAdmin .= $_POST['AL_raidersDel'];
		} else {
			$raidersAdmin .= '000';
		}
		//
		$permAdmin = $_POST['AL_permAdmin'];
		//
		$suicideAdmin = '';
		$suicideAdmin .= $_POST['AL_suicideAdmin'];
		if($suicideAdmin == '1'){
			$suicideAdmin .= $_POST['AL_suicideAdd'];
			$suicideAdmin .= $_POST['AL_suicideEdit'];
			$suicideAdmin .= $_POST['AL_suicideDel'];
		} else {
			$suicideAdmin .= '000';
		}
		//
		$permissions = $newsAdmin.$eventAdmin.$raidersAdmin.$permAdmin.$suicideAdmin;
		$sql = "UPDATE admin_perms SET group_perms='$permissions' WHERE group_id='13'";
		$que = mysqli_query($con,$sql);
		if($que){
			$_SESSION['success'] = 'Permissions successfully edited.';
			header('Location: '.$_SERVER['PHP_SELF'].'');
			exit();
		} else {
			$_SESSION['error'] = 'Could not edit Permissions. '.mysqli_error($con).'';
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
<title>[CCA] - Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css">
<link href="css/custom-theme/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<script src="./ckeditor/ckeditor.js"></script>
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
<script src="js/smooth-scroll.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/backstretch.js"></script>
<script type="text/javascript">
$('.navbar a, .subnav a').smoothScroll();

(function ($) {

	$(function(){

		// fix sub nav on scroll
		var $win = $(window),
				$body = $('body'),
				$nav = $('.subnav'),
				navHeight = $('.navbar').first().height(),
				subnavHeight = $('.subnav').first().height(),
				subnavTop = $('.subnav').length && $('.subnav').offset().top - navHeight,
				marginTop = parseInt($body.css('margin-top'), 10);
				isFixed = 0;

		processScroll();

		$win.on('scroll', processScroll);

		function processScroll() {
			var i, scrollTop = $win.scrollTop();

			if (scrollTop >= subnavTop && !isFixed) {
				isFixed = 1;
				$nav.addClass('subnav-fixed');
				$body.css('margin-top', marginTop + subnavHeight - 19 + 'px');
				$('[data-spy="scroll"]').each(function () {  
				  var $spy = $(this).scrollspy('refresh')
				});
			} else if (scrollTop <= subnavTop && isFixed) {
				isFixed = 0;
				$nav.removeClass('subnav-fixed');
				$body.css('margin-top', marginTop + 'px');
				$('[data-spy="scroll"]').each(function () {  
				  var $spy = $(this).scrollspy('refresh')
				});
			}
		}

	});

})(window.jQuery);
</script>
</head>
<body data-spy="scroll" data-target=".subnav" data-offset="100">
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
					<li class="active" id="activateme">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>">Admin</a>
					</li>
				</ul>
			</div><!--/nav-collapse collapse-->
		</div><!--/container-fluid-->
	</div><!--/navbar-inner-->
</div><!--/navbar navbar-fixed-top-->

<?php if( (!in_array('8',$userGroupIDs)) && (!in_array('9',$userGroupIDs)) && (!in_array('13',$userGroupIDs)) && ($userName != 'Neekasa') ){ ?>
<div class="container well well-small" style="margin-top: 20px;">
	<div class="row-fluid">		
		<div class="span12">
		<div class="alert alert-error" style="margin-bottom: 0;">
		<strong>Error</strong>
		You are not permitted to be here.
		</div>
		</div>
	</div><!--/row-fluid-->
</div><!--/container-fluid-->
<?php } else { ?>
<div class="container well well-small">
	<div class="row-fluid">
		<div class="subnav">
			<ul class="nav nav-pills">
				<li class="active" id="activatemetoo">
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>">Admin Control Panel</a>
				</li>
				<?php if ($perms_raidAdmin == '1'){ ?>
				<li class="notactive">
					<a href="#events">Event Management</a>
				</li>
				<?php } ?>
				<?php if ($perms_raidAdd == '1') { ?>
				<li class="notactive">
					<a href="#events_add">Add/Edit Event</a>
				</li>
				<?php } ?>
				<?php if ($perms_newsAdmin == '1') { ?>
				<li class="notactive">
					<a href="#news">Add/Edit News</a>
				</li>
				<?php } ?>
				<?php if ($perms_userAdmin == '1') { ?>
				<li class="notactive">
					<a href="#raiders">Raider Management</a>
				</li>
				<?php } ?>
				<?php if ($perms_permAdmin == '1') { ?>
				<li class="notactive">
					<a href="#permissions">Permissions</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<div class="container well well-small need-less-height">
<div style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>Administration Control Panel</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>Welcome to the Administration Control Panel. Please use the navigation to move between the areas.</p>
				<p>If you are entering times, please make sure to enter them in your current time zone, as the system converts them for you.</p>
				</div>
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
				<div>
				<?php
				$sql = "SELECT * FROM admin_variables";
				$que = mysqli_query($con,$sql);
				if($que){
					$row = mysqli_fetch_row($que);
					if($row[1] < 0){ $stat_changes = '-'.abs($row[1] / 60); } else { $stat_changes = abs($row[1] / 60); }
					if($row[2] < 0){ $sign_changes = '-'.abs($row[2] / 60); } else { $sign_changes = abs($row[2] / 60); }
					if($row[3] < 0){ $comm_changes = '-'.abs($row[3] / 60); } else { $comm_changes = abs($row[3] / 60); }
					if($row[4] < 0){ $admi_changes = '-'.abs($row[4] / 60); } else { $admi_changes = abs($row[4] / 60); }
				} else {
					echo mysqli_error($con);
				};
				?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=adminVariables">
					<fieldset>
						<table class="table table-bordered table-hover table-striped" style="color: #CCC;">
							<thead>
								<tr>
									<th colspan="2">Admin Variables</th>
								</tr>
								<tr>
									<th colspan="2"><small>Please enter a negative value for time before raid. All times are in Minutes.</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Event Status Changes - <small>This stops people editing their status' on events.</small></td>
									<td style="width: 100px;"><input type="number" name="stat_changes" value="<?php echo $stat_changes; ?>" class="input-small" style="margin-bottom: 0; width: 100px;"></td>
								</tr>
								<tr>
									<td>Event Signup Changes - <small>This stops people signing up to the event.</small></td>
									<td style="width: 100px;"><input type="number" name="sign_changes" value="<?php echo $sign_changes; ?>" class="input-small" style="margin-bottom: 0; width: 100px;"></td>
								</tr>
								<tr>
									<td>Event Comments - <small>This stops people posting comments on the event.</small></td>
									<td style="width: 100px;"><input type="number" name="comm_changes" value="<?php echo $comm_changes; ?>" class="input-small" style="margin-bottom: 0; width: 100px;"></td>
								</tr>
								<tr>
									<td>Event Admin Changes - <small>This stops admins from using all admin abilities, such as Setup Suicide List.</small></td>
									<td style="width: 100px;"><input type="number" name="admi_changes" value="<?php echo $admi_changes; ?>" class="input-small" style="margin-bottom: 0; width: 100px;"></td>
								</tr>
							</tbody>
						</table>
						<div class="form-actions pull-right" style="border-top: 0; padding: 0 5px 0 0; margin: 0;">
							<button type="submit" class="btn btn-success">Save Changes</button>
						</div>
					</fieldset>
				</form>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php if ($perms_raidAdmin == '1') { ?>
<div class="container well well-small need-height" style="overflow-y: auto;">
<div id="events" style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>Event Management</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>This is the Event Management section. Here you can Edit or Delete Events.</p>
				</div>
				<div>
					<table class="table table-bordered table-striped table-hover" style="color: #CCC;">
						<thead>
							<tr>
								<th>Name</th>
								<th>Date</th>
								<th>Desc</th>
								<th style="text-align: center;">Score</th>
								<th style="text-align: center;">Raiders</th>
								<?php if($perms_raidEdit == '1'){ ?>
								<th>&nbsp;</th>
								<?php } ?>
								<?php if($perms_raidDele == '1'){ ?>
								<th>&nbsp;</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							$now = date('Y-m-d H:i:s');
							$sql = "SELECT * FROM events_final WHERE datetime>='$now' ORDER BY datetime ASC";
							$res = mysqli_query($con,$sql);
							while($row = mysqli_fetch_array($res)){
								$eventDescription = $row['description'];
								echo '<tr>';
								echo '<td>';
								if(strlen($row['title']) > 30) { echo substr($row['title'],0,27).'...'; } else { echo $row['title']; }
								echo '</td>';
								echo '<td>'.date("d-m-Y \a\\t g:i a", abs(strtotime($row['datetime'])+$userTimeZone)).'</td>';
								echo '<td>';
								$replace_desc = array('<p>','</p>','\r\n','\n','\r');
								if(strlen($eventDescription) > 30) { echo str_replace($replace_desc,'',substr($eventDescription,0,27)).'...'; } else { echo str_replace($replace_desc, '', $row['description']); }
								echo '</td>';
								echo '<td style="text-align: center;">';
								if($row['enable_score'] == '1'){ 
									echo '<i class="icon-check" style="color: #33CC33;"></i>';
								} else {
									echo '<i class="icon-check-empty" style="color: #CC0000;"></i>';
								};
								echo '</td>';
								echo '<td style="text-align: center;">';
								if($row['enable_raiders'] == '1'){ 
									echo '<i class="icon-check" style="color: #33CC33;"></i>';
								} else {
									echo '<i class="icon-check-empty" style="color: #CC0000;"></i>';
								};
								echo '</td>';
								if($perms_raidEdit == '1'){
									echo '<td style="text-align: center; cursor: pointer;" onClick="postform(\'editForm_'.$row['id'].'\')" class="editForm"><i class="icon-pencil"></i></td>';
									echo '<form action="'.$_SERVER['PHP_SELF'].'?mode=eventEdit#events_add" method="post" class="hidden" id="editForm_'.$row['id'].'">';
									echo '<input type="hidden" name="id" value="'.$row['id'].'">';
									echo '</form>';
									echo '</td>';
								}
								if($perms_raidDele == '1'){
									echo '<td style="text-align: center; cursor: pointer;" onClick="postform(\'deleteForm_'.$row['id'].'\',\''.$row['title'].'\',\''.$row['id'].'\')" class="deleteForm"><i class="icon-trash"></i></td>';
									echo '<form action="'.$_SERVER['PHP_SELF'].'?method=eventDelete" method="post" class="hidden" id="deleteForm_'.$row['id'].'">';
									echo '<input type="hidden" name="id" value="'.$row['id'].'">';
									echo '</form>';
									echo '</td>';
								}
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php } ?>
<?php if ($perms_raidAdd == '1') { ?>
<div class="container well well-small need-height">
<div id="events_add" style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>Event Add/Edit Management</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>This is the Event Add/Edit section. Here you can Add, or Edit an event you have selected above.</p>
				</div>
				<br />
				<div>
				<?php
				if($_GET['mode'] == 'eventEdit'){
				// Edit Event
				$eventID = preg_replace('#[^0-9]#', '',$_POST['id']); 
				$sql = "SELECT * FROM events_final WHERE id='$eventID'";
				$que = mysqli_query($con,$sql);
				$row = mysqli_fetch_row($que);
				$eventNames = array('Triumph of the Dragon Queen','Frozen Tempest','Endless Eclipse','Grim Awakening');
				if($row[1] == 'Triumph of the Dragon Queen'){ $triumph = 'selected'; };
				if($row[1] == 'Frozen Tempest'){ $frozen = 'selected'; };
				if($row[1] == 'Endless Eclipse'){ $endless = 'selected'; };
				if($row[1] == 'Grim Awakening'){ $grim = 'selected'; };
				?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=editEvent">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="eventName">Event Name</label>
							<div class="controls">
							<?php if(in_array($row[1],$eventNames)){ ?>
								<select name="eventName" id="eventName" onChange="return isCustom()" class="input-xlarge">
									<option value="Triumph of the Dragon Queen" <?php echo $triumph; ?>>Triumph of the Dragon Queen</option>
									<option value="Frozen Tempest"<?php echo $frozen; ?>>Frozen Tempest</option>
									<option value="Endless Eclipse"<?php echo $endless; ?>>Endless Eclipse</option>
									<option value="Grim Awakening"<?php echo $grim; ?>>Grim Awakening</option>
									<option value="Custom">Custom</option>
								</select>
							<?php } else { ?>
								<input class="input-xlarge" id="event_title_text" name="eventName" type="text" onBlur="return isEmpty()" value="<?php echo $row[1]; ?>">
							<?php } ?>
								<p class="help-block">Please enter the name of the event. You may select custom to get a text box.</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="eventTime">Event Time</label>
							<div class="controls">
								<input type="text" name="eventTime" id="eventTime" value="<?php echo date('Y-m-d H:i:s',abs(strtotime($row[4])+$userTimeZone)); ?>">
								<p class="help-block">Please enter the date and time of the event.</p>
							</div>
						</div>
						<?php if($perms_raidScore == '1'){ ?>
						<div class="control-group">
							<label class="control-label" for="eventScore">Enable Score</label>
							<div class="controls">
								<label class="checkbox">
									<input type="checkbox" name="eventScore" id="eventScore" <?php if($row[6] == '1'){ echo 'checked'; } ?>>
									Check to enable Score Mode for this raid.
								</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="eventRaiders">Enable Raiders List</label>
							<div class="controls">
								<label class="checkbox">
									<input type="checkbox" name="eventRaiders" id="eventRaiders" <?php if($row[7] == '1'){ echo 'checked'; } ?>>
									Check to enable the Raiders List for this raid. <em>Not needed if above is checked</em>
								</label>
							</div>
						</div>
						<?php } else { ?>
						<input type="hidden" name="eventScore" id="eventScore" <?php if($row[6] == '1'){ echo 'value="on"'; } else { echo 'value=""'; }?>>
						<input type="hidden" name="eventRaiders" id="eventRaiders" <?php if($row[7] == '1'){ echo 'value="on"'; } else { echo 'value=""'; }?>>
						<?php } ?>
						<div class="control-group">
							<label class="control-label" for="eventDescription">Event Description</label>
							<div class="controls">
								<textarea class="input-xlarge" id="eventDescription" name="eventDescription"><?php echo $row[5]; ?></textarea>
							</div>
						</div>
						<div class="form-actions">
							<input type="hidden" name="eventID" value="<?php echo $row[0];?>">
							<button type="submit" class="btn btn-success" onClick="return verifyEventEdit()">Submit Event</button>
							<button type="reset" class="btn" onClick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php
				} else {
				// Add Event
				?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=addEvent">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="eventName">Event Name</label>
							<div class="controls">
								<select name="eventName" id="eventName" onChange="return isCustom()" class="input-xlarge">
									<option value="Triumph of the Dragon Queen">Triumph of the Dragon Queen</option>
									<option value="Frozen Tempest">Frozen Tempest</option>
									<option value="Endless Eclipse">Endless Eclipse</option>
									<option value="Grim Awakening">Grim Awakening</option>
									<option value="Custom">Custom</option>
								</select>
								<p class="help-block">Please enter the name of the event. You may select custom to get a text box.</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="eventTime">Event Time</label>
							<div class="controls">
								<?php 
								if ((isset($_GET['day'])) && (isset($_GET['month'])) && (isset($_GET['year']))){
									$daynew = preg_replace('#[^0-9]#','',$_GET['day']);
									$monnew = preg_replace('#[^0-9]#','',$_GET['month']);
									$yeanew = preg_replace('#[^0-9]#','',$_GET['year']);
									if(strlen($daynew) < 2){ $daynew = '0'.$daynew; };
									if(strlen($monnew) < 2){ $monnew = '0'.$monnew; };
									$dateofnewevent = date('Y-m-d H:i:s',strtotime(''.$yeanew.'-'.$monnew.'-'.$daynew.' 18:30:00')+$userTimeZone);
								} else {
									$dateofnewevent = date('Y-m-d H:i:s',abs(strtotime(date('Y-m-d 18:30:00'))+$userTimeZone));
								}
								?>
								<input type="text" name="eventTime" id="eventTime" value="<?php echo $dateofnewevent; ?>">
								<p class="help-block">Please enter the date and time of the event.</p>
							</div>
						</div>
						<?php if($perms_raidPM == '1'){ ?>
						<div class="control-group">
							<label class="control-label" for="eventPM">Event PM</label>
							<div class="controls">
								<select name="eventPM" id="eventPM">
									<option value="" selected>None</option>
									<option value="15">Alliance Raiders</option>
									<option value="10">All Alliance Members</option>
								</select>
								<p class="help-block">Which group would you like to send a PM to, alerting them of the Event?</p>
							</div>
						</div>
						<?php } ?>
						<?php if($perms_raidScore == '1'){ ?>
						<div class="control-group">
							<label class="control-label" for="eventScore">Enable Score</label>
							<div class="controls">
								<label class="checkbox">
									<input type="checkbox" name="eventScore" id="eventScore">
									Check to enable Score Mode for this raid.
								</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="eventRaiders">Enable Raiders List</label>
							<div class="controls">
								<label class="checkbox">
									<input type="checkbox" name="eventRaiders" id="eventRaiders">
									Check to enable the Raiders List for this raid. <em>Not needed if above is checked</em>
								</label>
							</div>
						</div>
						<?php } else { ?>
						<input type="hidden" name="eventScore" id="eventScore" value="">
						<input type="hidden" name="eventRaiders" id="eventRaiders" value="">
						<?php } ?>
						<div class="control-group">
							<label class="control-label" for="eventDescription">Event Description</label>
							<div class="controls">
								<textarea class="input-xlarge" id="eventDescription" name="eventDescription"></textarea>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-success" onClick="return verifyEventAdd()">Submit Event</button>
							<button type="reset" class="btn">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php
				}
				?>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php } ?>
<?php if ($perms_newsAdmin == '1') { ?>
<div class="container well well-small need-height">
<div id="news" style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>News Management</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>This is the News Management section. Here you can Add or Edit news.</p>
				</div>
				<br />
				<div>
				<?php
				if($_GET['mode'] == 'editNews'){
				// News Edit
				$newsID = preg_replace('#[^0-9]#', '',$_POST['id']);
				$sql = "SELECT * FROM news WHERE id='$newsID'";
				$que = mysqli_query($con,$sql);
				$row = mysqli_fetch_row($que);
				?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=editNews">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="newsTitle">News Title</label>
							<div class="controls">
								<input class="input-xlarge" id="newsTitle" name="newsTitle" type="text" value="<?php echo $row['news_title']; ?>">
								<p class="help-block">Please enter the title of the news post.</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="newsDescription">News Content</label>
							<div class="controls">
								<textarea class="input-xlarge" id="newsDescription" name="newsDescription"><?php echo $row['news_content']; ?></textarea>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-success" onClick="return verifyNewsAdd()">Edit News</button>
							<button type="reset" class="btn" onClick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php 
				} else {
				// News Add
				?>
				<?php if($perms_newsAdd == '1'){ ?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=addNews">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="newsTitle">News Title</label>
							<div class="controls">
								<input class="input-xlarge" id="newsTitle" name="newsTitle" type="text">
								<p class="help-block">Please enter the title of the news post.</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="newsDescription">News Content</label>
							<div class="controls">
								<textarea class="input-xlarge" id="newsDescription" name="newsDescription"></textarea>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-success" onClick="return verifyNewsAdd()">Submit News</button>
							<button type="reset" class="btn">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php } ?>
				<?php
				}
				?>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php } ?>
<?php if ($perms_userAdmin == '1') { ?>
<div class="container well well-small need-height">
<div id="raiders" style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>Raider Management</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>This is the Raider Management section. Here you can Add, Edit or Delete Raiders of the Alliance.</p>
				</div>
				<br />
				<div>
				<?php if($_GET['mode'] == 'editRaid'){
				$raiderID = preg_replace('#[^0-9]#', '',$_POST['id']); 
				$sql = "SELECT * FROM raider_info_final WHERE id='$raiderID'";
				$que = mysqli_query($con,$sql);
				$row = mysqli_fetch_array($que);
				// Edit Raider
				?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=editRaider" style="margin-bottom: 0;">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="userName">Character Name</label>
							<div class="controls">
								<input class="input-xlarge" id="userName" name="userName" type="text" value="<?php echo $row['username']; ?>">
								<p class="help-block">Please enter the users Character Name. <em>Forum Name is accepted, but not recommended.</em></p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="t1_20">Tier Info</label>
							<div class="controls">
							<table class="table table-bordered" id="raidersTable" style="text-align: center; margin-bottom: 0;">
								<thead>
									<tr>
										<th colspan="4" style="text-align: center;">Tier 1 - 20 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 10 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 20 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 10 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 20 Man</th>
									</tr>
									<tr>
										<th style="text-align: center;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t1_20_t" <?php if(strlen(strstr($row['T1_20'],'T'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t1_20_h" <?php if(strlen(strstr($row['T1_20'],'H'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t1_20_d" <?php if(strlen(strstr($row['T1_20'],'D'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t1_20_s" <?php if(strlen(strstr($row['T1_20'],'S'))>0){ echo 'checked'; } ?>></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Tank"><input type="checkbox" style="float: none;" name="t2_10_t" <?php if(strlen(strstr($row['T2_10'],'T'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Healer"><input type="checkbox" style="float: none;" name="t2_10_h" <?php if(strlen(strstr($row['T2_10'],'H'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Damage"><input type="checkbox" style="float: none;" name="t2_10_d" <?php if(strlen(strstr($row['T2_10'],'D'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Support"><input type="checkbox" style="float: none;" name="t2_10_s" <?php if(strlen(strstr($row['T2_10'],'S'))>0){ echo 'checked'; } ?>></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t2_20_t" <?php if(strlen(strstr($row['T2_20'],'T'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t2_20_h" <?php if(strlen(strstr($row['T2_20'],'H'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t2_20_d" <?php if(strlen(strstr($row['T2_20'],'D'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t2_20_s" <?php if(strlen(strstr($row['T2_20'],'S'))>0){ echo 'checked'; } ?>></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Tank"><input type="checkbox" style="float: none;" name="t3_10_t" <?php if(strlen(strstr($row['T3_10'],'T'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Healer"><input type="checkbox" style="float: none;" name="t3_10_h" <?php if(strlen(strstr($row['T3_10'],'H'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Damage"><input type="checkbox" style="float: none;" name="t3_10_d" <?php if(strlen(strstr($row['T3_10'],'D'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Support"><input type="checkbox" style="float: none;" name="t3_10_s" <?php if(strlen(strstr($row['T3_10'],'S'))>0){ echo 'checked'; } ?>></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t3_20_t" <?php if(strlen(strstr($row['T3_20'],'T'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t3_20_h" <?php if(strlen(strstr($row['T3_20'],'H'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t3_20_d" <?php if(strlen(strstr($row['T3_20'],'D'))>0){ echo 'checked'; } ?>></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t3_20_s" <?php if(strlen(strstr($row['T3_20'],'S'))>0){ echo 'checked'; } ?>></div></label></td>
									</tr>
								</tbody>
							</table>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="userWarn">Warning Level</label>
							<div class="controls">
								<input class="input-xlarge" id="userWarn" name="userWarn" type="text" value="<?php echo $row['warning']; ?>">
								<p class="help-block">Please be careful editing this value.</p>
							</div>
						</div>
						<div class="form-actions">
							<input type="hidden" name="userID" value="<?php echo $row['id']; ?>">
							<button type="submit" class="btn btn-success" onClick="return verifyRaiderEdit()">Submit Raider</button>
							<button type="reset" class="btn" onClick="document.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php } else { 
				// Add Raider
				?>
				<?php if($perms_userAdd == '1'){ ?>
				<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?method=addRaider" style="margin-bottom: 0;">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="userName">Character Name</label>
							<div class="controls">
								<input class="input-xlarge" id="userName" name="userName" type="text">
								<p class="help-block">Please enter the users Character Name. <em>Forum Name is accepted, but not recommended.</em></p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="t1_20">Tier Info</label>
							<div class="controls">
							<table class="table table-bordered" id="raidersTable" style="text-align: center; margin-bottom: 0;">
								<thead>
									<tr>
										<th colspan="4" style="text-align: center;">Tier 1 - 20 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 10 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 20 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 10 Man</th>
										<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 20 Man</th>
									</tr>
									<tr>
										<th style="text-align: center;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
										<th style="text-align: center; border-left: 1px solid #fff;">T</th>
										<th style="text-align: center;">H</th>
										<th style="text-align: center;">D</th>
										<th style="text-align: center;">S</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t1_20_t"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t1_20_h"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t1_20_d"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 1 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t1_20_s"></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Tank"><input type="checkbox" style="float: none;" name="t2_10_t"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Healer"><input type="checkbox" style="float: none;" name="t2_10_h"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Damage"><input type="checkbox" style="float: none;" name="t2_10_d"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 10 Man - Support"><input type="checkbox" style="float: none;" name="t2_10_s"></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t2_20_t"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t2_20_h"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t2_20_d"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 2 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t2_20_s"></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Tank"><input type="checkbox" style="float: none;" name="t3_10_t"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Healer"><input type="checkbox" style="float: none;" name="t3_10_h"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Damage"><input type="checkbox" style="float: none;" name="t3_10_d"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 10 Man - Support"><input type="checkbox" style="float: none;" name="t3_10_s"></div></label></td>
										
										<td style="text-align: center; border-left: 1px solid #fff;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Tank"><input type="checkbox" style="float: none;" name="t3_20_t"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Healer"><input type="checkbox" style="float: none;" name="t3_20_h"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Damage"><input type="checkbox" style="float: none;" name="t3_20_d"></div></label></td>
										<td style="text-align: center;"><label class="checkbox" style="text-align: center;">
										<div title="Tier 3 - 20 Man - Support"><input type="checkbox" style="float: none;" name="t3_20_s"></div></label></td>
									</tr>
								</tbody>
							</table>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-success" onClick="return verifyRaiderAdd()">Submit Raider</button>
							<button type="reset" class="btn">Reset</button>
						</div>
					</fieldset>
				</form>
				<?php } ?>
				<?php } ?>
				<hr />
					<table class="table table-bordered table-striped table-hover" id="raidersTable2">
						<thead>
							<tr>
								<th colspan="1">&nbsp;</th>
								<th colspan="4" style="text-align: center;">Tier 1 - 20 Man</th>
								<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 10 Man</th>
								<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 2 - 20 Man</th>
								<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 10 Man</th>
								<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Tier 3 - 20 Man</th>
								<th colspan="4" style="text-align: center; border-left: 1px solid #fff;">Raider Info</th>
								<th colspan="2" style="text-align: center; border-left: 1px solid #fff;">&nbsp;</th>
							</tr>
							<tr>
								<th>Username</th>
								<th style="text-align: center;">T</th>
								<th style="text-align: center;">H</th>
								<th style="text-align: center;">D</th>
								<th style="text-align: center;">S</th>
								<th style="text-align: center; border-left: 1px solid #fff;">T</th>
								<th style="text-align: center;">H</th>
								<th style="text-align: center;">D</th>
								<th style="text-align: center;">S</th>
								<th style="text-align: center; border-left: 1px solid #fff;">T</th>
								<th style="text-align: center;">H</th>
								<th style="text-align: center;">D</th>
								<th style="text-align: center;">S</th>
								<th style="text-align: center; border-left: 1px solid #fff;">T</th>
								<th style="text-align: center;">H</th>
								<th style="text-align: center;">D</th>
								<th style="text-align: center;">S</th>
								<th style="text-align: center; border-left: 1px solid #fff;">T</th>
								<th style="text-align: center;">H</th>
								<th style="text-align: center;">D</th>
								<th style="text-align: center;">S</th>
								<th style="text-align: center; border-left: 1px solid #fff;">Signs</th>
								<th style="text-align: center; ">Attens</th>
								<th style="text-align: center; ">Score</th>
								<th style="text-align: center; ">Warns</th>
								<?php if($perms_userEdit == '1') { ?>
								<th style="border-left: 1px solid #fff;">&nbsp;</th>
								<?php } ?>
								<?php if($perms_userDele == '1') { ?>
								<th>&nbsp;</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
						<?php
						$sql = "SELECT * FROM raider_info_final ORDER BY username ASC";
						$que = mysqli_query($con,$sql);
						while($row = mysqli_fetch_array($que)){
							$t1_20    = $row['T1_20'];
							$t2_10    = $row['T2_10'];
							$t2_20    = $row['T2_20'];
							$t3_10    = $row['T3_10'];
							$t3_20    = $row['T3_20'];
							$background = array('#282828','#303030','#383838','#404040','#484848');
							$Tiers      = array('Tier 1 - 20 Man','Tier 2 - 10 Man','Tier 2 - 20 Man','Tier 3 - 10 Man','Tier 3 - 20 Man');
							echo '<tr>';
							echo '<td>'.$row['username'].'</td>';
							for($i=0; $i <= 4; $i++){
								if ($i == 0){ $tier = $t1_20; }
								if ($i == 1){ $tier = $t2_10; }
								if ($i == 2){ $tier = $t2_20; }
								if ($i == 3){ $tier = $t3_10; }
								if ($i == 4){ $tier = $t3_20; }
								if ($i == 0){
								echo '<td style="text-align: center; background-color: '.$background[$i].';">';
								} else {
									echo '<td style="text-align: center; background-color: '.$background[$i].'; border-left: 1px solid #fff;">';
								}
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
							echo '<td style="text-align: center; border-left: 1px solid #fff;">'.$row['signups'].'</td>';
							echo '<td style="text-align: center; ">'.$row['attended'].'</td>';
							echo '<td style="text-align: center; ">'.abs($row['attended'] / $row['signups']).'</td>';
							echo '<td style="text-align: center; ">'.$row['warning'].'</td>';
							if($perms_userEdit == '1') {
								echo '<td style="text-align: center; border-left: 1px solid #fff;" onClick="postformraid(\'editFormRaid_'.$row['id'].'\')" class="editForm"><i class="icon-pencil"></i></td>';
								echo '<form action="'.$_SERVER['PHP_SELF'].'?mode=editRaid#raiders" method="post" class="hidden" id="editFormRaid_'.$row['id'].'">';
								echo '<input type="hidden" name="id" value="'.$row['id'].'">';
								echo '</form>';
							}
							if($perms_userDele == '1') {
								echo '<td style="text-align: center;" onClick="postformraid(\'deleFormRaid_'.$row['id'].'\',\''.$row['username'].'\',\''.$row['id'].'\')" class="deleteForm"><i class="icon-trash"></i></td>';
								echo '<form action="'.$_SERVER['PHP_SELF'].'?method=deleRaid" method="post" class="hidden" id="deleFormRaid_'.$row['id'].'">';
								echo '<input type="hidden" name="id" value="'.$row['id'].'">';
								echo '</form>';
							}
							echo '</tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php } ?>
<?php if ($perms_permAdmin == '1') { ?>
<div class="container well well-small need-height">
<div id="permissions" style="padding-top: 90px; margin-top: -90px;">
	<div class="row-fluid">
		<div class="span12">
			<div>
				<h5>Permissions Management</h5>
				<div style="background: #070809; border: 1px solid #000000; padding: 5px;">
				<p>This is the Permissions Management section. Here you can modify the permissions of the Officer, Event Manager and Alliance Leader groups.</p>
				</div>
				<br />
				<div>
				<?php
				$sql = "SELECT * FROM admin_perms";
				$que = mysqli_query($con,$sql);
				$ampr_GID = array();
				$ampr_GPR = array();
				while($row = mysqli_fetch_array($que)){
					array_push($ampr_GID,$row['group_id']);
					array_push($ampr_GPR,$row['group_perms']);
				}
				?>
					<div id="tabs">
						<ul>
							<li><a href="#tab-1">Alliance Leaders</a></li>
							<li><a href="#tab-2">Alliance Officers</a></li>
							<li><a href="#tab-3">Event Managers</a></li>
						</ul>
						<div id="tab-1">
							<?php
							$ampr_GPRAL = $ampr_GPR[0];
							$alP_newsAdmin = substr($ampr_GPRAL,0,1);
							$alP_newsAdd   = substr($ampr_GPRAL,1,1);
							$alP_newsEdit  = substr($ampr_GPRAL,2,1);
							$alP_newsDele  = substr($ampr_GPRAL,3,1);
							$alP_raidAdmin = substr($ampr_GPRAL,4,1);
							$alP_raidAdd   = substr($ampr_GPRAL,5,1);
							$alP_raidEdit  = substr($ampr_GPRAL,6,1);
							$alP_raidDele  = substr($ampr_GPRAL,7,1);
							$alP_raidScore = substr($ampr_GPRAL,8,1);
							$alP_raidPM    = substr($ampr_GPRAL,9,1);
							$alP_raidStat  = substr($ampr_GPRAL,10,1);
							$alP_userAdmin = substr($ampr_GPRAL,11,1);
							$alP_userAdd   = substr($ampr_GPRAL,12,1);
							$alP_userEdit  = substr($ampr_GPRAL,13,1);
							$alP_userDele  = substr($ampr_GPRAL,14,1);
							$alP_permAdmin = substr($ampr_GPRAL,15,1);
							$alP_suicAdmin = substr($ampr_GPRAL,16,1);
							$alP_suicAdd   = substr($ampr_GPRAL,17,1);
							$alP_suicEdit  = substr($ampr_GPRAL,18,1);
							$alP_suicDele  = substr($ampr_GPRAL,19,1);
							?>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=ALPerms" method="post" style="margin-bottom: 0;">
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">News Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global News Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="AL_newsAdmin" value="1" <?php if($alP_newsAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="AL_newsAdmin" value="0" <?php if($alP_newsAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add News - <small style="font-size: 11px;">Group can add news to the home page.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="AL_newsAdd" value="1" <?php if($alP_newsAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="AL_newsAdd" value="0" <?php if($alP_newsAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit News - <small style="font-size: 11px;">Group can edit news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="AL_newsEdit" value="1" <?php if($alP_newsEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="AL_newsEdit" value="0" <?php if($alP_newsEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete News - <small style="font-size: 11px;">Group can delete news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="AL_newsDel" value="1" <?php if($alP_newsDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="AL_newsDel" value="0" <?php if($alP_newsDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Event Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Event Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="AL_eventAdmin" value="1" <?php if($alP_raidAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="AL_eventAdmin" value="0" <?php if($alP_raidAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Events - <small style="font-size: 11px;">Group can add events to the calendar.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="AL_eventAdd" value="1" <?php if($alP_raidAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="AL_eventAdd" value="0" <?php if($alP_raidAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Events - <small style="font-size: 11px;">Group can edit events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="AL_eventEdit" value="1" <?php if($alP_raidEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="AL_eventEdit" value="0" <?php if($alP_raidEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Events - <small style="font-size: 11px;">Group can delete events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="AL_eventDel" value="1" <?php if($alP_raidDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="AL_eventDel" value="0" <?php if($alP_raidDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Enable Score Mode - <small style="font-size: 11px;">Group can enable score mode / raiders list only mode on events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="AL_eventScore" value="1" <?php if($alP_raidScore == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="AL_eventScore" value="0" <?php if($alP_raidScore == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>PM Users - <small style="font-size: 11px;">Group can chose to PM users on event creation.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="AL_eventPM" value="1" <?php if($alP_raidPM == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="AL_eventPM" value="0" <?php if($alP_raidPM == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Status - <small style="font-size: 11px;">Group can edit peoples status' on the signup pages. <b>Be careful!</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="AL_eventStat" value="1" <?php if($alP_raidStat == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="AL_eventStat" value="0" <?php if($alP_raidStat == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Raiders Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Raiders Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="AL_raidersAdmin" value="1" <?php if($alP_userAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="AL_raidersAdmin" value="0" <?php if($alP_userAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Raiders - <small style="font-size: 11px;">Group can add raiders to the Raiders List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="AL_raidersAdd" value="1" <?php if($alP_userAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="AL_raidersAdd" value="0" <?php if($alP_userAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Raiders - <small style="font-size: 11px;">Group can edit raiders information on the Raiders List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="AL_raidersEdit" value="1" <?php if($alP_userEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="AL_raidersEdit" value="0" <?php if($alP_userEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Raiders - <small style="font-size: 11px;">Group can delete raiders from the Raider List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="AL_raidersDel" value="1" <?php if($alP_userDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="AL_raidersDel" value="0" <?php if($alP_userDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Suicide List Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Suicide Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="AL_suicideAdmin" value="1" <?php if($alP_suicAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="AL_suicideAdmin" value="0" <?php if($alP_suicAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Suicide - <small style="font-size: 11px;">Group can add players to the Suicide List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="AL_suicideAdd" value="1" <?php if($alP_suicAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="AL_suicideAdd" value="0" <?php if($alP_suicAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Suicide - <small style="font-size: 11px;">Group can edit the suicide list <b>Including Movement / Suicides / Setup.</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="AL_suicideEdit" value="1" <?php if($alP_suicEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="AL_suicideEdit" value="0" <?php if($alP_suicEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Suicide - <small style="font-size: 11px;">Group can delete users from the Suicide List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="AL_suicideDel" value="1" <?php if($alP_suicDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="AL_suicideDel" value="0" <?php if($alP_suicDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Permissions Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Permissions Administration - <small style="font-size: 11px;">Allows the group to edit Permissions (This whole section).</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="AL_permAdmin" value="1" <?php if($alP_permAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="AL_permAdmin" value="0" <?php if($alP_permAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="form-actions">
									<button type="submit" class="btn btn-success">Save Changes</button>
								</div>
							</form>
						</div>
						<div id="tab-2">
							<?php
							$ampr_GPROF = $ampr_GPR[1];
							$ofP_newsAdmin = substr($ampr_GPROF,0,1);
							$ofP_newsAdd   = substr($ampr_GPROF,1,1);
							$ofP_newsEdit  = substr($ampr_GPROF,2,1);
							$ofP_newsDele  = substr($ampr_GPROF,3,1);
							$ofP_raidAdmin = substr($ampr_GPROF,4,1);
							$ofP_raidAdd   = substr($ampr_GPROF,5,1);
							$ofP_raidEdit  = substr($ampr_GPROF,6,1);
							$ofP_raidDele  = substr($ampr_GPROF,7,1);
							$ofP_raidScore = substr($ampr_GPROF,8,1);
							$ofP_raidPM    = substr($ampr_GPROF,9,1);
							$ofP_raidStat  = substr($ampr_GPROF,10,1);
							$ofP_userAdmin = substr($ampr_GPROF,11,1);
							$ofP_userAdd   = substr($ampr_GPROF,12,1);
							$ofP_userEdit  = substr($ampr_GPROF,13,1);
							$ofP_userDele  = substr($ampr_GPROF,14,1);
							$ofP_permAdmin = substr($ampr_GPROF,15,1);
							$ofP_suicAdmin = substr($ampr_GPROF,16,1);
							$ofP_suicAdd   = substr($ampr_GPROF,17,1);
							$ofP_suicEdit  = substr($ampr_GPROF,18,1);
							$ofP_suicDele  = substr($ampr_GPROF,19,1);
							?>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=OFPerms" method="post" style="margin-bottom: 0;">
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">News Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global News Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="OF_newsAdmin" value="1" <?php if($ofP_newsAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="OF_newsAdmin" value="0" <?php if($ofP_newsAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add News - <small style="font-size: 11px;">Group can add news to the home page.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="OF_newsAdd" value="1" <?php if($ofP_newsAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="OF_newsAdd" value="0" <?php if($ofP_newsAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit News - <small style="font-size: 11px;">Group can edit news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="OF_newsEdit" value="1" <?php if($ofP_newsEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="OF_newsEdit" value="0" <?php if($ofP_newsEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete News - <small style="font-size: 11px;">Group can delete news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="OF_newsDel" value="1" <?php if($ofP_newsDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="OF_newsDel" value="0" <?php if($ofP_newsDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Event Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Event Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="OF_eventAdmin" value="1" <?php if($ofP_raidAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="OF_eventAdmin" value="0" <?php if($ofP_raidAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Events - <small style="font-size: 11px;">Group can add events to the calendar.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="OF_eventAdd" value="1" <?php if($ofP_raidAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="OF_eventAdd" value="0" <?php if($ofP_raidAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Events - <small style="font-size: 11px;">Group can edit events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="OF_eventEdit" value="1" <?php if($ofP_raidEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="OF_eventEdit" value="0" <?php if($ofP_raidEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Events - <small style="font-size: 11px;">Group can delete events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="OF_eventDel" value="1" <?php if($ofP_raidDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="OF_eventDel" value="0" <?php if($ofP_raidDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Enable Score Mode - <small style="font-size: 11px;">Group can enable score mode / raiders list only mode on events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="OF_eventScore" value="1" <?php if($ofP_raidScore == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="OF_eventScore" value="0" <?php if($ofP_raidScore == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>PM Users - <small style="font-size: 11px;">Group can chose to PM users on event creation.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="OF_eventPM" value="1" <?php if($ofP_raidPM == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="OF_eventPM" value="0" <?php if($ofP_raidPM == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Status - <small style="font-size: 11px;">Group can edit peoples status' on the signup pages. <b>Be careful!</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="OF_eventStat" value="1" <?php if($ofP_raidStat == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="OF_eventStat" value="0" <?php if($ofP_raidStat == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Raiders Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Raiders Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="OF_raidersAdmin" value="1" <?php if($ofP_userAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="OF_raidersAdmin" value="0" <?php if($ofP_userAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Raiders - <small style="font-size: 11px;">Group can add raiders to the Raiders List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="OF_raidersAdd" value="1" <?php if($ofP_userAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="OF_raidersAdd" value="0" <?php if($ofP_userAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Raiders - <small style="font-size: 11px;">Group can edit raiders information on the Raiders List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="OF_raidersEdit" value="1" <?php if($ofP_userEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="OF_raidersEdit" value="0" <?php if($ofP_userEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Raiders - <small style="font-size: 11px;">Group can delete raiders from the Raider List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="OF_raidersDel" value="1" <?php if($ofP_userDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="OF_raidersDel" value="0" <?php if($ofP_userDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Suicide List Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Suicide Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="OF_suicideAdmin" value="1" <?php if($ofP_suicAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="OF_suicideAdmin" value="0" <?php if($ofP_suicAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Suicide - <small style="font-size: 11px;">Group can add players to the Suicide List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="OF_suicideAdd" value="1" <?php if($ofP_suicAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="OF_suicideAdd" value="0" <?php if($ofP_suicAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Suicide - <small style="font-size: 11px;">Group can edit the suicide list <b>Including Movement / Suicides / Setup.</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="OF_suicideEdit" value="1" <?php if($ofP_suicEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="OF_suicideEdit" value="0" <?php if($ofP_suicEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Suicide - <small style="font-size: 11px;">Group can delete users from the Suicide List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="OF_suicideDel" value="1" <?php if($ofP_suicDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="OF_suicideDel" value="0" <?php if($ofP_suicDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Permissions Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Permissions Administration - <small style="font-size: 11px;">Allows the group to edit Permissions (This whole section).</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="OF_permAdmin" value="1" <?php if($ofP_permAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="OF_permAdmin" value="0" <?php if($ofP_permAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="form-actions">
									<button type="submit" class="btn btn-success">Save Changes</button>
								</div>
							</form>
						</div>
						<div id="tab-3">
							<?php
							$ampr_GPRAM = $ampr_GPR[2];
							$emP_newsAdmin = substr($ampr_GPRAM,0,1);
							$emP_newsAdd   = substr($ampr_GPRAM,1,1);
							$emP_newsEdit  = substr($ampr_GPRAM,2,1);
							$emP_newsDele  = substr($ampr_GPRAM,3,1);
							$emP_raidAdmin = substr($ampr_GPRAM,4,1);
							$emP_raidAdd   = substr($ampr_GPRAM,5,1);
							$emP_raidEdit  = substr($ampr_GPRAM,6,1);
							$emP_raidDele  = substr($ampr_GPRAM,7,1);
							$emP_raidScore = substr($ampr_GPRAM,8,1);
							$emP_raidPM    = substr($ampr_GPRAM,9,1);
							$emP_raidStat  = substr($ampr_GPRAM,10,1);
							$emP_userAdmin = substr($ampr_GPRAM,11,1);
							$emP_userAdd   = substr($ampr_GPRAM,12,1);
							$emP_userEdit  = substr($ampr_GPRAM,13,1);
							$emP_userDele  = substr($ampr_GPRAM,14,1);
							$emP_permAdmin = substr($ampr_GPRAM,15,1);
							$emP_suicAdmin = substr($ampr_GPRAM,16,1);
							$emP_suicAdd   = substr($ampr_GPRAM,17,1);
							$emP_suicEdit  = substr($ampr_GPRAM,18,1);
							$emP_suicDele  = substr($ampr_GPRAM,19,1);
							?>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?method=EMPerms" method="post" style="margin-bottom: 0;">
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">News Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global News Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="EM_newsAdmin" value="1" <?php if($emP_newsAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdmin" id="EM_newsAdmin" value="0" <?php if($emP_newsAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add News - <small style="font-size: 11px;">Group can add news to the home page.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="EM_newsAdd" value="1" <?php if($emP_newsAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsAdd" id="EM_newsAdd" value="0" <?php if($emP_newsAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit News - <small style="font-size: 11px;">Group can edit news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="EM_newsEdit" value="1" <?php if($emP_newsEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsEdit" id="EM_newsEdit" value="0" <?php if($emP_newsEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete News - <small style="font-size: 11px;">Group can delete news.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="EM_newsDel" value="1" <?php if($emP_newsDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_newsDel" id="EM_newsDel" value="0" <?php if($emP_newsDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Event Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Event Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="EM_eventAdmin" value="1" <?php if($emP_raidAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdmin" id="EM_eventAdmin" value="0" <?php if($emP_raidAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Events - <small style="font-size: 11px;">Group can add events to the calendar.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="EM_eventAdd" value="1" <?php if($emP_raidAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventAdd" id="EM_eventAdd" value="0" <?php if($emP_raidAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Events - <small style="font-size: 11px;">Group can edit events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="EM_eventEdit" value="1" <?php if($emP_raidEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventEdit" id="EM_eventEdit" value="0" <?php if($emP_raidEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Events - <small style="font-size: 11px;">Group can delete events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="EM_eventDel" value="1" <?php if($emP_raidDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventDel" id="EM_eventDel" value="0" <?php if($emP_raidDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Enable Score Mode - <small style="font-size: 11px;">Group can enable score mode / raiders list only mode on events.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="EM_eventScore" value="1" <?php if($emP_raidScore == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventScore" id="EM_eventScore" value="0" <?php if($emP_raidScore == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>PM Users - <small style="font-size: 11px;">Group can chose to PM users on event creation.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="EM_eventPM" value="1" <?php if($emP_raidPM == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventPM" id="EM_eventPM" value="0" <?php if($emP_raidPM == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Status - <small style="font-size: 11px;">Group can edit peoples status' on the signup pages. <b>Be careful!</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="EM_eventStat" value="1" <?php if($emP_raidStat == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_eventStat" id="EM_eventStat" value="0" <?php if($emP_raidStat == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Raiders Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Raiders Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="EM_raidersAdmin" value="1" <?php if($emP_userAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdmin" id="EM_raidersAdmin" value="0" <?php if($emP_userAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Raiders - <small style="font-size: 11px;">Group can add raiders to the Raiders List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="EM_raidersAdd" value="1" <?php if($emP_userAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersAdd" id="EM_raidersAdd" value="0" <?php if($emP_userAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Raiders - <small style="font-size: 11px;">Group can edit raiders information on the Raiders List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="EM_raidersEdit" value="1" <?php if($emP_userEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersEdit" id="EM_raidersEdit" value="0" <?php if($emP_userEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Raiders - <small style="font-size: 11px;">Group can delete raiders from the Raider List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="EM_raidersDel" value="1" <?php if($emP_userDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_raidersDel" id="EM_raidersDel" value="0" <?php if($emP_userDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Suicide List Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Suicide Administration - <small style="font-size: 11px;">If "no", options below will have no effect.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="EM_suicideAdmin" value="1" <?php if($emP_suicAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdmin" id="EM_suicideAdmin" value="0" <?php if($emP_suicAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Add Suicide - <small style="font-size: 11px;">Group can add players to the Suicide List</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="EM_suicideAdd" value="1" <?php if($emP_suicAdd == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideAdd" id="EM_suicideAdd" value="0" <?php if($emP_suicAdd == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Edit Suicide - <small style="font-size: 11px;">Group can edit the suicide list <b>Including Movement / Suicides / Setup.</b></small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="EM_suicideEdit" value="1" <?php if($emP_suicEdit == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideEdit" id="EM_suicideEdit" value="0" <?php if($emP_suicEdit == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
										<tr>
											<td>Delete Suicide - <small style="font-size: 11px;">Group can delete users from the Suicide List.</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="EM_suicideDel" value="1" <?php if($emP_suicDele == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_suicideDel" id="EM_suicideDel" value="0" <?php if($emP_suicDele == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="table table-hover table-striped table-custom">
									<thead>
										<tr>
											<th colspan="3" style="text-align: center;">Permissions Administration</th>
										</tr>
										<tr>
											<th>Permission Description</th>
											<th style="width: 50px; text-align: center;">Yes</th>
											<th style="width: 50px; text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Global Permissions Administration - <small style="font-size: 11px;">Allows the group to edit Permissions (This whole section).</small></td>
											<td style="background: #0C0;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="EM_permAdmin" value="1" <?php if($emP_permAdmin == '1'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
											<td style="background: #900;">
												<label class="radio" style="text-align: center; padding-left: 0;">
													<div>
														<input type="radio" style="float: none; margin-left: 0;" name="AL_permAdmin" id="EM_permAdmin" value="0" <?php if($emP_permAdmin == '0'){ echo 'checked'; } ?>>
													</div>
												</label>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="form-actions">
									<button type="submit" class="btn btn-success">Save Changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!--/row-fluid-->
</div>
</div><!--/container-fluid-->
<?php } ?>
<?php } ?>

<div class="navbar navbar-inverse navbar-fixed-bottom">
	<div class="navbar-inner">
		<div class="container-fluid">
			<?php include('inc/footer.php'); ?>
		</div><!--/container-fluid-->
	</div><!--/navbar-inner-->
</div><!--/navbar-fixed-bottom-->
<!-- JAVASCRIPT -->
<script>
$(function() {
	$( "#tabs" ).tabs();
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	CKEDITOR.replace('eventDescription', {
		autoGrow_minHeight: 200,
		autoGrow_maxHeight: 500,
		height: 200
	});
	CKEDITOR.replace('newsDescription', {
		autoGrow_minHeight: 200,
		autoGrow_maxHeight: 500,
		height: 200
	});
});
</script>
<script type="text/javascript">
$.backstretch("./images/temp_bg.jpg");
</script>
<script type="text/javascript">
function postform(formName,userName,eventID)
{
	if(formName == 'deleteForm_'+eventID){
		if(confirm('Are you sure you want to delete ' + userName + ' from the Events?')){
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
function postformraid(formName,userName,eventID)
{
	if(formName == 'deleFormRaid_'+eventID){
		if(confirm('Are you sure you want to delete ' + userName + ' from the Raiders?')){
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
$('#eventTime').datetimepicker({
	timeFormat: 'HH:mm:ss',
	stepHour: 1,
	stepMinute: 10,
	stepSecond: 10,
	dateFormat: 'yy-mm-dd'
});
</script>
<script type="text/javascript">
function isEmpty(){
	if ($('#event_title_text').val() === "") {
		$('#event_title_text').replaceWith('<select name="eventName" id="eventName" onChange="return isCustom()" class="input-xlarge"><option value="Triumph of the Dragon Queen">Triumph of the Dragon Queen</option><option value="Frozen Tempest">Frozen Tempest</option><option value="Endless Eclipse">Endless Eclipse</option><option value="Grim Awakening">Grim Awakening</option><option value="Custom">Custom</option></select>');
		$('#eventName').focus();
		return false;
	}
	return true;
};
</script>
<script type="text/javascript">
function isCustom(){
	if ($('#eventName').val() === "Custom") {
		$('#eventName').replaceWith('<input class="input-xlarge" id="event_title_text" name="eventName" type="text" onBlur="return isEmpty()">');
		$('#event_title_text').focus();
		return false;
	}
	return true;
};
</script>
<script>
jQuery('#raidersTable tbody td div[title]').tooltip();
jQuery('#raidersTable2 tbody td div[title]').tooltip();
</script>
</body>
</html>