<?php
class Admin_PostsController extends AdminController {
	public static function setup($self) {
		$self->push_title('Posts');
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Post p')->orderBy('p.created_at DESC')->execute();
	}
	
	public function _new() {
		$this->push_title('New');
		$this->post = new Post();
	}
	
	public function create() {
		try {
			$this->post = new Post();
			$this->post->merge($_POST['post']);
			$this->post->Author = $this->user;
			$this->post->save();
		
			$this->flash->success = '"' . $this->post->title . '" was created!';
		
			CoreRouter::redirect_to('admin/posts');
		} catch(Doctrine_Validator_Exception $e) {
			$this->action_to_render = '_new';
		}
	}
	
	public function edit() {
		$this->post = Doctrine_Query::create()->from('Post p')->where('p.key = ?', $_GET['id'])->fetchOne();
		$this->breadcrumbs[$this->post->key] = $this->post->title;
	}
	
	public function update() {
		try {
			$this->post = Doctrine_Query::create()->from('Post p')->where('p.key = ?', $_GET['id'])->fetchOne();
			$this->post->merge($_POST['post']);
			$this->post->save();
		
			$this->flash->success = '"' . $this->post->title . '" was updated!';
		
			CoreRouter::redirect_to('admin/posts');
		} catch(Doctrine_Validator_Exception $e) {
			$this->action_to_render = 'edit';
		}
	}
	
	public function delete() {
		Doctrine_query::create()->delete()->from('Post p')->where('p.key = ?', $_GET['id'])->execute();
		
		CoreRouter::redirect_to('admin/posts');
	}
}
