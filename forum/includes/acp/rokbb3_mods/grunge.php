<?php

function grunge_bg_level($value, $key = '',$grunge_bg_level_options = '')
{
$grunge_bg_level_options .= '<option value="low"' . (($value == "low") ? ' selected="selected"' : '') . '>Low</option>
									 <option value="med"' . (($value == "med") ? ' selected="selected"' : '') . '>Medium</option>
									 <option value="high"' . (($value == "high") ? ' selected="selected"' : '') . '>High</option>		
									 ';
	return $grunge_bg_level_options;
}

function grunge_body_level($value, $key = '',$grunge_body_level_options = '')
{
$grunge_body_level_options .= '<option value="low"' . (($value == "low") ? ' selected="selected"' : '') . '>Low</option>
									 <option value="medium"' . (($value == "medium") ? ' selected="selected"' : '') . '>Medium</option>
									 <option value="high"' . (($value == "high") ? ' selected="selected"' : '') . '>High</option>		
									 ';
	return $grunge_body_level_options;
}
function grunge_font($value, $key = '', $grungefont_options='')
{
global $config;
if (($config['enable_webfonts']) == true) {	
$grungefont_options .= '   <option value="geneva"' . (($value == "geneva") ? ' selected="selected"' : '') . '>Geneva</option>
 <option value="georgia"' . (($value == "georgia") ? ' selected="selected"' : '') . '>Georgia</option>
 <option value="helvetica"' . (($value == "helvetica") ? ' selected="selected"' : '') . '>Helvetica</option>
 <option value="lucida"' . (($value == "lucida") ? ' selected="selected"' : '') . '>Lucida</option>
 <option value="optima"' . (($value == "optima") ? ' selected="selected"' : '') . '>Optima</option>
 <option value="palatino"' . (($value == "palatino") ? ' selected="selected"' : '') . '>Palatino</option>
 <option value="trebuchet"' . (($value == "trebuchet") ? ' selected="selected"' : '') . '>Trebuchet</option>
 <option value="Cantarell"' . (($value == "Cantarell") ? ' selected="selected"' : '') . '>Cantarell</option>
 <option value="Cardo"' . (($value == "Cardo") ? ' selected="selected"' : '') . '>Cardo</option>
 <option value="Crimson"' . (($value == "Crimson") ? ' selected="selected"' : '') . '>Crimson</option>
 <option value="Droid Sans"' . (($value == "Droid Sans") ? ' selected="selected"' : '') . '>Droid Sans</option>
 <option value="Droid Sans Mono"' . (($value == "Droid Sans Mono") ? ' selected="selected"' : '') . '>Droid Sans Mono</option>
 <option value="Droid Serif"' . (($value == "Droid Serif") ? ' selected="selected"' : '') . '>Droid Serif</option>
 <option value="IM Fell English"' . (($value == "IM Fell English") ? ' selected="selected"' : '') . '>IM Fell English</option>
 <option value="Inconsolata"' . (($value == "Inconsolata") ? ' selected="selected"' : '') . '>Inconsolata</option>
 <option value="Josefin Sans Std Light"' . (($value == "Josefin Sans Std Light") ? ' selected="selected"' : '') . '>Josefin Sans Std Light</option>
 <option value="Lobster"' . (($value == "Lobster") ? ' selected="selected"' : '') . '>Lobster</option>
 <option value="Molengo"' . (($value == "Molengo") ? ' selected="selected"' : '') . '>Molengo</option>
 <option value="Nobile"' . (($value == "Nobile") ? ' selected="selected"' : '') . '>Nobile</option>
 <option value="OFL Sorts Mill Goudy TT"' . (($value == "OFL Sorts Mill Goudy TT") ? ' selected="selected"' : '') . '>OFL Sorts Mill Goudy TT</option>
 <option value="Old Standard TT"' . (($value == "Old Standard TT") ? ' selected="selected"' : '') . '>OLD Standard TT</option>
 <option value="Reenie Beanie"' . (($value == "Reenie Beanie") ? ' selected="selected"' : '') . '>Reenie Beanie</option>
 <option value="Tangerine"' . (($value == "Tangerine") ? ' selected="selected"' : '') . '>Tangerine</option>
 <option value="Vollkorn"' . (($value == "Vollkorn") ? ' selected="selected"' : '') . '>Vollkorn</option>
 <option value="Yanone Kaffeesatz"' . (($value == "Yanone Kaffeesatz") ? ' selected="selected"' : '') . '>Yanone Kaffeesatz</option>
	
									 ';
return $grungefont_options;

}
else {
	$grungefont_options .= '
	 <option value="bebas"' . (($value == "bebas") ? ' selected="selected"' : '') . '>Bebas</option>
	<option value="helvetica"' . (($value == "helvetica") ? ' selected="selected"' : '') . '>Helvetica</option>
									<option value="geneva"' . (($value == "geneva") ? ' selected="selected"' : '') . '>Geneva</option>
									 <option value="georgia"' . (($value == "georgia") ? ' selected="selected"' : '') . '>Georgia</option>
									 <option value="lucida"' . (($value == "lucida") ? ' selected="selected"' : '') . '>Lucida</option>
									 <option value="optima"' . (($value == "optima") ? ' selected="selected"' : '') . '>Optima</option>
									 <option value="palatino"' . (($value == "palatino") ? ' selected="selected"' : '') . '>Palatino</option>
									 <option value="trebuchet"' . (($value == "trebuchet") ? ' selected="selected"' : '') . '>Trebuchet</option>		
									 ';
return $grungefont_options;
}
}



