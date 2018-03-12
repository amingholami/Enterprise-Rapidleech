<?php
function boxes() {
	global $list, $options, $PHP_SELF;
?><div align="left"><strong>Send Files to E-mails</strong></div>
<div class="neveshte_inner">
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="boxes_go" />
<?php
	echo count ( $_GET ["files"] ) . " file" . (count ( $_GET ["files"] ) > 1 ? "s" : "") . ":<br />";
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [($_GET ["files"] [$i])];
?>
	<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp"; ?>
<?php
	}
?>
<table align="center" width="100%">
	<tr>
		<td><?php echo lang(139); ?>:&nbsp;<textarea required name="emails" cols="30" rows="8"><?php
	if ($_COOKIE ["email"])
		echo $_COOKIE ["email"];
		?></textarea>
		</td>
        <td width="30" rowspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"><label><input type="checkbox" name="del_ok"<?php echo $options['disable_deleting'] ? ' disabled="disabled"' : ' checked="checked"';?> />&nbsp;<?php echo lang(165); ?></label></td>
	</tr>
	<tr><td align="left">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td width="100"><label><input type="checkbox" name="split"
					onclick="javascript:$('#methodtd2').toggle('slow');"
					<?php echo $_COOKIE ["split"] ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo lang(142); ?></label></td>
				<td width="10">&nbsp;</td>
				<td id="methodtd2"<?php echo $_COOKIE ["split"] ? '' : ' style="display: none;"'; ?>>
				<table width="100%" border="0" class="PO_in">
					<tr>
						<td width="40"><small><?php echo lang(124); ?>:</small></td><td>
                        <select name="method">
							<option value="tc" <?php echo $_COOKIE ["method"] == "tc" ? ' selected="selected"' : ''; ?>>Total Commander</option>
							<option value="rfc" <?php echo $_COOKIE ["method"] == "rfc" ? ' selected="selected"' : ''; ?>>RFC 2046</option>
						</select></td>
                        <td width="10" rowspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td><small><?php echo lang(143); ?>:</small></td><td><input type="text" name="partSize" size="2"
							value="<?php echo $_COOKIE ["partSize"] ? $_COOKIE ["partSize"] : 10; ?>" />&nbsp;MB
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
  </td></tr>
  <tr>
    <td align="center"><input style="width:auto" type="submit" value="<?php echo lang(140); ?>" /></td></tr>
</table>
		</form></div>
<?php
}

function boxes_go() {
	global $list, $options, $fromaddr;
	require_once (CLASS_DIR . "mail.php");
	$_POST ["partSize"] = ((isset ( $_POST ["partSize"] ) & $_POST ["split"] == "on") ? $_POST ["partSize"] * 1024 * 1024 : FALSE);
	$v_mails = explode ( "\n", $_POST['emails'] );
	$v_min = count ( (count ( $_POST ["files"] ) < count ( $v_mails )) ? $_POST ["files"] : $v_mails );
	
	for($i = 0; $i < $v_min; $i ++) {
		$file = $list [$_POST ["files"] [$i]];
		$v_mail = trim ( $v_mails [$i] );
		if (! checkmail ( $v_mail )) {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			printf(lang(144),$v_mail);
			echo "</div>";
		} elseif (file_exists ( $file ["name"] )) {
			if (xmail ( "$fromaddr", $v_mail, "File " . basename ( $file ["name"] ), "File: " . basename ( $file ["name"] ) . "\r\n" . "Link: " . $file ["link"] . ($file ["comment"] ? "\r\nComments: " . str_replace ( "\\r\\n", "\r\n", $file ["comment"] ) : ""), $file ["name"], $_POST ["partSize"], $_POST ["method"] )) {
				if ($_POST["del_ok"] && !$options['disable_deleting']) {
					if (@unlink ( $file ["name"] )) {
						$v_ads = " and deleted!";
						unset ( $list [$_POST ["files"] [$i]] );
					} else {
						$v_ads = ", but <b>not</b> deleted!";
					}
					;
				} else
					$v_ads = " !";
				echo '<script type="text/javascript">'."mail('File <b>" . basename ( $file ["name"] ) . "</b> it is sent for the address <b>" . $v_mail . "</b>" . $v_ads . "', '" . md5 ( basename ( $file ["name"] ) ) . "');</script>\r\n<br />";
			} else {
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(12)."</strong></div>";
			}
		} else {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
			printf(lang(145),$file['name']);
			echo "</div>";
		}
	}
	if (count ( $_POST ["files"] ) < count ( $v_mails )) {
		for($i = count ( $_POST ["files"] ); $i < count ( $v_mails ); $i ++) {
			$v_mail = trim ( $v_mails [$i] );
			echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center"><strong>';
			echo "$v_mail.</strong></div>";
		}
	}
	elseif (count ( $_POST ["files"] ) > count ( $v_mails )) {
		for($i = count ( $v_mails ); $i < count ( $_POST ["files"] ); $i ++) {
			$file = $list [$_POST ["files"] [$i]];
			if (file_exists ( $file ["name"] )) {
				echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center"><strong>';
				echo $file ["name"] . "</strong></div>";
			} else {
				echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center">';
				printf(lang(145),$file['name']);
				echo "</div>";
			}
		}
	}
	if ($_POST ["del_ok"]) {
		if (! updateListInFile ( $list )) {
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>';
			echo lang(146)."</strong></div>";
		}
	}
}
?>