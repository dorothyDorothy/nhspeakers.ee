<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Local config overrides & db credentials
 *
 * Our database credentials and any environment-specific overrides
 * This file should be specific to each developer and not tracked in Git
 *
 * @package    Focus Lab Master Config
 * @version    2.2.0
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 */

// $env_config['database'] = array (
// 	'expressionengine' => array (
// 		'hostname' => 'localhost',
// 		'username' => 'nhspeaker',
// 		'password' => '$1wagonEACH4$',
// 		'database' => 'nh_speakers',
// 		'dbdriver' => 'mysqli',
// 		'dbprefix' => 'exp_',
// 		'pconnect' => FALSE
// 	),
// );

$config['database'] = array(
  'expressionengine' => array(
    'hostname' => 'localhost',
    'database' => 'nhspeakers',
    'username' => 'nhspeaker',
    'password' => '$1wagonEACH4$',
    'dbprefix' => 'exp_',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'port'     => ''
  ),
);


// Local testing email address
$env_config['webmaster_email'] = '';


/* End of file config.local.php */
/* Location: ./config/config.local.php */
