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
	
	public function link_to_news($item) {
		return $this->link_to_unless_current($item->title, array('controller' => 'news', 'action' => 'show', 'key' => $item->key));
	}
}