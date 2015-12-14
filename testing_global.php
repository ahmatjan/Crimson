<?php
	session_start();
	// Set Date to UTC
	date_default_timezone_set('UTC');
	
	// Setup PHPBB
	define('IN_PHPBB', true);
	define('ROOT_PATH', "forum");
	if (!defined('IN_PHPBB') || !defined('ROOT_PATH')) {
		exit('Not in PHPBB, please contact an admin quoting error "PHPBB Define"');
	};
	$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : ROOT_PATH . '/';
	$phpEx = 'php';
	include($phpbb_root_path . 'common.' . $phpEx);
	include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
	include($phpbb_root_path . 'includes/functions_user.'.$phpEx);
	include($phpbb_root_path . 'config.' . $phpEx);
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup();
	$user->get_profile_fields( $user->data['user_id'] );
	
	//------------- Global Information of User -------------//
	
	// Get Users Name
	$userName = $user->data['username'];
	$userNameClean = $user->data['username_clean'];
	$userID   = $user->data['user_id'];
	
	// Get Users Groups
	$userGroups = group_memberships(false,$user->data['user_id']);
	$userGroupCou = count($userGroups);
	$userGroupIDs = array();
	for($x=0; $x<$userGroupCou; $x++){
		array_push($userGroupIDs,$userGroups[$x]['group_id']);
	};
	
	// Get Users Class
	$userMainClass = $user->profile_fields['pf_main_class'];
	$userAlt1Class = $user->profile_fields['pf_alt_one_class'];
	$userAlt2Class = $user->profile_fields['pf_alt_two_class'];
	$userAlt3Class = $user->profile_fields['pf_alt_three_class'];
	
	// Get Users Colour
	$userColour = $user->data['colour'];
	
	// Get Users Timezone
	$userTimeZone = $_SESSION['userTimeZone'];
	
	// Set Users Character Array
	$userCharacters = array();
	$userChar1 = $user->profile_fields['pf_main_char_name'];
	$userChar2 = $user->profile_fields['pf_alt_one'];
	$userChar3 = $user->profile_fields['pf_alt_two'];
	$userChar4 = $user->profile_fields['pf_alt_three'];
	for($x=1; $x<=4; $x++){
		if (${'userChar'.$x} == ''){
			// Do Nothing
		} else {
			array_push($userCharacters,${'userChar'.$x});
		};
	};
	
	//------------- MySQLi Connection Information -------------//
	$con = mysqli_connect('localhost','crimson_alliance','Acisherrig1@','crimson_alliance');
	if(mysqli_connect_errno($con)){
		exit('Failed to connect to MySQL: - Please contact the administrator of the website, quoting: \n'.mysqli_connect_error().'');
	};
	
	//------------- Administration Groups -------------//
	
	if( (in_array('8',$userGroupIDs)) || ($userName == 'Neekasa')){
		$sql = "SELECT group_perms FROM admin_perms WHERE group_id=8";
		$que = mysqli_query($con,$sql);
		$res = mysqli_fetch_array($que);
		$userPerms = $res['group_perms'];
	} else if ((in_array('9',$userGroupIDs))){
		$sql = "SELECT group_perms FROM admin_perms WHERE group_id=9";
		$que = mysqli_query($con,$sql);
		$res = mysqli_fetch_array($que);
		$userPerms = $res['group_perms'];
	} else if ((in_array('13',$userGroupIDs))){
		$sql = "SELECT group_perms FROM admin_perms WHERE group_id=13";
		$que = mysqli_query($con,$sql);
		$res = mysqli_fetch_array($que);
		$userPerms = $res['group_perms'];
	};
	
	$perms_newsAdmin = substr($userPerms,0,1);
	$perms_newsAdd   = substr($userPerms,1,1);
	$perms_newsEdit  = substr($userPerms,2,1);
	$perms_newsDele  = substr($userPerms,3,1);
	$perms_raidAdmin = substr($userPerms,4,1);
	$perms_raidAdd   = substr($userPerms,5,1);
	$perms_raidEdit  = substr($userPerms,6,1);
	$perms_raidDele  = substr($userPerms,7,1);
	$perms_raidScore = substr($userPerms,8,1);
	$perms_raidPM    = substr($userPerms,9,1);
	$perms_raidStat  = substr($userPerms,10,1);
	$perms_userAdmin = substr($userPerms,11,1);
	$perms_userAdd   = substr($userPerms,12,1);
	$perms_userEdit  = substr($userPerms,13,1);
	$perms_userDele  = substr($userPerms,14,1);
	$perms_permAdmin = substr($userPerms,15,1);
	$perms_suicAdmin = substr($userPerms,16,1);
	$perms_suicAdd   = substr($userPerms,17,1);
	$perms_suicEdit  = substr($userPerms,18,1);
	$perms_suicDele  = substr($userPerms,19,1);
	
	//------------- Global Information End -------------//
	
?>