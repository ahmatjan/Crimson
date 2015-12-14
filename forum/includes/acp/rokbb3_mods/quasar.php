<?php
function quasar_css_style($value, $key = '',$quasar_css_style_options = '')
{
$quasar_css_style_options .= '<option value="style1"' . (($value == "style1") ? ' selected="selected"' : '') . '>Style1</option>
									 <option value="style2"' . (($value == "style2") ? ' selected="selected"' : '') . '>Style2</option>
									 <option value="style3"' . (($value == "style3") ? ' selected="selected"' : '') . '>Style3</option>
									 <option value="style4"' . (($value == "style4") ? ' selected="selected"' : '') . '>Style4</option>
									 <option value="style5"' . (($value == "style5") ? ' selected="selected"' : '') . '>Style5</option>
									 <option value="style6"' . (($value == "style6") ? ' selected="selected"' : '') . '>Style6</option>
									 ';
	return $quasar_css_style_options;
}
function quasar_bg_level($value, $key = '',$quasar_bg_level_options = '')
{
$quasar_bg_level_options .= '<option value="low"' . (($value == "low") ? ' selected="selected"' : '') . '>Low</option>
									 <option value="med"' . (($value == "med") ? ' selected="selected"' : '') . '>Medium</option>
									 <option value="high"' . (($value == "high") ? ' selected="selected"' : '') . '>High</option>		
									 ';
	return $quasar_bg_level_options;
}
function quasar_font($value, $key = '', $quasarfont_options='')
{
$quasarfont_options .= '  <option value="geneva"' . (($value == "geneva") ? ' selected="selected"' : '') . '>Geneva</option>
									 <option value="georgia"' . (($value == "georgia") ? ' selected="selected"' : '') . '>Georgia</option>
									 <option value="helvetica"' . (($value == "helvetica") ? ' selected="selected"' : '') . '>Helvetica</option>
									 <option value="lucida"' . (($value == "lucida") ? ' selected="selected"' : '') . '>Lucida</option>
									 <option value="optima"' . (($value == "optima") ? ' selected="selected"' : '') . '>Optima</option>
									 <option value="palatino"' . (($value == "palatino") ? ' selected="selected"' : '') . '>Palatino</option>
									 <option value="trebuchet"' . (($value == "trebuchet") ? ' selected="selected"' : '') . '>Trebuchet</option>		
									 
									 
									
									 ';
	return $quasarfont_options;
}
function quasar_menu_position($value, $key = '',$quasar_mposition_options = '')
{
$quasar_mposition_options .= '<option value="right"' . (($value == "right") ? ' selected="selected"' : '') . '>Right</option>
									 <option value="left"' . (($value == "left") ? ' selected="selected"' : '') . '>Left</option>
									 <option value="full"' . (($value == "full") ? ' selected="selected"' : '') . '>Full width</option>
									 ';
	return $quasar_mposition_options;
}
function quasar_preset_style($value, $key = '',$quasar_preset_style_options = '')
{
$quasar_preset_style_options .= '<option value="style1"' . (($value == "style1") ? ' selected="selected"' : '') . '>Style1</option>
									 <option value="style2"' . (($value == "style2") ? ' selected="selected"' : '') . '>Style2</option>
									 <option value="style3"' . (($value == "style3") ? ' selected="selected"' : '') . '>Style3</option>
									 <option value="style4"' . (($value == "style4") ? ' selected="selected"' : '') . '>Style4</option>
									 <option value="style5"' . (($value == "style5") ? ' selected="selected"' : '') . '>Style5</option>
									 <option value="style6"' . (($value == "style6") ? ' selected="selected"' : '') . '>Style6</option>
									 <option value="custom"' . (($value == "custom") ? ' selected="selected"' : '') . '>Custom</option>
									 ';
	return $quasar_preset_style_options;
}

function quasar_body_level($value, $key = '',$quasar_body_level_options = '')
{
$quasar_body_level_options .= '<option value="low"' . (($value == "low") ? ' selected="selected"' : '') . '>Low</option>
									 <option value="med"' . (($value == "med") ? ' selected="selected"' : '') . '>Medium</option>
									 <option value="high"' . (($value == "high") ? ' selected="selected"' : '') . '>High</option>		
									 ';
	return $quasar_body_level_options;
}

	
	
	// Quasar configuration fields
if ($mode == 'quasar')
		{
$display_vars = array(
	'vars'	=> array(
	'legend1'			=> 'L_QUASAR_COLOR_SETTINGS',		
	'quasar_preset_style'			=> array('lang' => 'NEX_PRESET_STYLE','validate' => 'string',	'type' => 'select', 'function' => 'quasar_preset_style', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
        'quasar_bg_level'			=> array('lang' => 'REACT_BG_LEVEL','validate' => 'string',	'type' => 'select', 'function' => 'quasar_bg_level', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
	'quasar_body_level'			=> array('lang' => 'REACT_BODY_LEVEL','validate' => 'string',	'type' => 'select', 'function' => 'quasar_body_level', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
        'quasar_css_style'			=> array('lang' => 'REACT_CSS_STYLE','validate' => 'string',	'type' => 'select', 'function' => 'quasar_css_style', 'params' => array('{CONFIG_VALUE}'), 'explain' => false),
        'quasar_link_color'			=> array('lang' => 'REACT_LINK_COLOR', 'validate' => 'string','type' => 'text:7:255', 'explain' => false),
	'quasar_fontface'			=> array('lang' => 'V4_FONTFACE',		'validate' => 'string',	'type' => 'select', 'function' => 'quasar_font', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),



	'legend2'			=> 'L_QUASAR_SETTINGS',
	'show_jquasaruser'			=> array('lang' => 'SHOW_MERIDUSER','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'quasar_menu_position'			=> array('lang' => 'MERID_MENUPOSITION',	'validate' => 'string',	'type' => 'select', 'function' => 'quasar_menu_position', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
	'enable_quasar_bottom_modules'			=> array('lang' => 'ENABLE_DOMIN_BOTTOM_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_quasar_footer_modules'			=> array('lang' => 'ENABLE_KINETIC_FOOTER_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_colorswitcher'			=> array('lang' => 'SHOW_COLORSWITCHER','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'load_search'				=> array('lang' => 'SHOW_SEARCH','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_date'			=> array('lang' => 'SHOW_REACT_DATE',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_totop_scroller'			=> array('lang' => 'SHOW_TOTOP_SCROLLER',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_reset'			=> array('lang' => 'SHOW_RESET',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_pathway'			=> array('lang' => 'SHOW_MERID_PATHWAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_font'			=> array('lang' => 'SHOW_MERID_FONT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_quasar_copyright'			=> array('lang' => 'SHOW_MERID_COPYRIGHT','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'enable_quasar_fontspans'			=> array('lang' => 'ENABLE_VERT_FONTSPANS',				'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'quasar_logo_link'			=> array('lang' => 'NEX_LOGO_LINK',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
				)
				);				
		}
?>