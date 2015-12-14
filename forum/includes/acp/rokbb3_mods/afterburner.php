<?php

function after_menu_position($value, $key = '',$after_mposition_options = '')
{
$after_mposition_options .= '<option value="right"' . (($value == "right") ? ' selected="selected"' : '') . '>Right</option>
									 <option value="left"' . (($value == "left") ? ' selected="selected"' : '') . '>Left</option>
									 <option value="full"' . (($value == "full") ? ' selected="selected"' : '') . '>Full width</option>
									 ';
	return $after_mposition_options;
}

// Afterburner configuration fields
if ($mode == 'afterburner')
		{
$display_vars = array(
	'vars'	=> array(

	'legend1'			=> 'L_AFTER_SETTINGS',
	'show_jafteruser'			=> array('lang' => 'SHOW_MERIDUSER','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_after_bottom'			=> array('lang' => 'SHOW_AFTER_BOTTOM','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_after_top'			=> array('lang' => 'SHOW_AFTER_TOP','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'after_column_color'			=> array('lang' => 'AFTER_COLUMN_COLOR',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'default_after_scheme'			=> array('lang' => 'DEFAULT_AFTER_SCHEME',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_menu_position'			=> array('lang' => 'MERID_MENUPOSITION',	'validate' => 'string',	'type' => 'select', 'function' => 'after_menu_position', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
	
	'after_width'			=> array('lang' => 'MERID_WIDTH','validate' => 'int',	'type' => 'text:20:255', 'explain' => true),
	'after_sidecol_width'			=> array('lang' => 'AKIR_SIDECOL_WIDTH','validate' => 'int',	'type' => 'text:20:255', 'explain' => true),
	'show_after_colorswitcher'			=> array('lang' => 'SHOW_COLORSWITCHER','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_after_pathway'			=> array('lang' => 'SHOW_MERID_PATHWAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_after_font'			=> array('lang' => 'SHOW_MERID_FONT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_after_copyright'			=> array('lang' => 'SHOW_MERID_COPYRIGHT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'after_logo_link'			=> array('lang' => 'NEX_LOGO_LINK',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	
	// Bottom menu
	'legend4'				=> 'L_AFTER_BOTTOM_MENU',
	'after_link1_desc'			=> array('lang' => 'AKIR_BMENU_LINK1_DESC',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link1_href'			=> array('lang' => 'AKIR_BMENU_LINK1_HREF',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link2_desc'			=> array('lang' => 'AKIR_BMENU_LINK2_DESC',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link2_href'			=> array('lang' => 'AKIR_BMENU_LINK2_HREF',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link3_desc'			=> array('lang' => 'AKIR_BMENU_LINK3_DESC',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link3_href'			=> array('lang' => 'AKIR_BMENU_LINK3_HREF',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link4_desc'			=> array('lang' => 'AKIR_BMENU_LINK4_DESC',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link4_href'			=> array('lang' => 'AKIR_BMENU_LINK4_HREF',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link5_desc'			=> array('lang' => 'AKIR_BMENU_LINK5_DESC',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'after_link5_href'			=> array('lang' => 'AKIR_BMENU_LINK5_HREF',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),	
				
				)
				);				
		}	
?>