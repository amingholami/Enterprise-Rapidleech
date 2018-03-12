<?php
function unzip() {
	global $list, $PHP_SELF;
?>
<div align="left"><strong>Extracting files from ZIP archive</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="act" value="unzip_go" />
	<table align="center" width="100%">
		<tr>
			<td>
				<table width="100%">
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
		require_once (CLASS_DIR . "unzip.php");
		if (file_exists($file['name'])) {
			$zip = new dUnzip2 ( $file['name'] );
			$flist = $zip->getList();
?>
					<tr><td align="left">
						<input type="hidden" name="files[]" value="<?php echo $_GET['files'][$i]; ?>" />
						<b><?php echo basename($file['name']); ?></b> (<?php echo count($flist).' '.lang(204); ?>):
					</td></tr>
					<tr><td>
						<div class="PO_in" align="left" style="overflow-y:scroll; height:70px; margin-right:10px">
<?php
			foreach ($flist as $property) {
				echo('<small>'.$property['file_name'].'</small><br />');
			}
?>
						</div>
					</td></tr>
<?php
		}
	}
?>
				</table>
			</td><td width="10">&nbsp;</td>
		</tr>
		<tr><td align="center"><input style="width:80px" type="submit" name="submit" value="<?php echo lang(205); ?>" /></td></tr>
	</table>
</form>
</div>
<?php
}

function unzip_go() {
	global $list, $options;
	require_once (CLASS_DIR . "unzip.php");
	$any_file_unzippped = false;
	for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
		$file = $list [$_POST ["files"] [$i]];
		if (file_exists ( $file ["name"] )) {
			$zip = new dUnzip2 ( $file ["name"] );
			$allf = $zip->getList ();
			$file_inside_zip_exists = false;
			$forbidden_inside_zip = false;
			foreach ($allf as $k => $properties) {
				if (file_exists($options['download_dir'].basename($properties['file_name']))) {
					$file_inside_zip_exists = true; break;
				}
			}
			if ($options['check_these_before_unzipping']) {
				foreach ( $allf as $k => $property ) {
					$zfiletype = strrchr ( $property ['file_name'], "." );
					if (is_array ( $options['forbidden_filetypes'] ) && in_array ( strtolower ( $zfiletype ), $options['forbidden_filetypes'] )) {
						$forbidden_inside_zip = true; break;
					}
				}
			}
			if ($file_inside_zip_exists) {
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">Some file(s) inside <b>'.htmlentities(basename($file["name"])).'</b> already exist on download directory';
				echo "</div>";
			}
			elseif ($forbidden_inside_zip) {
				printf(lang(181), $zfiletype);
				echo "<br /><br />";
			}
			else {
				$zip->unzipAll ( $options['download_dir'] );
				if ($zip->getList () != false) {
					$any_file_unzippped = true;
					echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center">Archive: <b>'.htmlentities(basename($file["name"])).'</b><br><strong>Unzipped Successfully</strong></div>';
					foreach ($allf as $k => $properties) {
						$efile = $options['download_dir'].basename($properties['file_name']);
						if (is_file($efile)) {
							$time = filemtime($efile); while (isset($list[$time])) { $time++; }
							$list[$time] = array("name" => $efile, "size" => bytesToKbOrMbOrGb(filesize($efile)), "date" => $time);
						}
					}
					if (!updateListInFile($list)) { echo lang(146)."<br /><br />"; }
				}
				else {
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">File <b>'.$file["name"].'</b> not found!</div><br />';
				}
			}
		}
	}
}
?>