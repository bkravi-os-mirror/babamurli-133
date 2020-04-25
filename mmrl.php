<?php 
  
  session_start();
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $main_dir = "000-Ravi-DontDelete/htms";
  $zip_file_dir = "$rootdir/$main_dir";
  $distro_dir = "$rootdir/000-Ravi-DontDelete/mfm";
  $message = '';
  
  //as:Assame, bn:Bengali, de:Deutsch, el:Greek, en:English, es:Spanish, fr:French, gu:Gujarati, hi:Hindi, hu:Hungarian
  //it:Italian, kn:Kannada, ko:Korean, ml:Malayalam, mr:Marathi, ne:Nepali, or:Odia, pl:Polish, pt:Portuguese, pa:Punjabi
  //si:Sinhala, th:Thai, ta_my:TamilLanka, ta:Tamil, te:Telugu, ro:Romanian, 
  $code_lang_map = array(
    "hi" => "Hindi", "te" => "Telugu", "en" => "English", "as" => "Assame", "bn" => "Bengali", "de" => "Deutsch",
    "fr" => "French", "el" => "Greek", "gu" => "Gujarati", "ta" => "Tamil", "it" => "Italian", "ko" => "Korean",
    "es" => "Spanish", "hu" => "Hungarian", "kn" => "Kannada", "ml" => "Malayalam", "mr" => "Marathi", "ne" => "Nepali",
    "or" => "Odia", "pl" => "Polish", "pt" => "Portuguese", "pa" => "Punjabi", "th" => "Thai", "si" => "Sinhala",
    "ta_my" => "TamilLanka",
  );
  
  $need_sms = array("hi","en",);   //array for which we need SMS also. NOTE: Must configure the SMS locations also as below
  
  $destination_dir_array = array(
    "hi" => "$rootdir/$main_dir", "hi_sms" => "$rootdir/$main_dir", "hi_mob" => "$rootdir/$main_dir", "hi_mob_sms" => "$rootdir/$main_dir",
    "en" => "$rootdir/$main_dir", "en_sms" => "$rootdir/$main_dir", "en_mob" => "$rootdir/$main_dir", "en_mob_sms" => "$rootdir/$main_dir",
    "te" => "$rootdir/$main_dir", "te_mob" => "$rootdir/$main_dir", "as" => "$rootdir/$main_dir", "as_mob" => "$rootdir/$main_dir",
    "bn" => "$rootdir/$main_dir", "bn_mob" => "$rootdir/$main_dir", "de" => "$rootdir/$main_dir", "de_mob" => "$rootdir/$main_dir",
    "fr" => "$rootdir/$main_dir", "fr_mob" => "$rootdir/$main_dir", "el" => "$rootdir/$main_dir", "el_mob" => "$rootdir/$main_dir",
    "gu" => "$rootdir/$main_dir", "gu_mob" => "$rootdir/$main_dir", "ta" => "$rootdir/$main_dir", "ta_mob" => "$rootdir/$main_dir",
    "it" => "$rootdir/$main_dir", "it_mob" => "$rootdir/$main_dir", "ko" => "$rootdir/$main_dir", "ko_mob" => "$rootdir/$main_dir",
    "es" => "$rootdir/$main_dir", "es_mob" => "$rootdir/$main_dir", "hu" => "$rootdir/$main_dir", "hu_mob" => "$rootdir/$main_dir",
    "kn" => "$rootdir/$main_dir", "kn_mob" => "$rootdir/$main_dir", "ml" => "$rootdir/$main_dir", "ml_mob" => "$rootdir/$main_dir",
    "mr" => "$rootdir/$main_dir", "mr_mob" => "$rootdir/$main_dir", "ne" => "$rootdir/$main_dir", "ne_mob" => "$rootdir/$main_dir",
    "or" => "$rootdir/$main_dir", "or_mob" => "$rootdir/$main_dir", "pl" => "$rootdir/$main_dir", "pl_mob" => "$rootdir/$main_dir",
    "pt" => "$rootdir/$main_dir", "pt_mob" => "$rootdir/$main_dir", "pa" => "$rootdir/$main_dir", "pa_mob" => "$rootdir/$main_dir",
    "th" => "$rootdir/$main_dir", "th_mob" => "$rootdir/$main_dir", "si" => "$rootdir/$main_dir", "si_mob" => "$rootdir/$main_dir",
    "ta_my" => "$rootdir/$main_dir", "ta_my_mob" => "$rootdir/$main_dir", 
  );
  $actual_loc_array = array(
    "hi" => "$rootdir/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm",
    "hi_sms" => "$rootdir/01. Daily Murli/01. Hindi/07. Hindi Murli - Saar - SMS",
    "hi_mob" => "$rootdir/01. Daily Murli/01. Hindi/36. Mobile Htm",
    "hi_mob_sms" => "$rootdir/01. Daily Murli/01. Hindi/37. Mobile SMS",
    
    "en" => "$rootdir/01. Daily Murli/02. English/01. Eng Murli - Htm",
    "en_sms" => "$rootdir/01. Daily Murli/02. English/07. Eng Murli - Ess - SMS",
    "en_mob" => "$rootdir/01. Daily Murli/02. English/36. Mobile Htm",
    "en_mob_sms" => "$rootdir/01. Daily Murli/02. English/37. Mobile SMS",
    
    "te" => "$rootdir/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm",
    "te_mob" => "$rootdir/01. Daily Murli/04. Telugu/36. Mobile Htm",
    
    "as" => "$rootdir/01. Daily Murli/08. Assame/02. Assam3 - Htm",
    "as_mob" => "$rootdir/01. Daily Murli/08. Assame/03. Assam3 - Mobile Htm",
    
    "bn" => "$rootdir/01. Daily Murli/07. Bengali/01. Bengali Murli - Htm",
    "bn_mob" => "$rootdir/01. Daily Murli/07. Bengali/05. Bengali Murli - Mobile Htm",

    "de" => "$rootdir/01. Daily Murli/31. Deutsch/Htm-Deutsch",
    "de_mob" => "$rootdir/01. Daily Murli/31. Deutsch/Mobile Htm-Deutsch",

    "fr" => "$rootdir/01. Daily Murli/36. French/Htm-French",
    "fr_mob" => "$rootdir/01. Daily Murli/36. French/Mobile Htm-French",

    "el" => "$rootdir/01. Daily Murli/37. Greek/Htm-Greek",
    "el_mob" => "$rootdir/01. Daily Murli/37. Greek/Mobile Htm-Greek",

    "gu" => "$rootdir/01. Daily Murli/09. Gujarati/01. Gujarati Murli - Htm",
    "gu_mob" => "$rootdir/01. Daily Murli/09. Gujarati/02. Gujarati Mobile - Htm",

    "ta" => "$rootdir/01. Daily Murli/03. Tamil/01. Tamil Murli - Htm",
    "ta_mob" => "$rootdir/01. Daily Murli/03. Tamil/36. Mobile Htm",

    "it" => "$rootdir/01. Daily Murli/33. Italian/Htm-Italiano",
    "it_mob" => "$rootdir/01. Daily Murli/33. Italian/Mobile Htm-Italiano",

    "ko" => "$rootdir/01. Daily Murli/39. Korean/Htm-Korian",
    "ko_mob" => "$rootdir/01. Daily Murli/39. Korean/Mobile Htm-Korian",

    "es" => "$rootdir/01. Daily Murli/32. Spanish/Htm-Spanish",
    "es_mob" => "$rootdir/01. Daily Murli/32. Spanish/Mobile Htm-Spanish",

    "hu" => "$rootdir/01. Daily Murli/38. Hungarian/Htm-Hungarian",
    "hu_mob" => "$rootdir/01. Daily Murli/38. Hungarian/Mobile Htm-Hungarian",

    "kn" => "$rootdir/01. Daily Murli/05. Kannada/01. Kannada Murli - Htm",
    "kn_mob" => "$rootdir/01. Daily Murli/05. Kannada/06. Kannada Murli - Mobile -  Htm",

    "ml" => "$rootdir/01. Daily Murli/06. Malayalam/01. Malayalam Murli - Htm",
    "ml_mob" => "$rootdir/01. Daily Murli/06. Malayalam/04. Malayalam Murli -Mobile",

    "mr" => "$rootdir/01. Daily Murli/12. Marathi/01. Marathi Murli - Htm",
    "mr_mob" => "$rootdir/01. Daily Murli/12. Marathi/02. Marathi Mobile - Htm",

    "ne" => "$rootdir/01. Daily Murli/30. Nepali/03. Nepali Murli - Htm",
    "ne_mob" => "$rootdir/01. Daily Murli/30. Nepali/03. Nepali Murli - Mobile Htm",

    "or" => "$rootdir/01. Daily Murli/10. Odiya/01. Odiya Murli - Htm",
    "or_mob" => "$rootdir/01. Daily Murli/10. Odiya/04. Odiya Murli - Mobile Htm",

    "pl" => "$rootdir/01. Daily Murli/40. Polish/Htm-Polish",
    "pl_mob" => "$rootdir/01. Daily Murli/40. Polish/Mobile Htm-Polish",

    "pt" => "$rootdir/01. Daily Murli/41. Portuguese/Htm-Portuguese",
    "pt_mob" => "$rootdir/01. Daily Murli/41. Portuguese/Mobile Htm-Portuguese",

    "pa" => "$rootdir/01. Daily Murli/11. Punjabi/01. Punjabi Murli - Htm",
    "pa_mob" => "$rootdir/01. Daily Murli/11. Punjabi/03. Punjabi Murli - Mobile Htm",

    "th" => "$rootdir/01. Daily Murli/43. Thai/Htm-Thai",
    "th_mob" => "$rootdir/01. Daily Murli/43. Thai/Mobile Htm-Thai",

    "si" => "$rootdir/01. Daily Murli/44. Sinhala/Htm-Sinhala",
    "si_mob" => "$rootdir/01. Daily Murli/44. Sinhala/Mobile Htm-Sinhala",
    
    "ta_my" => "$rootdir/01. Daily Murli/35. Tamil-Lanka/Htm-Tamil-Lanka",
    "ta_my_mob" => "$rootdir/01. Daily Murli/35. Tamil-Lanka/Mobile Htm-Tamil-Lanka",

  );
  $filename_suffix = array(
    "ta_my" => "-TamilLanka.htm",
    "ta_my_mob" => "-TamilLanka-Mob.htm",

    "si" => "-Sinhala.htm",
    "si_mob" => "-Sinhala-Mob.htm",

    "th" => "-Thai.htm",
    "th_mob" => "-Thai-Mob.htm",

    "hi" => "-H.htm",
    "hi_sms" => "-Hin-SMS.htm",
    "hi_mob" => "-H-Mob.htm",
    "hi_mob_sms" => "-Hin-SMS-Mob.htm",
    
    "en" => "-E.htm",
    "en_sms" => "-Eng-SMS.htm",
    "en_mob" => "-E-Mob.htm",
    "en_mob_sms" => "-Eng-SMS-Mob.htm",
    
    "te" => "-Telugu.htm",
    "te_mob" => "-Telugu-Mob.htm",

    "as" => "-Assame.htm",
    "as_mob" => "-Assame-Mob.htm",
    
    "bn" => "-Bengali.htm",
    "bn_mob" => "-Bengali-Mob.htm",
 
    "de" => "-Deutsch.htm",
    "de_mob" => "-Deutsch-Mob.htm",
 
    "fr" => "-French.htm",
    "fr_mob" => "-French-Mob.htm",
 
    "el" => "-Greek.htm",
    "el_mob" => "-Greek-Mob.htm",
 
    "gu" => "-Gujarati.htm",
    "gu_mob" => "-Gujarati-Mob.htm",
 
    "ta" => "-Tamil.htm",
    "ta_mob" => "-Tamil-Mob.htm",
 
    "it" => "-Italian.htm",
    "it_mob" => "-Italian-Mob.htm",
 
    "ko" => "-Korean.htm",
    "ko_mob" => "-Korean-Mob.htm",

    "es" => "-Spanish.htm",
    "es_mob" => "-Spanish-Mob.htm",

    "hu" => "-Hungarian.htm",
    "hu_mob" => "-Hungarian-Mob.htm",

    "kn" => "-K.htm",
    "kn_mob" => "-K-Mob.htm",
    
    "pa" => "-Pun.htm",
    "pa_mob" => "-Pun-Mob.htm",

    "pt" => "-Port.htm",
    "pt_mob" => "-Port-Mob.htm",

    "pl" => "-Polish.htm",
    "pl_mob" => "-Polish-Mob.htm",

    "or" => "-Odia.htm",
    "or_mob" => "-Odia-Mob.htm",

    "ne" => "-Nep.htm",
    "ne_mob" => "-Nep-Mob.htm",

    "mr" => "-Marathi.htm",
    "mr_mob" => "-Marathi-Mob.htm",

    "ml" => "-Mal.htm",
    "ml_mob" => "-Mal-Mob.htm",

);
  
  asort($code_lang_map);   //ascending sort based on 'value'
  
  function getSanitizedStr($str) {
    $str = str_replace("\"'", '"', $str);
    $str = str_replace(" ''", '"', $str);
    $str = str_replace("“ ", '"', $str);
    $str = str_replace("“", '"', $str);
    $str = str_replace(" ,", ',', $str);
    $str = str_replace(" :- ", ':- ', $str);
    return $str;
  }
  
  function getMurliForDate($lang_code, $yyyy_mm_dd_date, &$for_SMS) {
    global $is_this_for_email_DL;
    $htm_URL = "http://madhubanmurli.org/murlis/$lang_code/html/murli-$yyyy_mm_dd_date.html";
    $html = file_get_html($htm_URL);
    if($html) {
      $top_level_element = $html->find(".lang-$lang_code", 0);   //eg. "lang-ta_my, lang-hi"
      $murli_header = $html->find(".header", 0)->plaintext;
      $essence = $html->find(".essence-txt", 0)->plaintext;
      $q_label = $html->find(".question-lbl", 0)->plaintext;
      $q_label_1 = $html->find(".question-lbl", 1)->plaintext;
      $q_txt = $html->find(".question-txt", 0)->plaintext;
      $q_txt_1 = $html->find(".question-txt", 1)->plaintext;   //sometimes there are 2 questions
      $a_label = $html->find(".answer-lbl", 0)->plaintext;
      $a_label_1 = $html->find(".answer-lbl", 1)->plaintext;
      $a_txt = $html->find(".answer-txt", 0)->plaintext;
      $a_txt_1 = $html->find(".answer-txt", 1)->plaintext;
      $s_label = $html->find(".song-lbl", 0)->plaintext;
      $s_txt = $html->find(".song-txt", 0)->plaintext;
      $tmp_murli_body = $html->find(".murli-body", 0);
      if($tmp_murli_body) {   //in some case like, French, TamilLanka, Porugese we don't have "murli-body" class
        $murli_body_div = $tmp_murli_body;
        $murli_body = $tmp_murli_body->innertext;
      } else {
        //Fix this for Tamil & Tamil Lanka. children(3)
        $murli_body_div = $top_level_element->children(0);
        while($murli_body_div->tag != 'div') {   //lets find the <div> that has full murli
          $murli_body_div = $murli_body_div->next_sibling();
        }
        $murli_body = $murli_body_div->innertext;
      }
      $b_label = $html->find(".blessing-lbl", 0)->plaintext;
      //below processing blessing because blessing-txt class has two things: blessing+blessing detail e.g. <td class=blessing-txt><b>blessing</b><br>"blessing detail"</td>
      $b_txt = trim($html->find(".blessing-txt", 0)->innertext);   //it will return like <b>blessing</b><br>"blessing detail"
      $tmp = explode("<br>", $b_txt, 2);   //here passing last argument will return that many array only.
      $b_bless = trim($tmp[0]);   //will return <b>blessing</b>
      $b_bless_dtl = trim($tmp[1]);   //will return "blessing detail"
      
      $sl_label = $html->find(".slogan-lbl", 0)->plaintext;
      $sl_txt = $html->find(".slogan-txt", 0)->plaintext;

      $post = "</span></p>";
      
      $final = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Brahma Kumaris</title></head>";
      $final = "$final<body link='#800000' vlink='#800000' bgcolor='#FFEBCC' topmargin='0' leftmargin='0'>";
      $final = "$final<table cellspacing='2' cellpadding='0' width='980' border='0' style='letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;'>";
      $final = "$final<tr><td valign='top' width='980'><table width='980'><colgroup><col width='325' style='width: 244pt;'><col width='97' style='width: 73pt;'></colgroup>";
      $final = "$final<tr><td class='xl26' valign='middle' align='left' width='980' style='border: 0px solid rgb(192, 192, 192);'>";
      $final = "$final<blockquote>";
      $final = "$final<span style='font-size:16pt; color:#006600; lang=HI; font-family:Mangal; text-align:justify; line-height:normal'>";

      if($murli_header) {
        $murli_header = getSanitizedStr($murli_header);
        $murli_header = "<p align=center><span style='color:red;'>$murli_header$post";
        $murli_header = "$murli_header<hr size='1' width='100%' noshade style='color:maroon' align='center'>";
      }
      if($essence) {
        $essence = getSanitizedStr($essence);
        $essence = "<p><span style='color:blue;'>$essence$post";
      }
      if($q_label) {
        $q_label = "<p><span style='color:#006600;'>$q_label$post";
      }
      if($q_label_1) {
        $q_label_1 = "<p><span style='color:#006600;'>$q_label_1$post";
      }
      if($q_txt) {
        $q_txt = getSanitizedStr($q_txt);
        $q_txt = "<p><span style='color:#7030A0;'>$q_txt$post";
      }
      if($q_txt_1) {
        $q_txt_1 = getSanitizedStr($q_txt_1);
        $q_txt_1 = "<p><span style='color:#7030A0;'>$q_txt_1$post";
      }
      if($a_label) {
        $a_label = "<p><span style='color:#008000;'>$a_label$post";
      }
      if($a_label_1) {
        $a_label_1 = "<p><span style='color:#008000;'>$a_label_1$post";
      }
      if($a_txt) {
        $a_txt = getSanitizedStr($a_txt);
        $a_txt = "<p><span style='color:#C00000;'>$a_txt$post";
      }
      if($a_txt_1) {
        $a_txt_1 = getSanitizedStr($a_txt_1);
        $a_txt_1 = "<p><span style='color:#C00000;'>$a_txt_1$post";
      }
      if($s_label) {
        $s_label = "<p><span style='color:#008000;'>$s_label$post";
      }
      if($s_txt) {
        $s_txt = getSanitizedStr($s_txt);
        $s_txt = "<p><span style='color:red;'>$s_txt$post";
      }
      
      //Below preparing Murli Body
      $murli_body = getSanitizedStr($murli_body);
      if(strpos($murli_body, "<b>") !== false) {
        $murli_body = str_replace("<b>", "<b><span style='color:red'>", $murli_body);
      }
      if(strpos($murli_body, "</b>") !== false) {
        $murli_body = str_replace("</b>", "</span></b>", $murli_body);
      }
      $murli_body = "<span style='color:#002060;'>$murli_body</span>";
      //Above preparing Murli Body
      
      if($b_label) {
        $b_label = "<p><span style='color:#008000;'>$b_label$post";
      }
      if($b_bless) {
        $b_bless = getSanitizedStr($b_bless);
        $b_bless = "<p><span style='color:fuchsia;'>$b_bless$post";
      }
      if($b_bless_dtl) {
        $b_bless_dtl = getSanitizedStr($b_bless_dtl);
        $b_bless_dtl = "<p><span style='color:#C00000;'>$b_bless_dtl$post";
      }
      if($sl_label) {
        $sl_label = "<p><span style='color:#008000;'>$sl_label$post";
      }
      if($sl_txt) {
        $sl_txt = getSanitizedStr($sl_txt);
        $sl_txt = "<p><span style='color:#0000FF;'>$sl_txt$post";
      }

      $dt_for_sms = date('d.m.Y', strtotime($yyyy_mm_dd_date));
      $for_SMS = "$final<p align=center><span style='color:red;'>$dt_for_sms</span></p>$essence$q_label$q_txt$a_label$a_txt$q_label_1$q_txt_1$a_label_1$a_txt_1$dharna_point_only$b_label$b_bless$b_bless_dtl$sl_label$sl_txt";
      $final = "$final$murli_header$essence$q_label$q_txt$a_label$a_txt$q_label_1$q_txt_1$a_label_1$a_txt_1$s_label$s_txt$murli_body$b_label$b_bless$b_bless_dtl$sl_label$sl_txt";
      $for_SMS = "$for_SMS</span></blockquote></td></tr></table></td></tr></table></body></html>";
      
      //Lets check if Murli has anything else after slogan
      //$murli_structure = $html->find(".lang-$lang_code", 0)->children;   //e.g. finding class="lang-hi"
      //echo count($murli_structure);
      $blessing_slogan = $html->find(".blessing-slogan", 0);
      if($blessing_slogan) {   //in some cases like french, tamil lanka, tamil, we don't have "blessing-slogan" class
        $blessing_slogan = $blessing_slogan->next_sibling();
      } else {
        $blessing_slogan = $murli_body_div->next_sibling()->next_sibling();   //after murli body, there is <table> for blessing+slogan then everything else is considered as 'post murli' stuff
      }
      $rest_of_the_Murli = '';
      while($blessing_slogan) {
        $rest_of_the_Murli = $rest_of_the_Murli . $blessing_slogan;
        $blessing_slogan = $blessing_slogan->next_sibling();
      }
      if(strlen($rest_of_the_Murli) > 1) {
        $rest_of_the_Murli = getSanitizedStr($rest_of_the_Murli);
        $rest_of_the_Murli = str_replace("<b>", "<span style='color:red;'><b>", $rest_of_the_Murli);
        $rest_of_the_Murli = str_replace("</b>", "</b></span>", $rest_of_the_Murli);
        $final = "$final<span style='color:#000080;'>$rest_of_the_Murli</span>";
      }
      $final = "$final</span></blockquote></td></tr></table></td></tr></table></body></html>";  //here </span> is the topmost level
      //echo $final;
      //die();
      return $is_this_for_email_DL ? str_get_html($final)->plaintext : $final;
    } else {
      return false;
    }
  }
  
  function scan_dir() {
    global $distro_dir;
    $ignored = array('.', '..', '.svn', '.htaccess', '.mp5');
    $files = array();    
    foreach(scandir($distro_dir) as $file) {
      if(in_array($file, $ignored)) continue;
      if((strpos($file, "_qz") !== false) && (strpos($file, "sent") !== false))   //e.g. E-27-Apr_TO_03-May_qz.txt.sent
        $files[$file] = filemtime($distro_dir . '/' . $file);
    }
    arsort($files);
    $files = array_keys($files);
    return ($files) ? $files : array();
  }

  function getFullFileNameToWrite($l_code, $for_date, $is_super_user) {   //date must be in YYYY-MM-DD format
    global $destination_dir_array, $filename_suffix, $actual_loc_array, $is_this_for_email_DL, $distro_dir;
    $file_name_prefix = date("d.m.y", strtotime($for_date));
    if($is_super_user && $is_this_for_email_DL)
      return "$distro_dir/$for_date{$filename_suffix[$l_code]}.txt";   //if it is for emailing, lets keep filename format YYYY-MM-DD
    else if($is_super_user)
      return "{$actual_loc_array[$l_code]}/$file_name_prefix{$filename_suffix[$l_code]}";
    else
      return "{$destination_dir_array[$l_code]}/$file_name_prefix{$filename_suffix[$l_code]}";
  }
  
  $curr_timestamp = strtotime(date('Y-m-d'));   //this approach will make $curr_timestamp constant first.
  $day = date('d', $curr_timestamp);
  $month = date('m', $curr_timestamp);
  $year = date('Y', $curr_timestamp);
  $how_many_days = 7;
  $display_downloadfiles = 'none';
  $super_user = false;
  $is_this_for_email_DL = false;
  $sent_files_array = scan_dir();
  
  if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_SESSION['token'] == 16108)
    $super_user = true;
  
  if(isset($_POST['proceed'])) {   //i.e. "Create Murli HTMs" is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    if(isset($_POST['token']) && !empty($_POST['token']) && $_POST['token'] == 16108) {   //i.e. secret token given. more previlized user
      $super_user = true;
    } else{
      emptyThisDirectory($zip_file_dir);
    }
    $message = "Below Results:<ul>";
    $day = $_POST['st_date'];  $month = $_POST['st_month'];  $year = $_POST['st_year'];
    $l_code = $_POST['l_code'];   $how_many_days = (int)$_POST['days'];
    $for_date = "$year-$month-$day";
    $is_this_for_email_DL = isset($_POST['distro']) && !empty($_POST['distro']) && $_POST['distro'] === "on";
    for($loop = 1; $loop <= $how_many_days; $loop++) {
      $for_SMS = '';
      $result = getMurliForDate($l_code, $for_date, $for_SMS);   //date must be YYYY-MM-DD format
      if($result === false) {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> <span class='bg-danger text-white'> {$code_lang_map[$l_code]} Htm</span> NOT available for $for_date!!</li>";
      } else {
        //below writing Desktop files ====================================
        //FULL HTM
        $file_to_write = getFullFileNameToWrite($l_code, $for_date, $super_user);
        $file_name_only = pathinfo($file_to_write, PATHINFO_FILENAME) . "." . pathinfo($file_to_write, PATHINFO_EXTENSION);
        $file = @fopen($file_to_write, "w");
        if($file) {
          if(fwrite($file, $result)) {
            $message = $message . "<li>$file_name_only <span class='bg-success text-white'>OK</span></li>";
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> writing into <span class='bg-danger text-white'>$file_to_write</span></li>";
          }
          fclose($file);
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> opening <span class='bg-danger text-white'>$file_to_write</span> to write!!</li>";
        }
        //SMS HTM
        if(in_array($l_code, $need_sms) && !$is_this_for_email_DL) {   //creating sms only if we need
          $file_to_write = getFullFileNameToWrite($l_code . "_sms", $for_date, $super_user);
          $file_name_only = pathinfo($file_to_write, PATHINFO_FILENAME) . "." . pathinfo($file_to_write, PATHINFO_EXTENSION);
          $file = @fopen($file_to_write, "w");
          if($file) {
            if(fwrite($file, $for_SMS)) {
              $message = $message . "<li>$file_name_only <span class='bg-success text-white'>OK</span></li>";
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> writing into <span class='bg-danger text-white'>$file_to_write</span></li>";
            }
            fclose($file);
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> opening <span class='bg-danger text-white'>$file_to_write</span> to write!!</li>";
          }
        }
        
        //below writing Mobile files ====================================
        //FULL HTM
        if(!$is_this_for_email_DL) {   //no need to write mobile files if this is for email DL
          $file_to_write = getFullFileNameToWrite($l_code . "_mob", $for_date, $super_user);
          $file_name_only = pathinfo($file_to_write, PATHINFO_FILENAME) . "." . pathinfo($file_to_write, PATHINFO_EXTENSION);
          $file = @fopen($file_to_write, "w");
          if($file) {
            if(fwrite($file, str_replace("16pt", "45pt", str_replace("hr size='1'", "hr size='5'", str_replace("16pt", "45pt", $result))))) {
              $message = $message . "<li>$file_name_only <span class='bg-success text-white'>OK</span></li>";
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> writing into <span class='bg-danger text-white'>$file_to_write</span></li>";
            }
            fclose($file);
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> opening <span class='bg-danger text-white'>$file_to_write</span> to write!!</li>";
          }
        }
        //SMS HTM
        if(in_array($l_code, $need_sms) && !$is_this_for_email_DL) {   //creating sms only of we need
          $file_to_write = getFullFileNameToWrite($l_code . "_mob_sms", $for_date, $super_user);
          $file_name_only = pathinfo($file_to_write, PATHINFO_FILENAME) . "." . pathinfo($file_to_write, PATHINFO_EXTENSION);
          $file = @fopen($file_to_write, "w");
          if($file) {
            if(fwrite($file, str_replace("16pt", "45pt", str_replace("hr size='1'", "hr size='5'", str_replace("16pt", "45pt", $for_SMS))))) {
              $message = $message . "<li>$file_name_only <span class='bg-success text-white'>OK</span></li>";
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> writing into <span class='bg-danger text-white'>$file_to_write</span></li>";
            }
            fclose($file);
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> opening <span class='bg-danger text-white'>$file_to_write</span> to write!!</li>";
          }
        }
      }
      $for_date = addDaysToDate(1, $for_date, 'Y-m-d', 'Y-m-d');
    }
    //Now starting zipping if not a super user
    if(!$super_user) {
      $zip_file_nm_only = time() . ".zip";
      $zipname = "$zip_file_dir/$zip_file_nm_only";
      $files_array = dirToArray($zip_file_dir);
      $zip = new ZipArchive();
      $ahref = '';
      if($zip->open($zipname, ZipArchive::CREATE) !== true) {
        $message = "<li><span class='bg-danger text-white'>ERROR creating zip file!!!</span></li>";
      } else {
        foreach($files_array as $key => $file) {
          $zip->addFile("$zip_file_dir/$file", "OmShanti/$file");
        }
        $zip->close();
        $ahref = "http://babamurli.com/$main_dir/$zip_file_nm_only";
        $display_downloadfiles = (file_exists($zipname) && filesize($zipname) > 0) ? "inherit" : "none";
      }
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>HTM Murli Generator</title>
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
    <script src="js/rav-util.js"></script>
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
      $brand_name = '<i class="fa fa-file-code-o" aria-hidden="true"></i>&nbsp;HTM Murli Generator';
      if($super_user) include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;display:<?php echo $super_user ? 'inline-block' : 'none';?>;">
          <label class="form-check-label" style="margin-left:35px;">
            <input class="form-check-input" type="checkbox" id="distro" name="distro" onclick="showHideSentFiles();" <?php echo $is_this_for_email_DL ? ' checked' : '';?>>
              <i class="fa fa-hand-o-left" aria-hidden="true"></i> Check this for E-mail DL
          </label>
        </div>
        <div class="form-group" id="sent_files_holder">
          <select class="form-control" id="sent_files_holder_sel" name="sent_files_holder_sel">
              <?php
                foreach($sent_files_array as $sent_file) {
                  echo "<option value='$sent_file'>$sent_file</option>";
                }
              ?>
          </select>
          <div class="mt-4"><kbd style='background-color:red;line-height:24px;'>
            It will email murlis in plaintext for quiz. No htm will be created</kbd>
          </div>
        </div>
        <div class="form-group row">
          <label for="st_date" class="col-sm-4 col-form-label" id="date_label">Start Date:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="st_date" name="st_date" onchange="onDateSelected()">
              <?php
                for($i = 1; $i <= 31; $i++) {
                  $dt = $i <= 9 ? "0$i" : $i;
                  $selected = $day == $dt ? "selected" : "";
                  echo "<option value='$dt' $selected>$dt</option>";
                }
              ?>
            </select>
            <select class="form-control" id="st_month" name="st_month" onchange="onDateSelected()">
              <option value='01' <?php echo $month === '01' ? "selected" : "";?>>Jan</option>
              <option value='02' <?php echo $month === '02' ? "selected" : "";?>>Feb</option>
              <option value='03' <?php echo $month === '03' ? "selected" : "";?>>Mar</option>
              <option value='04' <?php echo $month === '04' ? "selected" : "";?>>Apr</option>
              <option value='05' <?php echo $month === '05' ? "selected" : "";?>>May</option>
              <option value='06' <?php echo $month === '06' ? "selected" : "";?>>June</option>
              <option value='07' <?php echo $month === '07' ? "selected" : "";?>>July</option>
              <option value='08' <?php echo $month === '08' ? "selected" : "";?>>Aug</option>
              <option value='09' <?php echo $month === '09' ? "selected" : "";?>>Sep</option>
              <option value='10' <?php echo $month === '10' ? "selected" : "";?>>Oct</option>
              <option value='11' <?php echo $month === '11' ? "selected" : "";?>>Nov</option>
              <option value='12' <?php echo $month === '12' ? "selected" : "";?>>Dec</option>
            </select>
            <select class="form-control" id="st_year" name="st_year" onchange="onDateSelected()">
              <?php
                for($i = 2012; $i <= 2030; $i++) {
                  $selected = $year == $i ? "selected" : "";   //dont compare with === because $year is string and $i is integer
                  echo "<option value='$i' $selected>$i</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="l_code" class="col-sm-4 col-form-label">Language: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="l_code" name="l_code" onchange="onLangSelected()">
              <?php
                foreach($code_lang_map as $code => $lang) {
                  $selected = $l_code == $code ? "selected" : "";
                  echo "<option value='$code' $selected>$lang</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="days" class="col-sm-4 col-form-label">For How Many Days: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="days" name="days" onchange="onDaysSelected()">
              <?php
                for($i = 1; $i <= 14; $i++) {
                  $selected = $how_many_days === $i ? "selected" : "";
                  echo "<option value=$i $selected>$i Day(s)</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="token" class="col-sm-4 col-form-label">Secret Token (optional)</label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="token" name="token">
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Create Murli HTMs
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <input type="hidden" id="hidden_mmf_dir" value=<?php echo "'$distro_dir'"; ?>>
      <button id="email" name="email" class="btn btn-secondary text-white mt-3 mb-3 " style="width:100%;display:<?php echo $is_this_for_email_DL ? 'inline-block' : 'none'; ?>;">
        <span id="loading_spinner_email" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
        Files are ready! Email Now...
      </button>
      <a <?php echo "href='$ahref'"; ?>  id="downloadfiles" name="downloadfiles" class="btn btn-success" <?php echo "style='width:100%; margin-top:18px; display:$display_downloadfiles;'"; ?>>
        [Click] OR [Right Click & Save As] to download files
      </a>
      <a href="i.php" id="go_home" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;display:<?php echo $super_user ? 'inherit' : 'none';?>">
        Go Home
      </a>
      <button id="terminate" name="terminate" class="btn btn-danger mt-3" style='position: fixed;bottom: 3px;left: 3px;display:none;'>
        Click To Terminate
      </button>
    </div>
    <script language="javascript">
      
      var timeOut; //How frequently need to refresh
      var needToStop = false;
      var executeShellScript = "yes";   //If need to execute sell script
      var distroFileDir = '';   //All email files are stored here
      var selectedDay = '';
      var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      
      function disableButtons() {
        $('#email').prop('disabled', true);
        $('#proceed').prop('disabled', true);
        document.getElementById("go_home").style.display = "none";
      }
      
      function enableButtons() {
        $('#email').prop("disabled", false);
        $('#proceed').prop('disabled', false);
        $('#go_home').prop('disabled', false);
        document.getElementById("go_home").style.display = "inherit";
      }
      
      function enableTerminate() {   //i.e. show terminate button, spinner etc and make button non-clickable
        document.getElementById("loading_spinner_email").style.display = "inherit";
        document.getElementById("terminate").style.display = "inherit";
        disableButtons();
      }
      
      function disableTerminate() {   //i.e. hide terminate button, spinner etc and make button clickable
        document.getElementById("loading_spinner_email").style.display = "none";
        document.getElementById("terminate").style.display = "none";
        enableButtons();
      }
      
      function prepareMsgToShow(jsonObj) {
        msg_to_print = 'Below Progress<br><blockquote style="margin-left:10px;color:greenyellow;">';
        if(jsonObj.hasOwnProperty('msg')) {
          for(i = 0; i < jsonObj["msg"].length; i++) {
            if(i == 0) msg_to_print = msg_to_print + jsonObj["msg"][i];
            else msg_to_print = msg_to_print + "<br>" + jsonObj["msg"][i];
          }
        }
        msg_to_print = msg_to_print + "</blockquote>";
        return msg_to_print;
      }
      
      var callMyFunction = function() {
        $.ajax({
          dataType: "json",
          url: "http://www.babamurli.com/000-Ravi-DontDelete/mmrl-p1.php?execshell=" + executeShellScript + "&distroFileDir=" + distroFileDir,
          timeout: 10000,
          error: function (xhr, status, error) {
            if (status === "timeout" || status === "error") {
              console.log("RAVI_ERROR", status, error);
              alert("Error: " + status + error);
              disableTerminate();
            }
          },
          success: function (msg) {
            //console.log("RAVI_SUCCESS", msg);
            jsonObj = JSON.parse(JSON.stringify(msg));
            msg_sh = prepareMsgToShow(jsonObj);
            if(jsonObj.hasOwnProperty('start') && jsonObj.hasOwnProperty('end')) {
              needToStop = true;   //we have received a valid json data hence no more call to it
              msg_sh = msg_sh + "<kbd>Also, copied all needed E-mail IDs into clipboard!!</kbd>";
            }
            document.getElementById("id_card").innerHTML = msg_sh;
          },
          complete: function (jqXHR, status) {
            if(status !== "timeout" && status !== "error") {
              //console.log("RAVI_COMPLETE", status);
              if(!needToStop) {
                executeShellScript = "no";   //Do not execute shell script again!
                setTimeout(callMyFunction, 3000);   //call this function again after these many miliseconds
              }
              else {
                disableTerminate();
              }
            }
          }
        });
      }

      $("#email").click(function(){
        $(document).ready(function(){
          enableTerminate();
          executeShellScript = "yes";
          needToStop = false;
          distroFileDir = document.getElementById("hidden_mmf_dir").value;
          copyText = "tarunluthra1000@gmail.com;yogeshwar_k@hotmail.com;himthanineelam@gmail.com;shefu224@gmail.com;malarinfo@gmail.com;sudharani.kuram@gmail.com;murlimylife@gmail.com";
          copyClip(copyText);   //this function is in my custom "rav-util.js"
          callMyFunction();
        });
      });
      
      $("#terminate").click(function(){
        needToStop = true;
        disableTerminate();
      });
      
      function showHideSentFiles() {
        document.getElementById("sent_files_holder").style.display = document.getElementById('distro').checked ? "inherit" : "none";
      }
      
      function getMsgBasedOnSelection() {
        dt = document.getElementById("st_date").value;
        mn = document.getElementById("st_month").value;
        yr = document.getElementById("st_year").value;
        lg = document.getElementById("l_code");
        dy = document.getElementById("days").value;
        selectedDay = weekday[new Date(yr, parseInt(mn) - 1, dt).getDay()];
        return "<mark>" + dy + " Day(s)</mark> <kbd>" + (lg.options[lg.selectedIndex].text) + " Murli</kbd> will be created starting from <abbr title='For This Date...'>" + dt + "-" + mn + "-" + yr + "</abbr>";
      }
      
      function onDateSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
        document.getElementById("date_label").innerHTML = "Start Date <kbd style='background-color:#8611bb !important;'>(" + selectedDay + ")</kbd> : ";
      }
      
      function onDaysSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
      }
      
      function onLangSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
      }
      
      function validateForm1(form) {
        document.getElementById("loading_spinner").style.display = "inherit";
        document.getElementById("downloadfiles").style.display = "none";
        $('#proceed').addClass('disabled');
        return true;
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("sent_files_holder").style.display = document.getElementById('distro').checked ? "inherit" : "none";
        dt = document.getElementById("st_date").value;
        mn = document.getElementById("st_month").value;
        yr = document.getElementById("st_year").value;
        lg = document.getElementById("l_code");
        dy = document.getElementById("days").value;
        selectedDay = weekday[new Date(yr, parseInt(mn) - 1, dt).getDay()];
        document.getElementById("date_label").innerHTML = "Start Date <kbd style='background-color:#8611bb !important;'>(" + selectedDay + ")</kbd> : ";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>