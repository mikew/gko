<?php
class PostsController extends ApplicationController {
	public static function setup($self) {
		array_push($self->title, 'News');
		$self->selected_nav = 'news';
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Posts')->limit(10)->execute();
	}
	
	public function show() {
		$this->post = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['key'])->fetchOne();
		$this->breadcrumbs[$this->post->key] = $this->post->title;
		
		$this->comment = new PropertyObject(array(
			'name' => 'Joe Common',
			'body' => 'stock comment (these do not actually work yet!)'
		));
		
		array_push($this->title, '&#8220;' . $this->post->title . '&#8221;');
	}
	
	public function setup_authors() {
		$mike = new Authors();
		$mike->name = 'Mike Wyatt';
		$mike->handle = 'mikew';
		$mike->password = 'test';
		// $mike->save();
	}
	
	public function sync() {
		// $data = File::read(File::join(TMP_HOME, 'gko.news'));
		$data = File::read('http://games.kde.org/news.php?showAll=True');
		preg_match_all("/<h3><a name=\"(?:.*?)\">(.*?): (.*?)<\/a><\/h3><\/td>\s*<\/tr><tr><td class=\"newsbox2\">(.*?)<\/td>/s", $data, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$date = $matches[1][$i];
			$title = $matches[2][$i];
			$body = trim($matches[3][$i]);
			
			$post = new Posts();
			$post->title = $title;
			$post->body = $body;
			$post->body_markdown = $body;
			$post->save();
			
			$post->created_at = $date;
			$post->save();
		}
	}
}