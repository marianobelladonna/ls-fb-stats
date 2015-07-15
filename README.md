# ls-fb-stats
Get stats for livestrong articles from fb api

Prerequisites

1) Install dependencies using composer (https://getcomposer.org/):

composer Install

2) Create mysql database to store stats: see livestrong.sql

Scripts

import.php dumps all article urls in csv/livestrong_ids.csv into the database table.

getcomments.php goes query fb api for each url and save its stats into the database.

export.php dumps the content of the table out to a csv file.

getstatus.php gets some sample url from the database and outputs the response's status for each request.
