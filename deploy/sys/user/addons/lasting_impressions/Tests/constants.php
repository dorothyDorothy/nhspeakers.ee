<?php

$system_path = realpath(__DIR__ . '../../../../../');

if (realpath($system_path) !== FALSE)
{
	$system_path = realpath($system_path).'/';
}

// The PHP file extension
define('EXT', '.php');

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

define('APPPATH', $system_path.'expressionengine/');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path.'codeigniter/system/'));

define('PATH_THIRD', APPPATH.'third_party/');
define('PATH_MOD',		APPPATH.'modules/');

