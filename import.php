<?php

require 'vendor/autoload.php';
use League\Csv\Reader;

$user = 'root';
$pass = '';
$dbh = new PDO('mysql:host=localhost;dbname=livestrong', $user, $pass);

try {
	$sth = $dbh->prepare(
		"INSERT INTO article (id) VALUES (:id)"
	);

	$csv = Reader::createFromPath('csv/livestrong_ids.csv');
	$csv->setOffset(1); //because we don't want to insert the header

	$nbInsert = $csv->each(function ($row) use (&$sth) {
		//Do not forget to validate your data before inserting it in your database
		$sth->bindValue(':id', $row[0], PDO::PARAM_STR);
		return $sth->execute(); //if the function return false then the iteration will stop
	});

} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

echo 'All done';

?>