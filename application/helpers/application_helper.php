<?php
class ApplicationHelper extends CoreHelper {
	public static function write_title() {
		return implode(' - ', CoreContext::get('title'));
	}
	
	public static function start_em($id = '') {
		$divs = '<div class="em"';
		if(!empty($id))
			$divs .= ' id="' . $id . '"';
		
		$divs .= '><div class="top"><div class="right"><div class="bottom"><div class="left">';
		return $divs;
	}
	
	public static function end_em() {
		return '</div></div></div></div><div class="tl"></div><div class="tr"></div><div class="br"></div><div class="bl"></div></div>';
	}
	
	public static function link_to_post($item) {
		return CoreHelper::instance()->link_to_unless_current($item->title, self::post_path($item));
	}
	public static function post_path($item, $for_rss = false) {
		$url_options = array(
			'controller' => 'posts',
			'action' => 'show',
			'key' => $item->key
		);
		
		if($for_rss)
			$url_options['qualified'] = true;
		
		return $url_options;
	}
	
	public static function link_to_controllers() {
		$list = func_get_args();
		$generated = '';
		
		foreach($list AS $pair) {
			$parts = explode(':', $pair);
			$controller = $parts[0];
			$url = empty($parts[1]) ? '/' . $controller : $parts[1];
			
			$attributes = array('id' => $controller);
			if($controller == CoreContext::get('selected_nav'))
				$attributes['class'] = 'selected';
			
			$generated .= CoreHelper::instance()->tag('li', $attributes, CoreHelper::instance()->link_to(Inflector::humanize($controller), $url)) . "\n";
		}
		
		return $generated;
	}
	
	public static function lay_breadcrumbs() {
		$parts = explode('/', WWW_PATH);
		$crumbs = array('KDE Games Home');
		array_shift($parts);
		
		for($i = 0; $i < count($parts); $i++) {
			$key = $parts[$i];
			$human = empty(CoreContext::instance()->breadcrumbs[$key]) ? Inflector::titleize($key) : CoreContext::instance()->breadcrumbs[$key] ;
			
			if($i == count($parts) - 1)
				$human = CoreHelper::instance()->tag('span', '', $human);
			
			array_push($crumbs, $human);
		}
		
		return implode(' &raquo; ', $crumbs);
	}
	
	public static function in_admin() {
		return !(strpos(CoreHelper::instance()->url_for(), '/admin/') === FALSE);
	}
	
	public static function field_summary($summary) {
		return CoreHelper::instance()->tag('span', array(
			'class' => 'summary'
		), $summary);
	}
	
	public static function write_flash($for) {
		if(isset(CoreContext::get('flash')->{$for})) {
			return self::instance()->tag('div', array(
				'id' => "flash_{$for}",
				'class' => 'flash'
			), self::instance()->tag('strong', '', Inflector::titleize($for)) . CoreContext::get('flash')->{$for});
		}
	}
	
	public static function end_admin_form($form, $object, $column_for_delete) {
		$plural = String::lowercase(get_class($object));
		$singular = Inflector::singularize($plural);
		
		$buttons = array(
			$form->submit(),
			$form->reset(),
			self::instance()->link_to('Cancel', "admin/{$plural}")
		);
		
		$generated = self::instance()->tag('p', '', implode(' &mdash; ', $buttons));
		$generated .= self::instance()->end_form_tag();
		
		if($object->exists()) {
			$generated .= self::instance()->button_to('Delete', array("delete_admin/{$singular}", array(
				'id' => $object->{$column_for_delete}
			)), array(
				'method' => 'delete',
				'confirm' => 'Are you sure?\n\nThis cannot be undone'
			));
		}
		
		return $generated;
	}
}
