<?php

require '../bootstrap.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client;
$crawler = $client->request('GET', 'http://www.slps.ntpc.edu.tw/');

$tables = $crawler->filter('table.table-B01-table')->each(function (Crawler $node, $index) {
	return $node->html();	
});

echo '<pre>';
var_dump($tables);
echo '</pre>';