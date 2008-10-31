<?php
class CoreMime {
	static private $extensions = array();
	static private $mimes = array();
	static private $status = 200;
	static private $mime = 'html';
	static private $headers = array();
	
	public static function add($shorthand, $extension, $content_type = 'text/html') {
		self::$extensions[$shorthand] = $extension;
		self::$mimes[$shorthand] = $content_type;
	}
	
	public static function set($mime) {
		$options = array_keys(self::$mimes);
		if(in_array($mime, $options))
			self::$mime = $mime;
	}
	
	public static function set_status($status) {
		self::$status = $status;
	}
	
	public static function interpret($mime = '') {
		$options = array_keys(self::$mimes);
		return !empty($mime) && in_array($mime, $options) ? $mime : self::$mime;
	}
	
	public static function current($extended = false) {
		$key = self::$mime;
		return $extended ? self::$mimes[$key] : $key;
	}
	
	public static function extension($mime = '') {
		return self::$extensions[self::interpret($mime)];
	}
	
	public static function find_template_file($file, $mime = '') {
		$file = File::join($file);
		
		$extension = self::extension($mime);
		$file = substr($file, 0, 1) == '/' ? $file : File::join(APP_HOME, 'views', String::underscore(CONTROLLER), $file);
		$file .= substr($file, -strlen($extension)) == $extension ? '' : $extension;

		return is_file($file) ? $file : false;
	}
	
	public static function set_headers() {
		header('HTTP/1.0 ' . self::$status);
		header('Content-Type: ' . self::current(true));
	}
}