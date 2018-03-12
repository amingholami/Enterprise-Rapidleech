<?php
function upload() {
	global $upload_services, $list;
	$d = opendir ( HOST_DIR . "upload/" );
	while ( false !== ($modules = readdir ( $d )) ) {
		if ($modules != "." && $modules != "..") {
			if (is_file ( HOST_DIR . "upload/" . $modules )) {
				if (strpos ( $modules, ".index.php" ))
					include_once (HOST_DIR . "upload/" . $modules);
			}
		}
	}
	if (empty ( $upload_services )) {
		echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center"><strong>'.lang(48)."</strong></div>";
	} else {
		sort ( $upload_services );
		reset ( $upload_services );
		$cc = 0;
		foreach ( $upload_services as $upl ) {
			$uploadtype .= "\tupservice[" . ($cc ++) . "]=new Array('" . $upl . "','" . (str_replace ( "_", " ", $upl ) . " (" . ($max_file_size [$upl] == false ? "Unlim" : $max_file_size [$upl] . "Mb") . ")") . "');\n";
		}
?>
<script type="text/javascript">
/* <![CDATA[ */
	var upservice = new Array();

	function fill_option(id)
		{
			var elem=document.getElementById(id);
			
			for (var i=0; i<upservice.length;i++)
				{
					elem.options[elem.options.length]=new Option(upservice[i][1]);
					elem.options[elem.options.length-1].value=upservice[i][0];
				}
		}

<?php echo $uploadtype; ?>

	function openwinup(id)
		{
			var options = "width=700,height=250,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no";
			win=window.open('', id, options);
			win.focus();
			return true;
		}
/* ]]> */
</script>
<div align="left"><strong>Upload file</strong></div>
<div class="neveshte_inner">
<table align="center" border="0" width="100%">
<?php
				for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
					$file = $list [($_GET ["files"] [$i])];
					$tid = md5 ( time () . "_file" . $_GET ["files"] [$i] );
?>
	<tr><form action="upload.php" method="get" target="<?php echo $tid?>" onsubmit="return openwinup('<?php echo $tid?>');" style="padding-bottom: 6px;">
		<td valign="top" align="left"><?php echo "<b>" . basename ( $file ["name"] ) . "</b>  (" . $file ["size"].')'; ?></td>
		<td valign="top" align="center" width="50%">
			<select name="uploaded" id="d_<?php echo $tid;?>"></select><script type="text/javascript">fill_option('d_<?php echo $tid;?>');</script>
			<input type="hidden" name="filename" value="<?php echo base64_encode ( $file ["name"] ); ?>" />
			<br /><label><input type="checkbox" name="useuproxy" onclick="javascript:var displ=this.checked? $('#uproxyconfig<?php echo $i; ?>').fadeIn('slow') :$('#uproxyconfig<?php echo $i; ?>').hide('slow');displ;" />&nbsp;<?php echo lang(245); ?></label>
				<table class="PO_in hide_table" width="100%" border="0" id="uproxyconfig<?php echo $i; ?>">
				<tr><td width="60"><small><?php echo lang(246); ?>:&nbsp;</small></td><td><input type="text" name="uproxy" /></td></tr>
				<tr><td><small><?php echo lang(247); ?>:&nbsp;</small></td><td><input type="text" name="uproxyuser" /></td></tr>
				<tr><td><small><?php echo lang(248); ?>:&nbsp;</small></td><td><input type="text" name="uproxypass" /></td></tr>
				</table>
				
		</td>
		<td valign="top" align="center" width="25%">
            <input type="submit" value="Upload" style="width:80px" /></td>
	</form></tr>
    <tr><td colspan="3">&nbsp;</td></tr>

<?php } ?>
</table>
</div>
<?php
	}
}
?>