<?php
// ip-sperre in sekunden
$expire_count = 600;
$filename_count = CONFIG_DIR."/counter.txt";

if (file_exists($filename_count)) 
{
   $ignore = false;
   $current_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? addslashes(trim($_SERVER['HTTP_USER_AGENT'])) : "no agent";
   $current_time = time();
   $current_ip = getRealIp(); 
      
   // daten einlesen
   $c_file = array();
   $handle = fopen($filename_count, "r");
   
   if ($handle)
   {
      while (!feof($handle)) 
      {
         $line = trim(fgets($handle, 4096)); 
		 if ($line != "")
		    $c_file[] = $line;		  
      }
      fclose ($handle);
   }
   else
      $ignore = true;
   
   // bots ignorieren   
   if (substr_count($current_agent, "bot") > 0)
      $ignore = true;
	  
   
   // hat diese ip einen eintrag in den letzten expire_count sec gehabt, dann igornieren?
   for ($i = 1; $i < sizeof($c_file); $i++)
   {
      list($counter_ip, $counter_time) = explode("||", $c_file[$i]);
	  $counter_time = trim($counter_time);
	  
	  if ($counter_ip == $current_ip && $current_time-$expire_count < $counter_time)
	  {
	     // besucher wurde bereits gezählt, daher hier abbruch
		 $ignore = true;
		 break;
	  }
   }
   
   // counter hochzählen
   if ($ignore == false)
   {
      if (sizeof($c_file) == 0)
      {
	     // wenn counter leer, dann füllen      
		 $add_line1 = date("z") . ":1||" . date("W") . ":1||" . date("n") . ":1||" . date("Y") . ":1||1||1||" . $current_time . "\n";
		 $add_line2 = $current_ip . "||" . $current_time . "\n";
		 
		 // daten schreiben
		 $fp = fopen($filename_count,"w+");
		 if ($fp)
         {
		    flock($fp, LOCK_EX);
			fwrite($fp, $add_line1);
		    fwrite($fp, $add_line2);
			flock($fp, LOCK_UN);
		    fclose($fp);
		 }
		 
		 // werte zur verfügung stellen
		 $day = $week = $month = $year = $all = $record = 1;
		 $record_time = $current_time;
		 $online = 1;
	  }
      else
	  {
	     // counter hochzählen
		 list($day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode("||", $c_file[0]);
		 
		 // day
		 $day_data = explode(":", $day_arr);
		 $day = $day_data[1];
		 if ($day_data[0] == date("z")) $day++; else $day = 1;
		 
		 // week
		 $week_data = explode(":", $week_arr);
		 $week = $week_data[1];
		 if ($week_data[0] == date("W")) $week++; else $week = 1;
		 
		 // month
		 $month_data = explode(":", $month_arr);
		 $month = $month_data[1];
		 if ($month_data[0] == date("n")) $month++; else $month = 1;
		 
		 // year
		 $year_data = explode(":", $year_arr);
		 $year = $year_data[1];
		 if ($year_data[0] == date("Y")) $year++; else $year = 1;
		  
		 // all
		 $all++;
		 
		 // neuer record?
		 $record_time = trim($record_time);
		 if ($day > $record)
		 {
		    $record = $day;
			$record_time = $current_time;
		 }
		 
		 // speichern und aufräumen und anzahl der online leute bestimmten
		 
		 $online = 1;
		 
		 // daten schreiben
		 $fp = fopen($filename_count,"w+");
		 if ($fp)
         {
		    flock($fp, LOCK_EX);
			$add_line1 = date("z") . ":" . $day . "||" . date("W") . ":" . $week . "||" . date("n") . ":" . $month . "||" . date("Y") . ":" . $year . "||" . $all . "||" . $record . "||" . $record_time . "\n";		 
		    fwrite($fp, $add_line1);
		 
		    for ($i = 1; $i < sizeof($c_file); $i++)
            {
               list($counter_ip, $counter_time) = explode("||", $c_file[$i]);
	  
	           // übernehmen
		   	   if ($current_time-$expire_count < $counter_time)
	           {
	              $counter_time = trim($counter_time);
				  $add_line = $counter_ip . "||" . $counter_time . "\n";
			      fwrite($fp, $add_line);
			      $online++;
	           }
            }
		    $add_line = $current_ip . "||" . $current_time . "\n";
		    fwrite($fp, $add_line);
		    flock($fp, LOCK_UN);
		    fclose($fp);
	     }
	  }
   }
   else
   {
      // nur zum anzeigen lesen
	  if (sizeof($c_file) > 0)
	     list($day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode("||", $c_file[0]);
	  else
		 list($day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode("||", date("z") . ":1||" . date("W") . ":1||" . date("n") . ":1||" . date("Y") . ":1||1||1||" . $current_time);
	  
	  // day
	  $day_data = explode(":", $day_arr);
      $day = $day_data[1];
	  
	  // week
	  $week_data = explode(":", $week_arr);
	  $week = $week_data[1];
	
	  // month
	  $month_data = explode(":", $month_arr);
	  $month = $month_data[1];
	  
	  // year
	  $year_data = explode(":", $year_arr);
	  $year = $year_data[1];
	  
	  $record_time = trim($record_time);
	  
	  $online = sizeof($c_file) - 1;
   }
?>
<strong>Stats</strong>
<div class="neveshte_inner">
Online User(s): <?php echo '<b>'.$online.' user(s)</b>'; ?><br>
Today Visits: <?php echo '<b>'.$day.' visites</b>'; ?><br>
</div>
<?php
}
?>