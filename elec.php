<!DOCTYPE html>
<!--
Code by
  ______ __                                   __  ___
 /_  __// /_   ___   _____ ____ ___   ____   /  |/  /____ _ ____
  / /  / __ \ / _ \ / ___// __ `__ \ / __ \ / /|_/ // __ `// __ \
 / /  / / / //  __// /   / / / / / // /_/ // /  / // /_/ // / / /
/_/  /_/ /_/ \___//_/   /_/ /_/ /_/ \____//_/  /_/ \__,_//_/ /_/
-->
<?php
require_once 'common.php';

// Login status
$password = 'admin';  // This password should be in the config file!
$isLoggedIn = false;  // By default set logged in status to false.
if( isset($_POST['password']) && ($_POST['password'] == $password ) )
{ // Update logged in status to true only when the correct password has been entered.
  $isLoggedIn = true;
}

// Now do things that depend on that newly determined login status
// Set Config tab icon default value
$lockIcon = 'images/Lock.png';      // Default to locked
$lockAlt = 'icon: lock';
if( $isLoggedIn )
{
  // Set Config tab icon logged-in value
  $lockIcon = 'images/Unlock.png';  // Change to UNlocked icon only when user is logged in
  $lockAlt = 'icon: unlock';
}
?>

<html>
  <head>
    <meta http-equiv=Content-Type content='text/html; charset=utf-8'>
    <title>TED-5000 Electricity Tracking</title>
		<link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />

		<link rel='stylesheet' type='text/css' href='/common/css/reset.css' >
		<link rel='stylesheet' type='text/css' href='resources/elec.css' />

		<link rel='stylesheet' type='text/css' href='lib/tabs/tabs.css' />		 <!-- Add tab library and set default appearance -->
		<link rel='stylesheet' type='text/css' title='green' href='lib/tabs/tabs-green.css'>
		<meta http-equiv='Default-Style' content='green'>
		<link rel='stylesheet' type='text/css' title='white' href='lib/tabs/tabs-white.css'>

    <script type='text/javascript'>
			function sizeFrame( frame_name )
			{
				var F = document.getElementById( frame_name );
				F.height = F.contentDocument.documentElement.scrollHeight+30;
				F.width = F.contentDocument.documentElement.scrollWidth+30;
			}

			function startup()
			{
				sizeFrame( 'frame2' );
				sizeFrame( 'frame22' );
				sizeFrame( 'frame4' );
				sizeFrame( 'frame44' );
				//sizeFrame( 'frame5' );
				//sizeFrame( 'frame3' );

				location.href = '#chart2';
			}
			window.onload = startup;

			function doLogout()
			{
				alert( 'Logout is not yet implemented.  Break your session and your login ends.' );
				//document.getElementById( "password" ).value = "";
				//location.reload()
			}

			function switch_style( css_title )
			{
				// You may use this script on your site free of charge provided
				// you do not remove this notice or the URL below. Script from
				// http://www.thesitewizard.com/javascripts/change-style-sheets.shtml
				var i, link_tag;
				for( i = 0, link_tag = document.getElementsByTagName('link'); i < link_tag.length ; i++ )
				{
					if( (link_tag[i].rel.indexOf( 'stylesheet' ) != -1 ) && link_tag[i].title )
					{
						link_tag[i].disabled = true ;
						if( link_tag[i].title == css_title )
						{
							link_tag[i].disabled = false;
						}
					}
				}
			}
    </script>

  </head>

  <body>
		<!-- Internal variable declarations START -->
		<!-- Internal variable declarations END -->

	<div class='all_tabs'>
		<div class='tab' id='dashboard'> <a href='#dashboard'> Dashboard </a>
			<div class='container'>
				<div class='tab-toolbar'>
					<p>Is this font is too small?</p>
				</div>
				<div class='content'>
					<p>For now this is a place keeper.  Please look at the other tabs for the data.</p>
					<br><br><br><br>
						<br>The present rate of kW usage is <span id='present_usage'>unknown</span>
						<br>The daily use is <span id='daily_usage'>unknown</span>
						<br>The month to date is <span id='month_usage'>unknown</span>
						<br><br><br>
						<br>Click and drag to zoom.  Double click to un-zoom.
						<br>+0.2kW and 0kW are hard limits that keep bad data from messing up the chart too much.
						<br>+500kW and 0kW are hard limits that keep bad data from messing up the chart too much.
						<br>These bad (typically the bad data is 'MAXINT') data points really out to be omitted.
            <br><br>
            <br><br>The HTML5 components are tested to work in Chrome, Safari (Mac), Android 4.0.4 default browser.  They do not work (manually type in the date) in Firefox.  I've not tested the functionality in IE.  The HTML validator suggests that the HTML 5 components may also work in Opera.
				</div>
			</div>
		</div>
	    <div class='tab_gap'></div>

			<div class='tab' id='chart2'> <a href='#chart2'> Detail 1 </a>
			<div class='container'>
<!-- No toolbar needed for now
				<div class='tab-toolbar'>
            <input type='button' onClick='javascript: display_chart();' value='Refresh'>
            <select id='watts_selection' onClick=''>
              <option value='W'>Watts</option>
              <option selected='selected' value='kW'>kilo Watts</option>
            </select>
						&nbsp;
            <select id='time_selection' onClick=''>
              <option value='m'>per Minute</option>
              <option selected='selected' value='h'>per Hour</option>
            </select>
            &nbsp;Use date <input type='checkbox' id='use_dates' onChange=''/>
						From date <input type='date' id='from_date' size='10' value='<?php echo date( 'Y-m-d', time() - 259000 ); ?>' max='<?php echo date( 'Y-m-d', time() ); ?>' onInput='' step='1'/>
						to date <input type='date' id='to_date' size='10' value='<?php echo date( 'Y-m-d', time() ); ?>' max='<?php echo date( 'Y-m-d', time() ); ?>' onInput='' step='1'/>
						&nbsp;&lt;-- These controls are not turned on yet.
				</div>
