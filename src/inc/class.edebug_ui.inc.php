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
	 * edebug_ui
	 */
	class edebug_ui {

		/**
         * public methode
         * @var array
         */
        public $public_functions = array(
            'index'				=> true,
			'xhprof_view'		=> true,
			'xhprof_callgraph'	=> true,
			);

		/**
         * index
         * @param array $content
         */
        public function index($content=null) {
			$readonlys	= array();
			$msg		= '';


			// -----------------------------------------------------------------

			if( $content['nm']['action'] ) {
				if( !count($content['nm']['selected']) && !$content['nm']['select_all'] ) {
					$msg = lang('You need to select some entries first!');
				}
				else {
					$success	= null;
					$failed		= null;
					$action_msg = null;

					if( $this->_action(
						$content['nm']['action'],
						$content['nm']['selected'],
						$content['nm']['select_all'],
						$success,
						$failed,
						$action_msg,
						'index',
						$msg) )
					{
						$msg .= lang('%1 entry(s) %2', $success, $action_msg);
					}
					elseif( empty($msg) )
					{
						$msg .= lang(
							'%1 entries(s) %2, %3 failed because of insufficent rights !!!',
							$success,
							$action_msg,
							$failed);
					}
				}
			}

			// -----------------------------------------------------------------

			$content = array(
				'nm' => egw_session::appsession('edebugxhprof', 'edebug'),
				'msg' => $msg,
				);

			if( !($content['nm'] = egw_session::appsession('edebugxhprof', 'edebug')) ) {
				$content['nm'] = array(		// I = value set by the app, 0 = value on return / output
					'get_rows'      =>	'edebug.edebug_ui.xhprof_get_rows',	// I  method/callback to request the data for the rows eg. 'notes.bo.get_rows'
					'no_filter'     => true,// I  disable the 1. filter
					'no_filter2'    => true,// I  disable the 2. filter (params are the same as for filter)
					'no_cat'        => false,// I  disable the cat-selectbox
					//'never_hide'    => true,// I  never hide the nextmatch-line if less then maxmatch entrie
					'row_id'        => 'unid',
					'actions'       => self::index_xhprof_get_actions(),
					//'header_row'    => 'edebug.edebug_xhprof_list.header_right',
					'favorites'     => false
					);
			}

			$tpl = new etemplate_new('edebug.edebug_xhprof_list');
            $tpl->exec(
                'edebug.edebug_ui.index',
                $content,
                array(),
                $readonlys,
                array(),
                0);
		}

		/**
		 * index_xhprof_get_actions
		 * @param type $query
		 * @return array
		 */
		static public function index_xhprof_get_actions($query=array()) {
			$group = 1;

            $actions = array(
				'open' => array(
                    'caption'	=> 'Open',
                    'group'		=> $group,
                    'default'	=> true,
                    'icon'		=> 'view',
                    'hint'		=> 'XHProf Profiling Open',
                    'enabled'	=> true,
                ),
				'graph' => array(
                    'caption'	=> 'View Graph',
                    'group'		=> $group,
                    'default'	=> true,
                    'icon'		=> 'view',
                    'hint'		=> 'XHProf Profiling Graph Open',
                    'enabled'	=> true,
                ),
				);

			return $actions;
		}

		/**
		 * _action
		 *
		 * @param type $action
		 * @param type $checked
		 * @param type $use_all
		 * @param int $success
		 * @param int $failed
		 * @param type $action_msg
		 * @param type $session_name
		 * @param type $msg
		 * @return type
		 */
		protected function _action($action, $checked,
			$use_all, &$success, &$failed, &$action_msg, $session_name, &$msg)
		{
			$success	= 0;
			$failed		= 0;

			switch( $action ) {
				case 'open':
					if( is_array($checked) ) {
						$checked = $checked[0];
					}

					egw_framework::popup(
						egw::link('/index.php', 'menuaction=' .
							'edebug.edebug_ui.xhprof_view&unid=' .
							$checked), '_blank', '750x580');

					$success++;
					break;

				case 'graph':
					if( is_array($checked) ) {
						$checked = $checked[0];
					}

					egw_framework::popup(
						egw::link('/index.php', 'menuaction=' .
							'edebug.edebug_ui.xhprof_callgraph&unid=' .
							$checked), '_blank', '750x580');

					$success++;
					break;
			}

			return !$failed;
		}

		/**
		 * xhprof_get_rows
		 * @param array $query
		 * @param array $rows
		 * @param array $readonlys
		 */
		public function xhprof_get_rows(&$query, &$rows, &$readonlys) {
			$count = edebug_xhprof_so::get_rows($query, $rows, $readonlys);

			foreach( $rows as &$row ) {
				$row['icon']			= 'timing';
				$row['unid']			= $row['ed_unid'];
				$row['profilername']	= $row['ed_profilername'];
				$row['app']				= $row['ed_app'];
				$row['creator']			= $row['ed_owner'];
				$row['created']			= $row['ed_create'];
				$row['uri']				= $row['ed_uri'];
			}

			return $count;
		}

		/**
		 * xhprof_view
		 * @param array $content
		 */
		public function xhprof_view($content=array()) {
			$uid = ( isset($content['unid']) ? $content['unid'] : null);
			$uid = ( $uid == null ? (isset($_GET['unid']) ? $_GET['unid'] : null) : $uid);

			$_GET['run'] = $uid;

			$GLOBALS['XHPROF_LIB_ROOT'] = __DIR__ . '/vendor/xhprof_lib';
			$GLOBALS['XHPROF_URLS']		= array(
				'?' => egw::link('/index.php', array(
					'menuaction' => 'edebug.edebug_ui.xhprof_view',
					'unid' => $uid,
					)) . '&',
				'callgraph.php?' => egw::link('/index.php', array(
					'menuaction' => 'edebug.edebug_ui.xhprof_callgraph',
					'unid' => $uid,
					)) . '&',
				);

			/**
			 * include_once
			 */
			include_once(__DIR__ . "/inc/egw.xhprof.inc.php");

			// param name, its type, and default value
			$params = array('run'        => array(XHPROF_STRING_PARAM, ''),
							'wts'        => array(XHPROF_STRING_PARAM, ''),
							'symbol'     => array(XHPROF_STRING_PARAM, ''),
							'sort'       => array(XHPROF_STRING_PARAM, 'wt'), // wall time
							'run1'       => array(XHPROF_STRING_PARAM, ''),
							'run2'       => array(XHPROF_STRING_PARAM, ''),
							'source'     => array(XHPROF_STRING_PARAM, 'xhprof'),
							'all'        => array(XHPROF_UINT_PARAM, 0),
							);

			// pull values of these params, and create named globals for each param
			xhprof_param_init($params);

			/* reset params to be a array of variable names to values
			   by the end of this page, param should only contain values that need
			   to be preserved for the next page. unset all unwanted keys in $params.
			 */
			foreach ($params as $k => $v) {
			  $params[$k] = $$k;

			  // unset key from params that are using default values. So URLs aren't
			  // ridiculously long.
			  if ($params[$k] == $v[1]) {
				unset($params[$k]);
			  }
			}

			echo "<html>";
			echo "<head><title>XHProf: Hierarchical Profiler Report</title>";
			xhprof_include_js_css(egw::link('/edebug/inc/vendor/xhprof_html/'));
			echo "</head>";

			echo "<body>";

			$vbar  = ' class="vbar"';
			$vwbar = ' class="vwbar"';
			$vwlbar = ' class="vwlbar"';
			$vbbar = ' class="vbbar"';
			$vrbar = ' class="vrbar"';
			$vgbar = ' class="vgbar"';

			$xhprof_runs_impl = new edebug_xhprof_run_bo();

			displayXHProfReport($xhprof_runs_impl, $params, '', $uid, $GLOBALS['wts'],
                    $GLOBALS['symbol'], $GLOBALS['sort'], $GLOBALS['run1'], $GLOBALS['run2']);

			echo "</body>";
			echo "</html>";

			exit;
		}

		/**
		 * xhprof_callgraph
		 * @param array $content
		 */
		public function xhprof_callgraph($content=array()) {
			$uid = ( isset($content['unid']) ? $content['unid'] : null);
			$uid = ( $uid == null ? (isset($_GET['unid']) ? $_GET['unid'] : null) : $uid);

			$_GET['run'] = $uid;

			ini_set('max_execution_time', 100);

			$GLOBALS['XHPROF_LIB_ROOT'] = __DIR__ . '/vendor/xhprof_lib';
			$GLOBALS['XHPROF_URLS']		= array(
				'?' => egw::link('/index.php', array(
					'menuaction' => 'edebug.edebug_ui.xhprof_view',
					'unid' => $uid,
					)) . '&',
				'callgraph.php?' => egw::link('/index.php', array(
					'menuaction' => 'edebug.edebug_ui.xhprof_callgraph',
					'unid' => $uid,
					)) . '&',
				);

			/**
			 * include_once
			 */
			include_once(__DIR__ . "/inc/egw.xhprof.inc.php");

			$params = array(// run id param
                'run' => array(XHPROF_STRING_PARAM, ''),

                // source/namespace/type of run
                'source' => array(XHPROF_STRING_PARAM, 'xhprof'),

                // the focus function, if it is set, only directly
                // parents/children functions of it will be shown.
                'func' => array(XHPROF_STRING_PARAM, ''),

                // image type, can be 'jpg', 'gif', 'ps', 'png'
                'type' => array(XHPROF_STRING_PARAM, 'svg'),

                // only functions whose exclusive time over the total time
                // is larger than this threshold will be shown.
                // default is 0.01.
                'threshold' => array(XHPROF_FLOAT_PARAM, 0.01),

                // whether to show critical_path
                'critical' => array(XHPROF_BOOL_PARAM, true),

                // first run in diff mode.
                'run1' => array(XHPROF_STRING_PARAM, ''),

                // second run in diff mode.
                'run2' => array(XHPROF_STRING_PARAM, '')
                );

			// pull values of these params, and create named globals for each param
			xhprof_param_init($params);

			// if invalid value specified for threshold, then use the default
			if ($GLOBALS['threshold'] < 0 || $GLOBALS['threshold'] > 1) {
			  $GLOBALS['threshold'] = $params['threshold'][1];
			}

			// if invalid value specified for type, use the default
			if (!array_key_exists($GLOBALS['type'], $xhprof_legal_image_types)) {
			  $GLOBALS['type'] = $params['type'][1]; // default image type.
			}

			$xhprof_runs_impl = new edebug_xhprof_run_bo();

			if (!empty($uid)) {
				// single run call graph image generation
				xhprof_render_image(
					$xhprof_runs_impl,
					$uid,
					$GLOBALS['type'],
					$GLOBALS['threshold'],
					$GLOBALS['func'],
					$GLOBALS['source'],
					$GLOBALS['critical']
					);
			}
			else {
				// diff report call graph image generation
				xhprof_render_diff_image(
					$xhprof_runs_impl,
					$GLOBALS['run1'],
					$GLOBALS['run2'],
					$GLOBALS['type'],
					$GLOBALS['threshold'],
					$GLOBALS['source']
					);
			}

			exit;
		}
	}