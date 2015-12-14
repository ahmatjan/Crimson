<?php
function avatar_position($value, $key = '', $avatar_position_options='')
{
$avatar_position_options .= '<option value="right"' . (($value == "right") ? ' selected="selected"' : '') . '>Right</option>
									<option value="left"' . (($value == "left") ? ' selected="selected"' : '') . '>Left</option>
									 ';
	return $avatar_position_options;
}

if ($mode == 'global_conf')
		{
			
			
$display_vars = array(
	'vars'	=> array(
	'legend1'				=> 'JOOMLA_CONFIGURATION',
	'allow_jmenu'			=> array('lang' => 'ALLOW_JMERIDMENU','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'jmenu_path'			=> array('lang' => 'JOOMLA_MENU_PATH',	'validate' => 'jpath',	'type' => 'text:20:255', 'explain' => true),
	
	'jmenu_guest_name'			=> array('lang' => 'JMENU_GUEST_NAME',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'jmenu_reg_name'			=> array('lang' => 'JMENU_REG_NAME',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),

	'legend2'				=> 'OTHER_CONFIGURATION',
	'avatar_position'			=> array('lang' => 'AVATAR_POSITION',		'validate' => 'string',	'type' => 'select', 'function' => 'avatar_position', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
	'enable_rokbb3'			=> array('lang' => 'ENABLE_ROKBB3',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	
	'legend6'				=> 'ROKBOX_CONFIGURATION',
	'rokbox'			=> array('lang' => 'ROKBOX','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'rokbox_theme'			=> array('lang' => 'ROKBOX_THEME', 'validate' => 'string','type' => 'text:7:255', 'explain' => true),	
				)
				);			

			
		}
?>