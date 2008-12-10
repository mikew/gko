<?php
class Admin_GamesController extends AdminController {
	public static function setup($self) {
		array_push($self->title, 'Games');
	}
	
	public function index() {
		$this->games = Doctrine_query::create()->from('Games g')->execute();
	}
	
	public function _new() {
		$this->game = new Games();
	}
}