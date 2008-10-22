<?php
class NewsController extends ApplicationController {
	public function index() {
		$this->posts = Doctrine_Query::create()->from('News')->limit(10)->execute();
		switch($_GET['format']) {
			case 'rss':
				$this->core->mime = 'rss';
				$this->layout = false;
			break;
		}
	}
	
	public function show() {
		$this->item = Doctrine_Query::create()->from('News')->where('key = ?', $_GET['key'])->execute();
		$this->breadcrumbs[$this->item[0]->key] = $this->item[0]->title;
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