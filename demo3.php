<?php
require_once "common.php";

connect_to_db();

/*
 * GREATEST() doesn't seem to do a darn thing.
 *   The idea was that the -10,000 would be replaced by -10.
 *   Bth are incorrect, but -10,000 screws up the auto scaling more.
 */
//$sql = "SELECT date, GREATEST( watts/1000, -10 ) AS kW " .
$sql = "SELECT date, watts/1000 AS kW " .
       "FROM elec__elec_usage " .
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
    {
      //$answer_string .= '"' . $row->date . ',0\\n"' . PHP_EOL;
      $answer_string .= '"' . $row->date . ',0\\n"';
    }
    else
    {
      //$answer_string .= '+ "' . $row->date . ',' . round( min( max( $row->kW - $old_watts, 0 ), 0.2 ), 3 ) . '\\n"' . PHP_EOL;
      $answer_string .= '+ "' . $row->date . ',' . round( min( max( $row->kW - $old_watts, 0 ), 0.2 ), 3 ) . '\\n"';
    }
    //$old_watts = min( max( $row->kW, 0 ), .2 );
    //$old_watts = max( $row->kW, 0 );
    $old_watts = $row->kW;
  }

  $result->free();
}
?>

<!-- This is the thing that draws the chart in the div -->
<script type="text/javascript">

function data3()
{
  return "Date,kW\n" + <?php echo "$answer_string"; ?>;
}

var g = new Dygraph( document.getElementById( "bordered3" ), data3,
{
  labelsDivStyles: { border: "1px solid black" },
  title: "kW per minute usage",
  xlabel: "Date",
  ylabel: "kW"
});

/*
var foo = "" + data3();
document.write( foo );
*/

</script>