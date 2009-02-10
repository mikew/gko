<?php
class Admin_AuthorsController extends AdminController {
	public static function setup($self) {
		$self->push_title('Authors');
	}
	
	public function index() {
		$this->authors = Doctrine_query::create()->from('Author a')->execute();
	}
	
	public function _new() {
		$this->author = new Author();
	}
	
	public function create() {
		$this->author = new Author();
		$this->author->merge($_POST['author']);
		// $this->author->isValid();
		if(!$this->author->trySave()) {
			$this->action_to_render = '_new';
			return;
		}
		
		$this->flash->success = '"' . $this->author->name . '" was created!';
		
		CoreRouter::redirect_to('admin/authors');
	}
	
	public function update() {
		$this->author = Doctrine_Query::create()->from('Author a')->where('a.handle = ?', $_GET['id'])->fetchOne();
		$this->author->merge($_POST['author']);
		
		if(!$this->author->trySave()) {
			$this->action_to_render = 'edit';
			return;
		}
		
		$this->flash->success = '"' . $this->author->name . '" was edited!';
		
		CoreRouter::redirect_to('admin/authors');
	}
	
	public function delete() {
		Doctrine_query::create()->delete()->from('Author a')->where('a.handle = ?', $_GET['id'])->execute();
		
		CoreRouter::redirect_to('admin/authors');
	}
	
	public function edit() {
		$this->author = Doctrine_Query::create()->from('Author a')->where('a.handle = ?', $_GET['id'])->fetchOne();
		$this->breadcrumbs[$this->author->handle] = $this->author->name;
	}
}