<?php
class Core {
	private $extensions = array();
	private $mimes = array();
	private $mime = 'html';
	private $status = 200;
	
	public function __construct() {
		// $this->add_mime('html', '.phtml', 'application/xhtml+xml');
		$this->add_mime('html', '.phtml', 'text/html');
	}
	
	protected function add_mime($shorthand, $extension, $content_type) {
		$this->extensions[$shorthand] = $extension;
		$this->mimes[$shorthand] = $content_type;
	}
	
	public function find_template_file($file, $extension = '') {
		$file = Core::join_paths($file);
		
		$extension = empty($extension) ? $this->extensions[$this->mime] : $this->extensions[$extension];
		$file = substr($file, 0, 1) == '/' ? $file : File::join(APP_HOME, 'views', CONTROLLER, $file);
		$file .= substr($file, -strlen($extension)) == $extension ? '' : $extension;
		
		return is_file($file) ? $file : false;
	}
	
	public function set_headers() {
		header("HTTP/1.0 {$this->status}");
		header("Content-Type: {$this->mimes[$this->mime]}");
	}
	
	public function join_paths() {
		$args = func_get_args();
		$args = is_array($args[0]) ? $args[0] : $args;
		return implode(DIRECTORY_SEPARATOR, $args);
	}
}

define('FW_HOME', realpath(Core::join_paths(dirname(__FILE__), '..')));
define('APP_HOME', Core::join_paths(FW_HOME, 'application'));
define('CORE_HOME', Core::join_paths(FW_HOME, 'core'));
define('CORE_VENDOR_HOME', Core::join_paths(CORE_HOME, 'vendor'));
define('VENDOR_HOME', Core::join_paths(FW_HOME, 'vendor'));
define('CONFIG_HOME', Core::join_paths(FW_HOME, 'config'));
define('TMP_HOME', Core::join_paths(FW_HOME, 'tmp'));

define('FIXTURE_PATH', Core::join_paths(FW_HOME, 'test', 'fixtures'));
define('MODEL_PATH', Core::join_paths(APP_HOME, 'models'));
define('MIGRATIONS_PATH', Core::join_paths(FW_HOME, 'db', 'migrations'));
define('SCHEMA_PATH', Core::join_paths(FW_HOME, 'db', 'schema'));
define('SQL_PATH', Core::join_paths(FW_HOME, 'db', 'sql'));

require_once Core::join_paths('core', 'extensions', 'propertyobject.class.php');
require_once Core::join_paths('core', 'extensions', 'file.class.php');
require_once Core::join_paths('core', 'extensions', 'string.class.php');
