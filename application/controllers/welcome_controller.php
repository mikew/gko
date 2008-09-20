<?php
class WelcomeController extends ApplicationController {
	public $title = array('KDE Games');
	
	protected function _setup() {
		// $this->add_before_filter('test', 'after');
	}
	
	public function index() {
		array_push($this->title, 'Welcome');
	}
	
	// protected function run_before_filters() {
	// 	
	// }
	
	// protected function test() {
	// 	return false;
	// }
}