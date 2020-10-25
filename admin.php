<?php
/**
 * Max News
 * 
 * This is the Max News administration panel. 
 * For more details please read the readme.txt
 */
?>
<?php
define ('CLASS_DIR', 'classes/');
////////////////////////////////////////////////////////////////////////////
$password = "admin";  // Modify Password to suit for access, Max 10 Char. //
////////////////////////////////////////////////////////////////////////////

if (isset($_POST["password"]) && ($_POST["password"]== $password) || isset($_POST['submit'])) {
require_once(CLASS_DIR."maxNews.class.php"); 
$newsHandler = new maxNews();  
if (!isset($_POST['submit'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Max's News - Admin panel</title>
   <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
	<div id="header"><div id="header_left"></div>
	<div id="header_main">Max's News - Admin panel</div><div id="header_right"></div></div>
    <div id="content">
      <?php $newsHandler->displayAddForm(); ?>     
    </div>
    <div id="footer"><a href="http://www.phpf1.com" target="_blank">Powered by PHP F1</a></div>
</div>
</body>
</html>
<?php 
} else {
   $newsHandler->insertNews();
}?>
<?php }
else{ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>PRIVATE AREA - Password Protected!</title>
   <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
	<div id="header"><div id="header_left"></div>
	<div id="header_main">PRIVATE AREA - Password Protected!</div>
	<div id="header_right"></div></div>
    <div id="content">
    <?php
	if (isset($_POST['password']) || $password == "") {
  print "<p align=\"center\"><font color=\"red\"><b>Incorrect Password</b><br>Please enter the correct password</font></p>";}
  print "<form method=\"post\"><p align=\"center\">Please enter your password for access<br>";
  print "<input name=\"password\" type=\"password\" size=\"25\" maxlength=\"10\"><input value=\"Login\" type=\"submit\"></p></form>";
	 ?>
    </div>
</div>
</body>
</html> 
<?php }?>
