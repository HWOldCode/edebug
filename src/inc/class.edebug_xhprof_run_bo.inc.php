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
	 * include_once
	 */
	include_once(__DIR__ . "/vendor/xhprof_lib/utils/xhprof_lib.php");
	include_once(__DIR__ . "/vendor/xhprof_lib/utils/xhprof_runs.php");

	/**
	 * edebug_xhprof_run_bo
	 */
	class edebug_xhprof_run_bo implements iXHProfRuns {

		/**
		 * get_run
		 *
		 * @param string $run_id
		 * @param type $type
		 * @param type $run_desc
		 * @return array|null
		 */
		public function get_run($run_id, $type, &$run_desc) {
			$so = new edebug_xhprof_so($run_id);
			$data = $so->getXHProfData();

			if( is_array($data) ) {
				$run_desc = "XHProf Run (Namespace=$type)";
				return $data;
			}
			else {
				xhprof_error("Could not find file $run_id");
				$run_desc = "Invalid Run Id = $run_id";
			}

			return null;
		}

		public function save_run($xhprof_data, $type, $run_id = null) {

		}
	}