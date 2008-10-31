<?php
require_once File::join(CORE_LIB_HOME, 'mime.class.php');

// CoreMime::add('html', '.phtml', 'application/xhtml+xml');
CoreMime::add('html', '.phtml');
CoreMime::add('markdown', '.markdown');
CoreMime::add('mobile', '.mobile.phtml');
// CoreMime::add('rss', '.rss', 'application/rss+xml');
CoreMime::add('rss', '.rss.php', 'application/rss+xml');