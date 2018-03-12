<?php
/**
 * Max News
 * 
 * This is the Max News business logic class. 
 */
?>
<?php
class maxNews{
   var $newsDir = 'news';
   var $newsList;
   var $newsCount = -1;
function getNewsList(){ 

    $this->newsList = array();
   
	// Open the actual directory
	if ($handle = @opendir($this->newsDir)) {
		// Read all file from the actual directory
		while ($file = readdir($handle))  {
		    if (!is_dir($file) && $file!='.htaccess') {
		       $this->newsList[] = $file;
      	}
		}
	}	
	
	rsort($this->newsList);
	
	$filterOutKeys = array(
	$this->newsList[0],
	$this->newsList[1],
	$this->newsList[2],
	$this->newsList[3] );
	
	foreach($this->newsList as $elem){
		if($elem!=$this->newsList[0] && $elem!=$this->newsList[1] && $elem!=$this->newsList[2] && $elem!=$this->newsList[3]){
		  @unlink($this->newsDir.'/'.$elem);}
	}
		if(!isset($this->newsList[4]))
		{return $this->newsList;}
	else {return $filterOutKeys ;}
}   

function getNewsCount(){
   if ($this->newsCount == -1) $this->getNewsList();
   $this->newsCount = sizeof($this->newsList);
   return $this->newsCount;
}

function displayNews(){
      $list = $this->getNewsList();
      if (sizeof($list) == 0){
         echo "<div class='PO_in' align='center'><strong>No news at the moment!</strong></div>";
      }else{
      $x=0;
      foreach ($list as $value) {
      	$newsData = @file($this->newsDir.DIRECTORY_SEPARATOR.$value);
      	$newsTitle  = $newsData[0];
         $submitDate = $newsData[1];	
         unset ($newsData['0']);
         unset ($newsData['1']);
      	
         $newsContent = "";
         foreach ($newsData as $value) {
    	       $newsContent .= $value;
         }
		if($x >= 1){
		echo '<div style="line-height:5px">&nbsp;</div>';
		}$x++;
      	echo '<table class="newsContent" id="news'.$x.'" width="100%">';
      	echo "<tr><th align='left'><span rel='newsTitle'>$newsTitle</span><span class='right'>$submitDate</span></th></tr>";
		//hidden
      	echo "<tr class='newcontent".$x."'><td><address class='hide_table'>".$newsContent."</address></td></tr>";
		echo "</table>";
		
	  }
	  }
      
}

function displayAddForm(){
?>  
   <script language="javascript" type="text/javascript" src="js/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_align : "center",
	theme_advanced_toolbar_location : "top",

});
</script>  
  <form class="iform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    News title:<br/>
    <input type="text" name="title" size="40" style="width:100%"/><br/><br/>
    Content:<br/>
    <textarea name="newstext" rows="15" cols="67" style="width:100%"></textarea><br/>
    <center><input type="submit" name="submit" value="Save" /></center>
  </form> 
   
<?php   
}

function insertNews(){
   $newsTitel   = isset($_POST['title']) ? $_POST['title'] : 'Untitled';
   $submitDate  = date('Y-m-d g:i:s A');
   $newsContent = isset($_POST['newstext']) ? $_POST['newstext'] : 'No content';
   
   $filename = date('YmdHis');
   if (!file_exists($this->newsDir)){
      mkdir($this->newsDir);
   }
   $f = fopen($this->newsDir.DIRECTORY_SEPARATOR.$filename.".txt","w+");         
   fwrite($f,$newsTitel."\n");
   fwrite($f,$submitDate."\n");
   fwrite($f,$newsContent."\n");
   fclose($f);

   header('Location:index.php');   
   
}
}
?>