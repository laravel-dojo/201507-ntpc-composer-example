<?php

namespace App\Helpers;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class DomHelper
{
	public function parse(Crawler $crawler)
	{
		$contents = [];

		$tables = $crawler->filter('table.table-B01-table')->each(function (Crawler $node, $index) {
			return $node->filter('table.C-tableC-table table tr')->each(function (Crawler $node, $index) {
				return $node->html();
			});
		});

		$rows = $tables[4];

		foreach($rows as $index => $row) {
			if ($index < 15) {
				$domCrawler = new Crawler;
				$domCrawler->add($row);
				$content = $domCrawler->filter('a')->each(function (Crawler $node, $index) {

					$link = $node->attr('href');

					$rawContent = utf8_decode($node->html());
					$rawContent = strip_tags($rawContent);
					$rawContent = trim($rawContent);
					$rawContent = preg_replace("/\r|\n/", "", $rawContent);

					return [
						$link,
						$rawContent,
					];
				});

				$contents[$index]['link'] = str_replace('...', '', $content[0][0]);
				$contents[$index]['title'] = str_replace('...', '', $content[0][1]);
				$contents[$index]['from'] = $content[1][1];
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

		return $contents;
	}
}