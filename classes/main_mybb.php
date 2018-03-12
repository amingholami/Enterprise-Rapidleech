<?php
if (!defined('RAPIDLEECH')) {
	require('../deny.php');
	exit;
}

/*include(TEMPLATE_DIR.'header.php');
include(TEMPLATE_DIR.'login.php');
include(TEMPLATE_DIR.'footer.php');
*/
/************************************************/
define("IN_MYBB", "1");
require_once "../global.php";
  $siteurl = $mybb->settings['bburl'];
  $sitename = $mybb->settings['bbname'];
$isallowed = "no";
$uname = '';
$uid = '';

global $mybb;
if (!$mybb->user['uid'] || $mybb->user['uid']== "")
{$isallowed = "no";
  include(TEMPLATE_DIR.'header.php');
  include(TEMPLATE_DIR.'login.php');
  include(TEMPLATE_DIR.'footer.php');
}
else{
  $isallowed = "yes";
  $uname = $mybb->user['username'];
  $uid = $mybb->user['uid'];
// Render the main screen
include(TEMPLATE_DIR.'header.php');
include(TEMPLATE_DIR.'main.php');
include(TEMPLATE_DIR.'footer.php');
}

?>