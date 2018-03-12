<?php
////////////////////////////////////////////////////////////////////////////////
//              Rapidleech setup file
//
// If you need to do rapidleech setup again:
// To load default config: delete 'configs/config.php'
// To work on your old config: rename 'configs/config.php' to 'configs/config_old.php'
// After that, go to your rapidleech url to access setup
//
////////////////////////////////////////////////////////////////////////////////

$PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;
define('RAPIDLEECH', 'yes');
define ('CONFIG_DIR', 'configs/');

//Default options file
require_once (CONFIG_DIR.'default.php');
//Exit setup if config file exists and is complete
if (is_file(CONFIG_DIR.'config.php')) {
  require_once (CONFIG_DIR.'config.php');  
  if (count($options) == count($default_options)) { return; }
}

define('TEMPLATE_DIR', 'templates/Enterprise_by_AminGholami.com/');
//$options['default_language'] = "en";
require_once('classes/other.php');
$amin_color = 'blue';

?><!DOCTYPE HTML>
<html>
<meta charset="utf-8">
<head><title>Rapidleech Setup</title>
<link href="<?php echo TEMPLATE_DIR; ?>images/amin_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="classes/js.js"></script>
<script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>images/amin_js.js"></script>
<script type="text/javascript" src="<?php echo TEMPLATE_DIR; ?>images/jquery.easing.1.3.js"></script>
<script language="JavaScript" type="text/javascript">
hide_error();hide_success();
</script>
<?php

if (!isset($_POST['setup_save'])) {
  function js_special_chars($t) {
    return str_replace(array('\\', "'", '"', '&', "\n", "\r", "\t", chr(8), chr(12)), array("\\", "\\'", "\\\"", "\&", '\\n', "\\r", "\\t", "\\b", "\\f"), $t);
  }
?>
<script src="classes/js.js" type="text/javascript"></script>
<script type="text/javascript">
/* <![CDATA[ */
function load_current_config() {
<?php
$options = array(); $old_options = false;
if (is_file(CONFIG_DIR."config.php")) { include(CONFIG_DIR."config.php"); $old_options = true; }
elseif (is_file(CONFIG_DIR."config_old.php")) { include(CONFIG_DIR."config_old.php"); $old_options = true; }

foreach ($default_options as $k => $v) { if (!array_key_exists($k, $options)) { $options[$k] = $v; } }

foreach ($options as $k => $v) {
  if (!array_key_exists($k, $default_options) || is_array($default_options[$k])) { continue; }
  $v = js_special_chars($v);
  if (is_bool($default_options[$k])) {
    echo "  $('#opt_{$k}').".($v ? "attr('checked', 'checked')" : "removeAttr('checked')").";\n";
  }
  elseif (is_numeric($default_options[$k])) {
    $v = floor($v);
    echo "  set_element_val('opt_{$k}', '".($k == 'delete_delay' ? $v."', '".floor($v/60) : $v)."');\n";
  }  
  else { echo "  set_element_val('opt_{$k}', '{$v}');\n"; }
}
?>
  $('#opt_forbidden_filetypes').val('<?php
foreach ($options['forbidden_filetypes'] as $k => $v) {
  echo js_special_chars($v).(count($options['forbidden_filetypes'])-1 == $k ? '' : ', ');
}
?>');
  while ($('#opt_login_table tbody>tr').size() < <?php echo count($options['users']); ?>) { $("#opt_login_add").click(); }
  while ($('#opt_login_table tbody>tr').size() > <?php echo max(1, count($options['users'])); ?>) { $('#opt_login_table tbody>tr:last').remove(); }
<?php
$i = 0;
foreach ($options['users'] as $k => $v) {
  $k = js_special_chars($k); $v = js_special_chars($v);
  echo "  $('#opt_login_table [name=users[]]').eq({$i}).val('{$k}');\n";
  echo "  $('#opt_login_table [name=passwords[]]').eq({$i}).val('{$v}');\n";
  $i++;
}
?>
  if ($('#opt_forbidden_filetypes_block').attr('checked')) { $('#opt_rename_these_filetypes_to_0').show(); }
  else { $('#opt_rename_these_filetypes_to_0').hide(); }
  
  if ($('#opt_login').attr('checked')) { $('#opt_login_0').show(); $('#opt_login_cgi_0').show(); }
  else { $('#opt_login_0').hide(); $('#opt_login_cgi_0').hide(); }
  
  if ($('#opt_new_window').attr('checked')) { $('#opt_new_window_0').show(); }
  else { $('#opt_new_window_0').hide(); }
  
  if ($('#opt_setting').attr('checked')) { $('#opt_some_settings0').show(); }
  else { $('#opt_some_settings0').hide(); }
}

function set_element_val(id, value, display) {
  display = (typeof display == 'undefined') ? value : display;
  var e = $('#'+id);
  e.val(value); if (e.val() != value) { e.append($('<option><\/option>').val(value).html(display)); e.val(value); }
}

function save_config() {
  document.setup_form.submit();
}

$(document).ready(function() {
  $("#save").removeAttr("disabled");
  $("#reset").removeAttr("disabled");
  $('#save').click(function() { save_config(); });
  $('#reset').click(function() { load_current_config(); });

  $('div.div_title').append('&nbsp;v');
  $('div.div_title').click(function() {
    var t =  $(this).parent().children('div:not(.div_title)');
    if (t.is(':visible')) { t.hide(); $(this).text($(this).text().slice(0, - 1)+'>'); }
    else { t.show(); $(this).text($(this).text().slice(0, -1)+'v'); }
  });
  $('#div_main_advanced').click();

  $('#opt_disable_actions').click(function() {
    if ($(this).attr('checked')) { $('#opt_actions_table :checkbox:not(#opt_disable_deleting)').each(function() { $(this).attr('checked', 'checked'); }); }
    else { $('#opt_actions_table :checkbox:not(#opt_disable_deleting)').removeAttr('checked'); }
  });
  $('#opt_disable_deleting').click(function() {
    if ($(this).attr('checked')) {
      $('#opt_disable_delete').attr('checked', 'checked');
    }
    else { $('#opt_disable_delete').removeAttr('checked'); }
  });
  $("#opt_forbidden_filetypes_block").click(function() { $('#opt_rename_these_filetypes_to_0').toggle(); } );
  $("#opt_login").click(function() {$('#opt_login_cgi_0').toggle(); $('#opt_login_0').slideToggle('slow'); } );
  $("#opt_login_add").click(function() {
   var row = $('#opt_login_table tbody>tr:last').clone(true).insertAfter('#opt_login_table tbody>tr:last');
    $('td:eq(0)', row).html('<input style="width:auto" type="button" value="- Remove" onclick="$(this).parent().parent().remove();" />');
    $('td:eq(1)>input,td:eq(2)>input', row).val('');
    return false;
  });

  $('#opt_delete_delay').change(function() {
    if ($(this).val() == 'other') {
      var other = parseInt(prompt('How many minutes?', '0'), 10) || 0;
      set_element_val('opt_delete_delay', other*60, other);
    }
  });
  $('#opt_file_size_limit').change(function() {
    if ($(this).val() == 'other') {
      var other = parseInt(prompt('How many MiBs?', '0'), 10) || 0;
      set_element_val('opt_file_size_limit', other);
    }
  });

  load_current_config();
});
/* ]]> */
</script>
<?php
}
?>
<style type="text/css">
<!--
table{width:100%};
-->
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('table[class="neveshte_inner"]').attr('cellspacing','5');
	}
