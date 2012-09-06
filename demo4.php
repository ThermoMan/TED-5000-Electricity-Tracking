<?php
require_once "common.php";

connect_to_db();

$sql = "SELECT DATE_FORMAT( date, '%Y-%m-%d %H:00' ) AS date, SUM( watts ) / 1000 AS kWh ".
       "FROM elec__elec_usage ".
//       "WHERE date >= '2012-08-25' AND date < '2012-08-27 01:00:00' ".
       "GROUP BY DATE_FORMAT( date, '%Y-%m-%d %H:00' ) ".
       "ORDER BY date";
global $link;
$result = $link->query( $sql );

if( $result )
{
  $answer_string = "";
  $old_watts = 0;
  while( $row = $result->fetch_object() )
  {
    if( $answer_string == "" )
    { // Really ought to omit the first data point so that the chart does not start at 0.
      //$answer_string .= '"' . $row->date . ',0\\n"' . PHP_EOL;
      $answer_string .= '"' . $row->date . ',0\\n"';
    }
    else
    {
      //$answer_string .= '+ "' . $row->date . ',' . round( min( max( ($row->kWh - $old_watts), 0), 500 ), 3 ) . '\\n"' . PHP_EOL;
      $answer_string .= '+ "' . $row->date . ',' . round( min( max( ($row->kWh - $old_watts), 0), 500 ), 3 ) . '\\n"';
    }

    // Need some kind of logic in case $row->kWh is some retarded value to not screw up the next number too.
    $old_watts = $row->kWh;
  }

  $result->free();
}
?>

<!-- This is the thing that draws the chart in the div -->
<script type="text/javascript">

function data4()
{
  return "Date,kWh\n" + <?php echo "$answer_string"; ?>;
}

var g = new Dygraph( document.getElementById( "bordered4" ), data4,
{
  labelsDivStyles: { border: "1px solid black" },
  title: "kWh usage",
  xlabel: "Date",
  ylabel: "kWh"
});

/*
var foo = "" + data4();
document.write( foo );
*/

  </script>