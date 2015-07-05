<?php

require '../bootstrap.php';

date_default_timezone_set('Asia/Taipei');

use App\Helpers\DomHelper;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Support\Collection;

$baseUrl = 'http://www.slps.ntpc.edu.tw';

$client = new Client;
$crawler = $client->request('GET', $baseUrl);

$domHelper = new DomHelper;
$contents = $domHelper->parse($crawler);

$news = new Collection($contents);
$orderByRankNews = $news->sortByDesc(function ($article) {
	return $article['rank'];
});

Carbon::setLocale('zh-TW');
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
			<?php foreach($orderByRankNews->values()->all() as $index => $article): ?>
				<tr>
					<td class="text-center"><?=$index+1?></td>
					<td>
						<a href="<?=$baseUrl.$article['link']?>" target="_blank">
							<?=$article['title']?>
						</a>
					</td>
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