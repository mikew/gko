<?php
class Admin_GamesController extends AdminController {
	public static function setup($self) {
		$self->push_title('Games');
	}
	
	public function index() {
		$this->games = Doctrine_Query::create()->from('Game g')->execute();
	}
	
	public function _new() {
		$this->push_title('New');
		$this->game = new Game();
	}
	
	public function create() {
		try {
			$this->game = new Game();
			$this->game->merge($_POST['game']);
			$this->game->save();
			
			$this->flash_success($this->game->title, 'created');
			
			CoreRouter::redirect_to('admin/games');
		} catch(Doctrine_Validator_Exception $e) {
			$this->action_to_render = '_new';
		}
	}
	
	public function edit() {
		$this->game = Doctrine_Query::create()->from('Game g')->where('g.key = ?', $_GET['id'])->fetchOne();
	}
}