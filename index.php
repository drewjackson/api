<?php
$url = "http://en.wikipedia.org/w/api.php?action=feedrecentchanges&limit=100";
$html = file_get_contents($url);
$html = str_replace("–", "-",$html);
$varset = isset($_GET['start']);
$var = $_GET['start'];
$xml= simplexml_load_string($html) or die("Error: Cannot create object");
echo "Updated: " . $xml->channel->lastBuildDate . " | <hr>\n";
$list = array();
foreach ( $xml->channel->item as $item) {
	$pubDate = explode(' ', substr($item->pubDate, 0, -4));
	if (date('H:i:s', strtotime($var)) < date('H:i:s', strtotime($pubDate[4])) || !$varset) {
		$title = $item->title;
		if (strpos($title,'Talk:') === false && strpos($title,'User talk:') === false && strpos($title, 'Wikipedia talk:') === false) {
			array_push($list, $pubDate[4] . " ||| " . strlen($item->description) . " ||| $title");
		}
	}
} 
$list = array_reverse($list);
echo implode("<br>\n", $list);
?>
