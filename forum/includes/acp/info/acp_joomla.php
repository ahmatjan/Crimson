<?php
/** 
*
* @package acp
* @version $Id: v3_modules.xml,v 1.5 2007/12/09 19:45:45 jelly_doughnut Exp $
* @copyright (c) 2007 phpBB Group 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
							
/**
* @package module_install
*/

class acp_joomla_info
{
	function module()
	{
	return array(
		'filename'	=> 'acp_joomla',
		'title'		=> 'RokBB3',
		'version'	=> '1.0.0',
		'modes'		=> array(
			'global_conf'		=> array('title' => 'Global configuration', 'auth' => 'acl_a_group', 'cat' => array('ACP_AUTOMATION')),
			'afterburner'			=> array('title' => 'Afterburner', 'auth' => 'acl_a_group', 'cat' => array('ACP_AUTOMATION')),
			'quasar'			=> array('title' => 'Quasar', 'auth' => 'acl_a_group', 'cat' => array('ACP_AUTOMATION')),
			'modestus'			=> array('title' => 'Modestus', 'auth' => 'acl_a_group', 'cat' => array('ACP_AUTOMATION')),
			'grunge'			=> array('title' => 'Grunge', 'auth' => 'acl_a_group', 'cat' => array('ACP_AUTOMATION')),
			),
		);
		
	}
							
	function install()
	{
	}
								
	function uninstall()
	{
	}

}
?>