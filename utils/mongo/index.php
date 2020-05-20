<?php
//echo phpinfo();exit;
//echo __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/vendor/autoload.php";

$m = new MongoDB\Client();

$db = $m->local;
$collection = $db->posts;

$post = array(
	'title' => 'What is MongoDB',
	'content' => 'MongoDB is a document database that provides high performance...',
	'saved_at' => new MongoDate(),
);
$collection->insert($post);

// Now retrieve
$cursor = $collection->find();

// iterate through the results
foreach ($cursor as $document) {
	pr($document);
}

function pr($a, $echo = 1) {
	$o = '<pre>';
	$o .= print_r($a, true);
	$o .= '</pre>';

	if ($echo) {
		echo $o;
	}
	return $o;
}

?>