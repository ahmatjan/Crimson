<?php
/**
*
* @package phpbb3-Rokbb3
* @version 1.0: menunav.php 2008-09-15 jhalleck $
* @copyright (c) 2008 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Get current short script name, "i" and "mode" URL parameter to determine detailed menu location
	$menu_script_name=str_replace('.' . $phpEx, '', $user->page['page_name']);
 
 if (isset($_GET["i"])) $menu_url_i=($_GET['i']);
 if (isset($_GET["mode"])) $menu_url_mode=($_GET['mode']);
	
// Set class for style state (blank single space needed)
	$style_class_name=' class="active"';
	
// Assign null defaults for menu styles
	$style_U_INDEX='';
	$style_U_MCP='';
	$style_U_PROFILE='';
	$style_U_PRIVATEMSGS='';
	$style_U_FAQ='';
	$style_U_MEMBERLIST='';
	$style_U_REGISTER='';
    $style_U_LOGIN_LOGOUT='';
	
// Determine menu location for style state

switch ($menu_script_name) {
case "index":
	$style_U_INDEX=$style_class_name;
	break;
case "mcp":
	$style_U_MCP=$style_class_name;
	break;
case "ucp":
	switch ($menu_url_i) {
	default:
		$style_U_PROFILE=$style_class_name;
		break;
	case "pm":
		$style_U_PRIVATEMSGS=$style_class_name;
		break;
    }
	switch ($menu_url_mode) {
    case "register":
    	$style_U_REGISTER=$style_class_name;
        break;
	case "login":
		$style_U_LOGIN_LOGOUT=$style_class_name;
        break;
	}        
    break;
case "faq":
	$style_U_FAQ=$style_class_name;
	break;
case "memberlist":
	$style_U_MEMBERLIST=$style_class_name;
	break;
}

$template->assign_vars(array(
// Assign menu style state
	'U_INDEX_STYLE' => ($style_U_INDEX),
	'U_MCP_STYLE' => ($style_U_MCP),
	'U_PROFILE_STYLE' => ($style_U_PROFILE),
	'U_PRIVATEMSGS_STYLE' => ($style_U_PRIVATEMSGS),
	'U_FAQ_STYLE' => ($style_U_FAQ),
	'U_MEMBERLIST_STYLE' => ($style_U_MEMBERLIST),
	'U_REGISTER_STYLE' => ($style_U_REGISTER),    
	'U_LOGIN_LOGOUT_STYLE' => ($style_U_LOGIN_LOGOUT),
));
?>