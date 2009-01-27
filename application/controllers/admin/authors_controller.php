<?php
class Admin_AuthorsController extends AdminController {
	public static function setup($self) {
		array_push($self->title, 'Authors');
	}
	
	public function index() {
		$this->authors = Doctrine_query::create()->from('Authors a')->execute();
	}
	
	public function _new() {
		$this->author = new Authors();
	}
	
	public function edit() {
		// $this->post = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['id'])->fetchOne();
		$this->author = Doctrine_Query::create()->from('Authors')->where('handle = ?', $_GET['id'])->fetchOne();
		$this->breadcrumbs[$this->author->handle] = $this->author->name;
	}
	
	public function update() {
		// $this->author = Doctrine_Query::create()->from
		return Authors::table_name();
	}
}