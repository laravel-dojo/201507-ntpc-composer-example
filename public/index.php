<?php

require '../bootstrap.php';

use Goutte\Client;

$client = new Client;
$crawler = $client->request('GET', 'http://www.slps.ntpc.edu.tw/');

echo $crawler->filter('table')->count();