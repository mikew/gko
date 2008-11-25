<?php
class WelcomeController extends ApplicationController {
	public $kde_feed;
	public $latest = array();
	
	const DKO_TTL = 1800;
	const DKO_URL = 'http://www.kde.org/dotkdeorg.rdf';
	
	protected function setup($self) {
		$self->selected_nav = 'home';
	}
	
	public function index() {
		array_push($this->title, 'Welcome');
		$this->breadcrumbs[''] = 'Welcome!';
		
		$this->kde_feed = $this->update_dko_cache();
		$this->latest = Doctrine_Query::create()->from('Posts p')->limit(5)->execute();
	}
	
	public function init_author() {
		$auth = new Authors();
		$auth->name = 'Mike Wyatt';
		$auth->password = 'test';
		$auth->handle = 'mikew';
		// $auth->save();
	}
	
	protected function update_dko_cache() {
		$dko_cache = File::join(TMP_HOME, 'dko.rss');
		if(!is_file($dko_cache) || filemtime($dko_cache) < (time() - self::DKO_TTL)) {
			$feed = new SimpleXMLElement(self::DKO_URL, null, TRUE);
			$feed->asXML($dko_cache);
		}
		
		return new SimpleXMLElement(file_get_contents($dko_cache));
	}
}
