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

	/**
	 * edebug_hooks
	 */
	class edebug_hooks {

		/**
		 * all_hooks
         * hooks to build ewawi's sidebox-menu plus the admin and preferences sections
		 *
         * @param string/array $args hook args
         */
        static public function all_hooks($args) {
			if( is_array($args) && isset($args['hook_location']) ) {
				$location = (is_array($args) ? $args['hook_location'] : $args);
			}
			else {
				$location = (is_array($args) ? $args['location'] : $args);
			}

			// Sidebox Menu ----------------------------------------------------

			if( $location == 'sidebox_menu' ) {
				// EDebug
				// -------------------------------------------------------------
				$file = array();
                $file['XHProf list'] = egw::link('/index.php',
					'menuaction=edebug.edebug_ui.index&ajax=true');

                display_sidebox('edebug', 'EDebug', $file);
			}

			// Admin Menu ------------------------------------------------------

            if( $GLOBALS['egw_info']['user']['apps']['admin'] ) {

			}
		}

		/**
         * Hook called by link-class to include ewawi in the appregistry of the linkage
         *
         * @param array/string $location location and other parameters (not used)
         * @return array with method-names
         */
        static public function search_link($location) {
			return array(
                'query'			=> 'edebug.edebug_xhprof_bo.link_query',
                'title'			=> 'edebug.edebug_xhprof_bo.link_title',
                'titles'		=> 'edebug.edebug_xhprof_bo.link_titles',
				/*'view'			=> array(
					'menuaction' => $appname . '.ewawi_master_data_ui.edit',
				),*/
				'view_id'		=> 'unid',
				'view_popup'	=> '750x580',
				'entry'			=> 'EDebug',
                'entries'		=> 'EDebugs',
				'name'			=> 'EDebug',
				);
		}

		/**
		 * settings
		 *
		 * @param mixed $hook_data
		 * @return array
		 */
		static public function settings($hook_data=null) {
			$settings = array();

			// -----------------------------------------------------------------

			$settings[] = array(
					'type'		=> 'section',
					'title'		=> lang('xhprof'),
					'no_lang'	=> true,
					'xmlrpc'	=> false,
					'admin'		=> true
					);

			$settings['xhprof_enable'] = array(
				'type'   => 'select',
				'label'  => 'XHProf Enable',
				'name'   => 'xhprof_enable',
				'values' => array(
					0 => lang('No'),
					1 => lang('Yes'),
				),
				'help'   => '',
				'xmlrpc' => true,
				'admin'  => false,
				'default'=> 0,
				);

			// -----------------------------------------------------------------

			$settings[] = array(
					'type'		=> 'section',
					'title'		=> lang('xdebug'),
					'no_lang'	=> true,
					'xmlrpc'	=> false,
					'admin'		=> true
					);



			return $settings;
		}
	}