<?php
function rl_list() {
	global $list, $options;
	if ($list) {
?>
<div align="left"><strong>List Files</strong></div>
<div class="neveshte_inner">
<table width="100%" border="0" id="list_files">
  <tr align="left">
    <td width="50%">
      <table width="100%">
      <?php
              foreach($list as $file) {
                  if(file_exists($file["name"])) {
                  echo '<tr><td>'.htmlentities(basename($file["name"])).'</td></tr>'.$nn;
                  }
                  else if ($options['2gb_fix'] && file_exists($file) && !is_dir($file) && !is_link($file)) {
                      echo '<tr><td>'.htmlentities(basename($file["name"])).'</td></tr>'.$nn;
                  }
              }
      ?>
      </table>
      </td>
      <td>
      <table width="100%">
      <?php
              foreach($list as $file) {
                  if(file_exists($file["name"])) {
                      echo '<tr><td><a href="'.link_for_file($file["name"], TRUE).'">'.link_for_file($file["name"], TRUE).'</a></td></tr>'.$nn;
                  }
                  else if ($options['2gb_fix'] && file_exists($file) && !is_dir($file) && !is_link($file)) {
                      echo '<tr><td><a href="'.link_for_file($file["name"], TRUE).'">'.link_for_file($file["name"], TRUE).'</a></td></tr>'.$nn;
                  }
              }
      ?>
      </table>
    </td>
  </tr>
</table>
</div>
<?php
	}
}
?>