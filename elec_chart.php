<?php

require_once 'common.php';

$show_chart = '1';
if( isset( $_GET['show_chart'] ) )
{ // Use chart ID if provided otherwise use 2
  $show_chart = $_GET['show_chart'];
}


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
		// This selects individual rows of data - each is one minute apart
		$sql = "SELECT date, ROUND( watts/1000 ) " .
					 "FROM elec__elec_usage " .
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 10 DAY " .
					 "ORDER BY date";
	break;
	case 22:
		// This selects individual rows of data - each is one minute apart
		$sql = "SELECT date, watts " .
					 "FROM elec__elec_usage " .
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 10 DAY " .
					 "ORDER BY date";
	break;

	case 4:
		// This aggregates rows of data - each is one hour of usage
		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d %H:00' ), ROUND(watts / 1000) ".
					 "FROM elec__elec_usage ".
		//       "WHERE date >= '2012-08-25' AND date < '2012-08-27 01:00:00' ".
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 10 DAY " .
"AND DATE_FORMAT( date, '%i' ) = '00' " .
					 "ORDER BY 1";
	break;

	case 44:
		// This aggregates rows of data - each is one hour of usage
//		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d %H:00' ), ROUND(watts) ".
		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d %H:%i' ), ROUND(watts) ".
					 "FROM elec__elec_usage ".
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 10 DAY " .
"AND DATE_FORMAT( date, '%i' ) IN ( '00', '15', '30', '45' )" .
					 "ORDER BY 1";
	break;

	case '5':
	case '55':
		// This aggregates rows of data - each is one hour of usage
		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d' ) AS date, -1 * watts/ 1000 AS kWh ".
					 "FROM elec__elec_usage ".
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 10 DAY " .
"AND DATE_FORMAT( date, '%H%i' ) = '0000' " .
					 "ORDER BY 1";
	break;
	case 3:
		// This selects individual rows of data - each is one minute apart (was originally to have a different date range than #2 above)
		$sql = "SELECT date, watts/1000 AS kW " .
					 "FROM elec__elec_usage " .
"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 30 DAY " .
					 "ORDER BY date";
	break;
	case '9':/* Formerly case 5 */
		// This aggregates rows of data - each is one day of usage
		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d' ) AS date, -1 * watts / 1000 AS kWh " .
//		$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d' ) AS date, watts / 1000 AS kWh " .
					 "FROM elec__elec_usage " .
//"WHERE date >= '2013-01-01' AND date < '2013-02-01' " .
//"WHERE date >= (SELECT DATE(MAX(date)) from elec__elec_usage) - INTERVAL 90 DAY " .
					 "ORDER BY 1";
	break;
	default;
	  return;	// There is no other default chart...
}
$querySelect = $pdo->prepare( $sql );
$querySelect->execute();

