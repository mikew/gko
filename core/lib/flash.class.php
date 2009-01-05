<?php
class CoreFlash {
	private $used = array();
	
	public function __construct() {
		if(!isset($_SESSION['flash']))
			$_SESSION['flash'] = array();
	}
	
	public function __set($key, $value) {
		// if(!in_array($key, $this->used))
		// echo $key;
		$this->used[] = $key;
		$_SESSION['flash'][$key] = $value;
	}
	
	public function __get($key) {
		return $_SESSION['flash'][$key];
	}
	
	public function __isset($key) {
		return isset($_SESSION['flash'][$key]);
	}
	
	public function __unset($key) {
		unset($_SESSION['flash'][$key]);
	}
	
	public function reset() {
		foreach(array_keys($_SESSION['flash']) AS $key) {
			if(!in_array($key, $this->used))
				unset($_SESSION['flash'][$key]);
		}
	}
}