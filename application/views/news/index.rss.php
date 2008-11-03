<?php
$feed->title = 'KDE Games';
$feed->description = 'The latest news in KDE Games';
$feed->link = Helpers::url_for(array(
	'controller' => 'news',
	'action' => 'index',
	'format' => 'html',
	'qualified' => true
));

foreach($this->posts AS $item) {
	$feed->item = new RSSItem(array(
		'title' => $item->title,
		'link' => Helpers::url_for(Helpers::post_path($item, true)),
		'description' => $item->body,
		'pubDate' => date('r', strtotime($item->created_at))
	));
}