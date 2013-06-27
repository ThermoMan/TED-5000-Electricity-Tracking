<?php

// Database connection parameters
$dbConfig = array(
	'dsn'          => 'mysql:host=localhost:port=3306;dbname=electric',
	'username'     => 'user',
	'password'     => 'password',
	'table_prefix' => 'elec__'             // Prefix to attach to all table/procedure names to make unique in unknown environment.
);


/**
	* Really need to have timezone for each location so that all data is stored in the 'local' zone.
	* At present this is used to force the servers (php procesor, web server, DB server) to think they
	*  are in the same timezone as the location of all the thermostats.
	*
	* If you are using a system that does not understand timezones (for example Synology NAS) or you are
	*  using it in a 100% local environment
	* $timezone = 'SYSTEM';
	*/
$timezone = 'America/Los_Angeles';


/**
  * The following ought to be stored in the DB with a config page
  *
  */
$send_end_of_day_email = 'Y';     // 'Y' or 'N'
$send_eod_email_time = '0800';    // format is HHMM (24-hour) as text string
$send_eod_email_address = 'your_address@wherever.com';
$send_eod_email_smtp = '';
$send_eod_email_pw = '';
/**
  * Add a cron job at the end of each hour to see if an alarm condition has been met.  Then email.
  */
?>