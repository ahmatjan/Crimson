<?php
		// Modestus configuration fields
if ($mode == 'modestus')
		{
$display_vars = array(
	'vars'	=> array(
	'legend2'			        => 'L_MODESTUS_SETTINGS',
	'enable_modestus_rokbb3'			=> array('lang' => 'ENABLE_ROKBB3',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'modestus_full_width'			=> array('lang' => 'MODESTUS_FULL_WIDTH','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'modestus_width'			=> array('lang' => 'MODESTUS_WIDTH','validate' => 'int',	'type' => 'text:20:255', 'explain' => true),
	'enable_modestus_logo'			=> array('lang' => 'ENABLE_MODESTUS_LOGO','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'default_modestus_scheme'		=> array('lang' => 'DEFAULT_MODESTUS_SCHEME',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'show_modestus_colorswitcher'		=> array('lang' => 'SHOW_COLORSWITCHER','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'load_search'				=> array('lang' => 'SHOW_SEARCH','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_modestus_date'			=> array('lang' => 'SHOW_MODESTUS_DATE',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'show_modestus_pathway'			=> array('lang' => 'SHOW_MODESTUS_PATHWAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
	'modestus_logo_link'			=> array('lang' => 'MODESTUS_LOGO_LINK',	'validate' => 'string',	'type' => 'text:20:255', 'explain' => true),
	'enable_modestus_bottom_modules'	=> array('lang' => 'ENABLE_MODESTUS_BOTTOM_MODULES','validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => true),
				)
				);

		}
?>