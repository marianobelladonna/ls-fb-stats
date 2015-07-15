<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$user = 'root';
$pass = '';
$batch_size = 1000;
try {
	$dbh = new PDO('mysql:host=localhost;dbname=livestrong', $user, $pass);
	$rows = $dbh->query("SELECT id FROM article WHERE comment_count IS NULL")->fetchAll();
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$batches = array_chunk($rows, $batch_size, true);
foreach ($batches as $batch) {
		$ids = [];
		foreach ($batch as $row) {
				array_push($ids, $row['id']);
		}

		$client = new Client([]);

		try {
			$response = $client->get('https://api.facebook.com/method/links.getStats', [
				'query' => [
					'urls' => implode(',', $ids),
					'format' => 'json',
				]
			]);

			//header('Content-Type: application/json');
			//echo $response->getBody();

			$data = json_decode($response->getBody(), true);
			//var_dump($data);

			foreach ($data as $fb_stats) {
				$sth = $dbh->prepare(
					"UPDATE article SET comment_count=:comment_count, share_count=:share_count, like_count=:like_count, total_count=:total_count, click_count=:click_count, commentsbox_count=:commentsbox_count, comments_fbid=:comments_fbid WHERE id=:id"
				);

				$sth->bindValue(':id', $fb_stats['url'], PDO::PARAM_STR);
				$sth->bindValue(':comments_fbid', $fb_stats['comments_fbid'], PDO::PARAM_STR);
				$sth->bindValue(':comment_count', $fb_stats['comment_count'], PDO::PARAM_INT);
				$sth->bindValue(':share_count', $fb_stats['share_count'], PDO::PARAM_INT);
				$sth->bindValue(':like_count', $fb_stats['like_count'], PDO::PARAM_INT);
				$sth->bindValue(':total_count', $fb_stats['total_count'], PDO::PARAM_INT);
				$sth->bindValue(':click_count', $fb_stats['click_count'], PDO::PARAM_INT);
				$sth->bindValue(':commentsbox_count', $fb_stats['commentsbox_count'], PDO::PARAM_INT);

				$sth->execute();
			}
		} catch (ClientException $e) {

		}
}

echo 'All done';
?>