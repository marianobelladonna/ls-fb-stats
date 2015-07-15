<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

$user = 'root';
$pass = '';
$dbh = new PDO('mysql:host=localhost;dbname=livestrong', $user, $pass);

//we fetch the info from a DB using a PDO object
$sth = $dbh->prepare(
	"SELECT id FROM article ORDER BY comment_count DESC limit 30"
);
//because we don't want to duplicate the data for each row
// PDO::FETCH_NUM could also have been used
$sth->setFetchMode(PDO::FETCH_ASSOC);
$sth->execute();
$urls = $sth->fetchAll();

foreach ($urls as $url) {
	$client = new Client([]);
	try {
		$response = $client->get($url['id'], ['allow_redirects' => false]);
		echo "{$url['id']}: {$response->getStatusCode()}<br/>";
	} catch (ClientException $e) {
		echo "{$url['id']}: client exception<br/>";
	}
}

echo 'All done';
?>