-->
				<div class='content'>
						<br>Remember, the granularity is per MINUTE (measured in kWatts.
						<br>This is an accumulation chart of the last ten days.
					<div class='electric_chart'>
						<iframe id='frame2' src='elec_chart.php?show_chart=2' style='border:0px'></iframe>
					</div>
				</div>
			</div>
		</div>
    	<div class='tab_gap'></div>


			<div class='tab' id='chart4'> <a href='#chart4'> Detail 2 </a>
				<div class='container'>
					<div class='content'>
						<br>Remember, the granularity is per HOUR (measured in kWatts.
						<br>This is an accumulation chart of the last ten days.
						<div class='electric_chart'>
							<iframe id='frame4' src='elec_chart.php?show_chart=4' style='border:0px'></iframe>
						</div>
						<br>Remember, the granularity is per HOUR.
					</div>
				</div>
			</div>
    	<div class='tab_gap'></div>


			<div class='tab' id='chart22'> <a href='#chart22'> Delta 1</a>
				<div class='container'>
					<div class='content'>
						<br>Remember, the granularity is per MINUTE (measured in Watts).
						<br>This is a per minute delta chart of the last ten days.
						<div class='electric_chart'>
							<iframe id='frame22' src='elec_chart.php?show_chart=22' style='border:0px'></iframe>
						</div>
					</div>
				</div>
			</div>
    	<div class='tab_gap'></div>


			<div class='tab' id='chart44'> <a href='#chart44'> Delta 2 </a>
				<div class='container'>
					<div class='content'>
						<br>Remember, the granularity is per HOUR (measured in Watts).
						<br>This is a per hour delta chart of the last ten days.
						<div class='electric_chart'>
							<iframe id='frame44' src='elec_chart.php?show_chart=44' style='border:0px'></iframe>
						</div>
						<br>Remember, the granularity is per HOUR.
					</div>
				</div>
			</div>
    	<div class='tab_gap'></div>

<!--
			<div class='tab' id='chart3'> <a href='#chart3'> 30 days (per day) </a>
			<div class='container'>
				<div class='tab-toolbar'>
					<p>&nbsp;</p>
				</div>
				<div class='content'>
					<div class='electric_chart'>
							<iframe id='frame3' src='elec_chart.php?show_chart=5' style='border:0px'></iframe>
					</div>
						<br>Remember, the granularity is per DAY.
				</div>
			</div>
		</div>
    	<div class='tab_gap'></div>
-->


<!-- Don't need this chart for now
		<div class='tab' id='chart4'> <a href='#chart4'> kW per Hour </a>
			<div class='container'>
				<div class='tab-toolbar'>
					<p>&nbsp;</p>
				</div>
				<div class='content'>
					<div class='electric_chart'>
						<iframe id='frame4' src='elec_chart.php?show_chart=4' style='border:0px'></iframe>
					</div>
						<br>Remember, these are per HOUR numbers.
				</div>
			</div>
		</div>
			<div class='tab_gap'></div>
-->

			<div class='tab' id='config'> <a href='#config'> <img class='tab-icon' src='<?php echo $lockIcon;?>' alt='<?php echo $lockAlt;?>'/>Configure </a>
			<div class='container'>
				<div class='tab-toolbar'>
<?php
    // Prompt for pwd to login -or- present logout button
    if( ! $isLoggedIn )
    {
?>
					<form method='post'>
						<input name='password' type='password' size='25' maxlength='25'><input value='Login' type='submit'>Please enter your password for access.
					</form>
<?php
			if( isset($_POST['password']) && ($_POST['password'] != $password ) )
			{
				echo "<font color='red'> Incorrect Username or Password - I think you typed &quot; {$_POST['password']} ?>&quot; </font>";
			}
    }
    if( $isLoggedIn )
    {
      echo '<input value="Logout" type="button" onClick="doLogout();">Logout doesn&rsquo;t work yet....';
    }
?>
				</div>
				<div class='content'>
						<br>
<?php
    // If password is valid let the user get access
    if( $isLoggedIn )
    {
?>
<!-- START OF HIDDEN HTML - PLACE YOUR CONTENT HERE -->

					<p align='center'><br><br><br>
					<b>Congratulations</b><br>you have gained access to the Protected and Secret Area!</p>
						<p>Choose appearance: <select id='colorPicker' onChange='javascript: switch_style( document.getElementById( "colorPicker" ).value )'>
							<option value='white'>Ice</option>
							<option value='green' selected>Leafy</option>
						</select></p>

<!-- END OF HIDDEN HTML -->
<?php
}
else
{
        echo 'You must enter password to access config information.';
}
?>
				</div>
			</div>
		</div>
			<div class='tab_gap'></div>

			<div class='tab' id='about'> <a href='#about'><img class='tab-icon' src='images/Info.png' alt='icon: about'/> About </a>
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
							<li><a target='_blank' href='https://github.com/ThermoMan/Tabbed-Interface-CSS-Only'>Tabbed-Interface-CSS-Only</a> by ThermoMan.</li>
							<li><a target='_blank' href='http://www.customicondesign.com//'>Free for non-commercial use icons from Custom Icon Designs</a>.  These icons are in the package <a target='_blank' href='http://www.veryicon.com/icons/system/mini-1/'>Mini 1 Icons</a>.</li>
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