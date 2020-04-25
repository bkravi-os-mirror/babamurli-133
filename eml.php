<!DOCTYPE html>
<?php
  //Steps to do if you add new e-mail ID or the existing ID is sending more different files than they usually do
  /*
    1) Add email id in $sender_list array
    2) Add location in $file_loc array where the file will go in our server
    3) What file name postfix is, provide this info in $file_postfix array
    4) Now what all valid file extensions are valid for this email id. Provide it in $valid_ext array
    5) Finally add/update some piece of code in function processAttachments()
  */
  
  //Reference: https://www.codediesel.com/php/downloading-gmail-attachments-in-php-an-update/
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  $sender_list = array(
    "amola51@gmail.com", "prema_french@rediffmail.com", "madhvi.lamba09@gmail.com", "preetiswami2005@gmail.com",
    "murlimylife@gmail.com", "ekta.khakhar@gmail.com", "bkabbigere@gmail.com", "bkaditi@bkmail.org",
    "shivamanibk1936@gmail.com", "malayalammurli@gmail.com", "yogamart@sg.brahmakumaris.org", "bksamuk@gmail.com",
    "raghubiru010@gmail.com", "bknavrang@gmail.com", "bkwangyuanyuan@163.com", "jaishreejani@gmail.com", 
    "shefu224@gmail.com", "bknagamani1@gmail.com", "mala.khetarpal@rediffmail.com", "bkteluguclasses@gmail.com",
    "kv.kavyashree@gmail.com", "kabidash@gmail.com", "sudharani.kuram@gmail.com", "suhasinibk@gmail.com",
  );
  
  $file_loc = array(  
    "amola51@gmail.com_MP3" => "$rootdir/01. Daily Murli/02. English/11. Eng Murli Hindi Words - Amola",
    "amola51@gmail.com_PDF" => "$rootdir/01. Daily Murli/02. English/30. Todays Thought",
    "amola51@gmail.com_JPG" => "$rootdir/01. Daily Murli/01. Hindi/21. Murli Vardan - jpg",
    "bkabbigere@gmail.com_MP3" => "$rootdir/01. Daily Murli/05. Kannada/03. Kannada Murli - V2 - MP3",
    "bkaditi@bkmail.org_ESSMP3" => "$rootdir/01. Daily Murli/01. Hindi/05. Hindi Murli - Saar - MP3",
    "bkaditi@bkmail.org_FULLMP3" => "$rootdir/01. Daily Murli/01. Hindi/03. Hindi Murli - MP3",
    "bknagamani1@gmail.com_MP3" => "$rootdir/01. Daily Murli/04. Telugu/10. Murli Chintan - Suraj Bhai",
    "bkteluguclasses@gmail.com_ESSMP3" => "$rootdir/01. Daily Murli/04. Telugu/04. Telugu - Murli - Ess - MP3",
    "bkteluguclasses@gmail.com_FULLMP3" => "$rootdir/01. Daily Murli/04. Telugu/03. Telugu - Murli - MP3",
    "bkteluguclasses@gmail.com_PDF" => "$rootdir/01. Daily Murli/04. Telugu/02. Telugu - Murli - Pdf",

    "bknavrang@gmail.com_ENGESSMP3" => "$rootdir/01. Daily Murli/02. English/05. Eng Murli - Ess - MP3",
    "bknavrang@gmail.com_ENGFULLMP3" => "$rootdir/01. Daily Murli/02. English/04. Eng Murli - MP3 - 2",
    "bknavrang@gmail.com_HINESSMP3" => "$rootdir/01. Daily Murli/01. Hindi/06. Hindi Murli - Saar - MP3 - 2",
    "bknavrang@gmail.com_HINFULLMP3" => "$rootdir/01. Daily Murli/01. Hindi/04. Hindi Murli - MP3 - 2",
    "bksamuk@gmail.com_MP3" => "$rootdir/01. Daily Murli/35. Tamil-Lanka/MP3-Tamil-Lanka",
    "bkwangyuanyuan@163.com_MP3" => "$rootdir/01. Daily Murli/34. Chinese/MP3-Chinese",
    "ekta.khakhar@gmail.com_JPG" => "$rootdir/01. Daily Murli/01. Hindi/30. Murli Vardan-2- jpg",
    "ekta.khakhar@gmail.com_E_JPG" => "$rootdir/01. Daily Murli/02. English/29. Murli Vardan Hand - jpg",
    "jaishreejani@gmail.com_MP3" => "$rootdir/01. Daily Murli/09. Gujarati/04. Gujarati Murli - Mp3",
    "kabidash@gmail.com_MP3" => "$rootdir/01. Daily Murli/10. Odiya/03. Odiya Murli - MP3",
    "kabidash@gmail.com_PDF" => "$rootdir/01. Daily Murli/10. Odiya/02. Odiya Murli - Pdf",
    "kv.kavyashree@gmail.com_ESSMP3" => "$rootdir/01. Daily Murli/05. Kannada/04. Kannada Murli - Ess - MP3",

    "madhvi.lamba09@gmail.com_ESSMP3" => "$rootdir/01. Daily Murli/02. English/06. Eng Murli - Ess - MP3 - UK",
    "madhvi.lamba09@gmail.com_FULLMP3" => "$rootdir/01. Daily Murli/02. English/04. Eng Murli - MP3 - UK",
    "mala.khetarpal@rediffmail.com_ESWAJPG" => "$rootdir/01. Daily Murli/02. English/27. Eng Murli Swaman - jpg",
    "mala.khetarpal@rediffmail.com_HSWAJPG" => "$rootdir/01. Daily Murli/01. Hindi/27. Murli Swaman - jpg",
    "malayalammurli@gmail.com_MP3" => "$rootdir/01. Daily Murli/06. Malayalam/03. Malayalam Murli - MP3",
    "murlimylife@gmail.com_HDESKMURLIHTM" => "$rootdir/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm",
    "murlimylife@gmail.com_HDESKSMSHTM" => "$rootdir/01. Daily Murli/01. Hindi/07. Hindi Murli - Saar - SMS",
    "murlimylife@gmail.com_HMOBMURLIHTM" => "$rootdir/01. Daily Murli/01. Hindi/36. Mobile Htm",
    "murlimylife@gmail.com_HMOBSMSHTM" => "$rootdir/01. Daily Murli/01. Hindi/37. Mobile SMS",
    "murlimylife@gmail.com_TDESKHTM" => "$rootdir/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm",
    "murlimylife@gmail.com_TMOBHTM" => "$rootdir/01. Daily Murli/04. Telugu/36. Mobile Htm",
    "preetiswami2005@gmail.com_MP3" => "$rootdir/01. Daily Murli/01. Hindi/12. Murli Preeti Bahen",

    "prema_french@rediffmail.com_MP3" => "$rootdir/01. Daily Murli/36. French/Mp3-French",
    "raghubiru010@gmail.com_MP3" => "$rootdir/01. Daily Murli/11. Punjabi/04. Punjabi Murli - MP3",
    "shefu224@gmail.com_DESKHTM" => "$rootdir/01. Daily Murli/02. English/01. Eng Murli - Htm",
    "shefu224@gmail.com_DESKSMSHTM" => "$rootdir/01. Daily Murli/02. English/07. Eng Murli - Ess - SMS",
    "shefu224@gmail.com_MOBHTM" => "$rootdir/01. Daily Murli/02. English/36. Mobile Htm",
    "shefu224@gmail.com_MOBSMSHTM" => "$rootdir/01. Daily Murli/02. English/37. Mobile SMS",
    "shivamanibk1936@gmail.com_KMP3" => "$rootdir/01. Daily Murli/05. Kannada/03. Kannada Murli - MP3",
    "shivamanibk1936@gmail.com_HTOKMP3" => "$rootdir/01. Daily Murli/05. Kannada/07. Hindi To Kannada Murli - Mp3",
    "shivamanibk1936@gmail.com_AKPMP3" => "$rootdir/01. Daily Murli/05. Kannada/05. Kannada - AKP",
    "sudharani.kuram@gmail.com_HDESKMURLIHTM" => "$rootdir/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm",
    "sudharani.kuram@gmail.com_HDESKSMSHTM" => "$rootdir/01. Daily Murli/01. Hindi/07. Hindi Murli - Saar - SMS",
    "sudharani.kuram@gmail.com_HMOBMURLIHTM" => "$rootdir/01. Daily Murli/01. Hindi/36. Mobile Htm",
    "sudharani.kuram@gmail.com_HMOBSMSHTM" => "$rootdir/01. Daily Murli/01. Hindi/37. Mobile SMS",
    "sudharani.kuram@gmail.com_TDESKHTM" => "$rootdir/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm",
    "sudharani.kuram@gmail.com_TMOBHTM" => "$rootdir/01. Daily Murli/04. Telugu/36. Mobile Htm",
    "suhasinibk@gmail.com_AKPMP3" => "$rootdir/01. Daily Murli/01. Hindi/24. Hindi - Aaj Ka Purushrath",

    "yogamart@sg.brahmakumaris.org_MP3" =>"$rootdir/01. Daily Murli/35. Tamil-Lanka/MP3-Tamil-Lanka",
    "yogamart@sg.brahmakumaris.org_DESKTHOTHTM" =>"$rootdir/01. Daily Murli/03. Tamil/39. Tamil Thoughts",
    "yogamart@sg.brahmakumaris.org_MOBTHOTHTM" =>"$rootdir/01. Daily Murli/03. Tamil/39. Tamil Thoughts",
  );
  
  $file_postfix = array(
    "amola51@gmail.com_MP3" => "-E-H.mp3",
    "amola51@gmail.com_PDF" => "-Thought.pdf",
    "amola51@gmail.com_JPG" => "-Var.jpg",
    "bkabbigere@gmail.com_MP3" => "-V2-K.mp3",
    "bkaditi@bkmail.org_ESSMP3" => "-H-ess.mp3",
    "bkaditi@bkmail.org_FULLMP3" => "-H.mp3",
    "bknagamani1@gmail.com_MP3" => "-Murli-Chintan-(Telugu)-Suraj Bhaiji.mp3",
    "bkteluguclasses@gmail.com_ESSMP3" => "-Telugu-Murli-Saar.mp3",
    "bkteluguclasses@gmail.com_FULLMP3" => "-Telugu.mp3",
    "bkteluguclasses@gmail.com_PDF" => "-Telugu.pdf",

    "bknavrang@gmail.com_ENGESSMP3" => "-Eng-Ess.mp3",
    "bknavrang@gmail.com_ENGFULLMP3" => "-Eng-Full.mp3",
    "bknavrang@gmail.com_HINESSMP3" => "-Hindi-Ess.mp3",
    "bknavrang@gmail.com_HINFULLMP3" => "-Hindi-Full.mp3",
    "bksamuk@gmail.com_MP3" => "-TamilLanka.mp3",
    "bkwangyuanyuan@163.com_MP3" => "-Chinese.mp3",
    "ekta.khakhar@gmail.com_JPG" => "-Var-Hand.jpg",
    "ekta.khakhar@gmail.com_E_JPG" => "-E-Var-Hand.jpg",
    "jaishreejani@gmail.com_MP3" => "-Gujarati.mp3",
    "kabidash@gmail.com_MP3" => "-Odia.mp3",
    "kabidash@gmail.com_PDF" => "-Odia.pdf",
    "kv.kavyashree@gmail.com_ESSMP3" => "-Kan-Ess.mp3",

    "madhvi.lamba09@gmail.com_ESSMP3" => "-E-Ess-Mob-UK.mp3",
    "madhvi.lamba09@gmail.com_FULLMP3" => "-E-UK.mp3",
    "mala.khetarpal@rediffmail.com_ESWAJPG" => "-Swa-Eng.jpg",
    "mala.khetarpal@rediffmail.com_HSWAJPG" => "-Swa.jpg",
    "malayalammurli@gmail.com_MP3" => "-Mal.mp3",
    "murlimylife@gmail.com_HDESKMURLIHTM" => "-H.htm",
    "murlimylife@gmail.com_HDESKSMSHTM" => "-Hin-SMS.htm",
    "murlimylife@gmail.com_HMOBMURLIHTM" => "-H-Mob.htm",
    "murlimylife@gmail.com_HMOBSMSHTM" => "-Hin-SMS-Mob.htm",
    "murlimylife@gmail.com_TDESKHTM" => "-Telugu.htm",
    "murlimylife@gmail.com_TMOBHTM" => "-Telugu-Mob.htm",
    "preetiswami2005@gmail.com_MP3" => "-PREETI.mp3",

    "prema_french@rediffmail.com_MP3" => "-French.mp3",
    "raghubiru010@gmail.com_MP3" => "-Pun.mp3",
    "shefu224@gmail.com_DESKHTM" => "-E.htm",
    "shefu224@gmail.com_DESKSMSHTM" => "-Eng-SMS.htm",
    "shefu224@gmail.com_MOBHTM" => "-E-Mob.htm",
    "shefu224@gmail.com_MOBSMSHTM" => "-Eng-SMS-Mob.htm",
    "shivamanibk1936@gmail.com_KMP3" => "-K.mp3",
    "shivamanibk1936@gmail.com_HTOKMP3" => "-Murli hindi to kannada.mp3",
    "shivamanibk1936@gmail.com_AKPMP3" => "-AKP-K.mp3",
    "sudharani.kuram@gmail.com_HDESKMURLIHTM" => "-H.htm",
    "sudharani.kuram@gmail.com_HDESKSMSHTM" => "-Hin-SMS.htm",
    "sudharani.kuram@gmail.com_HMOBMURLIHTM" => "-H-Mob.htm",
    "sudharani.kuram@gmail.com_HMOBSMSHTM" => "-Hin-SMS-Mob.htm",
    "sudharani.kuram@gmail.com_TDESKHTM" => "-Telugu.htm",
    "sudharani.kuram@gmail.com_TMOBHTM" => "-Telugu-Mob.htm",
    "suhasinibk@gmail.com_AKPMP3" => "-AKP.mp3",

    "yogamart@sg.brahmakumaris.org_MP3" =>"-TamilLanka.mp3",
    "yogamart@sg.brahmakumaris.org_DESKTHOTHTM" =>".htm",
    "yogamart@sg.brahmakumaris.org_MOBTHOTHTM" =>"-Mob.htm",
  );
  
  $valid_ext = array();
  $valid_ext["amola51@gmail.com"] = array("mp3", "pdf", "jpg");   //if(in_array("mp3", $valid_ext["amola51@gmail.com"]))
  $valid_ext["bkabbigere@gmail.com"] = array("mp3");
  $valid_ext["bkaditi@bkmail.org"] = array("mp3");
  $valid_ext["bknagamani1@gmail.com"] = array("mp3");
  $valid_ext["bkteluguclasses@gmail.com"] = array("mp3", "pdf");
  
  $valid_ext["bknavrang@gmail.com"] = array("mp3");
  $valid_ext["bksamuk@gmail.com"] = array("mp3");
  $valid_ext["bkwangyuanyuan@163.com"] = array("mp3");
  $valid_ext["ekta.khakhar@gmail.com"] = array("jpg");
  $valid_ext["jaishreejani@gmail.com"] = array("mp3");
  $valid_ext["kabidash@gmail.com"] = array("pdf", "mp3");
  $valid_ext["kv.kavyashree@gmail.com"] = array("mp3");
  
  $valid_ext["madhvi.lamba09@gmail.com"] = array("mp3");
  $valid_ext["mala.khetarpal@rediffmail.com"] = array("jpg");
  $valid_ext["malayalammurli@gmail.com"] = array("mp3");
  $valid_ext["murlimylife@gmail.com"] = array("htm");
  $valid_ext["preetiswami2005@gmail.com"] = array("mp3");
  
  $valid_ext["prema_french@rediffmail.com"] = array("mp3");
  $valid_ext["raghubiru010@gmail.com"] = array("mp3");
  $valid_ext["shefu224@gmail.com"] = array("htm");
  $valid_ext["shivamanibk1936@gmail.com"] = array("mp3");   //not taking kannada pdf from him as downloading it from Madhuban ORG
  $valid_ext["sudharani.kuram@gmail.com"] = array("htm");
  $valid_ext["suhasinibk@gmail.com"] = array("mp3");
  
  $valid_ext["yogamart@sg.brahmakumaris.org"] = array("mp3", "htm");
  
  sort($sender_list);
  $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
  $username = 'bkravi.os.mirror@gmail.com';
  $password = 'bkravi$os$mirror';
  
  function startsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), 0, strlen($needle)) === 0;
  }
  
  function endsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
  }  
  
  function getAllAttachments($structure, $inbox, $email_number) {
    $attachments = array();
    if(isset($structure->parts) && count($structure->parts)) {  //if any attachments found
      for($i = 0; $i < count($structure->parts); $i++) {
        $attachments[$i] = array('is_attachment' => false, 'filename' => '', 'name' => '', 'attachment' => '');
        if($structure->parts[$i]->ifdparameters) {
          foreach($structure->parts[$i]->dparameters as $object) {
            if(strtolower($object->attribute) == 'filename') {
              $attachments[$i]['is_attachment'] = true;
              $attachments[$i]['filename'] = $object->value;
            }
          }
        }

        if($structure->parts[$i]->ifparameters) {
          foreach($structure->parts[$i]->parameters as $object) {
            if(strtolower($object->attribute) == 'name') {
              $attachments[$i]['is_attachment'] = true;
              $attachments[$i]['name'] = $object->value;
            }
          }
        }

        if($attachments[$i]['is_attachment']) {
            $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
            if($structure->parts[$i]->encoding == 3) {  //3 = BASE64 encoding
                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
            }
            elseif($structure->parts[$i]->encoding == 4) {   //4 = QUOTED-PRINTABLE encoding
                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
            }
        }
      }
    }
    return $attachments;
  }
  
  function getDatePrefixFor($str){   //will return DD.MM.YY type string from $str=19.02.20-E-UK etc..
    try {
      $explode_array_1 = explode(".", $str);   //most preferable
      $explode_array_2 = explode("-", $str);   //fall back if $str is '-' separated
      if(intval($explode_array_1[1]) > 0) {   //i.e. $str is '.' separated
        if(intval($explode_array_1[0]) > 0 && intval($explode_array_1[1]) > 0 && intval($explode_array_1[2]) > 0) {
          $dd = (strlen($explode_array_1[0]) < 2) ? ("0" . $explode_array_1[0]) : $explode_array_1[0];
          $mm = (strlen($explode_array_1[1]) < 2) ? ("0" . $explode_array_1[1]) : $explode_array_1[1];
          $yy = intval($explode_array_1[2]) . '';   //intval of '20-E-UK' will yield '20'
          $yy = (strlen($yy) > 2) ? substr($yy, -2) : $yy;   //if year is more than length 2, get the last 2 digits
          return "$dd.$mm.$yy";
        } else {
          return false;
        }
      } else if(intval($explode_array_2[1]) > 0) {   //i.e. $str is '-' separated
        if(intval($explode_array_2[0]) > 0 && intval($explode_array_2[1]) > 0 && intval($explode_array_2[2]) > 0) {
          $dd = (strlen($explode_array_2[0]) < 2) ? ("0" . $explode_array_2[0]) : $explode_array_2[0];
          $mm = (strlen($explode_array_2[1]) < 2) ? ("0" . $explode_array_2[1]) : $explode_array_2[1];
          $yy = intval($explode_array_2[2]) . '';   //intval of '20-E-UK' will yield '20'
          $yy = (strlen($yy) > 2) ? substr($yy, -2) : $yy;   //if year is more than length 2, get the last 2 digits
          return "$dd.$mm.$yy";
        } else {
          return false;
        }
      } else {
        return false;
      }
    } catch(Exception $e) {
      return false;
    }
  }
  
  function downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite) {
    global $rootdir;
    $attached_files = '';
    if(strlen($filename_postfix_as_desired) > 1) {
      $date_prefix_as_desired = getDatePrefixFor($filename_as_rcvd);
      if($date_prefix_as_desired) {
        if($target_loc) {
          $final_file_name_as_desired = $date_prefix_as_desired . $filename_postfix_as_desired;
          if($overwrite === 0 && file_exists("$target_loc/$final_file_name_as_desired") && filesize("$target_loc/$final_file_name_as_desired") > 0) {
            $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd => $final_file_name_as_desired Already Exists!</span></li>";
          } else {
            try {
              $action = '';
              if($overwrite === 1 && file_exists("$target_loc/$final_file_name_as_desired") && filesize("$target_loc/$final_file_name_as_desired") > 0) {
                $action = "<span class='bg-dark text-white'>Overwritten!</span>";
              } else {
                $action = "<span class='bg-success text-white'>Newly Copied!</span>";
              }
              $fp = fopen("$target_loc/$final_file_name_as_desired", "w+");
              fwrite($fp, $attachment['attachment']);
              fclose($fp);
              if(($filename_postfix_as_desired === "-Var.jpg") && strpos(strtolower($target_loc), "hindi") !== false) {   //hindi and english vardaan received from Amola are same. so copying
                copy("$target_loc/$final_file_name_as_desired", "$rootdir/01. Daily Murli/02. English/28. Eng Murli Vardan - jpg/$final_file_name_as_desired");
                $action = $action . "&nbsp;<span class='bg-success text-white'>And copied to English directory also</span>";
              }
              $attached_files = $attached_files . "<li>$filename_as_rcvd => $final_file_name_as_desired $action</li>";
            } catch(Exception $e) {
              $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd => $final_file_name_as_desired ERROR/Exception " . $e->getMessage() . "</span></li>";
            }
          }
        } else{
          $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Could not parse target location for $filename_as_rcvd. Please check if it is defined in 'file_loc' array</span></li>";
        }
      } else {
        $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Could not parse DATE in $filename_as_rcvd</span></li>";
      }
    } else {
      $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Could not parse FILENAME of $filename_as_rcvd</span></li>";
    }
    return $attached_files;
  }
  
  function stringContainsAll($string_to_test, $array_of_substrs) {
    if(count($array_of_substrs) < 1) return true;
    for($i = 0; $i < count($array_of_substrs); $i++) {
      if(strpos($string_to_test, $array_of_substrs[$i]) !== false);
      else return false;
    }
    return true;
  }
  
  function processAttachments($attachments, $mail_from, $overwrite) {
    global $rootdir, $valid_ext, $file_postfix, $file_loc;
    $attached_files = '';
    if(count($attachments) > 0) {
      foreach($attachments as $attachment) {
        if($attachment['is_attachment'] == 1) {
          $filename_as_rcvd = $attachment['name'];
          $filename_as_desired = '';
          $filename_postfix_as_desired = '';
          $target_loc = '';
          if(empty($filename_as_rcvd)) $filename_as_rcvd = $attachment['filename'];
          if(!empty($filename_as_rcvd)) {
            
            //====== BEGIN amola51@gmail.com ==================================================
            if($mail_from === "amola51@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("-e", "-h")) && $ext === "mp3") $tmp = '_MP3';
                else if(stringContainsAll($lowercase_file_name, array("thought")) && $ext === "pdf") $tmp = '_PDF';
                else if(stringContainsAll($lowercase_file_name, array("var")) && $ext === "jpg") $tmp = '_JPG';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END amola51@gmail.com ==================================================

            //====== START bkabbigere@gmail.com ==================================================
            else if($mail_from === "bkabbigere@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("v2", "k")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bkabbigere@gmail.com ==================================================

            //====== BEGIN bkaditi@bkmail.org ==================================================
            else if($mail_from === "bkaditi@bkmail.org") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("h", "ess")) && $ext === "mp3") $tmp = '_ESSMP3';
                else if(stringContainsAll($lowercase_file_name, array("h")) && $ext === "mp3") $tmp = '_FULLMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bkaditi@bkmail.org ==================================================

            //====== BEGIN bknagamani1@gmail.com ==================================================
            else if($mail_from === "bknagamani1@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("chintan")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bknagamani1@gmail.com ==================================================

            //====== BEGIN bkteluguclasses@gmail.com ==================================================
            else if($mail_from === "bkteluguclasses@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("murli", "saar")) && $ext === "mp3") $tmp = '_ESSMP3';
                else if(stringContainsAll($lowercase_file_name, array("telugu")) && $ext === "mp3") $tmp = '_FULLMP3';
                else if(stringContainsAll($lowercase_file_name, array("m")) && $ext === "pdf") $tmp = '_PDF';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bkteluguclasses@gmail.com ==================================================
            
            //====== BEGIN bknavrang@gmail.com ==================================================
            else if($mail_from === "bknavrang@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("eng", "ess")) && $ext === "mp3") $tmp = '_ENGESSMP3';
                else if(stringContainsAll($lowercase_file_name, array("eng", "full")) && $ext === "mp3") $tmp = '_ENGFULLMP3';
                else if(stringContainsAll($lowercase_file_name, array("hin", "ess")) && $ext === "mp3") $tmp = '_HINESSMP3';
                else if(stringContainsAll($lowercase_file_name, array("hin", "full")) && $ext === "mp3") $tmp = '_HINFULLMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bknavrang@gmail.com ==================================================

            //====== BEGIN bksamuk@gmail.com ==================================================
            else if($mail_from === "bksamuk@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("lanka")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bksamuk@gmail.com ==================================================
            
            //====== BEGIN bkwangyuanyuan@163.com ==================================================
            else if($mail_from === "bkwangyuanyuan@163.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("chin")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END bkwangyuanyuan@163.com ==================================================

            //====== BEGIN ekta.khakhar@gmail.com ==================================================
            else if($mail_from === "ekta.khakhar@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("e", "hand")) && $ext === "jpg") $tmp = '_E_JPG';
                else if(stringContainsAll($lowercase_file_name, array("hand")) && $ext === "jpg") $tmp = '_JPG';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END ekta.khakhar@gmail.com ==================================================

            //====== BEGIN jaishreejani@gmail.com ==================================================
            else if($mail_from === "jaishreejani@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("rati")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END jaishreejani@gmail.com ==================================================

            //====== BEGIN kabidash@gmail.com ==================================================
            else if($mail_from === "kabidash@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("odia")) && $ext === "pdf") $tmp = '_PDF';
                else if(stringContainsAll($lowercase_file_name, array("odia")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END kabidash@gmail.com ==================================================
            
            //====== BEGIN kv.kavyashree@gmail.com ==================================================
            else if($mail_from === "kv.kavyashree@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("kan", "ess")) && $ext === "mp3") $tmp = '_ESSMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END kv.kavyashree@gmail.com ==================================================
            
            //====== BEGIN madhvi.lamba09@gmail.com ==================================================
            else if($mail_from === "madhvi.lamba09@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("mob", "uk", "ess")) && $ext === "mp3") $tmp = '_ESSMP3';
                else if(stringContainsAll($lowercase_file_name, array("uk", "e")) && $ext === "mp3") $tmp = '_FULLMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END madhvi.lamba09@gmail.com ==================================================

            //====== BEGIN mala.khetarpal@rediffmail.com ==================================================
            else if($mail_from === "mala.khetarpal@rediffmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("swa", "eng")) && $ext === "jpg") $tmp = '_ESWAJPG';
                else if(stringContainsAll($lowercase_file_name, array("swa")) && $ext === "jpg") $tmp = '_HSWAJPG';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END mala.khetarpal@rediffmail.com ==================================================
            
            //====== BEGIN malayalammurli@gmail.com ==================================================
            else if($mail_from === "malayalammurli@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("mal")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END malayalammurli@gmail.com ==================================================

            //====== BEGIN murlimylife@gmail.com / sudharani.kuram@gmail.com =======================
            else if($mail_from === "murlimylife@gmail.com" || $mail_from === "sudharani.kuram@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("telugu", "mob")) && $ext === "htm") $tmp = '_TMOBHTM';
                else if(stringContainsAll($lowercase_file_name, array("telugu")) && $ext === "htm") $tmp = '_TDESKHTM';
                else if(stringContainsAll($lowercase_file_name, array("hin", "sms", "mob")) && $ext === "htm") $tmp = '_HMOBSMSHTM';
                else if(stringContainsAll($lowercase_file_name, array("hin", "sms")) && $ext === "htm") $tmp = '_HDESKSMSHTM';
                else if(stringContainsAll($lowercase_file_name, array("h", "mob")) && $ext === "htm") $tmp = '_HMOBMURLIHTM';
                else if(stringContainsAll($lowercase_file_name, array("h")) && $ext === "htm") $tmp = '_HDESKMURLIHTM';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END murlimylife@gmail.com / sudharani.kuram@gmail.com =======================

            //====== BEGIN preetiswami2005@gmail.com ==================================================
            else if($mail_from === "preetiswami2005@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("pree")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END preetiswami2005@gmail.com ==================================================

            //====== BEGIN prema_french@rediffmail.com ==================================================
            else if($mail_from === "prema_french@rediffmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("french")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END prema_french@rediffmail.com ==================================================

            //====== BEGIN raghubiru010@gmail.com ==================================================
            else if($mail_from === "raghubiru010@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("pun")) && $ext === "mp3") $tmp = '_MP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END raghubiru010@gmail.com ==================================================

            //====== BEGIN shefu224@gmail.com ==================================================
            else if($mail_from === "shefu224@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("eng", "sms", "mob")) && $ext === "htm") $tmp = '_MOBSMSHTM';
                else if(stringContainsAll($lowercase_file_name, array("eng", "sms")) && $ext === "htm") $tmp = '_DESKSMSHTM';
                else if(stringContainsAll($lowercase_file_name, array("e", "mob")) && $ext === "htm") $tmp = '_MOBHTM';
                else if(stringContainsAll($lowercase_file_name, array("-e")) && $ext === "htm") $tmp = '_DESKHTM';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END shefu224@gmail.com ==================================================

            //====== BEGIN shivamanibk1936@gmail.com ==================================================
            else if($mail_from === "shivamanibk1936@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("akp", "k")) && $ext === "mp3") $tmp = '_AKPMP3';
                else if(stringContainsAll($lowercase_file_name, array("hindi", "kan")) && $ext === "mp3") $tmp = '_HTOKMP3';
                else if(stringContainsAll($lowercase_file_name, array("-k")) && $ext === "mp3") $tmp = '_KMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END shivamanibk1936@gmail.com ==================================================

            //====== BEGIN suhasinibk@gmail.com ==================================================
            else if($mail_from === "suhasinibk@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("akp")) && $ext === "mp3") $tmp = '_AKPMP3';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END suhasinibk@gmail.com ==================================================

            //====== BEGIN yogamart@sg.brahmakumaris.org ==================================================
            else if($mail_from === "yogamart@sg.brahmakumaris.org") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                $tmp = '';
                if(stringContainsAll($lowercase_file_name, array("lanka")) && $ext === "mp3") $tmp = '_MP3';
                else if(stringContainsAll($lowercase_file_name, array("mob")) && $ext === "htm") $tmp = '_MOBTHOTHTM';
                else if(stringContainsAll($lowercase_file_name, array("htm")) && $ext === "htm") $tmp = '_DESKTHOTHTM';
                $filename_postfix_as_desired = strlen($tmp) > 1 ? $file_postfix[$mail_from . $tmp] : '';
                $target_loc = strlen($tmp) > 1 ? $file_loc[$mail_from . $tmp] : '';
                $attached_files = $attached_files . downloadAndMoveFile($attachment, $filename_postfix_as_desired, $filename_as_rcvd, $target_loc, $overwrite);
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not allowed from $mail_from!</span></li>";
              }
            }
            //====== END yogamart@sg.brahmakumaris.org ==================================================
            else {
              $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Sender $mail_from is not yet configured!</span></li>";
            }
          } else {
            $attached_files = $attached_files . '<li>Empty file name attachment found!</li>';
          }
        }
      }
      return $attached_files;
    } else {
      return '<li>No attachments found!</li>';
    }
  }
  
  if(isset($_POST['proceed'])) {   //this means proceed button on form is pressed
    $filter = '';
    
    //1) setting email from filter
    if($_POST['email_from'] === 'ALL') $filter = $filter . 'ALL ';
    else $filter = $filter . 'FROM "' . $_POST['email_from'] . '" ';
    
    //2) adding up date filter
    $curr_date = date_create(date("Y-m-d"));
    $prev_date = $curr_date;
    date_sub($prev_date, date_interval_create_from_date_string($_POST['since'] . " days"));
    $filter = $filter . 'SINCE "' . $prev_date->format('d M Y') . '" ';
    
    //3) adding up seen/unseen filter
    $filter = $filter . $_POST['type'];
    $filter = trim($filter);
    try {
      $inbox = imap_open($hostname, $username, $password);   //Check all labels: https://electrictoolbox.com/open-mailbox-other-than-inbox-php-imap/
      if($inbox) {
        $emails = imap_search($inbox, $filter);
        $mail_from = '';
        $attachments = array();
        if($emails) {
          rsort($emails);   //put the newest emails on top
          foreach($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $detailed_overview = imap_headerinfo($inbox, $email_number);
            //echo json_encode($detailed_overview) . "<br><br>";
            $mail_from = $detailed_overview->from[0]->mailbox . '@' . $detailed_overview->from[0]->host;
            $structure = imap_fetchstructure($inbox, $email_number);
            //echo json_encode($structure) . "<br><br>";
            $attachments = getAllAttachments($structure, $inbox, $email_number);
            $message = $message . "<span style='color:yellow;'>From: $mail_from on " . $detailed_overview->date . "</span><ul>";
            $message = $message . processAttachments($attachments, $mail_from, intval($_POST['mode']));
            $message = $message . '</ul>';
          }
          $message = $message . '<span class="bg-warning text-white p-2">Total ' . count($emails) . ' email(s) processed!!</span>';
        } else {
          $message = $message . '<br><br><span class="bg-danger text-white">No emails found using filter: <span class="text-warning">' . $filter . '</span></span>';
        }
        imap_close($inbox);
      } else {
        $message = $message . '<br><br><span class="bg-danger text-white">Cannot connect to Gmail: ' . imap_last_error() . '</span>';
      }
    } catch(Exception $e) {
      $message = $message . '<br><br><span class="bg-danger text-white">ERROR/EXCEPTION: ' . $e->getMessage() . '</span>';
    }
    $message = startsWith($message, '<br><br>') ? str_replace('<br><br>', '', $message) : $message;
  }

?>
<html lang="en">
  <head>
    <title>Downloader - GMAIL Data</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/png" href="images/bks/sb_72x72.png"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.4_3_1.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu"/>
    <link rel="stylesheet" href="js/flash/dist/flash.css">
    <script src="js/jquery.slim.min.3_4_1.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.4_3_1.js"></script>
    <script src="js/flash/dist/flash.min.js"></script>
    <script src="js/flash/dist/flash.jquery.min.js"></script>
    <style>
      .navbar-nav > li > a {
        padding-top: 8px;
        padding-bottom: 8px;
        height: 35px;
        line-height: 35px;
      }
      .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
          -webkit-user-select: none; /* Safari */
           -khtml-user-select: none; /* Konqueror HTML */
             -moz-user-select: none; /* Firefox */
              -ms-user-select: none; /* Internet Explorer/Edge */
                  user-select: none; /* Non-prefixed version, currently supported by Chrome and Opera */
      }      
    </style>
  </head>
  <body style="padding-top: 70px;font-family: Ubuntu;background-color: #FFEBCC">
    <?php
      $brand_name = '<i class="fa fa-google" aria-hidden="true"></i>-MAIL Data Downloader';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="email_from" class="col-sm-4 col-form-label">From: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="email_from" name="email_from">
              <option value="ALL">ALL</option>
              <?php 
                for($i = 0; $i < count($sender_list); $i++) {
                  echo "<option value='$sender_list[$i]'>$sender_list[$i]</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="since" class="col-sm-4 col-form-label">Since: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="since" name="since">
              <option value="0">Today</option>
              <option value="1">Yesterday</option>
              <option value="2">1 Day Before Yesterday</option>
              <option value="3">2 Days Before Yesterday</option>
              <option value="4">3 Days Before Yesterday</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="mode" class="col-sm-4 col-form-label">Mode: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="mode" name="mode">
              <option value="0">Do Not Overwrite</option>
              <option value="1">Overwrite</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="type" class="col-sm-4 col-form-label">Type: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="type" name="type">
              <option value="SEEN">Seen</option>
              <option value="UNSEEN" selected>UnSeen</option>
              <option value="">Both</option>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Download GMAIL & Upload To Server
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      function validateForm1(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#proceed').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>