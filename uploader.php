<link href="templates/plugmod/styles/rl_style_pm.css" rel="stylesheet" type="text/css" />
<center><img src="templates/plugmod/images/rlh_logo.png" alt="RapidLeech" border="0" /></center>
<?php
$upload_dir = "/home/hiro/public_html/r/files";


$web_upload_dir = "/files";

if (isset($_POST['fileframe'])) 
{
    $result = 'ERROR';
    $result_msg = 'No FILE field found';

    if (isset($_FILES['file']))  
    {
	
		// Extension check

        $info = pathinfo($_FILES['file']['name']);

        $extension = $info['extension'];    // like jpg, exe, php etc.

        if ($extension == "php" || $extension == "php3" || $extension == "pl" || $extension == "cgi"  || $extension == "php4"  || $extension == "php5"  || $extension == "sphp"  || $extension == "phtml"  || $extension == "shtml"  || $extension == "dhtml"  || $extension == "html"  || $extension == "htm"  || $extension == "htaccess"  || $extension == "htpasswd"  || $extension == "asp"  || $extension == "aspx") {

            die ("File extension not allowed");

        }
		
        if ($_FILES['file']['error'] == UPLOAD_ERR_OK)
        {
            $filename = $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.'/'.$filename);
        
            $result = 'OK';
        }
        elseif ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE)
            $result_msg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        else 
            $result_msg = 'Unknown error';
    }

    echo '<html><head><title>-</title></head><body>';
    echo '<script language="JavaScript" type="text/javascript">'."\n";
    echo 'var parDoc = window.parent.document;';
    
    if ($result == 'OK')
    {
        echo 'parDoc.getElementById("upload_status").value = "File uploaded successfully!";';
        echo 'parDoc.getElementById("filename").value = "'.$filename.'";';
        echo 'parDoc.getElementById("filenamei").value = "'.$filename.'";';
        echo 'parDoc.getElementById("upload_button").disabled = false;';
    }
    else
    {
        echo 'parDoc.getElementById("upload_status").value = "ERROR: '.$result_msg.'";';
    }

    echo "\n".'</script></body></html>';

    exit();
}

function safehtml($s)
{
    $s=str_replace("&", "&amp;", $s);
    $s=str_replace("<", "&lt;", $s);
    $s=str_replace(">", "&gt;", $s);
    $s=str_replace("'", "&apos;", $s);
    $s=str_replace("\"", "&quot;", $s);
    return $s;
}

$html =<<<END
END;
?>
<!-- Beginning of main page -->
<html><head>
<title>PC 2 Server Uploader</title>
</head>
<body>
<center>
<?php 
if (isset($msg)) // this is special section for outputing message 
    echo '<p style="font-weight: bold;">'.$msg.'</p>';
?> 
<br />
<br />
<div style="margin-top: -16px">
<div style="margin-left: 36px">
<font size="3"><br>
<p><b>PC Uploader</b> allows you to upload Files From PC to RL server.</p>
<p>Coded By SaKIB [itleech.com] Thx to darkra<br><br></p>
<form action="<?=$PHP_SELF?>" target="upload_iframe" method="post" enctype="multipart/form-data">
<input type="hidden" name="fileframe" value="true">
<label for="file">Select file to upload:</label><br>
<!-- JavaScript is called by OnChange attribute -->
<input type="file" name="file" id="file" onChange="jsUpload(this)">
</form>
<script type="text/javascript">
/* This function is called when user selects file in file dialog */
function jsUpload(upload_field)
{
    upload_field.form.submit();
    document.getElementById('upload_status').value = "Uploading... Plese wait";
    upload_field.disabled = true;
    return true;
}
</script>
<p><font size="2"><b>Maximum file size:</b> 100 MB</font></p>
<iframe name="upload_iframe" style="width: 400px; height: 100px; display: none;">
</iframe>
<br>
Upload status:<br>
<input style='color:#FF0000' type="text" name="upload_status" id="upload_status" 
       value="No file to upload" size="44" disabled>

<br><br>

File Name:<br>
<input style='color:#FF0000' type="text" name="filenamei" id="filenamei" value=" " size="44" disabled>

<form action="<?=$PHP_SELF?>" method="POST">

<input type="hidden" name="filename" id="filename">
</form>
<br>
<a href='index.php'>Back to RapidLeech</a>
</div>
</font>
</center>
</body>
</html>
