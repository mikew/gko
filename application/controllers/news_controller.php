<?php
class NewsController extends ApplicationController {
	public function controller_setup() {
		array_push($this->title, 'News');
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('News')->limit(10)->execute();
	}
	
	public function show() {
		$this->item = Doctrine_Query::create()->from('News')->where('key = ?', $_GET['key'])->fetchOne();
		$this->breadcrumbs[$this->item->key] = $this->item->title;
		
		array_push($this->title, '&#8220;' . $this->item->title . '&#8221;');
	}
	
	public function sync() {
		$data = File::read(File::join(TMP_HOME, 'gko.news'));
		preg_match_all("/<h3><a name=\"(?:.*?)\">(.*?): (.*?)<\/a><\/h3><\/td>\s*<\/tr><tr><td class=\"newsbox2\">(.*?)<\/td>/s", $data, $matches);
		
		for($i = 0; $i < count($matches[0]); $i++) {
			$date = $matches[1][$i];
			$title = $matches[2][$i];
			$body = $matches[3][$i];
			
			$news = new News();
			$news->title = $title;
			$news->body = $body;
			
			$news->save();
			$news->created_at = $date;
			$news->save();
		}
	}
}