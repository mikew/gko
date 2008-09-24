<?php
class WelcomeController extends ApplicationController {
	public $title = array('KDE Games');
	public $kde_feed;
	
	protected function _setup() {
		// $this->add_before_filter('test', 'after');
	}
	
	public function index() {
		array_push($this->title, 'Welcome');
		$this->kde_feed = simplexml_load_file('http://www.kde.org/dotkdeorg.rdf');
	}
	
	// protected function run_before_filters() {
	// 	
	// }
	
	// protected function test() {
	// 	return false;
	// }
}