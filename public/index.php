<?php

require '../bootstrap.php';

date_default_timezone_set('Asia/Taipei');

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

$client = new Client;
$crawler = $client->request('GET', 'http://www.slps.ntpc.edu.tw/');

$tables = $crawler->filter('table.table-B01-table')->each(function (Crawler $node, $index) {
	return $node->filter('table.C-tableC-table table tr')->each(function (Crawler $node, $index) {
		return $node->html();
	});
});

$rows = $tables[4];

$contents = [];

foreach($rows as $index => $row) {
	if ($index < 15) {
		$domCrawler = new Crawler;
		$domCrawler->add($row);
		$content = $domCrawler->filter('a')->each(function (Crawler $node, $index) {

			$rawContent = utf8_decode($node->html());
			$rawContent = strip_tags($rawContent);
			$rawContent = trim($rawContent);
			$rawContent = preg_replace("/\r|\n/", "", $rawContent);

			return $rawContent;
		});

		$contents[$index]['title'] = str_replace('...', '', $content[0]);
		$contents[$index]['from'] = $content[1];
	}
}

foreach($rows as $index => $row) {
	if ($index < 15) {
		$rawContent = explode('</a>', $row);

		$step1 = str_replace(')</span>', '', $rawContent[2]);
		$step1 = str_replace('</td>', '', $step1);
		$step1 = preg_replace("/\r|\n/", "", $step1);

		$step2 = explode('點閱率', $step1);
		
		$date = explode('/', $step2[0]);

		$contents[$index]['date'] = Carbon::create((int) mb_substr($date[0], 1, mb_strlen($date[0])), (int) $date[1], (int) $date[2], 0, 0, 0);
		$contents[$index]['rank'] = (int) $step2[1];
	}
}

// echo '<pre>';
// var_dump($contents);
// echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>最新消息 | 新北市樹林區樹林國民小學</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
</head>
<body>
	<div class="container">

		<div class="page-header">
			<h1>新北市樹林區樹林國民小學</h1>
			<p class="lead">最新消息一覽表</p>
		</div>

		<table class="table table-hover">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th width="800">標題</th>
					<th class="text-center">處室</th>
					<th>發佈於</th>
					<th class="text-center">點閱率</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($contents as $index => $article): ?>
				<tr>
					<td class="text-center"><?=$index+1?></td>
					<td><?=$article['title']?></td>
					<td class="text-center"><?=$article['from']?></td>
					<td><?=$article['date']->diffForHumans()?></td>
					<td class="text-center"><?=$article['rank']?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

    </div>
</body>
</html>