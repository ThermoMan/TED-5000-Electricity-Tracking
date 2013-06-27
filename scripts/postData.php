<?php
require dirname(__FILE__).'/../common.php';

// Look here
// http://www.theenergydetectiveforums.com/index.php/topic,4.0.html

//$rootDir = '~/elec/';
//$logDir =  $rootDir . 'logs/';
//$logFile = $logDir . 'elec.' . date( 'Y.m.d' ) . '.log';
$fp = fopen( 'testfile2', 'a' );
//$fp = fopen( $logFile, 'a' );

$text_string = 'default_value';
//if( isset( $_SERVER['QUERY_STRING'] ) )
//if( isset( $HTTP_RAW_POST_DATA ) )
//{
	$text_string = file_get_contents("php://input");
//	$text_string = $HTTP_RAW_POST_DATA;
//	$text_string = urldecode($_SERVER['QUERY_STRING']);
fwrite( $fp, "\n------------------\n" );
fwrite( $fp, $text_string );
fwrite( $fp, "\n------------------\n" );
//}
//else
//{
//fwrite( $fp, "Expected variable was not set!" );
//echo( $_SERVER['QUERY_STRING'] );
//goto broken;
//}

/**
  * Capture the raw input and put into an XML structure.
  *
  * Here are two examples of input.  The first is the trivial case and the second is more complex.
	*  Both are REAL data...
	*
	* <ted5000 GWID="111111" auth="123456789">
	* <MTU ID="104F7C" type="0">
	* <cumulative timestamp="1346200560" watts="15640652" rate="0.06617"/>
	* </MTU>
	* </ted5000>
	*
	*
	* <ted5000 GWID="111111" auth="123456789">
	* <MTU ID="104F7C" type="0">
	* <cumulative timestamp="1346200620" watts="15640696" rate="0.06617"/>
	* <cumulative timestamp="1346200680" watts="15640739" rate="0.06617"/>
	* <cumulative timestamp="1346200740" watts="15640783" rate="0.06617"/>
	* <cumulative timestamp="1346200800" watts="15640827" rate="0.06617"/>
	* <cumulative timestamp="1346200860" watts="15640871" rate="0.06617"/>
	* <cumulative timestamp="1346200920" watts="15640915" rate="0.06617"/>
	* <cumulative timestamp="1346200980" watts="15640959" rate="0.06617"/>
	* <cumulative timestamp="1346201040" watts="15641003" rate="0.06617"/>
	* <cumulative timestamp="1346201100" watts="15641047" rate="0.06617"/>
	* <cumulative timestamp="1346201160" watts="15641091" rate="0.06617"/>
	* <cumulative timestamp="1346201220" watts="15641135" rate="0.06617"/>
	* <cumulative timestamp="1346201280" watts="15641179" rate="0.06617"/>
	* <cumulative timestamp="1346201340" watts="15641223" rate="0.06617"/>
	* </MTU>
	* </ted5000>
	*
	*
  */


$xml = new SimpleXMLElement( $text_string );


try
{
	$sql = "INSERT INTO {$dbConfig['table_prefix']}elec_usage( date, watts, rate ) VALUES ( FROM_UNIXTIME(?), ?, ? )";
	$queryRunInsert = $pdo->prepare( $sql );
}
catch( Exception $e )
{
	fwrite( $fp, "\nSQL PREP ERROR\n" );
  doError( 'DB Exception: ' . $e->getMessage() );
}


foreach( $xml->children() as $child )
{
    foreach( $child->children() as $subchild )
    {
      foreach( $subchild->attributes() as $attr2 )
      {
        switch( $attr2->getName() )
        {
				case 'timestamp':
            //echo "<br>Found match for $attr2->getName() of $attr2";
            $timestamp = $attr2;
            //echo "<br>Found match for timestamp of $timestamp";
          break;
				case 'watts':
            $watts = $attr2;
          break;
				case 'rate':
            $rate = $attr2;
          break;
          //default:
            //echo "<br>No match for $attr2->getName()";
        }
        //echo '{{{'. $attr2->getName().', '.$attr2. '}}}';
      }

$sql = "INSERT INTO {$dbConfig['table_prefix']}elec_usage ( date, watts, rate ) VALUES ( FROM_UNIXTIME($timestamp), $watts, $rate )";
fwrite( $fp, "SQL is $sql (THIS SQL IS NOT USED!!!!!)\n" );
//$result = mysql_query( $sql );

    $queryRunInsert->execute( array( $timestamp, $watts, $rate ) );
    }
}

broken:
fwrite( $fp, "\n===================\n" );
fclose( $fp );

?>