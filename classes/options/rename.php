<?php
function rl_rename() {
	global $list, $PHP_SELF;
?>
<div align="left"><strong>Rename File</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="rename_go" />
		<table width="100%" align="center" style="text-align: left;">
			<tr>
				<td>
				<table width="100%">
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
?>
<tr><td><small>Real name:</small></td>
	<td align="left"><input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /><b> <?php echo basename ( $file ["name"] ); ?></b></td>
</tr>
<tr>
	<td width="50"><small><?php echo lang(201); ?>:</small></td><td><input type="text" name="newName[]" size="25"
		value="<?php echo basename ( $file ["name"] ); ?>" /></td>
</tr><tr><td>&nbsp;</td></tr>
<?php
	}
?>
                                  </table>
				</td>
				
			</tr>
			<tr>
				<td align="center"><input style="width:80px" type="submit" value="Rename" /></td>
			</tr>
		</table>
		</form></div>
<?php
}

function rename_go() {
	global $list, $options;
	$smthExists = FALSE;
	for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
		$file = $list [$_POST ["files"] [$i]];
		
		if (file_exists ( $file ["name"] )) {
			$smthExists = TRUE;
			$newName = dirname ( $file ["name"] ) . PATH_SPLITTER . stripslashes(basename($_POST["newName"][$i]));
			$filetype = strrchr ( $newName, "." );
			
			if (is_array ( $options['forbidden_filetypes'] ) && in_array ( strtolower ( $filetype ), $options['forbidden_filetypes'] )) {
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>';
				printf(lang(82),$filetype);
				echo "</strong></div>";
			} else {
				if (@rename ( $file ["name"], $newName )) {
					echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
					printf(lang(194),$file['name'],basename($newName));
					echo "</div>";
					$list [$_POST ["files"] [$i]] ["name"] = $newName;
				} else {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(202),$file['name']);
					echo "</div>";
				}
			}
		} else {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			printf(lang(145),$file['name']);
			echo "</div>";
		}
	}
	if ($smthExists) {
		if (! updateListInFile ( $list )) {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			echo lang(9)."</div>";
		}
	}
}
?>