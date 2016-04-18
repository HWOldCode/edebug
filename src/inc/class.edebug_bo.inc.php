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
	 * edebug_bo
	 */
	class edebug_bo {

		/**
		 * xhprof init
		 * @var boolean
		 */
		static public $_xhprof_init = false;

		/**
		 * xhprof enable
		 * @var boolean
		 */
		static public $_xhprof_enable = false;

		/**
		 * profiler namespace
		 * @var string
		 */
		static private $_xhprof_profiler_namespace = null;

		/**
		 * init
		 */
		static public function init() {
			egw_vfs::init_static();

			$config = array();

			if( isset($GLOBALS['egw_info']['user']['preferences']['edebug']) ) {
				$config = $GLOBALS['egw_info']['user']['preferences']['edebug'];
			}

			// -----------------------------------------------------------------

			if( !extension_loaded('xdebug') ) {
				if( !dl('xdebug.so') ) {
					throw new Exception('dl not loaded: xdebug');
				}
			}

			// -----------------------------------------------------------------

			if( !extension_loaded('xhprof') ) {
				if( !dl('xhprof.so') ) {
					throw new Exception('dl not loaded: xhprof');
				}
			}

			if( isset($config['xhprof_enable']) && ($config['xhprof_enable'] == '1') ) {
				include_once(__DIR__ . "/vendor/xhprof_lib/utils/xhprof_lib.php");
				include_once(__DIR__ . "/vendor/xhprof_lib/utils/xhprof_runs.php");

				self::$_xhprof_init = true;
			}
		}

		/**
		 * xhprof_enable
		 */
		static public function xhprof_enable() {
			if( self::$_xhprof_init ) {
				self::$_xhprof_enable = true;
				xhprof_enable();
			}
		}

		/**
		 * xhprof_disable
		 */
		static public function xhprof_disable() {
			if( self::$_xhprof_enable ) {
				$xhprof_data = xhprof_disable();

				if( $xhprof_data !== null ) {
					$xhprof = new edebug_xhprof_so();
					$xhprof->setXHProfData($xhprof_data);
					$xhprof->save();
				}
			}
		}

		/**
		 * getPHPUuid
		 * return a unid
		 *
		 * @return string UUID aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
		 */
		static public function getPHPUuid() {
			$randstr = md5(uniqid(mt_rand(), true));
			$uuid = substr($randstr,0,8) . '-';
			$uuid .= substr($randstr,8,4) . '-';
			$uuid .= substr($randstr,12,4) . '-';
			$uuid .= substr($randstr,16,4) . '-';
			$uuid .= substr($randstr,20,12);
			return $uuid;
		}
	}

	/**
	 * init
	 */
	edebug_bo::init();