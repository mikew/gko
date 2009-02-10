<?php
class PostsController extends ApplicationController {
	public static function setup($self) {
		$self->push_title('News');
		$self->selected_nav = 'news';
	}
	
	public function index() {
		$this->posts = Doctrine_Query::create()->from('Post p')->orderBy('p.id DESC')->limit(10)->execute();
	}
	
	public function show() {
		$this->post = Doctrine_Query::create()->from('Post p')->where('p.key = ?', $_GET['key'])->fetchOne();
		$this->breadcrumbs[$this->post->key] = $this->post->title;
		
		$this->comment = new PropertyObject(array(
			'name' => 'Joe Common',
			'body' => 'stock comment (these do not actually work yet!)'
		));
		
		$this->push_title('&#8220;' . $this->post->title . '&#8221;');
	}
	
	public function initialize() {
		$posts = Doctrine_Query::create()->from('Post p');
		$author = $this->find_or_create_author('mikew');
		
		if($posts->count() == 0) {
			unset($posts);

			$data = File::read('http://games.kde.org/news.php?showAll=True');
			preg_match_all("/<h3><a name=\"(?:.*?)\">(.*?): (.*?)<\/a><\/h3><\/td>\s*<\/tr><tr><td class=\"newsbox2\">(.*?)<\/td>/s", $data, $matches);
			$matches[1] = array_reverse($matches[1]);
			$matches[2] = array_reverse($matches[2]);
			$matches[3] = array_reverse($matches[3]);
		
			for($i = 0; $i < count($matches[0]); $i++) {
				$date = strtotime($matches[1][$i]);
				$title = $matches[2][$i];
				$body = trim($matches[3][$i]);
			
				$post = new Post();
				$post->title = $title;
				$post->body = $body;
				$post->body_markdown = $body;
				$post->Author = $author;
				$post->save();
				
				// save the post again to retain the proper timestamp
				$post->created_at = $date;
				$post->save();
			}
		}
	}
	
	private function find_or_create_author($handle) {
		$author = Doctrine_Query::create()->from('Author a')->where('a.handle = ?', $handle)->fetchOne();
		if(empty($author)) {
			$author = new Author();
			$author->name = 'a fake name';
			$author->handle = $handle;
			$author->password = 'aassdd';
			$_POST['author']['password_confirmation'] = 'aassdd';
			
			$author->save();
		}
		
		return $author;
	}
}