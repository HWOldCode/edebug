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
	 * edebug_xhprof_bo
	 */
	class edebug_xhprof_bo {

		/**
		 * link_title
		 *
		 * @param type $info
		 * @return string
		 */
		static public function link_title($info) {
			$xhprof = new edebug_xhprof_so($info);

			if( $xhprof->getOwner() != null ) {
				return $xhprof->getProfilerName();
			}

			return lang('not found');
		}

		/**
		 * link_titles
		 *
		 * @param array $ids
		 */
		static public function link_titles(array $ids) {
            $titles = array();

            foreach( $ids as $id ) {
                $titles[$id] = self::link_title($id);
            }

            return $titles;
		}

		/**
         * link_query
         *
         * @param type $pattern
         * @param array $options
         */
        static public function link_query($pattern, Array &$options = array()) {
			$rows		= array();
			$readonlys	= array();
			$result = array();

			if( edebug_xhprof_so::get_rows($options, $rows, $readonlys) > 0 ) {
				foreach( $rows as &$row ) {
					$result[$row['ed_unid']] = array(
							'label' => $row['ed_profilername'],
							);
				}

				return $result;
			}

			return array();
		}
	}