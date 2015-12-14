<?php
/**
*
* @package phpbb3-Rokbb3
* @version 3.1.0: rokbb3.php 2010-07 $
* @copyright (c) 2008 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Don't allow direct access
die( 'Restricted access' );
?>
<!-- PHP -->
global $config, $template;
function rok_isIe($version = false) {   

	$agent=$_SERVER['HTTP_USER_AGENT'];  

	$found = strpos($agent,'MSIE ');  
	if ($found) { 
	        if ($version) {
	            $ieversion = substr(substr($agent,$found+5),0,1);   
	            if ($ieversion == $version) return true;
	            else return false;
	        } else {
	            return true;
	        }
	        
        } else {
                return false;
        }
	if (stristr($agent, 'msie'.$ieversion)) return true;
	return false;        
}

if (rok_isIe(6)) {
$template->assign_vars(array(
'IE_VERSION'	=>  "6",
));
}
if (rok_isIe(7)) {
$template->assign_vars(array(
'IE_VERSION'	=>  "7",
));
}
if (rok_isIe(8)) {
$template->assign_vars(array(
'IE_VERSION'	=>  "8",
));
}



global $db, $config, $template, $SID, $_SID, $user, $auth, $phpEx, $phpbb_root_path;
$joomla = get_include_contents($phpbb_root_path."{$config['jmenu_path']}"."{$config['jmenu_guest_name']}");
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

$joomla_reg = get_include_contents($phpbb_root_path."{$config['jmenu_path']}"."{$config['jmenu_reg_name']}");
function get_include_registered_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}


// Menu Navigation
// Get current short script name, "i" and "mode" URL parameter to determine detailed menu location
	$menu_script_name=str_replace('.' . $phpEx, '', $user->page['page_name']);
	$menu_url_i='';
	$menu_url_mode='';
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
        
        'U_SEARCH_ID' => $_GET["search_id"]
));
// End Menu Navigation

function get_avatar($user)
{
  global $phpbb_root_path, $phpEx;

  $user_avatar = '';
  if ($user->data['user_avatar'])
  {
      $avatar_size = 60;

    if (!function_exists('get_user_avatar'))
      {
          include($phpbb_root_path . 'includes/functions_display.'. $phpEx);
      }

      if ($user->data['user_avatar_width'] < $avatar_size && $user->data['user_avatar_height'] < $avatar_size)
      {
          $width = $user->data['user_avatar_width'];
          $height = $user->data['user_avatar_height'];
      }
      else 
      {
          $width = $avatar_size;
          $height = $avatar_size;
      }

      $user_avatar = get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], $width, $height, $user->data['username']);
  }

  return $user_avatar;
}


$template->assign_vars(array(
// Avatar position
'AVATAR_POSITION'	=>  "{$config['avatar_position']}",
'T_JOOMLA_MENU'	=>  $joomla,
'T_JOOMLA_MENU_REG'	=>  $joomla_reg,
'S_ALLOW_JMENU'		=> ($config['allow_jmenu']) ? true : false,
));
global $user;
	$template->assign_vars(array(
                                        'USER_AVATAR' => get_avatar($user),
				));
                                
                                
// RokBox Implementation                                   	
$template->assign_vars(array(
            'ROKBOX'                  => ($config['rokbox']) ? true : false,
            'ROKBOX_THEME'            => "{$config['rokbox_theme']}"
		    ));                            
    
if (!isset($config['rokbox'])) {
	$template->assign_vars(array(
		'ROKBOX'		=> true
		)
	);
 }    
 
// Simple helper for color variation switcher
       
$string = $this->_rootref['U_INDEX'];
if(strstr($string, '?') == true) 
{ 
global $config, $template;

$template->assign_vars(array(
		'QUESTION_MARK'		=> true,
)
	);
}      
        
                                
<!-- ENDPHP -->
 
 