//if( $result )
//{
  $chartString = '';
	$tableString = '';
  $previousWatts = 0;
	$minVal = 1000000;
	$maxVal = 1;
	while( $row = $querySelect->fetch( PDO::FETCH_NUM ) )
  {
		switch( $show_chart )
		{
			case 2:
			case 4:
				$timeStamp = $row[0];
				$newData = $row[1];
				$compute = $newData;

				if( $chartString == "" )
				{
					$add = '';
				}
				else
				{
					$add = '+';
				}

				$chartString .= $add . '"' . $timeStamp . ',' . $compute . '\\\n"';

 				// Had to add third escape '\\\' because of how the dynamic function is built later.
 				$tableString = $tableString . "<tr><td>$row[0]</td><td>$row[1]</td></tr>";

				if( $compute > $maxVal ) $maxVal = $compute;	// Start from low and work our way up
				if( $compute < $minVal ) $minVal = $compute;
			break;

			case 22:
			case 44:
				$timeStamp = $row[0];
				$newData = $row[1];

				if( $chartString == "" )
				{
					$add = '';
					$compute = 0;
				}
				else
				{
					$add = '+';
					$compute = $newData - $previousWatts;
				}

				$chartString .= $add . '"' . $timeStamp . ',' . $compute . '\\\n"';

		    $previousWatts = $newData;
				$tableString = $tableString . "<tr><td>$timeStamp</td><td>$compute</td></tr>";

				if( $compute > $maxVal ) $maxVal = $compute;	// Start from low and work our way up
				if( $compute < $minVal ) $minVal = $compute;
			break;


/*
			case 3:
				if( $chartString == "" )
				{
					$chartString .= '"' . $row[0] . ',0\\\n"';
				}
				else
				{
					$chartString .= '+ "' . $row[0] . ',' . round( min( max( $row[1] - $previousWatts, 0 ), 0.2 ), 3 ) . '\\\n"';
				}
		    $previousWatts = $row[1];
			break;
			case 4:
				if( $chartString == "" )
				{ // Really ought to omit the first data point so that the chart does not start at 0.
					$chartString .= '"' . $row[0] . ',0\\\n"';
				}
				else
				{
					$chartString .= '+ "' . $row[0] . ',' . round( min( max( ($row[1] - $previousWatts), 0), 500 ), 3 ) . '\\\n"';
				}
		    $previousWatts = $row[1];
			break;
			case 5:
				if( $chartString == "" )
				{
					$chartString .= '"' . $row[0] . ',0\\\n"';
		}
				else
				{
					$chartString .= '+ "' . $row[0] . ',' . round( $row[1] - $previousWatts, 3 ) . '\\\n"';
  }
		    $previousWatts = $row[1];
			break;
			case 9:
				if( $chartString == "" )
				{
					$chartString .= '"' . $row[0] . ',0\\\n"';
				}
				else
				{
					$chartString .= '+ "' . $row[0] . ',' . $row[1] - $previousWatts . '\\\n"';
				}
		    $previousWatts = $row[1];
			break;
*/
			default:
				return;
		}
  }
//  $result->free();
//}
?>


<!DOCTYPE html>
<!--
  --  This file expects to be included as an iframe with a passed parameter identifying which chart to show.
  --
  -->
<html>
	<head>
		<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
		<title>Chart</title>
    	<script type='text/javascript' src='/common/js/dygraph-combined.js'></script> <!-- Add charting library -->
		<script type='text/javascript'>
			var uniqueness;
			var unique_div_name;
			var div_tag_text;
			var func;

			function setup()
			{
				uniqueness = Math.floor( Math.random() * 1000000 );
				unique_div_name = 'id_' + uniqueness;
				div_tag_text = '<div id="'+unique_div_name+'" style="width:900px; height:450px;"></div>';

				func = new Function( 'return function chartMe(){return "Date,kW\\n" + <?php echo $chartString; ?>;}');

				/** This is the div in which the chart will appear. The ID of the div
  * must be unique in both this file and in the page that includes this file.
	*
  */
				document.write( div_tag_text );
			}

			function showChart()
			{
				/**
					* This is the code that actally draws the chart
					*
					* If I replace the variable g with an array element added to a
					* new global arracy maybe I can ditch the iframes and just use divs?
					*/


				var g<?php echo $show_chart; ?> = new Dygraph( document.getElementById( unique_div_name ), func(),
				{
  labelsDivStyles: { border: '1px solid black' },
					title: 'kW usage',
					valueRange: [<?php echo $minVal; ?>, <?php echo $maxVal; ?>],
  xlabel: 'Date',
  ylabel: 'kW'
				});
			}

			function showTable()
			{
				//document.getElementById( 'tabtab' ).innerHTML = 'I am chart #<?php echo $show_chart; ?>';
				var foo = document.getElementById( 'tabtab' );

				foo.innerHTML = 'Y range is from <?php echo $minVal; ?> to <?php echo $maxVal; ?>'
				foo.innerHTML = foo.innerHTML + '<br><table><?php echo $tableString ?></table>'
			}
		</script>
		<style>
		table, td
		{
			border: 1px solid black;
			border-collapse: collapse;
		}
		</style>
	</head>

	<body>
		<!-- The location of this script in the flow is where teh chart will appear on the page -->
		<script type='text/javascript'>
			/* alert( "I am chart <?php echo $show_chart; ?> and my SQL is <?php echo $sql; ?>" ); */
			/** Dump the data for inspection */
			//var foo = '' + data2();
			//document.write( 'Look here ->>' + foo + '<<-' );
			setup();

			showChart();

		</script>
		<div id='tabtab'>Table data will appear here</div>
		<script type='text/javascript'>
			showTable();
		</script>

	</body>
</html>