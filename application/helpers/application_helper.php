<?php
class ApplicationHelper extends CoreHelper {
	public function write_title() {
		return implode(' - ', $this->locals->title);
	}
	
	public function start_em($id = '') {
		// <div class="em" id="whatsnew"><div class="top"><div class="right"><div class="bottom"><div class="left">
		$divs = '<div class="em"';
		if(!empty($id))
			$divs .= ' id="' . $id . '"';
		
		$divs .= '><div class="top"><div class="right"><div class="bottom"><div class="left">';
		return $divs;
	}
	
	public function end_em() {
		return '</div></div></div></div><div class="tl"></div><div class="tr"></div><div class="br"></div><div class="bl"></div></div>';
	}
	
	public function link_to_post($item) {
		return $this->link_to_unless_current($item->title, $this->post_path($item));
	}
	public function post_path($item, $for_rss = false) {
		$url_options = array(
			'controller' => 'news',
			'action' => 'show',
			'key' => $item->key
		);
		
		if($for_rss)
			$url_options['qualified'] = true;
		
		return $url_options;
	}
	
	public function link_to_controllers() {
		$list = func_get_args();
		$generated = '';
		
		foreach($list AS $pair) {
			$parts = explode(':', $pair);
			$controller = $parts[0];
			$url = empty($parts[1]) ? '/' . $controller : $parts[1];
			
			$attributes = array('id' => $controller);
			if($controller == $this->locals->selected_nav)
				$attributes['class'] = 'selected';
			
			$generated .= $this->tag('li', $attributes, $this->link_to(Inflector::humanize($controller), $url)) . "\n";
		}
		
		return $generated;
	}
	
	public function lay_breadcrumbs() {
		$parts = explode('/', WWW_PATH);
		$crumbs = array('KDE Games Home');
		array_shift($parts);
		
		for($i = 0; $i < count($parts); $i++) {
			$key = $parts[$i];
			$human = empty($this->locals->breadcrumbs[$key]) ? Inflector::titleize($key) : $this->locals->breadcrumbs[$key];
			
			if($i == count($parts) - 1)
				$human = $this->tag('span', '', $human);
			
			array_push($crumbs, $human);
		}
		
		return implode(' &raquo; ', $crumbs);
	}
}
