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
    <link href='lib/tabs/tabs.css' rel='stylesheet' type='text/css' />     <!-- Add tab library -->
    <script type='text/javascript' src='lib/dygraph-combined.js'></script> <!-- Add charting library -->
    <script type='text/javascript'>
    function doLogout()
    {
      alert( 'Logout is not yet implemented.  Break your session and your login ends.' );
      //document.getElementById( "password" ).value = "";
      //location.reload()
    }
    </script>

  </head>

  <body>

  <div class='all_tabs'>
    <div class='tab' id='dashboard'> <a href='#dashboard'> Dashboard </a>
      <div class='container'>
        <div class='tab-toolbar'>
          <p>Is this font is too small?</p>
        </div>
        <div class='content'>
          <p>For now this is a place keeper.  Please look at the other tabs for the data.</p>
          <br><br><br><br>
          <!-- <br>The present rate of kW usage is <span id='present_usage'>unknown</span> -->
          <!-- <br>The daily use is <span id='daily_usage'>unknown</span> -->
          <!-- <br>The month to date is <span id='month_usage'>unknown</span> -->
        </div>
      </div>
    </div>

    <div class='tab' id='chart2'> <a href='#chart2'> Chart 2 </a>
      <div class='container'>
        <div class='tab-toolbar'>
          <p>&nbsp;</p>
        </div>
        <div class='content'>
          <div class='electric_chart'>
            <div id='bordered2' style='width:900px; height:450px;'></div>
            <?php include( 'demo2.php' ); ?>
          </div>
        </div>
      </div>
    </div>

    <div class='tab' id='chart3'> <a href='#chart3'> Chart 3 </a>
      <div class='container'>
        <div class='tab-toolbar'>
          <p>&nbsp;</p>
        </div>
        <div class='content'>
          <div class='electric_chart'>
            <div id='bordered3' style='width:900px; height:450px;'></div>
            <?php include( 'demo3.php' ); ?>
          </div>
          <br>Click and drag to zoom.  Double click to un-zoom.
          <br>+0.2kW and 0kW are hard limits that keep bad data from messing up the chart too much.  These data points really out to be omitted.  Remember, these are per MINUTE numbers.
        </div>
      </div>
    </div>

    <div class='tab' id='chart4'> <a href='#chart4'> Chart 4 </a>
      <div class='container'>
        <div class='tab-toolbar'>
          <p>&nbsp;</p>
        </div>
        <div class='content'>
          <div class='electric_chart'>
            <div id='bordered4' style='width:900px; height:450px;'></div>
            <?php include( 'demo4.php' ); ?>
          </div>
          <br>Click and drag to zoom.  Double click to un-zoom.
          <br>+500kW and 0kW are hard limits that keep bad data from messing up the chart too much.  These data points really out to be omitted.  Remember, these are per HOUR numbers.
        </div>
      </div>
    </div>

    <div class='tab' id='admin'> <a href='#admin'> Config </a>
      <div class='container'>
        <div class='tab-toolbar'>
<?php
$password = 'admin';  // This password should be in the config file!

// Prompt for pwd to login -or- present logout button
if( !isset($_POST['password']) || ($_POST['password'] != $password ) )
{
?>
  <form method='post'>
  <input name='password' type='password' size='25' maxlength='25'><input value='Login' type='submit'>Please enter your password for access.
<?php
}

if( isset($_POST['password']) && ($_POST['password'] != $password ))
{
?>
  <font color='red'> Incorrect Username or Password - I think you typed &quot;<?php echo $_POST['password'] ?>&quot; </font>
<?php
}

if( isset($_POST['password']) && ($_POST['password'] == $password ) )
{
?>
  <input value='Logout' type='button' onClick='doLogout();'>Logout doesn&rsquo;t work yet....
<?php
}
?>
        </div>
        <div class='content'>
<?php
// If password is valid let the user get access
if( isset($_POST['password']) && ($_POST['password'] == $password ) )
{
?>
<!-- START OF HIDDEN HTML - PLACE YOUR CONTENT HERE -->

  <p align="center"><br><br><br>
  <b>Congratulations</b><br>you have gained access to the Protected and Secret Area!</p>

<!-- END OF HIDDEN HTML -->
<?php
}
else
{


if( !isset($_POST['password']) || ($_POST['password'] != $password ) )
{ // Default presentation before password is entered
?>
  You must enter password to access config information.  For the test system, the password is &quot;<?php echo $password ?>&quot;.
<?php
}
}
?>
        </div>
      </div>
    </div>

    <div class='tab' id='about'> <a href='#about'> <img class='tab-icon' src='images/Info.png' alt='icon: about'/> About </a>
      <div class='container'>
        <div class='tab-toolbar'>
          <p>&nbsp;</p>
        </div>
        <div class='content'>
          <br>
          <p>
          <p>Source code for this project can be found on <a target='_blank' href='https://github.com/ThermoMan'>github</a>
          <p>
          <br>I used <a target='_blank' href='http://www.winscp.net'>WinSCP</a> to connect and edited the code using <a target='_blank' href='http://www.textpad.com'>TextPad</a>.
          <p>
          <p>This project also uses code from the following external projects
          <ul>
            <li><a target='_blank' href='http://dygraphs.com//'>dygraphs by Dan Vanderkam</a>.</li>
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
      </div>
    </div>

  </div>

</body>
</html>