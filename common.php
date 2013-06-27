<?php
require_once 'config.php';
require_once 'lib/e_lib.php';

// Future logging method
function logIt( $msg )
{
   echo $msg . "\n";
}

// Future logging method
function doError( $msg )
{
   echo $msg . "\n";
   file_put_contents( 'php://stderr', $msg . "\n" );
}


// Common code that should run for EVERY page follows here

global $timezone;

// Set timezone for all PHP functions
date_default_timezone_set( $timezone );

// Always connect to the database, don't wait for a request to connect
$pdo = new PDO( $dbConfig['dsn'], $dbConfig['username'], $dbConfig['password'] );
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$pdo->exec( "SET time_zone = '$timezone'" );	// Set timezone for all MySQL functions


function bobby_tables()
{
  $filename = './images/exploits_of_a_mom.png';
  $handle = fopen( $filename, 'r' );
  $contents = fread( $handle, filesize($filename) );
  fclose( $handle );
  echo $contents;
}


function validate_date( $some_date )
{
  $date_pattern = '/[2]{1}[0]{1}[0-9]{2}-[0-9]{2}-[0-9]{2}/';
  if( !preg_match( $date_pattern, $some_date ) )
  {
    bobby_tables();
    return false;
  }
  return true;
}

$error_log_file = 'errors.log';
function bug( $error_string )
{
  $bug_fp = fopen( $error_log_file, 'a' );
  fwrite( $bug_fp, '\n===================' );
  fwrite( $bug_fp, strftime( '%F %T %z' ) . ' ' . $error_string );
  fwrite( $bug_fp, '\n===================' );
  fclose( $bug_fp );
}


// Common code that should run for EVERY page follows here

global $timezone;
date_default_timezone_set( $timezone );


?>