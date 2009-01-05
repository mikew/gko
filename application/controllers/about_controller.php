<?php
class AboutController extends ApplicationController {
	public function setup($self) {
		$self->sidebar = 'about';
		$self->push_title('About');
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