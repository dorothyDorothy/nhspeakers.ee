<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['debug'] = '1';
$config['enable_devlog_alerts'] = 'n';
$config['cache_driver'] = 'file';
// ExpressionEngine Config Items
// Find more configs and overrides at
// https://docs.expressionengine.com/latest/general/system_configuration_overrides.html

$config['app_version'] = '4.0.6';
$config['encryption_key'] = '36bcf98ff5292d339b6b2c7585c3021b686ea6b3';
$config['session_crypt_key'] = '1cda6cd12ef7f6e45286714d8c1b432f3e81e323';
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