<?php
final class Core {
	private $extensions = array();
	private $mimes = array();
	public $mime = 'html';
	public $status = 200;
	
	public function __construct() {
		// $this->add_mime('html', '.phtml', 'application/xhtml+xml');
		$this->add_mime('html', '.phtml');
		$this->add_mime('markdown', '.markdown');
		$this->add_mime('mobile', '.mobile.phtml');
		// $this->add_mime('rss', '.rss', 'application/rss+xml');
		$this->add_mime('rss', '.rss.php', 'application/rss+xml');
	}
	
	protected function add_mime($shorthand, $extension, $content_type = 'text/html') {
		$this->extensions[$shorthand] = $extension;
		$this->mimes[$shorthand] = $content_type;
	}
	
	public function interpret_mime($mime = '') {
		$options = array_keys($this->mimes);
		return !empty($mime) && in_array($mime, $options) ? $mime : $this->mime;
	}
	
	public function interpret_extension($mime = '') {
		return $this->extensions[$this->interpret_mime($mime)];
	}
	
	public function find_template_file($file, $mime = '') {
		$file = Core::join_paths($file);
		
		$extension = $this->interpret_extension($mime);
		$file = substr($file, 0, 1) == '/' ? $file : File::join(APP_HOME, 'views', String::underscore(CONTROLLER), $file);
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
define('LIB_HOME', Core::join_paths(FW_HOME, 'lib'));

# Constants for Doctrine
define('FIXTURE_PATH', Core::join_paths(FW_HOME, 'db', 'fixtures'));
define('MODEL_PATH', Core::join_paths(APP_HOME, 'models'));
define('MIGRATIONS_PATH', Core::join_paths(FW_HOME, 'db', 'migrations'));
define('SCHEMA_PATH', Core::join_paths(FW_HOME, 'db', 'schema'));
define('SQL_PATH', Core::join_paths(FW_HOME, 'db', 'sql'));

require_once Core::join_paths(CORE_HOME, 'extensions', 'propertyobject.class.php');
require_once Core::join_paths(CORE_HOME, 'extensions', 'file.class.php');
require_once Core::join_paths(CORE_HOME, 'extensions', 'string.class.php');
