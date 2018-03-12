<?php
function md5_change() {
	global $list, $PHP_SELF;
?><div align="left"><strong>MD5 Change File</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="md5_change_go" />
<?php
	echo lang(count($_GET['files']) > 1 ? 379 : 104).':';
	foreach ($_GET['files'] as $k => $v) {
		echo '<input type="hidden" name="files[]" value="'.$v.'" /><br />';
		echo '<b>'.htmlentities(basename($list[$v]['name'])).'</b>';
	}
	echo '<br /><br />'.lang(380);
?>
<br />
<table>
	<tr>
		<td><input type="submit" name="yes" style="width:auto" value="<?php echo lang(149); ?>" /></td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><input type="submit" name="no" style="width:auto" value="<?php echo lang(150); ?>" /></td>
	</tr>
</table>
</form>
</div>
<?php
}

function md5_change_go() {
	global $list, $PHP_SELF;
	if (isset($_POST['yes'])) {
		foreach ($_POST['files'] as $k => $v) {
			$name = $list[$v]['name'];
			$html_name = htmlentities(basename($name));
			if (file_exists($name)) {
				if (@write_file($name, chr(0), 0)) {
					clearstatcache();
					echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
					printf(lang(381), $html_name);
					echo '</div>';
					unset($list[$v]);
					$time = filemtime($name); while (isset($list[$time])) { $time++; }
					$list[$time] = array('name' => $name, "size" => bytesToKbOrMbOrGb(filesize($name)), "date" => $time);
				}
				else { 
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
				printf(lang(382), $html_name);
				echo '</div>'; }
			}
			else { echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			printf(lang(145), $html_name);
			echo '</div>'; }
		}
		if (!updateListInFile($list)) { echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">'.lang(146)."</div><br />"; }
	}
	else { echo('<script type="text/javascript">location.href="'.$PHP_SELF.'?act=files";</script>'); }
}
?>