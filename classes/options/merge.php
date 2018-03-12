<?php
function merge() {
	global $options, $list, $PHP_SELF;
	if (count($_GET["files"]) !== 1) {
		echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(167)."</strong></div>";
	}
	else {
		$file = $list [$_GET ["files"] [0]];
		if (substr ( $file ["name"], - 4 ) == '.001' && is_file ( substr ( $file ["name"], 0, - 4 ) . '.crc' )) {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(168)."</strong></div>";
		} elseif (substr ( $file ["name"], - 4 ) !== '.crc' && substr ( $file ["name"], - 4 ) !== '.001') {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(169)."</strong></div>";
		} else {
			echo "<div align=\"left\"><strong>Merging *.0** Files</strong></div>
<div class=\"neveshte_inner\" align=\"left\"><b>".basename(substr($file["name"], 0, -4))."</b><br />";
			$usingcrcfile = (substr ( $file ["name"], - 4 ) === '.001') ? false : true;
?>
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="files[0]" value="<?php echo $_GET ["files"] [0]; ?>" />
<table width="100%" class="neveshte_inner">
<?php
			if ($usingcrcfile) {
?>
<tr valign="top">
<td align="left"><label><input type="checkbox" name="crc_check" value="1" checked="checked" onclick="javascript:$('#crc_check_mode').toggle('slow')" />&nbsp;<?php echo lang(170); ?></label><br>
<label><input type="checkbox" name="del_ok" <?php echo $options['disable_deleting'] ? 'disabled="disabled"' : 'checked="checked"'; ?> />&nbsp;<?php echo lang(175); ?></label></td>
<td><span id="crc_check_mode"><b><?php echo lang(171); ?>:</b><br />
<?php
				if (function_exists ( 'hash_file' )) {
?><label><input type="radio" name="crc_mode" value="hash_file" checked="checked" />&nbsp;<?php echo lang(172); ?></label><br />
<?php } ?>
<label><input type="radio" name="crc_mode" value="file_read" />&nbsp;<?php echo lang(173); ?></label><br />
<label><input type="radio" name="crc_mode" value="fake"<?php if (! function_exists ( 'hash_file' )) { echo 'checked="checked"'; }?> />&nbsp;<?php echo lang(174); ?></label></span></td>
</tr>
<?php
					} else {
?>
<tr>
<td align="center"><?php echo lang(176); ?>: <b><?php echo lang(177); ?></b></td>
</tr>
<?php
					}
?>
</table>
<div align="center"><input type="hidden" name="act" value="merge_go" /> <input style="width:auto" type="submit" value="<?php echo lang(291); ?>" /></div>
</form>
</div>
<?php
		}
	}
}

