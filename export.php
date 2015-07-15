<?php

require 'vendor/autoload.php';
use League\Csv\Writer;

$user = 'root';
$pass = '';
$dbh = new PDO('mysql:host=localhost;dbname=livestrong', $user, $pass);

//we fetch the info from a DB using a PDO object
$sth = $dbh->prepare(
	"SELECT id, comment_count FROM article"
);
//because we don't want to duplicate the data for each row
// PDO::FETCH_NUM could also have been used
$sth->setFetchMode(PDO::FETCH_ASSOC);
$sth->execute();

//we create the CSV into memory
$csv = Writer::createFromFileObject(new SplTempFileObject());

//we insert the CSV header
$csv->insertOne(['id', 'comments_count']);

// The PDOStatement Object implements the Traversable Interface
// that's why Writer::insertAll can directly insert
// the data into the CSV
$csv->insertAll($sth);

// Because you are providing the filename you don't have to
// set the HTTP headers Writer::output can
// directly set them for you
// The file is downloadable
$csv->output('comments_count.csv');
die;

?>