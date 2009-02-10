<?php
class WelcomeController extends ApplicationController {
	public $kde_feed;
	public $latest = array();
	
	const DKO_TTL = 1800;
	const DKO_URL = 'http://dot.kde.org/rss.xml';
	
	public static function setup($self) {
		$self->selected_nav = 'home';
	}
	
	public function index() {
		$this->push_title('Welcome');
		$this->breadcrumbs[''] = 'Welcome!';
		
		$this->kde_feed = $this->update_dko_cache();
		$this->latest = Doctrine_Query::create()->from('Post p')->orderBy('p.created_at DESC')->limit(5)->execute();
	}
	
	protected function update_dko_cache() {
		$dko_cache = File::join(TMP_HOME, 'dko.rss');
		if(!is_file($dko_cache) || filemtime($dko_cache) < (Time::now() - self::DKO_TTL)) {
			$feed = new SimpleXMLElement(self::DKO_URL, null, TRUE);
			$feed->asXML($dko_cache);
		}
		
		return new SimpleXMLElement(file_get_contents($dko_cache));
	}
}
