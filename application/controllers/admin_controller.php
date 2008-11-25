<?php
class AdminController extends ApplicationController {
	public $user;
	
	public function index() {
		CoreRouter::redirect_to('admin/posts');
	}
	
	public static function setup($self) {
		array_push($self->title, 'Administration');
		$self->add_before_filter('require_auth');
	}
	
	public function require_auth() {
		$this->user = $this->http_auth();
	}
	
	public function process_http_auth($username, $password) {
		$user = Doctrine_Query::create()->from('Authors a')->where('handle = ? AND password = ?', array($username, $password))->fetchOne();
		return $user;
	}
}
