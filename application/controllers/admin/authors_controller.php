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
		try {
			$this->author = new Author();
			$this->author->merge($_POST['author']);
			$this->author->save();
		
			$this->flash_success($this->author->name, 'created');
		
			CoreRouter::redirect_to('admin/authors');
		} catch(Doctrine_Validator_Exception $e) {
			$this->action_to_render = '_new';
		}
	}
	
	public function edit() {
		$this->author = Doctrine_Query::create()->from('Author a')->where('a.handle = ?', $_GET['id'])->fetchOne();
		$this->breadcrumbs[$this->author->handle] = $this->author->name;
	}
	
	public function update() {
		try {
			$this->author = Doctrine_Query::create()->from('Author a')->where('a.handle = ?', $_GET['id'])->fetchOne();
			$this->author->merge($_POST['author']);
			$this->author->save();
		
			$this->flash_success($this->author->name, 'updated');
		
			CoreRouter::redirect_to('admin/authors');
		} catch(Doctrine_Validator_Exception $e) {
			$this->action_to_render = 'edit';
		}
	}
	
	public function delete() {
		Doctrine_query::create()->delete()->from('Author a')->where('a.handle = ?', $_GET['id'])->execute();
		$this->flash_success($this->author->name, 'deleted');
		
		CoreRouter::redirect_to('admin/authors');
	}
}