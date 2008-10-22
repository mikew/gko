<?php
class Admin_PostsController extends AdminController {
	public function _setup() {
		$this->add_before_filter('require_auth');
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('News n')->execute();
	}
}
