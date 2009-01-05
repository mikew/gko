<?php
class ApplicationController extends CoreController {
	public $title = array('KDE Games');
	public $selected_nav = CONTROLLER;
	public $breadcrumbs = array();
	public $sidebar;
	
	public function push_title($with) {
		$this->title[] = $with;
	}
}