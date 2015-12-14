<?php
/** 
*
* acp_joomla [English]
*
* @package language
* @version $Id: v3_modules.xml,v 1.5 2007/12/09 19:45:45 jelly_doughnut Exp $
* @copyright (c) 2005 phpBB Group 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
					
/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
	
}
						
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
						
$lang = array_merge($lang, array(
	
// Global
	'TITLE'	=> 'RokBB3',
	'TITLE_EXPLAIN'	=> 'Here you can set various options for your RocketTheme Phpbb3 style.',
	
	'SITE_NAME' => 'Site name',
	'SITE_DESC' => 'Site description',
	
	'OTHER_CONFIGURATION'	=> 'Global configuration',
	'JOOMLA_CONFIGURATION'	=> 'Joomla configuration',
	'AVATAR_POSITION'	=> 'Avatar position',
	'AVATAR_POSITION_EXPLAIN' => 'Set avatar and user profile position.',
	
        'JOOMLA_MENU_PATH' => 'Joomla menu path',
	'JOOMLA_MENU_PATH_EXPLAIN' => 'Relative path to directory of your Joomla installation.Remember about ending slash.',
	'ALLOW_JMENU'	=> 'Show Joomla menu',
	'INTEGRATION'	=> 'Joomla integration',
	'ALLOW_JCOLUMN' => 'Display right column',
	'ALLOW_JCOLUMN_EXPLAIN' => 'Display right column in your phpbb3 style.',
	
	'JMENU_GUEST_NAME' => 'Guest Menu Filename',
	'JMENU_REG_NAME' => 'Registered Menu Filename',

	'SHOW_COLORSWITCHER'	=> 'Show style switcher',
	'SHOW_COLORSWITCHER_EXPLAIN'	=> 'Show or hide color switcher for quick color variation change.',

// Afterburner	
		'L_AFTER_SETTINGS' => 'Afterburner style configuration',
		'L_AFTER_BOTTOM_MENU' => 'Afterburner bottom and top menu',
		
		'SHOW_AFTER_BOTTOM'	=> 'Show Bottom menu',
		'SHOW_AFTER_BOTTOM_EXPLAIN'	=> 'Display menu at the bottom of the page.',
		
		'SHOW_AFTER_TOP'	=> 'Show Top menu',
		'SHOW_AFTER_TOP_EXPLAIN'	=> 'Display menu at the top of the page.',
		
		'AFTER_COLUMN_COLOR' => 'Column color',
		'AFTER_COLUMN_COLOR_EXPLAIN' => 'Choose preferred background color for right or left column.Default values are <strong>color1</strong> or <strong>color2</strong>',
		
		
		'SHOW_MERIDUSER' => 'Display User menu',
		'SHOW_MERIDUSER_EXPLAIN' => 'Display User menu below Main menu.',
		
		'MERID_MENUPOSITION' => 'Modules position',
		'MERID_MENUPOSITION_EXPLAIN' => 'Decide which column to use for menus,login and color variations.',
		
		'MERID_WIDTH' => 'Style width',
		'MERID_WIDTH_EXPLAIN' => 'This style configuration option allows you to easily change the width of the style itself, simply enter pixel value.',
		
		'AKIR_SIDECOL_WIDTH' => 'Side column width',
		'AKIR_SIDECOL_WIDTH_EXPLAIN' => 'This style configuration option allows you to easily change the width of the side column, simply enter pixel value. <strong>Note:</strong> Left or Right column layout must be set.',
		
		'SHOW_COLORSWITCHER'	=> 'Show style switcher',
		'SHOW_COLORSWITCHER_EXPLAIN'	=> 'Show or hide color switcher for quick color variation change.',
		
		'SHOW_MERID_PATHWAY' => 'Show Pathway',
		'SHOW_MERID_PATHWAY_EXPLAIN' => 'The pathway or breadcrumbs function can be disabled with this setting.',
		
		'SHOW_MERID_COPYRIGHT' => 'Show copyright',
		'SHOW_MERID_COPYRIGHT_EXPLAIN' => 'This setting allows you to enable/disable the RocketTheme logo.',
		
		'SHOW_MERID_FONT' => 'Show Font Controls',
		'SHOW_MERID_FONT_EXPLAIN' => 'This setting allows you to disable the text size controls in the upper right of the style..',
		
		'NEX_LOGO_LINK'                => 'Logo link',
		'NEX_LOGO_LINK_EXPLAIN'                => 'Custom logo link. If empty, root forum page will be set.',
		
		'AKIR_BMENU_LINK1_HREF' => 'Item 1 url',
		'AKIR_BMENU_LINK1_HREF_EXPLAIN' => 'An url for 1 item at bottom menu.',
		'AKIR_BMENU_LINK1_DESC' => 'Item 1 description',
		'AKIR_BMENU_LINK1_DESC_EXPLAIN' => 'Description for item 1',
		
		'AKIR_BMENU_LINK2_HREF' => 'Item 2 url',
		'AKIR_BMENU_LINK2_HREF_EXPLAIN' => 'An url for 2 item at bottom menu.',
		'AKIR_BMENU_LINK2_DESC' => 'Item 2 description',
		'AKIR_BMENU_LINK2_DESC_EXPLAIN' => 'Description for item 2',
		
		'AKIR_BMENU_LINK3_HREF' => 'Item 3 url',
		'AKIR_BMENU_LINK3_HREF_EXPLAIN' => 'An url for 3 item at bottom menu.',
		'AKIR_BMENU_LINK3_DESC' => 'Item 3 description',
		'AKIR_BMENU_LINK3_DESC_EXPLAIN' => 'Description for item 3',
		
		'AKIR_BMENU_LINK4_HREF' => 'Item 4 url',
		'AKIR_BMENU_LINK4_HREF_EXPLAIN' => 'An url for 4 item at bottom menu.',
		'AKIR_BMENU_LINK4_DESC' => 'Item 4 description',
		'AKIR_BMENU_LINK4_DESC_EXPLAIN' => 'Description for item 4',
		
		'AKIR_BMENU_LINK5_HREF' => 'Item 5 url',
		'AKIR_BMENU_LINK5_HREF_EXPLAIN' => 'An url for 5 item at bottom menu.',
		'AKIR_BMENU_LINK5_DESC' => 'Item 5 description',
		'AKIR_BMENU_LINK5_DESC_EXPLAIN' => 'Description for item 5',
		
		'ALLOW_JMERIDMENU'	=> 'Show Joomla menu',
		'ALLOW_JMERIDMENU_EXPLAIN'	=> 'Show Joomla menu instead of phpbb3 menu at the top of the page.',
		
		'DEFAULT_AFTER_SCHEME' => 'Color scheme',
		'DEFAULT_AFTER_SCHEME_EXPLAIN' => 'Default color scheme. Default available color schemes are: <strong>light,light2,light3,light4,dark,dark2,dark3,dark4</strong>',
		
		// RokBox		
		'ROKBOX_CONFIGURATION' => 'RokBox configuration',
		
		'ROKBOX'		=> 'Enable RokBox',
		'ROKBOX_EXPLAIN'	=> 'Enable or disable RokBox support in phpBB3.',
		
		'ROKBOX_THEME'		=> 'Preset Themes',
		'ROKBOX_THEME_EXPLAIN'	=> 'Choose from Presets Themes or type your own custom theme name.Preset themes are: <strong>dark</strong>, <strong>light</strong> and <strong>mynxx</strong>',
		

// Quasar
'NEX_PRESET_STYLE'  => 'Preset Style',
'REACT_BG_LEVEL' => 'Background Level',
'REACT_BODY_LEVEL' => 'Body Level',
'REACT_CSS_STYLE'  => 'Css Style',
'REACT_LINK_COLOR' => 'Link Color',
'V4_FONTFACE' => 'Font Family',
'L_QUASAR_SETTINGS' => 'Quasar style configuration',
'L_QUASAR_COLOR_SETTINGS' => 'Quasar color and style settings',
'ENABLE_DOMIN_BOTTOM_MODULES'	=> 'Show Bottom Modules',
'ENABLE_DOMIN_BOTTOM_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics at the bottom of the page.',
'ENABLE_KINETIC_FOOTER_MODULES'	=> 'Show Footer Modules',
'ENABLE_KINETIC_FOOTER_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics in the footer of the page.',
'SHOW_SEARCH'	=> 'Enable search facilities',
'SHOW_SEARCH_EXPLAIN'	=> 'Enables user facing search functionality including member search.',
'SHOW_REACT_DATE'	=> 'Show Date',
'SHOW_REACT_DATE_EXPLAIN'	=> 'Choose to show the style Date.',
'SHOW_TOTOP_SCROLLER'	=> 'Show To-Top Scroller',
'SHOW_TOTOP_SCROLLER_EXPLAIN'	=> 'The To-Top scroller allows smooth scrolling from the bottom of the style back to the top.',
		
'SHOW_RESET'	=> 'Show Reset Settings',
'SHOW_RESET_EXPLAIN'	=> 'The Reset Settings link allows to clear all style cookies and settings set during your session. For example if you change the preset style and you want it back to its original state..',

'ENABLE_VERT_FONTSPANS' => 'Font Spans',
'ENABLE_VERT_FONTSPANS_EXPLAIN' => 'Choose whether module titles are multi-coloured by enabling Font Spans.',



//  Modestus		
		'L_MODESTUS_SETTINGS' => 'Modestus style configuration',
		'MODESTUS_FULL_WIDTH' => 'Full Width',
		'MODESTUS_FULL_WIDTH_EXPLAIN' => 'Set total forum width to 100%.',
		'MODESTUS_WIDTH' => 'Style width',
		'MODESTUS_WIDTH_EXPLAIN' => 'This style configuration option allows you to easily change the width of the style itself, simply enter pixel value.Notice that <strong>Full Width</strong>  must be set to <strong>No</strong> to make fixed width work properly.',
		'ENABLE_MODESTUS_LOGO' => 'Show Logo',
		'ENABLE_MODESTUS_LOGO_EXPLAIN' => 'Modestus allows you to turn the logo on/off.',
		'DEFAULT_MODESTUS_SCHEME' => 'Color scheme',
		'DEFAULT_MODESTUS_SCHEME_EXPLAIN' => 'Default color scheme. Default available color schemes are: style1, style2, style3, style5',
		'SHOW_MODESTUS_DATE' => 'Show date/last visit date',
		'SHOW_MODESTUS_DATE_EXPLAIN' => 'This setting allows you to enable/disable the date.',
		'SHOW_MODESTUS_PATHWAY' => 'Show Pathway',
		'SHOW_MODESTUS_PATHWAY_EXPLAIN' => 'The pathway function can be disabled with this setting.',
		'MODESTUS_LOGO_LINK'                => 'Logo Link',
		'MODESTUS_LOGO_LINK_EXPLAIN'                => 'Custom logo link. If empty, root forum page will be set.',
		'ENABLE_MODESTUS_BOTTOM_MODULES'	=> 'Show Bottom Modules',
		'ENABLE_MODESTUS_BOTTOM_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics at the bottom of the page.',
		
		'ENABLE_ROKBB3'   => 'Enable RokBB3',
		'ENABLE_ROKBB3_EXPLAIN'   => 'Enable or Disable this module. If set to <strong>No</strong>.Configuration file will be used.',

//  Grunge		
		'L_GRUNGE_SETTINGS' => 'Grunge style configuration',
		'L_GRUNGE_COLOR_SETTINGS' => 'Grunge color and style settings',
	
		'MAIN_MENU'          => 'Main Menu block title',
		'MAIN_MENU_EXPLAIN'          => 'Title of the main menu module. Default is "Main Menu". You can modify this text freely.',
		'USER_MENU'        	=> 'User Menu block title',
		'USER_MENU_EXPLAIN'        	 => 'Title of the user menu module. Default is "User Menu". You can modify this text freely.',
                'BG_LEVEL' => 'Background Level',
                'BODY_LEVEL' => 'Body Level',
                'ENABLE_SIDE_MODULES'	=> 'Show Side Modules',
		'ENABLE_SIDE_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics on the left or right column.',
                'ENABLE_FOOTER_MODULES'	=> 'Show Footer Modules',
		'ENABLE_FOOTER_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics in the footer of the page.',
		'ENABLE_BOTTOM_MODULES'	=> 'Show Bottom Modules',
		'ENABLE_BOTTOM_MODULES_EXPLAIN'	=> 'Display modules ie. Who Is Online, Statistics at the bottom of the page.',
		'SHOW_COPYRIGHT' => 'Show Copyright',
		'SHOW_COPYRIGHT_EXPLAIN' => 'This setting allows you to enable/disable the RocketTheme logo.',
                'SHOW_FONT' => 'Show Font Controls',
		'SHOW_FONT_EXPLAIN' => 'This setting allows you to disable the text size controls in the upper right of the style..',
                'ENABLE_LOGO' => 'Show Logo',
		'ENABLE_LOGO_EXPLAIN' => 'This setting allows you to turn the logo on/off.',
		'SHOW_USER_MENU' => 'Display User menu',
		'SHOW_USER_MENU_EXPLAIN' => 'Display User menu below Main menu.',
                'FONTFACE' => 'Font Family',
                'LINK_COLOR'=> 'Link text color',
                'LAYOUT'   => 'Forum layout',
		'LAYOUT_EXPLAIN'   => 'This option allows you to customize the forum layout.For example you can select right or left side column configuration or even use fluid width variant',
		'ENABLE_FONTSPANS' => 'Font Spans',
		'ENABLE_FONTSPANS_EXPLAIN' => 'Choose whether module titles are multi-coloured by enabling Font Spans.',
		'SHOW_DATE' => 'Show date/last visit date',
		'SHOW_DATE_EXPLAIN' => 'This setting allows you to enable/disable the date.',
		'SHOW_PATHWAY' => 'Show Pathway',
		'SHOW_PATHWAY_EXPLAIN' => 'The pathway function can be disabled with this setting.',
		'LOGO_LINK'                => 'Logo Link',
		'LOGO_LINK_EXPLAIN'                => 'Custom logo link. If empty, root forum page will be set.',
		
));
			
?>