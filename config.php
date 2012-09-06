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
 * But before it can be remotely configurable there has to be an ID/PW system for some tabs
 * I guess a tab woudl have to contain an iframe and the iframe has a page that checks permissions.
 */
$send_end_of_day_email = "Y";     // "Y" or "N"
$send_eod_email_time = "0800";    // format is HHMM (24-hour) as text string
$send_eod_email_address = "your_address@wherever.com";
$send_eod_email_smtp = "";
$send_eod_email_pw = "";
/*
 * Add a check at the end of the one per minute task to see if time now == $send_eod_email_time
 * The better way woudl be to use Windows Scheduler to create a task to run at the named time
 *  In order to implement that, need to store Windows ID and Password to be able to write the
 *  command line necesary to change the existing schedule.  Those two items shoudl be in this
 *  config file on the theory that the file system is slightly more secure than a DB that is
 *  already available online.  Make sure to use a non-privilaged account.
 */
?>