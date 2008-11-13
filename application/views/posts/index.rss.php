<?php
$feed->title = 'KDE Games';
$feed->description = 'The latest news in KDE Games';
$feed->link = $helpers->url_for(array(
	'controller' => 'news',
	'action' => 'index',
	'format' => 'html',
	'qualified' => true
));

foreach($__posts AS $item) {
	$feed->item = new RSSItem(array(
		'title' => $item->title,
		'link' => $helpers->url_for($helpers->post_path($item, true)),
		'description' => $item->body,
		'pubDate' => date('r', strtotime($item->created_at))
	));
}