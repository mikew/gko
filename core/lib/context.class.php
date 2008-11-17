<?php
class CoreContext {
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
			$this->{$key} = $value;
		}
		
		CoreHelper::instance();
	}
	
	static public function get($key) {
		return self::instance()->{$key};
	}
	
	final public function __call($method, $arguments) {
		return CoreHelper::__callStatic($method, $arguments);
	}
	
	public function yield() {
		return $this->controller->content_for_layout();
	}
}