<?php
class Admin_PostsController extends AdminController {
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Posts p')->execute();
	}
	
	public function edit() {
		eval('class Helper extends CoreHelper {}');
		$this->post = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['id'])->fetchOne();
	}
}
