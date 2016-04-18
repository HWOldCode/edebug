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

function edebug_upgrade14_1_000()
{
	$GLOBALS['egw_setup']->oProc->DropColumn('egw_edebug_xhprof',array(
		'fd' => array(
			'ed_unid' => array('type' => 'varchar','precision' => '64'),
			'ed_create' => array('type' => 'timestamp'),
			'ed_owner' => array('type' => 'int','precision' => '4'),
			'ed_app' => array('type' => 'varchar','precision' => '25'),
			'ed_profilername' => array('type' => 'varchar','precision' => '255')
		),
		'pk' => array('ed_unid'),
		'fk' => array(),
		'ix' => array('ed_unid','ed_create','ed_owner','ed_app','ed_profilername'),
		'uc' => array()
	),'ed_xhprof_data');

	return $GLOBALS['setup_info']['edebug']['currentver'] = '14.1.001';
}


function edebug_upgrade14_1_001()
{
	/* done by RefreshTable() anyway
	$GLOBALS['egw_setup']->oProc->AddColumn('egw_edebug_xhprof','ed_uri',array(
		'type' => 'varchar',
		'precision' => '512'
	));*/
	$GLOBALS['egw_setup']->oProc->RefreshTable('egw_edebug_xhprof',array(
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
	));

	return $GLOBALS['setup_info']['edebug']['currentver'] = '14.1.002';
}

