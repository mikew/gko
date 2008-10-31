<?php
class Admin_PostsController extends AdminController {
	public function index() {
		$this->posts = Doctrine_Query::create()->from('News n')->execute();
	}
	
	public function edit() {
		eval('class Helper extends CoreHelper {}');
		$this->item = Doctrine_Query::create()->from('News')->where('key = ?', $_GET['id'])->fetchOne();
	}
}
