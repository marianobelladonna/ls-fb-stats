# ls-fb-stats
Get stats for livestrong articles from fb api

##Prerequisites

- Install dependencies using composer (https://getcomposer.org/):

`composer install`

- Create mysql database to store stats: see `livestrong.sql`

##Scripts

*import.php* dumps all article urls in csv/livestrong_ids.csv into the database table.

*getcomments.php* goes query fb api for each url and saves its stats into the database.

*export.php* dumps the content of the table out to a csv file.

*getstatus.php* gets some sample urls from the database and outputs the response's status for each request.

##Disclaimer

Just a bunch of one time scripts to get things done quickly.
Uploading just for future reference.
Contains known security holes and performance issues.