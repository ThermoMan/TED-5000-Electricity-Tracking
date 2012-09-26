<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
		<title>Chart</title>
    	<script type='text/javascript' src='lib/dygraph-combined.js'></script> <!-- Add charting library -->
	</head>
	<body>
<?php
/**
  *  This file expects to be included as an iframe with a passed parameter identifying which chart to show.
  *
  */

require_once 'common.php';

$show_chart = '2';
if( isset( $_GET['show_chart'] ) )
{ // Use chart ID if provided otherwise use 2
  $show_chart = $_GET['show_chart'];
}


connect_to_db();

/*
 * $sql = "SELECT date, FLOOR(GREATEST( watts/1000, -10 )) AS kW " .
 *
 * GREATEST() doesn't seem to do a darn thing.
 *   The idea was that the -10,000 would be replaced by -10.
 *   Both are incorrect, but -10,000 screws up the chart auto scaling more whhile -10 still shows the data bug.
 */

switch( $show_chart )
{
	case 2:
		$sql = "SELECT date, watts/1000 AS kW " .
					 "FROM elec__elec_usage " .
					 "ORDER BY date";
	break;
	case 3:
		$sql = "SELECT date, watts/1000 AS kW " .
					 "FROM elec__elec_usage " .
					 "ORDER BY date";
	break;
	case 4:
		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d %H:00' ) AS date, SUM( watts ) / 1000 AS kWh ".
					 "FROM elec__elec_usage ".
		//       "WHERE date >= '2012-08-25' AND date < '2012-08-27 01:00:00' ".
					 "GROUP BY DATE_FORMAT( date, '%Y-%m-%d %H:00' ) ".
					 "ORDER BY date";
	break;
	default;
	  return;	// There is no other default chart...
}

global $link;
$result = $link->query( $sql );
if( $result )
{
  $answer_string = "";
  $old_watts = 0;	// Only for 3 and 4
  while( $row = $result->fetch_object() )
  {
		switch( $show_chart )
		{
			case 2:
				if( $answer_string == "" )
				{ // Had to add third escape because of how the dynamic function is built later."\"
					$answer_string .= '"' . $row->date . ',' . floor( max($row->kW,-10) ) . '\\\n"';
				}
				else
				{
					$answer_string .= '+ "' . $row->date . ',' . floor( max($row->kW,-10) ) . '\\\n"';
				}
			break;
			case 3:
				if( $answer_string == "" )
				{
					$answer_string .= '"' . $row->date . ',0\\\n"';
				}
				else
				{
					$answer_string .= '+ "' . $row->date . ',' . round( min( max( $row->kW - $old_watts, 0 ), 0.2 ), 3 ) . '\\\n"';
				}
		    $old_watts = $row->kW;
			break;
			case 4:
				if( $answer_string == "" )
				{ // Really ought to omit the first data point so that the chart does not start at 0.
					$answer_string .= '"' . $row->date . ',0\\\n"';
				}
				else
				{
					$answer_string .= '+ "' . $row->date . ',' . round( min( max( ($row->kWh - $old_watts), 0), 500 ), 3 ) . '\\\n"';
				}
		    $old_watts = $row->kWh;
			break;
		}
  }
  $result->free();
}
?>

<!-- This is the code that draws the chart -->
<script type='text/javascript'>

var uniqueness = Math.floor( Math.random() * 1000000 );
var unique_div_name = 'id_' + uniqueness;
var div_tag_text = '<div id="'+unique_div_name+'" style="width:900px; height:450px;"></div>';

/** This is the div in which the chart will appear. The ID of the div
  * must be unique in both this file and in the page that includes this file.
	*
  */
document.write( div_tag_text );

var unique_function_name = 'fn_' + uniqueness;

var func = new Function( 'return function ' + unique_function_name + '(){return "Date,kW\\n" + <?php echo $answer_string; ?>;}');
var g = new Dygraph( document.getElementById( unique_div_name ), func(),
{
  labelsDivStyles: { border: '1px solid black' },
  title: 'Total accumulated kW usage',
  xlabel: 'Date',
  ylabel: 'kW'
});

/* Dump the data for inspection
var foo = '' + data2();
document.write( foo );
*/

</script>
	</body>
</html>