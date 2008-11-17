<?php
$feed->title = 'KDE Games';
$feed->description = 'The latest news in KDE Games';
$feed->link = $context->url_for(array(
	'controller' => 'news',
	'action' => 'index',
	'format' => 'html',
	'qualified' => true
));

foreach($context->posts AS $item) {
	$feed->item = new RSSItem(array(
		'title' => $item->title,
		'link' => $context->url_for($context->post_path($item, true)),
		'description' => $item->body,
		'pubDate' => date('r', strtotime($item->created_at))
	));
}