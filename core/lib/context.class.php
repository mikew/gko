<?php
class CoreContext {
	private static $from_controller = array();
	private $controller;
	
	static public function instance() {
		static $instance;
		if(!isset($instance)) {
			$instance = new self();
		}
		
		return $instance;
	}
	
	public function __construct() {
		$this->controller = $GLOBALS['controller'];
		foreach($this->controller AS $key => $value) {
			self::$from_controller[$key] = $value;
		}
		
		CoreHelper::instance();
	}
	
	public function __get($v) {
		return self::$from_controller[$v];
	}
	public static function get($key) {
		return self::instance()->{$key};
	}
	
	public function __isset($v) {
		return isset(self::$from_controller[$v]);
	}
		
	public static function export() {
		return self::$from_controller;
	}
	
	final public function __call($method, $arguments) {
		return CoreHelper::__callStatic($method, $arguments);
	}
	
	public function yield() {
		return $this->controller->content_for_layout();
	}
}