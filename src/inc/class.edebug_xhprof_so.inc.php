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
	 * edebug_xhprof_so
	 */
	class edebug_xhprof_so {

		/**
         * TABLE
         */
        const TABLE = 'egw_edebug_xhprof';

		/**
         * Reference to global db object
         *
         * @var egw_db
         */
        static protected $_db;

		/**
         * id
         * @var string
         */
        protected $_id = null;

		/**
		 * create
		 * @var int
		 */
		protected $_create = null;

		/**
		 * owner
		 * @var int
		 */
		protected $_owner = null;

		/**
		 * app
		 * @var string
		 */
		protected $_app = null;

		/**
		 * uri
		 * @var string
		 */
		protected $_uri = null;

		/**
		 * request data
		 * @var array
		 */
		protected $_request_data = null;

		/**
		 * profilername
		 * @var string
		 */
		protected $_profilername = 'default';

		/**
		 * xhprof data
		 * @var string
		 */
		protected $_xhprof_data = null;

		/**
         * Init our static properties
         */
        static public function init_static() {
            self::$_db = $GLOBALS['egw']->db;
        }

		/**
         * constructor
         *
         * @param string $id
         */
        public function __construct($id=null) {
            if( $id != null ) {
				$data = self::read($id);

				if( $data ) {
					$this->_create			= $data['ed_create'];
					$this->_owner			= $data['ed_owner'];
					$this->_app				= $data['ed_app'];
					$this->_profilername	= $data['ed_profilername'];
					$this->_uri				= $data['ed_uri'];
				}
			}

			$this->_id = $id;
		}

		/**
         * getId
         * @return string
         */
        public function getId() {
            return $this->_id;
        }

		/**
		 * setOwner
		 * @param int $owner
		 */
		public function setOwner($owner) {
			$this->_owner = $owner;
		}

		/**
		 * getOwner
		 * @return int
		 */
		public function getOwner() {
			return $this->_owner;
		}

		/**
		 * setApp
		 * @param string $app
		 */
		public function setApp($app) {
			$this->_app = $app;
		}

		/**
		 * getApp
		 * @return string
		 */
		public function getApp() {
			return $this->_app;
		}

		/**
		 * setProfilerName
		 * @param string $name
		 */
		public function setProfilerName($name) {
			$this->_profilername = $name;
		}

		/**
		 * getProfilerName
		 * @return string
		 */
		public function getProfilerName() {
			return $this->_profilername;
		}

		/**
		 * getURI
		 * @return string
		 */
		public function getURI() {
			return $this->_uri;
		}

		/**
		 * setXHProfData
		 * @param array $data
		 */
		public function setXHProfData($data) {
			$this->_xhprof_data = json_encode($data);
		}

		/**
		 * getXHProfData
		 * @return array
		 */
		public function getXHProfData() {
			$links = egw_link::get_links('edebug', $this->_id);

			foreach( $links as $link ) {
				$filename = null;
				$id = null;
				$app = null;

				if( $link['app'] == 'file' ) {
					$filename = $link['id'];
					$id = $link['id2'];
					$app = $link['app2'];
				}
				elseif( $link['app2'] == 'file' ) {
					$filename = $link['id2'];
					$id = $link['id'];
					$app = $link['app'];
				}

				if( $filename !== null ) {
					$info = pathinfo($filename);

					if( $info['extension'] == 'xhprof' ) {
						$filecontent = file_get_contents(egw_link::vfs_path($app, $id, $filename));

						if( $filecontent != '' ) {
							return json_decode($filecontent, true);
						}
					}
				}
			}

			return null;
		}

		/**
         * save
         */
        public function save() {
            $data = array();

			if( $this->_id ) {
                $data['ed_unid'] = $this->_id;
            }

			if( $this->_create == null ) {
				$this->_create = time();
			}

			$data['ed_create'] = $this->_create;

			if( $this->_owner == null ) {
				$this->_owner = $GLOBALS['egw_info']['user']['account_id'];
			}

			$data['ed_owner']			= $this->_owner;

			if( $this->_app == null ) {
				$this->_app = $GLOBALS['egw_info']['flags']['currentapp'];
			}

			$data['ed_app']				= $this->_app;
			$data['ed_profilername']	= $this->_profilername;

			if( $this->_uri === null ) {
				$data['ed_uri'] = $_SERVER['REQUEST_URI'];
			}
			else {
				$data['ed_uri'] = $this->_uri;
			}

			$return = self::_write($data);

            if( $return ) {
                if( !($this->_id) ) {
                    $this->_id = $return;
                }

				if( $this->_xhprof_data != null ) {
					$file = sys_get_temp_dir() . '/' . $this->_id . '.xhprof';
					$fp = fopen($file, 'w+');
					fwrite($fp, $this->_xhprof_data);
					fclose($fp);

					egw_link::attach_file(
						'edebug',
						$this->_id,
						$file
						);
				}
            }
		}

		/**
         * read
         *
         * @param string $id
         * @return boolean|array
         */
        static public function read($id=null) {
            $where = array(self::TABLE . '.ed_unid=' . "'" . (string)$id . "'");
            $cols = array(self::TABLE . '.*');
            $join = '';

            if (!($data = self::$_db->select(self::TABLE, $cols, $where, __LINE__, __FILE__,
                false, '', false, -1, $join)->fetch()))
            {
                return false;
            }

            return $data;
        }

		/**
         * _write
         *
         * @param array $data
         */
        static protected function _write(array $data) {
            if( isset($data['ed_unid']) ) {
                $unid = $data['ed_unid'];
                unset($data['ed_unid']);

                self::$_db->update(
                    self::TABLE,
                    $data,
                    array(
                        'ed_unid' => $unid,
                        ),
                    __LINE__,
                    __FILE__,
                    'edebug'
                    );
            }
            else {
                $data['ed_unid'] = edebug_bo::getPHPUuid();

                self::$_db->insert(
                    self::TABLE,
                    $data,
                    false,
                    __LINE__,
                    __FILE__,
                    'edebug'
                    );
            }

            return $data['ed_unid'];
        }

		/**
		 * _delete
		 * @param string $id
		 */
		static protected function _delete($id) {
			self::$_db->delete(
				self::TABLE,
				array(
					'ed_unid' => $id,
					),
				__LINE__,
				__FILE__,
				'edebug'
				);
		}

		/**
         * get_rows
         *
         * @param type $query
         * @param type $rows
         * @param type $readonlys
         * @return type
         */
        static public function get_rows(&$query, &$rows, &$readonlys) {
            $where = array();
            $cols = array(self::TABLE . '.*');
            $join = '';

            if( key_exists('col_filter', $query) ) {
                if( isset($query['col_filter']['ed_unid']) ) {
                    $where['ed_unid'] = $query['col_filter']['ed_unid'];
                }
            }

			if( !isset($query['start']) ) {
				$query['start'] = 0;
			}

			$start = ($query['num_rows'] ?
				array((int)$query['start'], $query['num_rows']) :
				(int)$query['start']);

			list($start, $num_rows) = $start;

			if( ($num_rows == null) || ($num_rows == false) ) {
				$num_rows = -1;
			}

			$total = self::$_db->select(self::TABLE, 'COUNT(*)',
				$where, __LINE__, __FILE__, false, '', false, 0, $join)->fetchColumn();

            if (!($rs = self::$_db->select(self::TABLE, $cols, $where, __LINE__, __FILE__,
                $start, '', false, $num_rows, $join)))
            {
                return array();
            }

            $rows = array();

            foreach( $rs as $row ) {
				$row = (array) $row;
                $rows[] = $row;
            }

            return $total;
        }
	}

	/**
	 * init_static
	 */
	edebug_xhprof_so::init_static();