<?php

	/**
	 * EDebug - Egroupware
	 *
	 * @link http://www.hw-softwareentwicklung.de
	 * @author Stefan Werfling <stefan.werfling-AT-hw-softwareentwicklung.de>
	 * @package edebug
	 * @copyright (c) 2016 by Stefan Werfling <stefan.werfling-AT-hw-softwareentwicklung.de>
	 * @license http://opensource.org/licenses/GPL-2.0 GPL2 - GNU General Public License, version 2 (GPL-2.0)
	 * @version $Id$
	 */

	$GLOBALS['egw_info'] = array(
		'flags' => array(
			'noheader'                => true,
			'nonavbar'                => true,
			'currentapp'              => 'edebug',
			'enable_network_class'    => false,
			'enable_contacts_class'   => false,
			'enable_nextmatchs_class' => false,
			'include_xajax'		  	  => true,
		)
	);

	// Header Inc
	include('../header.inc.php');

	// Redirect
	$GLOBALS['egw']->redirect_link('/index.php','menuaction=edebug.edebug_ui.index');