<?php
class AdminController extends ApplicationController {
	public $user;
	
	public function index() {
		CoreRouter::redirect_to('admin/posts');
	}
	
	public function logout() {
		// TODO: none of this works
		setcookie(session_name(), '', time() - 3600);
		CoreRouter::redirect_to('/');
	}
	
	public static function setup($self) {
		$self->push_title('Administration');
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
