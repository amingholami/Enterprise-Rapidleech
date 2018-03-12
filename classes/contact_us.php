<?php 
if(strtoupper($_POST['captcha']) == $_SESSION['capcha'])
{
  function alertchaptcha()
  {	return true; } $_SESSION['capcha'] = $rand;
}
else {
  function alertchaptcha()
  {	return false; } $_SESSION['capcha'] = $rand;
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 $len = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
 return str_ireplace($len,'',$pageURL);
}

function getRealIp() {
       if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
         $contact_ip=$_SERVER['HTTP_CLIENT_IP'];
       } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
         $contact_ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       } else {
         $contact_ip=$_SERVER['REMOTE_ADDR'];
       }
       return $contact_ip;
    }

if(isset($_POST['contact_name']) && isset($_POST['contact_mail']) && isset($_POST['contact_msg']) && alertchaptcha())
  {
	  $contact_name = $_POST['contact_name'];
	  $contact_mail = $_POST['contact_mail'];
	  $contact_msg  = $_POST['contact_msg'];
			
	  if(strlen($contact_name)<= 30 && strlen($contact_mail)<= 50 && strlen($contact_msg)<=800)
		{		  
		
		  $contact_to = $options['admin_mail'];
		  $contact_subject = 'You have a new message in '.strtoupper($options['site_title']).'';
		  
		  $contact_headers  = "MIME-Version: 1.0\r\n";
		  $contact_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		  $contact_headers .= 'From: '.$contact_mail."\r\n";
		  $contact_headers .= "Reply-To: ". strip_tags($contact_mail) . "\r\n";
		  
		  $contact_body = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>'.$contact_subject.'</title>
<style type="text/css">
<!--

body{
  padding:0;
  margin:20px 15px;
  font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
}
strong{
  font-size:1.2em;
	
}
a,a:visited{  
  text-decoration: none; 
  color:#010c15;
}
a:hover{
  text-shadow:rgba(0,255,255,.9) 0 0 6px;
}
.neveshte_inner{
  max-width:100%;
  margin-top:5px;
  padding:8px;
  border-radius: 8px;
  border: 1px dashed rgba(255,255,255,0.3);
}	
.defult{
  max-width:100%; 
  padding:5px; 
  border-radius:10px; 
}
._div2{
  padding:1px; 
  border-radius:8px;
}
._div3{
  border-radius:8px;
}
.onvan{
  max-width:100%; 
  padding:8px; 
  font-size:1.3em; 
  font-weight:bolder; 
  text-align:center; 
  border-top-left-radius:8px;
  border-top-right-radius:8px;
}
.neveshte{ 
  margin:12px;
  font-size:12px; 
  color:#010c15;
}
#white{ 
  background:rgba(255,255,255,.25); 
  box-shadow:rgba(255,255,255,.75) 0px 0px 9px 1px;
}
#white ._div2{
  border:rgba(255,255,255,.5) dashed 1px; 
}
#white ._div3{
  border:rgba(255,255,255,.75) dashed 1px; 
  background:rgba(255,255,255,.6);
}
#white .onvan{
  border-bottom:rgba(255,255,255,.75) dashed 1px; 
  background:rgba(255,255,255,.4); 
  text-shadow:rgba(255,255,255,1) 1px 1px;
  color:#010c15;
}
-->
</style>
</head>
<body>
<div id="white" class="defult">
<div class="_div2">
  <div class="_div3">
    <div class="onvan" align="center"><em>'.$contact_name.'</em> is sent you a new message from <a href="'.curPageURL().'">'.strtoupper($options["site_title"]).'</a></div>
     <div class="neveshte">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:8px">
      <tr valign="top"  style="font-size:12px;">
        <td width="50%">
        <div class="neveshte_inner" style="margin-bottom:8px"><strong>Name</strong><br />
         '.$contact_name.'
        </div>
        <div class="neveshte_inner" style="margin-bottom:8px"><strong>E-mail</strong><br />
         '.$contact_mail.'
        </div>';
		if(isset($_POST['contact_plugin']) && !empty($_POST['contact_plugin']))
			{
				$contact_plugin = $_POST['contact_plugin'];
				$contact_body .='
        <div class="neveshte_inner"><strong>Plugin / Premium account</strong><br />
         '.$contact_plugin.'
        </div>';
			}
			$contact_body .='
        </td>
        <td width="8">&nbsp;</td>
        <td valign="top">
        <div class="neveshte_inner"><strong>Message</strong><br />
          '.$contact_msg.'
        </div>
        </td>
      </tr>
    </table>
    <div class="neveshte_inner" style="font-size:10px;">
     <strong>User Information</strong><br />
     <b>IP address: &nbsp;</b><a href="http://whois.domaintools.com/'.getRealIp().'">'.getRealIp().'</a><br />
     <b>Refferer: &nbsp;&nbsp;&nbsp;&nbsp;</b>'.$_SERVER['HTTP_REFERER'].'<br />
     <b>User agent: </b>'.$_SERVER['HTTP_USER_AGENT'].'
    </div> 
     </div>
   </div>
  </div>
 </div>
</div>
</body>
</html>
';
		if(mail($contact_to,$contact_subject,$contact_body,$contact_headers))
		  {
			  echo '<div class="neveshte_inner_success" title="Click to Hide!" align="center" id="server_files"><strong>Message sent successfully!</strong></div>';
		  }
		  else
		  {
			  echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center" id="server_files"><strong>An ERROR occurred while sending mail!</strong></div>';
		  }
		}
		else
		{
			echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center" id="server_files"><strong>Message length is limited to maximum 800 characters.</strong></div>';
	}
}
elseif(isset($_POST['contact_name']) && isset($_POST['contact_mail']) && isset($_POST['contact_msg']) && !alertchaptcha()) 
{
	echo '<div class="neveshte_inner_error" title="Click to Hide!" align="center" id="server_files"><strong>CAPTCHA ERROR!</strong><br>try again.</div>';  
}	
?>