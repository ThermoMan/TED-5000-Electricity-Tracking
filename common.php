<?php
require_once 'config.php';
require_once 'lib/e_lib.php';

function bobby_tables()
{
  $filename = './images/exploits_of_a_mom.png';
  $handle = fopen( $filename, 'r' );
  $contents = fread( $handle, filesize($filename) );
  fclose( $handle );
  echo $contents;
}

function connect_to_db()
{
  // Using the keyword global really points out that this might be a bad idea.  Should these be buried in a class for safety?
  global $host;
  global $user;
  global $pass;
  global $db;
  global $link;

  // Only open a new connection if it's not already open
  if( !isset( $link ) )
  {
    // Using 'p:' tells the DB that I want a persistent connection - so that I'm not constantly opening and closing connections
    $link = new mysqli( 'p:'.$host, $user, $pass, $db );
    if( $link->connect_error )
    {
      die( 'Could not connect: <no error message provided to hackers>'  );
    }

    global $timezone;
    mysqli_query( $link, 'SET time_zone = "$timezone"' );
  }
}

function disconnect_from_db()
{
  global $link;
  $link->close();
  unset( $link );
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