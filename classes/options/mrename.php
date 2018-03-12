<?php
function mrename() {
	global $list, $PHP_SELF;
?>
<div align="left"><strong>Mass Rename Extension</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="mrename_go" />
<?php echo lang(104); ?>:
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
?>
<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp;"; ?>
<?php
	}
?>
<table width="100%">
<tr>
<td valign="middle"><b><?php echo lang(188); ?>&nbsp;</b>&nbsp; <b><?php echo lang(189); ?></b>&nbsp;(dot)<br><input title="File.ext to Files.ext.new_ext" type="text" name="extension" size="10" value="" /><br>
<input name="yes" type="submit" style="width:auto" value="<?php echo lang(191); ?>" />&nbsp;&nbsp;
<input name="no" type="submit" style="width:auto" value="<?php echo lang(192); ?>" /></td>
</tr>
</table>
</form>
</div>
<?php
}

function mrename_go() {
	global $list, $options, $PHP_SELF;
	if ($_POST ["yes"] && @trim($_POST['extension'])) {
		$_POST ['extension'] = @trim ( $_POST ['extension'] );
		
		while ( $_POST ['extension'] [0] == '.' )
			$_POST ['extension'] = substr ( $_POST ['extension'], 1 );
		
		if ($_POST [extension]) {
			for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
				$file = $list [$_POST ["files"] [$i]];
				if (file_exists ( $file ["name"] )) {
					$filetype = '.' . strtolower ( $_POST ['extension'] );
					if (is_array ( $options['forbidden_filetypes'] ) && in_array ( '.' . strtolower ( $_POST ['extension'] ), $options['forbidden_filetypes'] )) {
						echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
						printf(lang(82),$filetype);
						echo('</div>');
					} else {
						if (@rename ( $file ["name"], fixfilename ( $file ["name"] . ".{$_POST['extension']}" ) )) {
							echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
							printf(lang(194).'</div>',basename($file['name']),fixfilename ( basename ( $file ["name"] . ".{$_POST['extension']}" ) ));
							$list [$_POST ["files"] [$i]] ["name"] .= '.' . $_POST ['extension'];
							$list [$_POST ["files"] [$i]] ["name"] = fixfilename ( $list [$_POST ["files"] [$i]] ["name"] );
						} else {
							echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
							printf(lang(193),basename($file['name']));
							echo '</div>';
						}
					}
				} else {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(145),basename($file['name']));
					echo('/div>');
				}
			}
			if (! updateListInFile ( $list ))
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>';
				echo lang(146)."</strong></div>";
		}
	} else {
?>
<script type="text/javascript">location.href="<?php echo substr ( $PHP_SELF, 0, strlen ( $PHP_SELF ) - strlen ( strstr ( $PHP_SELF, "?" ) ) ) . "?act=files"; ?>";</script>
<?php
	}
}
?>