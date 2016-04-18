<?php
/**
 * eGroupWare - Setup
 * http://www.egroupware.org
 * Created by eTemplates DB-Tools written by ralfbecker@outdoor-training.de
 *
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @package edebug
 * @subpackage setup
 * @version $Id$
 */


$phpgw_baseline = array(
	'egw_edebug_xhprof' => array(
		'fd' => array(
			'ed_unid' => array('type' => 'varchar','precision' => '64'),
			'ed_create' => array('type' => 'timestamp'),
			'ed_owner' => array('type' => 'int','precision' => '4'),
			'ed_app' => array('type' => 'varchar','precision' => '25'),
			'ed_profilername' => array('type' => 'varchar','precision' => '255'),
			'ed_uri' => array('type' => 'varchar','precision' => '512')
		),
		'pk' => array('ed_unid'),
		'fk' => array(),
		'ix' => array('ed_unid','ed_create','ed_owner','ed_app','ed_profilername','ed_uri'),
		'uc' => array()
	)
);
