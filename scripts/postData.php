<?php
require "../common.php";
// Look here
// http://www.theenergydetectiveforums.com/index.php/topic,4.0.html

$text_string = $HTTP_RAW_POST_DATA;

//echo "123456";

$fp = fopen( "testfile", "a" );
fwrite( $fp, "\n===================\n" );
fwrite( $fp, "$text_string" );
fwrite( $fp, "\n------------------\n" );

$xml = new SimpleXMLElement( $text_string );

connect_to_db();
global $link;
$stmt = $link->stmt_init();

if( $stmt->prepare( "INSERT INTO " . $table_prefix . "elec_usage ( date, watts, rate ) VALUES ( FROM_UNIXTIME(?), ?, ? )" ) )
{
  foreach( $xml->children() as $child )
  {
    foreach( $child->children() as $subchild )
    {
      foreach( $subchild->attributes() as $attr2 )
      {
        switch( $attr2->getName() )
        {
          case "timestamp":
            //echo "<br>Found match for $attr2->getName() of $attr2";
            $timestamp = $attr2;
            //echo "<br>Found match for timestamp of $timestamp";
          break;
          case "watts":
            $watts = $attr2;
          break;
          case "rate":
            $rate = $attr2;
          break;
          //default:
            //echo "<br>No match for $attr2->getName()";
        }
        //echo '{{{'. $attr2->getName().', '.$attr2. '}}}';
      }
$sql = "INSERT INTO " . $table_prefix . "elec_usage ( date, watts, rate ) VALUES ( FROM_UNIXTIME($timestamp), $watts, $rate )";
fwrite( $fp, "SQL is $sql (THIS SQL IS NOT USED!!!!!\n)" );
//$result = mysql_query( $sql );

      $stmt->bind_param( "sss", $timestamp, $watts, $rate );
      $stmt->execute();
    }
  }
}
else
{
  log_it( "No joy prepping SQL" );
}


fwrite( $fp, "\n===================\n" );
fclose( $fp );

$stmt->close();
disconnect_from_db();
?>