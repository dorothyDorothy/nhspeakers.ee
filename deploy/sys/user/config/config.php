<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['is_system_on'] = 'y';
$config['multiple_sites_enabled'] = 'n';
$config['save_tmpl_files'] = 'y';
// ExpressionEngine Config Items
// Find more configs and overrides at
// https://docs.expressionengine.com/latest/general/system_configuration_overrides.html

$config['app_version'] = '4.0.0-dp.3';
$config['encryption_key'] = '686e7ee894e0885772c3ac318407b474dac50e47';
$config['session_crypt_key'] = '1bb31d76ae0dd574c7b5e732607eda16a75123dc';
$config['database'] = array(
	'expressionengine' => array(
		'hostname' => 'localhost',
		'database' => 'nh_speakers',
		'username' => 'nhspeaker',
		'password' => '$1wagonEACH4$',
		'dbprefix' => 'exp_',
		'char_set' => 'utf8mb4',
		'dbcollat' => 'utf8mb4_unicode_ci',
		'port'     => ''
	),
);

/**
 * Require the Focus Lab, LLC Master Config file
 */
require $_SERVER['DOCUMENT_ROOT'] . '/../config/config.master.php';

// EOF