function grunge_menu_position($value, $key = '',$grunge_mposition_options = '')
{
$grunge_mposition_options .= '<option value="right"' . (($value == "right") ? ' selected="selected"' : '') . '>Right</option>
									 <option value="left"' . (($value == "left") ? ' selected="selected"' : '') . '>Left</option>
									 <option value="full"' . (($value == "full") ? ' selected="selected"' : '') . '>Full width</option>
									 ';
	return $grunge_mposition_options;
}
// Grunge configuration fields
if ($mode == 'grunge')
		{
$display_vars = array(
	'vars'	=> array(
	'legend2'			       		=> 'L_GRUNGE_SETTINGS',
	'enable_grunge_rokbb3'					=> array('lang' => 'ENABLE_ROKBB3',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'grunge_link_color'				=> array('lang' => 'LINK_COLOR', 'validate' => 'string','type' => 'text:7:255', 'explain' => false),
	'grunge_bg_level'				=> array('lang' => 'BG_LEVEL','validate' => 'string',	'type' => 'select', 'function' => 'grunge_bg_level', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
	'grunge_body_level'				=> array('lang' => 'BODY_LEVEL','validate' => 'string',	'type' => 'select', 'function' => 'grunge_body_level', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
	'grunge_menu_position'				=> array('lang' => 'LAYOUT',	'validate' => 'string',	'type' => 'select', 'function' => 'grunge_menu_position', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
	'enable_webfonts'				=> array('lang' => 'ENABLE_WEBFONTS','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'grunge_font_family'				=> array('lang' => 'FONTFACE', 'validate' => 'string',	'type' => 'select', 'function' => 'grunge_font', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
	'show_jgrungeuser'				=> array('lang' => 'SHOW_USER_MENU','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'main_menu'				=> array('lang' => 'MAIN_MENU', 'validate' => 'string','type' => 'text:20:255', 'explain' => true),
	'user_menu'				=> array('lang' => 'USER_MENU', 'validate' => 'string','type' => 'text:20:255', 'explain' => true),
	'enable_grunge_logo'				=> array('lang' => 'ENABLE_LOGO','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'grunge_logo_link'				=> array('lang' => 'LOGO_LINK',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'load_search'					=> array('lang' => 'SHOW_SEARCH','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_grunge_date'				=> array('lang' => 'SHOW_DATE',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_grunge_pathway'				=> array('lang' => 'SHOW_PATHWAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_grunge_font'				=> array('lang' => 'SHOW_FONT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_grunge_copyright'				=> array('lang' => 'SHOW_COPYRIGHT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_grunge_fontspans'			=> array('lang' => 'ENABLE_FONTSPANS','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_grunge_bottom_modules'			=> array('lang' => 'ENABLE_BOTTOM_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_grunge_side_modules'			=> array('lang' => 'ENABLE_SIDE_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_grunge_bottom_modules'			=> array('lang' => 'ENABLE_BOTTOM_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_grunge_footer_modules'			=> array('lang' => 'ENABLE_FOOTER_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_grunge_totop_scroller'			=> array('lang' => 'SHOW_TOTOP_SCROLLER',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
				)
				);

		}
?>