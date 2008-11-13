<?php
class AboutController extends ApplicationController {
	public function controller_setup() {
		array_push($this->title, 'About');
	}
	
	// public function index() {
		// 
	// }
	
	public function __call($action, $args) {
		// return Markdown();
		// $this->core->mime = 'markdown';
		return CoreView::render($action, 'markdown');
	}
}