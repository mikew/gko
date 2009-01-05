<?php
class CoreMime {
	static private $extensions = array();
	static private $mimes = array();
	static private $status = 200;
	static private $mime = 'html';
	static private $headers = array();
	static private $simulate_post_for = array('PUT', 'DELETE');
	
	public static function add($shorthand, $extension, $content_type = 'text/html') {
		self::$extensions[$shorthand] = $extension;
		self::$mimes[$shorthand] = $content_type;
	}
	
	public static function set($mime) {
		if(self::knows_of($mime))
			self::$mime = $mime;
	}
	
	protected static function knows_of($mime) {
		return in_array($mime, array_keys(self::$mimes));
	}
	
	public static function set_status($status) {
		self::$status = $status;
	}
	
	public static function interpret($mime = '') {
		return !empty($mime) && self::knows_of($mime) ? $mime : self::$mime;
	}
	
	public static function current($extended = false) {
		$key = self::$mime;
		return $extended ? self::$mimes[$key] : $key;
	}
	
	public static function extension($mime = '') {
		return self::$extensions[self::interpret($mime)];
	}
	
	public static function set_headers() {
		if(empty(self::$headers['Location'])) {
			header('HTTP/1.0 ' . self::$status);
			header('Content-Type: ' . self::current(true));
		}
		
		foreach(self::$headers AS $key => $value) {
			header("{$key}: $value");
		}
	}
	
	public static function reset_headers() {
		self::$headers = array();
	}
	
	public static function set_header($key, $value = '') {
		if(empty($value))
			self::$headers[] = $key;
		else
			self::$headers[$key] = $value;
	}
	
	public static function should_simulate_post($method = '') {
		$method = empty($method) ? @$_REQUEST['_method'] : $method ;
		
		return in_array(String::uppercase($method), self::$simulate_post_for);
		
		return false;
	}
	
	public static function request_method() {
		if(self::should_simulate_post()) {
			return String::uppercase($_REQUEST['_method']);
		}
		
		return $_SERVER['REQUEST_METHOD'];
	}
	
	public static function is_post() {
		return self::request_method() == 'POST';
	}
	
	public static function is_get() {
		return self::request_method() == 'GET';
	}
}