function merge_go() {
	global $list, $options;
	if (count($_POST["files"]) !== 1) {
		echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(167)."</strong></div>";
	} else {
		$file = $list [$_POST ["files"] [0]];
		if (substr ( $file ["name"], - 4 ) == '.001' && is_file ( substr ( $file ["name"], 0, - 4 ) . '.crc' )) {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(168)."</strong></div>";
		} elseif (substr ( $file ["name"], - 4 ) !== '.crc' && substr ( $file ["name"], - 4 ) !== '.001') {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(169)."</strong></div>";
		} else {
			$usingcrcfile = (substr ( $file ["name"], - 4 ) === '.001') ? false : true;
			if (! $usingcrcfile) {
				$data = array ('filename' => basename ( substr ( $file ["name"], 0, - 4 ) ), 'size' => - 1, 'crc32' => '00111111' );
			} else {
				$fs = @fopen ( $file ["name"], "rb" );
			}
			if ($usingcrcfile && ! $fs) {
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(178)."</strong></div>";
			} else {
				if ($usingcrcfile) {
					$data = array ();
					while ( ! feof ( $fs ) ) {
						$data_ = explode ( '=', trim ( fgets ( $fs ) ), 2 );
						$data [$data_ [0]] = $data_ [1];
					}
					fclose ( $fs );
				}
				$path = realpath ( DOWNLOAD_DIR ) . '/';
				$filename = basename ( $data ['filename'] );
				$partfiles = array ();
				$partsSize = 0;
				for($j = 1; $j < 10000; $j ++) {
					if (! is_file ( $path . $filename . '.' . sprintf ( "%03d", $j ) )) {
						if ($j == 1) {
							$partsSize = - 1;
						}
						break;
					}
					$partfiles [] = $path . $filename . '.' . sprintf ( "%03d", $j );
					$partsSize += filesize ( $path . $filename . '.' . sprintf ( "%03d", $j ) );
				}
				if (file_exists ( $path . $filename )) {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(179),$path . $filename);
					echo "</div>";
				} elseif ($usingcrcfile && $partsSize != $data ['size']) {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(180)."</strong></div>";
				} elseif ($options['check_these_before_unzipping'] && is_array ( $options['forbidden_filetypes'] ) && in_array ( strtolower ( strrchr ( $filename, "." ) ), $options['forbidden_filetypes'] )) {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(181),strrchr ( $filename, "." ));
					echo "</div>";
				} else {
					$merge_buffer_size = 2 * 1024 * 1024;
					$merge_dest = @fopen ( $path . $filename, "wb" );
					if (! $merge_dest) {
						echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
						printf(lang(182),$path . $filename);
						echo "</div>";
					} else {
						$merge_ok = true;
						foreach ( $partfiles as $part ) {
							$merge_source = @fopen ( $part, "rb" );
							while ( ! feof ( $merge_source ) ) {
								$merge_buffer = fread ( $merge_source, $merge_buffer_size );
								if ($merge_buffer === false) {
									echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
									printf(lang(65),$part);
									echo "</div>";
									$merge_ok = false;
									break;
								}
								if (fwrite ( $merge_dest, $merge_buffer ) === false) {
									echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
									printf(lang(183),$path . $filename);
									echo "</div>";
									$merge_ok = false;
									break;
								}
							}
							fclose ( $merge_source );
							if (! $merge_ok) {
								break;
							}
						}
						fclose ( $merge_dest );
						if ($merge_ok) {
							$fc = ($_POST ['crc_mode'] == 'file_read') ? dechex ( crc32 ( read_file ( $path . $filename ) ) ) : (($_POST ['crc_mode'] == 'hash_file' && function_exists ( 'hash_file' )) ? hash_file ( 'crc32b', $path . $filename ) : '111111');
							$fc = str_repeat ( "0", 8 - strlen ( $fc ) ) . strtoupper ( $fc );
							if ($fc != strtoupper ( $data ["crc32"] )) {
								echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(184)."</strong></div>";
							} else {
								echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
								printf(lang(185),$filename);
								echo '!</div>';
								if ($usingcrcfile && $fc != '00111111' && $_POST["del_ok"] && !$options['disable_deleting']) {
									if ($usingcrcfile) {
										$partfiles [] = $file ["name"];
									}
									foreach ( $partfiles as $part ) {
										if (@unlink ( $part )) {
											foreach ( $list as $list_key => $list_file ) {
												if ($list_file ["name"] === $part) {
													unset ( $list [$list_key] );
												}
											}
											echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">';
											echo "<b>" . basename ( $part ) . "</b> ".lang(186).".</div>";
										} else {
											echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
											echo "<b>" . basename ( $part ) . "</b> ".lang(187).".</div>";
										}
									}
								}
								$time = filemtime($path.$filename);
								while ( isset ( $list [$time] ) ) {
									$time ++;
								}
								$list [$time] = array ("name" => $path . $filename, "size" => bytesToKbOrMbOrGb ( $partsSize ), "date" => $time );
								if (! updateListInFile ( $list )) {
									echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(146)."<br /><br />";
								}
							}
						}
					}
				}
			}
		}
	}
}
?>