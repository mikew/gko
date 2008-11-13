<?php
class PostsController extends ApplicationController {
	public function controller_setup() {
		array_push($this->title, 'News');
		$this->selected_nav = 'news';
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Posts')->limit(10)->execute();
	}
	
	public function show() {
		$this->item = Doctrine_Query::create()->from('Posts')->where('key = ?', $_GET['key'])->fetchOne();
		$this->breadcrumbs[$this->item->key] = $this->item->title;
		
		$this->comment = new PropertyObject(array(
			'name' => 'Joe Common',
			'body' => 'stock comment (these do not actually work yet!)'
		));
		
		array_push($this->title, '&#8220;' . $this->item->title . '&#8221;');
	}
	
	public function sync() {
		$data = File::read(File::join(TMP_HOME, 'gko.news'));
		preg_match_all("/<h3><a name=\"(?:.*?)\">(.*?): (.*?)<\/a><\/h3><\/td>\s*<\/tr><tr><td class=\"newsbox2\">(.*?)<\/td>/s", $data, $matches);
		
		for($i = 0; $i < count($matches[0]); $i++) {
			$date = $matches[1][$i];
			$title = $matches[2][$i];
			$body = $matches[3][$i];
			
			$post = new Posts();
			$post->title = $title;
			$post->body = $body;
			$post->save();
			
			$post->created_at = $date;
			$post->save();
		}
	}
}