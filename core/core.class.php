<?php
require_once FW_HOME . '/core/propertyobject.class.php';
require_once FW_HOME . '/core/file.class.php';
require_once FW_HOME . '/core/string.class.php';

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
		$file = File::join($file);
		
		$extension = empty($extension) ? $this->extensions[$this->mime] : $this->extensions[$extension];
		$file = substr($file, 0, 1) == '/' ? $file : File::join(APP_HOME, 'views', CONTROLLER, $file);
		$file .= substr($file, -strlen($extension)) == $extension ? '' : $extension;
		
		return is_file($file) ? $file : false;
	}
	
	public function set_headers() {
		header("HTTP/1.0 {$this->status}");
		header("Content-Type: {$this->mimes[$this->mime]}");
	}
}