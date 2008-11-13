<?php
class Admin_PostsController extends AdminController {
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Posts p')->execute();
	}
	
	public function _new() {
		$this->post = new Posts();
	}
	
	public function edit() {
		$this->post = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['id'])->fetchOne();
	}
	
	public function update() {
		$this->post = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['id'])->fetchOne();
		$this->post->merge($_POST['post']);
		$this->post->save();
		
		CoreRouter::redirect_to('admin/posts');
	}
}
