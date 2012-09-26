<?php

// Server and port for your MySQL instance
$host = "localhost";
// ID with permission to access the thermostat database
$user = "user";
$pass = "password";
// Database name.  Default is "electric"
$db = "electric";
// Prefix to attach to all table/procedure names to make unique in unknown environment.
$table_prefix = "elec__";

$timezone = "America/Chicago";


/*
 * The following ought to be stored in the DB with a config page
 *
 */
$send_end_of_day_email = "Y";     // "Y" or "N"
$send_eod_email_time = "0800";    // format is HHMM (24-hour) as text string
$send_eod_email_address = "your_address@wherever.com";
$send_eod_email_smtp = "";
$send_eod_email_pw = "";
/*
 * Add a cron job at the end of each hour to see if an alarm condition has been met.  Then email.
 */
?>