<!DOCTYPE html>
<?php
require_once 'common.php';
?>

<html>
<head>
  <meta http-equiv=Content-Type content='text/html; charset=utf-8'>
  <title>TED-5000 Electricity Tracking</title>
  <link href='favicon.ico' rel='shortcut icon' type='image/x-icon' />
  <link href='resources/elec.css' rel='stylesheet' type='text/css' />
  <link href='lib/tabs/tabs.css' rel='stylesheet' type='text/css' />  <!-- Add tab library -->
  <script type='text/javascript' src='lib/dygraph-combined.js'></script>
  <!-- Style for js library shoudl be in js csss file -->
  <style type='text/css'>
  #bordered
  {
    border: 1px solid red;
  }
  </style>
  </head>

  <body>
<ol id='toc'>
  <li><a href='#dashboard'><span>Dashboard</span></a></li>
  <li><a href='#chart2'><span>Chart 2</span></a></li>
  <li><a href='#chart3'><span>Chart 3</span></a></li>
  <li><a href='#chart4'><span>Chart 4</span></a></li>
  <li><a href='#admin'><span>Config</span></a></li>
  <li><a href='#about'><span><img src='images/Info.png' alt='icon: about'/> About</span></a></li>
</ol>

<div class='content' id='dashboard'>
  <div class='toolbar'>
    Is this font is too small?
  </div>
  For now this is a place keeper.  Please look at the other tabs for the data.
  <br><br><br><br>
  <!-- <br>The present rate of kW usage is <span id='present_usage'>unknown</span> -->
  <!-- <br>The daily use is <span id='daily_usage'>unknown</span> -->
  <!-- <br>The month to date is <span id='month_usage'>unknown</span> -->
</div>

<div class='content' id='chart2'>
  <div class='toolbar'>
    &nbsp;
  </div>
  <br>
  <div class='electric_chart'>
    <div id="bordered2" style="width:900px; height:450px;"></div>
    <?php include( 'demo2.php' ); ?>
  </div>
</div>

<div class='content' id='chart3'>
  <div class='toolbar'>
    &nbsp;
  </div>
  <br>
  <div class='electric_chart'>
    <div id="bordered3" style="width:900px; height:450px;"></div>
    <?php include( 'demo3.php' ); ?>
  </div>
  <br>Click and drag to zoom.  Double click to un-zoom.
  <br>+0.2kW and 0kW are hard limits that keep bad data from messing up the chart too much.  These data points really out to be omitted.  Remember, these are per MINUTE numbers.
</div>

<div class='content' id='chart4'>
  <div class='toolbar'>
    &nbsp;
  </div>
  <br>
  <div class='electric_chart'>
    <div id="bordered4" style="width:900px; height:450px;"></div>
    <?php include( 'demo4.php' ); ?>
  </div>
  <br>Click and drag to zoom.  Double click to un-zoom.
  <br>+500kW and 0kW are hard limits that keep bad data from messing up the chart too much.  These data points really out to be omitted.  Remember, these are per HOUR numbers.
</div>

<div class='content' id='admin'>
<?php
// This password should be in the condig file.
$password = "admin";
?>
  <div class='toolbar'>
<?php
if( !isset( $_POST["password"] ) || ( $_POST["password"] != "$password" ) )
{
?>
<form method='post'>
<input name='password' type='password' size='25' maxlength='25'><input value='Login' type='submit'>Please enter your password for access
<?php
  // Wrong password or no password entered display this message
  if( isset( $_POST['password'] ) || $password == '' )
  {
?>
  <font color='red'>Incorrect Username or Password</font>
<?php
  }
?>
</form>
<?php
}
else
{
?>
<input value='Logout' type='button' onClick='javascript:document.getElementById( "password" ).value="";'>Logout doesn&rsquo;t work yet....
<?php
}
?>

  </div>


<?php
// If password is valid let the user get access
if( isset( $_POST["password"] ) && ( $_POST["password"] == "$password" ) )
{
?>
<br>Config stuff here
<?php
}
else
{
?>
<br>You must enter password to access config information
<?php
}
?>
</div>


<div class='content' id='about'>
  <div class='toolbar'>
    &nbsp;
  </div>
  <br>
  <p>
  <p>Source code for this project can be found on <a target='_blank' href='https://github.com/ThermoMan'>github</a>
  <p>
  <br>I used <a target='_blank' href='http://www.winscp.net'>WinSCP</a> to connect and edited the code using <a target='_blank' href='http://www.textpad.com'>TextPad</a>.
  <p>
  <p>This project also uses code from the following external projects
  <ul>
    <li><a target='_blank' href='http://dygraphs.com//'>dygraphs by Dan Vanderkam</a>.</li>
    <li><a target='_blank' href='http://blixt.org/articles/tabbed-navigation-using-css#section=introduction'>Blixt tab library</a>.</li>
    <li><a target='_blank' href='http://www.veryicon.com/icons/folder/leopard-folder-replacements/'>Free icons from Very Icon</a>.  These icons were made by <a target='_blank' href='http://jasonh1234.deviantart.com'>jasonh1234</a>.</li>
    <li><a target='_blank' href='http://www.stevedawson.com/article0014.php'>Password access loosely based on code by Steve Dawson</a>.</li>

  </ul>
  <p>This project is based on the <a target='_blank' href='http://www.theenergydetective.com/homeowner'>TED 5000</a>.
  <br><br><br>
  <div style='text-align: center;'>
    <a target='_blank' href='http://validator.w3.org/check?uri=referer'><img style='border:0;width:88px;height:31px;' src='images/valid-html5.png' alt='Valid HTML 5'/></a>
    <a target='_blank' href='http://jigsaw.w3.org/css-validator/check/referer'><img style='border:0;width:88px;height:31px;' src='http://jigsaw.w3.org/css-validator/images/vcss' alt='Valid CSS!'/></a>
  </div>
</div>

<!-- This following scripts MUST be dead last for the tab library to work properly -->
<script src='lib/tabs/activatables.js' type='text/javascript'></script>
<script type='text/javascript'>
  activatables( 'tab', ['dashboard', 'chart2', 'chart3', 'chart4', 'admin', 'about'] );
</script>
</body>
</html>