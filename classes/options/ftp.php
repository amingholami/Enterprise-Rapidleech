<?php
function ftp() {
	global $list, $options, $PHP_SELF;
?>
<div align="left"><strong>FTP files to another server</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="act" value="ftp_go" />
<?php echo lang(104); ?>:
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [($_GET ["files"] [$i])];
?>
<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp;"; ?>
<?php
	}
?><br />
		<br />
		<table align="center" style="text-align: left;" width="100%">
			<tr>
				<td>
				<table width="100%">
					<tr>
						<td width="50"><small><?php echo lang(153); ?>:</small></td>
						<td><input type="text" name="host" id="host" <?php echo $_COOKIE ["host"] ? ' value="' . $_COOKIE ["host"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><small><?php echo lang(154); ?>:</small></td>
						<td><input type="text" name="port" id="port" <?php echo $_COOKIE ["port"] ? ' value="' . $_COOKIE ["port"] . '"' : ' value="21"'; ?>
							size="4" /></td>
					</tr>
					<tr>
						<td><small><?php echo lang(37); ?>:</small></td>
						<td><input type="text" name="login" id="login" <?php echo $_COOKIE ["login"] ? ' value="' . $_COOKIE ["login"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><small><?php echo lang(38); ?>:</small></td>
						<td><input type="password" name="password" id="password" <?php echo $_COOKIE ["password"] ? ' value="' . $_COOKIE ["password"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><small><?php echo lang(155); ?>:</small></td>
						<td><input type="text" name="dir" id="dir" <?php echo $_COOKIE ["dir"] ? ' value="' . $_COOKIE ["dir"] . '"' : ' value="/"'; ?>
							size="23" /></td>
					</tr>
					<tr><td>&nbsp;</td>
						<td><label><input type="checkbox" name="del_ok" <?php if ($options['disable_deleting']) echo 'disabled="disabled"'; ?> />&nbsp;<?php echo lang(156); ?></label></td>
					</tr>
				</table>
				</td>
				<td width="10">&nbsp;</td>
				<td width="100">
				<table width="100%">
					<tr align="center">
						<td><input type="submit" value="Upload" /></td>
					</tr>
					<tr align="center">
						<td>&nbsp;</td>
					</tr>
					<tr align="center">
						<td><label><input type="checkbox" checked="checked" onclick="javascript:var displ=this.checked? setFtpParams():delFtpParams();displ;" /><small><?php echo lang(157); ?></small></label>
                       </td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</form></div>
<?php
}

function ftp_go() {
	global $list, $options;
	require_once (CLASS_DIR . "ftp.php");
	$ftp = new ftp ( );
	if (! $ftp->SetServer ( $_POST ["host"], ( int ) $_POST ["port"] )) {
		$ftp->quit ();
		echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
		printf(lang(79),$_POST ["host"] . ":" . $_POST ["port"]);
		echo '<br /><a href="javascript:history.back(-1);">'.lang(78).'</a></div>';
	} else {
		if (! $ftp->connect ()) {
			$ftp->quit ();
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			printf(lang(79),$_POST ["host"] . ":" . $_POST ["port"]);
			echo '<br /><a href="javascript:history.back(-1);">'.lang(78).'</a></div>';
		} else {
			printf(lang(81),'ftp://'.$_POST['host'].':'.$_POST['port']);
			if (! $ftp->login ( $_POST ["login"], $_POST ["password"] )) {
				$ftp->quit ();
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
				echo lang(80);
				echo '<br /><a href="javascript:history.back(-1);">'.lang(78).'</a></div>';
			} else {
				//$ftp->Passive(FALSE);
				if (! $ftp->chdir ( $_POST ["dir"] )) {
					$ftp->quit ();
					echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
					printf(lang(159),$_POST['dir']);
					echo '<br /><a href="javascript:history.back(-1);">'.lang(78).'</a></div>';
				} else {
?>
<br />
				<div id="status"></div>
				<br />
				<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td></td>
						<td>
						<div class="progressouter">
						<div style="width:298px">
							<div id="progress" class="ftpprogress"></div>
						</div>
						</div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td align="left" id="received">0 KB</td>
						<td align="center" id="percent">0%</td>
						<td align="right" id="speed">0 KB/s</td>
					</tr>
				</table>
				<br />
<?php
					for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
						$file = $list [$_POST ["files"] [$i]];
						echo '<script type="text/javascript">changeStatus('."'" . addslashes(basename ( $file ["name"] )) . "', '" . $file ["size"] . "');</script>";
						$FtpBytesTotal = filesize ( $file ["name"] );
						$FtpTimeStart = getmicrotime ();
						if ($ftp->put ( $file ["name"], basename ( $file ["name"] ) )) {
							$time = round ( getmicrotime () - $FtpTimeStart );
							$speed = @round ( $FtpBytesTotal / 1024 / $time, 2 );
							echo '<script type="text/javascript">pr(100, '."'" . bytesToKbOrMbOrGb ( $FtpBytesTotal ) . "', " . $speed . ")</script>\r\n";
							flush ();
							
							if ($_POST["del_ok"] && !$options['disable_deleting']) {
								if (@unlink ( $file ["name"] )) {
									unset ( $list [$_POST ["files"] [$i]] );
								}
							}
							
								printf(lang(160),'<a href="ftp://' . $_POST ["login"] . ':' . $_POST ["password"] . '@' . $_POST ["host"] . ':' . $_POST ["port"] . $_POST ["dir"] . '/' . basename ( $file ["name"] ) . '"><b>' . basename ( $file ["name"] ) . '</b></a>');
								echo "<br />".lang(161).": <b>" . sec2time ( $time ) . "</b><br />".lang(162).": <b>" . $speed . " KB/s</b><br /><br />";
						} else {
							printf(lang(163),basename($file['name']));
							echo "<br />";
						}
					}
					$ftp->quit ();
				}
			}
		}
	
	}
}
?>