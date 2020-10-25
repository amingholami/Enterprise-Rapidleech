<?php
// Access denied page
define('RAPIDLEECH', 'yes');
error_reporting(0);
//ini_set('display_errors', 1);
set_time_limit(0);
ini_alter("memory_limit", "1024M");
ob_end_clean();
ob_implicit_flush(TRUE);
ignore_user_abort(1);
clearstatcache();
$PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;
define('HOST_DIR', 'hosts/');
define('IMAGE_DIR', 'images/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', 'configs/');
define('RAPIDLEECH', 'yes');
define('ROOT_DIR', realpath("./"));
define('PATH_SPLITTER', (strstr(ROOT_DIR, "\\") ? "\\" : "/"));
require_once("configs/config.php");
if (substr($options['download_dir'],-1) != '/') $options['download_dir'] .= '/';
define('DOWNLOAD_DIR', (substr($options['download_dir'], 0, 6) == "ftp://" ? '' : $options['download_dir']));
define ( 'TEMPLATE_DIR', 'templates/'.$options['template_used'].'/' );
$nn = "\r\n";
require_once("classes/other.php");
include(TEMPLATE_DIR.'header.php');
?>
<div color="<?php echo $amin_color; ?>" class="defult">
  <div class="_div2">
    <div class="_div3">
     <div class="onvan"><?php echo lang(1); ?></div>
      <div class="neveshte">
        <div class="neveshte_inner" align="center">
          <h2><div class="neveshte_inner_error" align="center" title="Click to Hide!"><?php echo lang(1) ?></div></h2> 
          <p><strong><?php echo lang(2) ?></strong></p>       
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div align="center" id="footer">
<?php
$emsal = date(Y);
$emsal == 2011 ? $tarikhe_copy = '.' : $tarikhe_copy = '-'.$emsal;

echo '&copy; Copyright <a href="" title="'.$options['site_title_description'].'">'.$options['site_title'].'</a> 2011'.$tarikhe_copy.' | All Rights Reserved';
?>
<br />
<!--
THIS OPEN SOURCE PROJECT IS UNDER LICENSE OF PHP LICENCE & SOURCEFORGE.NET.
IF YOU REMOVE BOTTOM CODES, YOU WILL PROBATE UNDER CONTINUATION OF FEDERAL!
SO, YOU CANNOT CHANGE ANYTHINGS HERE.
 --> 
<?php 
 echo '{<a href="https://sourceforge.net/projects/enterprise-rl/" target="_blank" title="Enterprise is a new Rapidleech script template based on HTML5, CSS3 and jQuery.">Enterprise</a>} Template by <a href="http://www.amingholami.com" alt="AminGholami.com" target="_blank" >AminGholami.com </a> <span> </span>
</div>';
?>
<div id="tip"></div>
</body>
<?php
include(TEMPLATE_DIR.'footer.php');
?>