</script>
</head>
<body id="rl_setup">
  <div id='branding'>      
   <header>
        <h1><a href="<?php echo $PHP_self ?>" title="Rapidleech Setup" >Rapidleech Setup</a></h1>
        <div id="discr"><?php echo ($old_options ? 'Old' : 'Default'); ?> rapidleech options loaded</div>
   </header>
  </div>
  <div id="body">
<noscript>
<div color="<?php echo $amin_color; ?>" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">JavaScript Error!</div>
      <div class="neveshte">
<div class="neveshte_inner_error" align="center"><strong>This page won't work without JavaScript, please enable JavaScript and refresh the page.</strong></div>
        </div>
    </div>
  </div>
</div>
<div id="spacer">&nbsp;</div>
</noscript>
<?php
if (isset($_POST['setup_save']) && $_POST['setup_save'] == 1) {

  $options = array();
  foreach ($default_options as $k => $v) { if (!array_key_exists($k, $options)) { $options[$k] = $v; } }
  
  foreach($default_options as $k => $v) {
    if (is_array($default_options[$k])) { continue; }
    if (is_bool($default_options[$k])) {
      $options[$k] = (isset($_POST['opt_'.$k]) && $_POST['opt_'.$k] ? true : false);
    }
    elseif (is_numeric($default_options[$k])) {
      $options[$k] = (isset($_POST['opt_'.$k]) && $_POST['opt_'.$k] ? floor($_POST['opt_'.$k]) : 0);
    }  
    else {
      $options[$k] = (isset($_POST['opt_'.$k]) && $_POST['opt_'.$k] ? stripslashes($_POST['opt_'.$k]) : '');
    }
  }
  
  function array_trim(&$v) { $v = trim($v); }
  $tmp = (isset($_POST['opt_forbidden_filetypes']) ? stripslashes($_POST['opt_forbidden_filetypes']) : '');
  $tmp = explode(',', $tmp);
  array_walk($tmp, 'array_trim');
  $tmp = (count($tmp) > 0 && strlen(trim($tmp[0])) > 0 ? $tmp : array());
  $options['forbidden_filetypes'] = $tmp;

  $tmp = "\r\n\r\n<IfModule mod_rewrite.c>\r\nRewriteEngine on\r\nRewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]\r\n</IfModule>";
  $htacess = @file_get_contents('.htaccess');
  if (empty($htacess)) { echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_error" align="center"><strong>It was not possible to read .htacess file</strong></div>
        </div>
    </div>
  </div>
</div>'; }
  elseif (isset($_POST['opt_login_cgi']) && $_POST['opt_login_cgi']) {
    if (strpos($htacess, $tmp) === false) { if (!@write_file(".htaccess", $htacess.$tmp, 1)) {
      echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_error" align="center"><strong>It was not possible to edit .htacess file to enable CGI authorization fix</strong></div>
        </div>
    </div>
  </div>
</div>';
      $options['login_cgi'] = false;
    }
    }
  }
  else {
    if (strpos($htacess, $tmp) !== false) { if (!@write_file(".htaccess", str_replace($tmp, '', $htacess))) {
      echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_error" align="center"><strong>It was not possible to write .htacess file to completely disable CGI authorization fix</strong></div>
        </div>
    </div>
  </div>
</div>';
    } }
  }

  $options['users'] = array();
  if (isset($_POST['users']) && isset($_POST['passwords']) && 
  count($_POST['users']) > 0 && count($_POST['users']) == count($_POST['passwords'])) {
    foreach ($_POST['users'] as $k => $u) {
      $u = stripslashes($u); $p = stripslashes($_POST['passwords'][$k]);
      if ($u == '' && $p == '') { continue; }
      $options['users'][$u] = $p;
    }
  }
  else { echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_error" align="center"><strong>There was a problem with users and passwords</strong></div>
        </div>
    </div>
  </div>
</div>'; }
  
  ob_start(); var_export($options); $opt = ob_get_contents(); ob_end_clean();
  $opt = (strpos($opt, "\r\n") === false ? str_replace(array("\r", "\n"), "\r\n", $opt) : $opt);
  $opt = "<?php\r\n if (!defined('RAPIDLEECH')) { require_once('index.html'); exit; }\r\n\r\n\$options = ".
        $opt.
        "; \r\n\r\nrequire_once('site_checker.php');\r\nrequire_once('accounts.php');\r\n?>";
  if (!@write_file(CONFIG_DIR."config.php", $opt, 1)) { echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_error" align="center"><strong>It was not possible to write the configuration</strong></div>Set permissions of "configs" folder to 0777 and try again
        </div>
    </div>
  </div>
</div>'; }
  else {
    if (is_file(CONFIG_DIR.'config_old.php')) { if (@!unlink(CONFIG_DIR.'config_old.php') && is_file(CONFIG_DIR.'config_old.php')) { '<div class="div_message">It was not possible to delete the old configuration.<br />Manually delete "configs/config_old.php"</div><br />'; } }
    echo '<div color="'.$amin_color.'" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Congratulation!</div>
      <div class="neveshte" align="center">
<div class="neveshte_inner_success" align="center"><strong>Configuration Saved Successfully!</strong></div>Click <a style="text-decoration:underline" href="'.$PHP_SELF.'">here</a> to continue to Rapidleech
        </div>
    </div>
  </div>
</div>';
  }
?>
<?php
}
else {
?>
<form method="post" enctype="multipart/form-data" name="setup_form" action="<?php echo $PHP_SELF; ?>">
<table><tr><td valign="top" style="min-width:100; width:20%">
<div color="<?php echo $amin_color; ?>" class="defult">
    <div class="_div2">
      <div class="_div3">  
          <div class="onvan">Navigation</div>
        <div class="neveshte" align="center">
        <nav>
        <div class="neveshte_inner" id="tools">
        <input id="navcell1" type="button" value="General Options" class="selected" onClick="javascript:switchCell_amin(1);">
        <input id="navcell2" type="button" value="Presentation Options" onClick="javascript:switchCell_amin(2);"><div style="height:5px">&nbsp;</div></div>
        <div class="neveshte_inner" id="tools">
        <input id="navcell3" type="button" value="File Actions Restrictions" onClick="javascript:switchCell_amin(3);"><div style="height:5px">&nbsp;</div></div>
        <div class="neveshte_inner" id="tools">
        <input id="navcell4" type="button" value="Authorization mode" onClick="javascript:switchCell_amin(4);"><div style="height:5px">&nbsp;</div></div>
        <div class="neveshte_inner" id="tools">
        <input id="navcell5" type="button" value="Advanced Options" onClick="javascript:switchCell_amin(5);"><div style="height:5px">&nbsp;</div></div>
        </nav>
        </div>
        </div>
      </div>
    </div>
</td><td width="9">&nbsp;</td><td valign="top">
<div color="<?php echo $amin_color; ?>" rel="tb0" class="defult tb0" style="min-width:500px;">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan"><span id="onvan">General Options</span></div>
      <div class="neveshte">
      <span id="content">
       <div id="tb1">
       <div class="onvan hide_table">General Options</div>
      <table class="neveshte_inner">                              
          <tr><td width="50%" title="Use utmost 2 or 3 words for better appearance of your website"><label for="opt_site_title">Website Title Name</label><br><input required type="text" id="opt_site_title" name="opt_site_title"></td>
            <td title="Use lowercase and short texts for better appearance"><label for="opt_site_title_description">Website short Description</label><br><input type="text" id="opt_site_title_description" name="opt_site_title_description" required></td>
          </tr>
          <tr>
            <td colspan="2" title="Just for Contact Form Use. if You take it Blank, Contact us won't be shown"><label for="opt_admin_mail">Admin E-Mail</label>
              <br>
              <input style="width:99%" type="text" id="opt_admin_mail" name="opt_admin_mail"></td>
            </tr>
          </table>
        <table class="neveshte_inner">
          <tr title="This is where your downloaded files are saved"><td><label for="opt_download_dir">Download Directory</label><br><input style="width:99%" required type="text" id="opt_download_dir" name="opt_download_dir"></td>
          </tr>
          <tr title="Allow users to change download directory"><td><input type="checkbox" value="1" name="opt_download_dir_is_changeable" id="opt_download_dir_is_changeable"> <label for="opt_download_dir_is_changeable">Download Directory is Changeable</label></td>
          </tr>
        </table>
          <table class="neveshte_inner">
          <tr><td title="Time before Downloaded Files are Deleted!" width="50%"><label for="opt_delete_delay">Auto Delete</label><br><select size="1" name="opt_delete_delay" id="opt_delete_delay">
          <option value="0">Disabled</option>
          <option value="3600">One Hour</option>
          <option value="7200">2 Hours</option>
          <option value="10800">3 Hours</option>
          <option value="14400">4 Hours</option>
          <option value="21600">6 Hours</option>
          <option value="28800">8 Hours</option>
          <option value="43200">12 Hours</option>
          <option value="64800">18 Hours</option>
          <option value="86400">One day</option>
          <option value="172800">2 days</option>
          <option value="other">Other</option>
        </select></td>
      <td title="Limit File Size"><label for="opt_file_size_limit">File Size Limit</label>
        <br>
        <select size="1" name="opt_file_size_limit" id="opt_file_size_limit">
          <option value="0">Disabled</option>
          <option value="100">100 MB</option>
          <option value="250">250 MB</option>
          <option value="550">550 MB</option>
          <option value="750">750 MB</option>
          <option value="1024">1 GB</option>
          <option value="2048">2 GB</option>
          <option value="other">Other</option>
        </select></td>
            
            </tr>
          <tr title="Don't Allow download same name files"><td colspan="3"><input type="checkbox" value="1" name="opt_bw_save" id="opt_bw_save"> <label for="opt_bw_save">Bandwidth Saving</label></td>
            </tr>
          </table>
            <table class="neveshte_inner">                              
          <tr><td width="50%" title="Add prefix to File Names<br>i.e: <b style='color:rgb(200,0,0)'>prefix_</b>filename.ext"><label for="opt_rename_prefix">File Name prefix</label><br><input type="text" id="opt_rename_prefix" name="opt_rename_prefix"></td>
            <td title="Add suffix to File Names<br>i.e: filename<b style='color:rgb(200,0,0)'>_suffix</b>.ext"><label for="opt_rename_suffix">File Name suffix</label><br><input type="text" id="opt_rename_suffix" name="opt_rename_suffix"></td>
          </tr>
          <tr title="Replace spaces for underscore in File Names<br>i.e: file name.ext -> file<b style='color:rgb(200,0,0)'>_</b>name.ext"><td colspan="2"><input type="checkbox" value="1" name="opt_rename_underscore" id="opt_rename_underscore"> <label for="opt_rename_underscore">Rename underscore</label>            </td>
            </tr>
          </table>
          <table class="neveshte_inner">  
          <tr><td width="50%" title="Disable *.upload.html creation after uploading a File"><input type="checkbox" value="1" name="opt_upload_html_disable" id="opt_upload_html_disable"> <label for="opt_upload_html_disable">Upload HTML File Disable</label></td>
            <td title="Disable myuploads.txt creation"><input type="checkbox" value="1" name="opt_myuploads_disable" id="opt_myuploads_disable"> <label for="opt_myuploads_disable">myuploads.txt Disable</label>
              </td>
            </tr>
</table>
</div>
 <div id="tb2" class="hide_table">
     <div class="onvan hide_table">Presentation Options</div>
            <table width="50%" class="neveshte_inner">
              <tr><td width="50%" title="Default Template used. Must be <b style='color:rgb(200,0,0)'>Enterprise</b>"><label for="opt_template_used">Template</label><br><select size="1" name="opt_template_used" id="opt_template_used">
<?php
$d = dir('templates/');
while (false !== ($f = $d->read())) {
  if (!is_dir('templates/'.$f) || $f == '.' || $f == '..') { continue; }
  echo '<option value="'.$f.'">'.$f.'</option>';
}
$d->close();
?>
                </select></td><td title="Default Language used"><label for="opt_default_language">Language</label><br>
                <select size="1" name="opt_default_language" id="opt_default_language">
<?php
$d = dir('languages/');
while (false !== ($f = $d->read())) {
  if (substr($f, -4) != '.php') { continue; }
  echo '<option value="'.substr($f, 0, -4).'">'.substr($f, 0, -4).'</option>';
}
$d->close();
?>
                </select>                
              </td></tr>
            </table>
            <table class="neveshte_inner">
          <tr title="Default Template Color"><td><label for="opt_default_color">Default Template Schem Color</label><br><select name="opt_default_color" id="opt_default_color">
                <option value="white">White</option>
                <option value="blue">Blue</option>
                <option value="green">Green</option>
                <option value="yellow">Yellow</option>
                <option value="orange">Orange</option>
                <option value="red">Red</option>
              </select></td>
          </tr>
          <tr title="Show Schem Color Box to users change Schem Color for him/herselves"><td><input type="checkbox" value="1" name="opt_color_schem_box" id="opt_color_schem_box"> <label for="opt_color_schem_box"> Schem Color is Changeable</label></td>
          </tr>
        </table>
        <table class="neveshte_inner">
              <tr><td width="50%" title="Use New Windows for Transloading"><input type="checkbox" value="1" name="opt_new_window" id="opt_new_window" onClick="javascript:$('#opt_new_window_0').toggle('fast');"> <label for="opt_new_window">Transload files in a new window</label></td>
              <td colspan="2" title="will Show Referer Input under Tranload Link Input"><input type="checkbox" name="opt_referer" id="opt_referer">
                  <label for="opt_referer">Show Referer Input</label></td>
                 </tr>
              <tr> 
                <td id="opt_new_window_0" title="Use JavaScript Window"><span><input type="checkbox" value="1" name="opt_new_window_js" id="opt_new_window_js"> <label for="opt_new_window_js">Use  Full Size window</label></span></td>                
              </tr>
            </table>
        <table class="neveshte_inner">
              <tr><td title="Show Left Column &amp; Premium Accounts, if any"><input type="checkbox" value="1" name="opt_left_col" id="opt_left_col"> <label for="opt_left_col">Show Left Column</label></td>
                <td width="50%" title="Show Plugins Box"><input type="checkbox" value="1" name="opt_plugins" id="opt_plugins"> <label for="opt_plugins">Show Plugins Box</label></td>
              </tr>
              <tr>
                <td title="Show Tools Box with contents: Auto Transload, Auto Upload, Notes, ...<br>for Hiding every content, delete <b>audl.php</b> or <b>auup.php</b> or ... from root" colspan="2"><input type="checkbox" value="1" name="opt_tools" id="opt_tools"> <label for="opt_tools">Show Tools Box</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr><td width="50%" title="Show Latest News under Transload Panel in Main Windows<br>Go to <i>ROOT/admin.php</i> for Add News"><input type="checkbox" value="1" name="opt_latest_news" id="opt_latest_news"> <label for="opt_latest_news">Show Latest News</label></td>
                <td title="Show Server Files Panel to Users with Button in the Menu"><span><input type="checkbox" value="1" name="opt_server_files" id="opt_server_files"> <label for="opt_server_files">Show Server Files Panel</label></span></td>
              </tr>
              
              <tr>
                <td title="Show Settings Panel to Users with Button in the Menu"><input type="checkbox" value="1" name="opt_setting" id="opt_setting" onClick="$(this).parent().parent().children('td:last').toggle('fast');"> <label for="opt_setting">Show Setting Panel</label></td>
  <td id="opt_some_settings0" title="Show all other Settings aren't Necessary: YouTube Format Selector, Megaupload Cookie val, Additional Cookie val, Send File to Email."><input type="checkbox" name="opt_some_settings" id="opt_some_settings">
    <label for="opt_some_settings">Show All Settings</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr><td title="Make File List Columns Clickable to Sort the List"><input type="checkbox" value="1" name="opt_flist_sort" id="opt_flist_sort"> <label for="opt_flist_sort">Make file list sortable</label></td>
                <td width="50%" title="To Show All Files in the Catalog, uncheck to Hide Them"><input type="checkbox" value="1" name="opt_show_all" id="opt_show_all"> <label for="opt_show_all">Show all files, not only downloaded</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr>
                <td title="Server Info: CPU, Memory & Time"><input type="checkbox" value="1" name="opt_server_info" id="opt_server_info"> <label for="opt_server_info">Show Server Information</label></td>
                <td width="50%" title="Use Ajax to Auto Refresh Server Info every 2 seconds"><input type="checkbox" value="1" name="opt_ajax_refresh" id="opt_ajax_refresh"> <label for="opt_ajax_refresh">Auto Refresh Server Info</label></td>
              </tr>
              <tr>
                <td title="Users Stats: Online User(s), Today Visits"><input type="checkbox" value="1" name="opt_stats" id="opt_stats"> <label for="opt_stats">Show Users Statistics</label></td>
              </tr>
            </table>
</div>
 <div id="tb3" class="hide_table">
      <div class="onvan hide_table">File Actions Restrictions</div>
        <table class="neveshte_inner">
          <tr>
            <td title="Disable All File Actions"><input type="checkbox" value="1" name="opt_disable_actions" id="opt_disable_actions"> <label for="opt_disable_actions"><strong>Disable all actions</strong></label></td>
            </tr>
</table>
<table class="neveshte_inner">
              <tr><td width="50%" title="Disable Delete Action"><input type="checkbox" value="1" name="opt_disable_delete" id="opt_disable_delete"> <label for="opt_disable_delete">Disable Delete</label></td>
                <td title="Disable deleting on all actions <b style='color:rgb(200,0,0)'>(Except Delete)</b>"><input type="checkbox" value="1" name="opt_disable_deleting" id="opt_disable_deleting"> <label for="opt_disable_deleting">Disable Deleting</label></td>
              </tr>
              <tr><td title="Disable Rename Action"><input type="checkbox" value="1" name="opt_disable_rename" id="opt_disable_rename"> <label for="opt_disable_rename">Disable Rename</label></td>
                <td title="Disable Massive Rename"><input type="checkbox" value="1" name="opt_disable_mass_rename" id="opt_disable_mass_rename"> <label for="opt_disable_mass_rename">Disable Massive Rename</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr><td width="50%" title="Disable Email Action"><input type="checkbox" value="1" name="opt_disable_email" id="opt_disable_email"> <label for="opt_disable_email">Disable Email</label></td>
                <td title="Disable Massive Email Action"><input type="checkbox" value="1" name="opt_disable_mass_email" id="opt_disable_mass_email"> <label for="opt_disable_mass_email">Disable Massive Email</label></td>
              </tr>
              <tr><td title="Disable FTP File Action"><input type="checkbox" value="1" name="opt_disable_ftp" id="opt_disable_ftp"> <label for="opt_disable_ftp">Disable FTP</label></td>
                <td title="Disable Upload File Action"><input type="checkbox" value="1" name="opt_disable_upload" id="opt_disable_upload"> <label for="opt_disable_upload">Disable Upload</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr><td width="50%" title="Disable MD5 File Action"><input type="checkbox" value="1" name="opt_disable_md5" id="opt_disable_md5"> <label for="opt_disable_md5">Disable MD5</label></td>
                <td title="Disable MD5 Files Change Action"><input type="checkbox" value="1" name="opt_disable_md5_change" id="opt_disable_md5_change"> <label for="opt_disable_md5_change">Disable MD5 Change</label></td>
              </tr>
              <tr><td title="Disable List Files Action"><input type="checkbox" value="1" name="opt_disable_list" id="opt_disable_list"> <label for="opt_disable_list">Disable List</label></td><td>&nbsp;</td></tr>
            </table>
            <table class="neveshte_inner">
              <tr><td width="50%" title="Disable Merge *.001, *.002 Files Action"><input type="checkbox" value="1" name="opt_disable_merge" id="opt_disable_merge"> <label for="opt_disable_merge">Disable Merge</label></td>
                <td title="Disable Split to *.001, *.002 Files Action"><input type="checkbox" value="1" name="opt_disable_split" id="opt_disable_split"> <label for="opt_disable_split">Disable Split</label></td>
              </tr>
            </table>
            <table class="neveshte_inner">
              <tr><td width="50%" title="Allow Compression in Tar, Zip &amp; Rar Archives if Possible"><input type="checkbox" value="1" name="opt_disable_archive_compression" id="opt_disable_archive_compression"> <label for="opt_disable_archive_compression">Disable Compression</label></td>
                <td title="Disable Compress to Tar Action"><input type="checkbox" value="1" name="opt_disable_tar" id="opt_disable_tar">
 <label for="opt_disable_tar">Disable Tar</label></td>
              </tr>
              <tr><td title="Disable Compress to Zip Action"><input type="checkbox" value="1" name="opt_disable_zip" id="opt_disable_zip"> <label for="opt_disable_zip">Disable Zip</label></td>
                <td title="Disable UnZip Compress Archives Action"><input type="checkbox" value="1" name="opt_disable_unzip" id="opt_disable_unzip"> <label for="opt_disable_unzip">Disable UnZip</label></td>
              </tr>
              <tr><td title="Disable Compress to Rar Action"><input type="checkbox" value="1" name="opt_disable_rar" id="opt_disable_rar"> <label for="opt_disable_rar">Disable Rar</label></td>
                <td title="Disable UnRar Compress Archives Action"><input type="checkbox" value="1" name="opt_disable_unrar" id="opt_disable_unrar"> <label for="opt_disable_unrar">Disable UnRar</label></td>
              </tr>
            </table>
</div>
 <div id="tb4" class="hide_table">
      <div class="onvan hide_table">Authorization mode</div>
      <table class="neveshte_inner">  
          <tr><td title="Authorization mode" width="50%"><input type="checkbox" value="1" name="opt_login" id="opt_login"> <label for="opt_login"><strong>Enable Authorization mode</strong></label></td>
            <td id="opt_login_cgi_0"><span title="Will try to workaround CGI authorization<br>Verify that main <b style='color:rgb(200,0,0)'>.htaccess</b> is <b>WRITEABLE</b> before saving the config so the CGI fix can be applied correctly."><input type="checkbox" value="1" name="opt_login_cgi" id="opt_login_cgi"> <label for="opt_login_cgi">Enable CGI authorization fix</label></span>
              </td>
            </tr>
</table>
        
        <div id="opt_login_0">
          <table id="opt_login_table" class="table_opt neveshte_inner">
            <thead>
            <tr><td width="10%">&nbsp;</td>
            <td width="40%">Username<br></td>
            <td>Password<br></td></tr>
          </thead><tbody>
          <tr>
            <td><input id="opt_login_add" type="button" value="+ Add user" style="width:auto"></td>
            <td><input type="text" name="users[]"></td><td><input type="text" name="passwords[]"></td></tr>
        </tbody></table></div>
        </div>

 <div id="tb5" class="hide_table">
      <div class="onvan hide_table">Advanced Options</div>
        <div style="text-align: center; padding-bottom: 10px;"><strong>(You don't need to change these unless you know what you are doing)</strong></div>
        <table class="neveshte_inner">
          <tr><td width="50%" title="Try to List Files Bigger than 2GB on 32Bit OS"><input type="checkbox" value="1" name="opt_2gb_fix" id="opt_2gb_fix"> <label for="opt_2gb_fix">2GB Fix</label></td>
            <td title="If True, Prohibition by Browser; Otherwise Allowed"><input type="checkbox" value="1" name="opt_no_cache" id="opt_no_cache"> <label for="opt_no_cache">No Cache</label></td>
            </tr></table>
            <table class="neveshte_inner">
          <tr><td width="50%" title="Enter the Forbidden Filetypes in the Given Way"><label for="opt_forbidden_filetypes">Forbidden File Types</label><br><input type="text" id="opt_forbidden_filetypes" name="opt_forbidden_filetypes"></td>
            <td title="If <b><i>Block Download of Forbidden file types</i></b> be <b style='color:rgb(200,0,0)'>UnChecked</b>, then Rename those Filetypes to this"><label for="opt_rename_these_filetypes_to">Rename Forbidden File Types to</label><br>
              <input type="text" size="8" value="" name="opt_rename_these_filetypes_to" id="opt_rename_these_filetypes_to"></td>
          </tr>
          <tr>
            <td title="If Not Checked, Rename <b><i>Forbidden File Types</i></b>; otherwise Completely Block them"><input type="checkbox" value="1" name="opt_forbidden_filetypes_block" id="opt_forbidden_filetypes_block"> <label for="opt_forbidden_filetypes_block">Block Download of Forbidden File Types</label></td>
            <td title="Don't allow Extraction/Creation of <b><i>Forbidden File Types</i></b> from File Actions"><input type="checkbox" value="1" name="opt_check_these_before_unzipping" id="opt_check_these_before_unzipping"> <label for="opt_check_these_before_unzipping">Block Forbidden File Types for File Actions</label></td>
            </tr></table>
            <table class="neveshte_inner">
          <tr><td colspan="2"><label for="opt_fgc">FGC</label>
              <br>
            <input style="width:99%" type="text" value="" size="2" name="opt_fgc" id="opt_fgc"></td></tr>
          <tr>
            <td width="50%" title="Rapidshare Images are Downloaded through the Script, But it Requires SSL Support; Uncheck it if You Can't See the Images."><input type="checkbox" value="1" name="opt_images_via_php" id="opt_images_via_php"> <label for="opt_images_via_php">Images via PHP</label></td>
            <td title="Redirect Passive Method"><input type="checkbox" value="1" name="opt_redir" id="opt_redir"> <label for="opt_redir">Redirect Passive Method</label></td>
          </tr>
        </table>
</div></span>
</div></div></div></div>
</td></tr><tr><td colspan="3">
<div id="spacer">&nbsp;</div>
<div color="<?php echo $amin_color; ?>" class="defult" title="<div align='center' style='width:674px'>
        <strong>If you need to do rapidleech setup again,</strong>
        <div align='justify'><table><tr valign='top'><td width='50%'>
          <b>- To load default config:</b><br />delete <i>ROOT/configs/<b>config.php</b></i>.</td>
          <td width='10'>&nbsp;</td><td><b>- To work on your old config:</b><br> 
          rename <i>ROOT/configs/<b>config.php</b></i> to <i><b style='color:rgb(200,0,0)'>config_old.php</b></i>.</td></tr></table>
          <p>After that, go to your rapidleech url to access setup.</p>
        </div>
        </div>">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan">Save Changes</div>
      <div class="neveshte">
      <div class="neveshte_inner" align="center">
        <input type="hidden" value="1" name="setup_save">
        <input style="width:auto" type="button" value="Save Configuration" id="save" name="save" disabled="disabled">
        <input style="width:auto" type="button" value="Reset" id="reset" name="reset" disabled="disabled">
</div>
    </div></div></div></div>
    </td></tr></table>
</form>
</div>
<div align="center" id="footer">
<?php
$emsal = date(Y);
$emsal == 2011 ? $tarikhe_copy = '.' : $tarikhe_copy = '-'.$emsal;

echo '&copy; Copyright <a href="" title="'.$options['site_title_description'].'">'.$options['site_title'].'</a> 2011'.$tarikhe_copy.' | All Rights Reserved';
?>
<br>
<!--
THIS OPEN SOURCE PROJECT IS UNDER LICENSE OF PHP LICENCE & SOURCEFORGE.NET.
IF YOU REMOVE BOTTOM CODES, YOU WILL PROBATE UNDER CONTINUATION OF FEDERAL!
SO, YOU CANNOT CHANGE ANYTHINGS HERE.
 --> 
<?php 
 echo '{<a href="https://sourceforge.net/projects/enterprise-rl/" target="_blank" title="Enterprise is a new Rapidleech script template based on HTML5, CSS3 and jQuery.">Enterprise</a>} Template by <a href="http://www.amingholami.com" alt="AminGholami.com" target="_blank" >AminGholami.com </a> <span> </span>
</div>';
?></div>
<div id="tip"></div>
<?php
}
?>
</body>
</html><?php exit; ?>