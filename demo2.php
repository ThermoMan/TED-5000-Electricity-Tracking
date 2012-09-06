<?php
require_once "common.php";

connect_to_db();

/*
 * GREATEST() doesn't seem to do a darn thing.
 *   The idea was that the -10,000 would be replaced by -10.
 *   Bth are incorrect, but -10,000 screws up the auto scaling more.
 */
//$sql = "SELECT date, FLOOR(GREATEST( watts/1000, -10 )) AS kW " .
$sql = "SELECT date, FLOOR( watts/1000 ) AS kW " .
       "FROM elec__elec_usage " .
       "ORDER BY date";

global $link;
$result = $link->query( $sql );

if( $result )
{
  $answer_string = "";
  while( $row = $result->fetch_object() )
  {
    if( $answer_string == "" )
    {
      //$answer_string .= '"' . $row->date . ',' . max($row->kW,-10) . '\\n"' . PHP_EOL;
      $answer_string .= '"' . $row->date . ',' . max($row->kW,-10) . '\\n"';
    }
    else
    {
      //$answer_string .= '+ "' . $row->date . ',' . max($row->kW,-10) . '\\n"' . PHP_EOL;
      $answer_string .= '+ "' . $row->date . ',' . max($row->kW,-10) . '\\n"';
    }
  }

  $result->free();
}
?>

<!-- This is the thing that draws the chart in the div -->
<script type="text/javascript">

function data2()
{
  return "Date,kW\n" + <?php echo "$answer_string"; ?>;
}

var g = new Dygraph( document.getElementById( "bordered2" ), data2,
{
  labelsDivStyles: { border: "1px solid black" },
  title: "Total accumulated kW usage",
  xlabel: "Date",
  ylabel: "kW"
});

/*
var foo = "" + data2();
document.write( foo );
*/

</script>