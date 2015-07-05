<?php

require '../bootstrap.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client;
$crawler = $client->request('GET', 'http://www.slps.ntpc.edu.tw/');

$tables = $crawler->filter('table.table-B01-table')->each(function (Crawler $node, $index) {
	return $node->filter('table.C-tableC-table table tr')->each(function (Crawler $node, $index) {
		return $node->html();
	});
});

$rows = $tables[4];

echo '<pre>';
var_dump($rows);
echo '</pre>';