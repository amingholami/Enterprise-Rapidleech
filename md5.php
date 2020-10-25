<?php
// Here the downloads
$path = "files";

if (isset($_GET['file'])) {
    $handle = fopen("$path/".$_GET['file']."", 'a+');

    if ($handle) {
        fwrite($handle, '0');
    } else {
        echo "<br />Error!<br />";   
    }
   
    fclose($handle);
}

echo "Welcome to RapidleechHost.com Special MD5 Hash Change Script. Just click on the File Name to change the md5 hash<br /><br />";



$handle = opendir ($path);


while (false !== ($file = readdir ($handle))){
    if ($file != "."){
        if ($file != ".."){
            if ($file != "index.html") {
               
                $byte = filesize($path."/".$file);
               
               
                if (! is_dir($path."/".$file)) {
                    echo "<a href='md5.php?file=$file'> $file </a> ($byte Bytes)<br />";
                }
            }
        }
    }
}

closedir($handle);
?>