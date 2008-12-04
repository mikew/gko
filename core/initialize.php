<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

ini_set('include_path', implode(':', array(
	'.',
	realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..')
)));

require_once 'core/lib/core.class.php';

spl_autoload_register(array('Core', 'autoload'));

require_once File::join(CORE_VENDOR_HOME, 'php-markdown-extra', 'markdown.php');
require_once File::join(CORE_HOME, 'init', 'doctrine.php');
require_once File::join(CORE_HOME, 'init', 'inflector.php');
require_once File::join(CORE_HOME, 'init', 'mimes.php');