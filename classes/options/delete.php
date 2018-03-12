<?php
function delete() {
	global $list, $PHP_SELF;
?>
<div align="left"><strong>Delete File</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="delete_go" />
<?php
	echo lang(count($_GET['files']) > 1 ? 379 : 104).':';
	foreach ($_GET['files'] as $k => $v) {
		echo '<input type="hidden" name="files[]" value="'.$v.'" /> ';
		echo '<b>'.htmlentities(basename($list[$v]['name'])).'</b>, ';
	}
?>
<br />
<strong><?php echo lang(148); ?>?</strong>
<br />
<table>
	<tr>
		<td><input type="submit" name="yes" value="<?php echo lang(149); ?>" />
		</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><input type="submit" name="no" value="<?php echo lang(150); ?>" />
		</td>
	</tr>
</table>
</form>
</div>
<?php
}

function delete_go() {
	global $list, $PHP_SELF;
	if (isset($_POST["yes"])) {
		for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
			$file = $list [$_POST ["files"] [$i]];
			if (file_exists ( $file ["name"] )) {
				if (@unlink ( $file ["name"] )) {
					echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
					printf(lang(151),$file['name']);
					echo "</div>";
					unset ( $list [$_POST ["files"] [$i]] );
				} else {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(152),$file['name']);
					echo "<div />";
				}
			} else {
				unset ( $list [$_POST ["files"] [$i]] );
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
				printf(lang(145),$file['name']);
				echo "</div>";
			}
		}
		if (! updateListInFile ( $list )) {
			echo lang(146)."<br /><br />";
		}
	} else {
		echo('<script type="text/javascript">location.href="'.$PHP_SELF.'?act=files";</script>');
	}
}
?>