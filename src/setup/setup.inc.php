<?php

	/**
	 * HW Softwareentwicklung GbR
	 * Base Modul Setup
	 *
	 * @author Stefan Werfling
	 * @generate-date {generatedate}
	 *
	 */

	/**
	 * Base App Info
	 */
	$setup_info['edebug']['name']      			= 'edebug';
	$setup_info['edebug']['title']     			= 'EDebug';
	$setup_info['edebug']['version']			= '14.1.002';
	$setup_info['edebug']['app_order'] 			= 2;
	$setup_info['edebug']['enable']    			= 1;

	/**
	 * Base Dev Info
	 */
	$setup_info['edebug']['author']				= 'Stefan Werfling';
	$setup_info['edebug']['license']  			= 'http://opensource.org/licenses/lgpl-license.php LGPL - GNU Lesser General Public License';
	$setup_info['edebug']['description']		= 'HW';

	$setup_info['edebug']['maintainer'] = array(
		'name' 	=> 'HW-Softwareentwicklung GbR',
		'email' => 'info@hw-softwareentwicklung.de'
		);

	$setup_info['edebug']['hooks']['admin']			= 'edebug_hooks::all_hooks';
	$setup_info['edebug']['hooks']['settings']		= 'edebug_hooks::settings';
	$setup_info['edebug']['hooks']['sidebox_menu']	= 'edebug_hooks::all_hooks';
	$setup_info['edebug']['hooks']['search_link']   = 'edebug_hooks::search_link';


	$setup_info['edebug']['tables'] = array('egw_edebug_xhprof');


