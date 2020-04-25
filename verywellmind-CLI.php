<?php 
  //Usage: [php verywellmind-CLI.php]
  /*
    heading:
      article-heading_1-0
    cpmplete page:
      mntl-sc-page_1-0

    mntl-sc-block_1-0
    mntl-sc-block_1-0-1
    mntl-sc-block_1-0-2
    mntl-sc-block_1-0-3
  */
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log.log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  $skip_classes_that_contains = array("adslot", "featuredlink", "block-image", "video", "guide",);
  $links_filename = "./generated_links.txt";
  $message = '';
  
  $working_dir = "./x-verywellmind/01. Disorders/11. PTSD-Post Traumatic Stress Disorder/00. Main/069. How Talk Therapy Helps PTSD";
  
  function getHeading() {
    global $working_dir;
    $tmp = explode("/", $working_dir);
      if(count($tmp) > 2) {
        $tmp = $tmp[2];
          $tmp = explode(".", $tmp);
          if(count($tmp) > 1) {
            return trim($tmp[1]);
          } else {
            return trim($tmp[0]);
          }
      } else {
        return "Mind";
      }
  }

  function getSubHeading() {
    global $working_dir;
    $tmp = explode("/", $working_dir);
      if(count($tmp) > 3) {
        $tmp = $tmp[3];
          $tmp = explode(".", $tmp);
          if(count($tmp) > 1) {
            return trim($tmp[1]);
          } else {
            return trim($tmp[0]);
          }
      } else {
        return "Main";
      }
  }
  
  $one = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
  $two = "<title>Brahmakumaris BK DR Luhar</title></head><style>table, th, td {border: 2px solid #000080; border-collapse:collapse;width:80%;} th,td {width:40%;text-align:left;padding: 5px;} ul{text-align:justify;} p{text-align:justify;} tr{font-family:Arial; font-size:16pt; color:#000080;}</style>";
  $three = "<body bgcolor='#ffebcc'>";
  $four = "<blockquote><blockquote><hr><p style='text-align:center;' dir='ltr'><font face='Arial' color='#FF00FF' size='5'>"
          . getHeading() . " - </font>";
  $five = "<font face='Arial' color='#000080' size='5'>"
          . getSubHeading() . " - </font>";
  $six = "<font face='Arial' color='#008000' size='5'>";
  //title here after six
  $fourthlast = "</font></p><hr><font face='Arial' style='font-size: 16pt' color='#000080'><p align='justify' dir='ltr'>";
  //detail matter here after fourthlast
  $thirdlast = "</p></font>";
  $secondlast = "<font face='Arial' style='font-size: 16pt' color='#000080' color='#000080'>";
  $last = "<br><hr></font></blockquote></blockquote></body></html>";
  
  function commentCaptionLinkInFile($line) {
    global $links_filename;
    $readAll = file_get_contents($links_filename);
    $readAll = str_replace($line, "#$line", $readAll);
    $myNewFile = fopen("$links_filename", "w");
    fwrite($myNewFile, "$readAll");
    fclose($myNewFile);
  }
  
  function getCaptionAndLinkFromFile() {   //returns e.g. 1^Create ...^https://www.verywell.....
    global $links_filename;
    $fn = fopen("$links_filename","r");
    if($fn) {
      while(!feof($fn)) {
        $result = trim(fgets($fn));
        if(strpos($result, "#") === 0) {   //skip reading line those start with #
          continue;
        } else {
          return $result;
        }
      }
      fclose($fn);
      return '';   //it will reach here iff nothing to read from $links_filename i.e. all lines start with '#' of are blank
    } else {
      return false;
    }
  }
  
  function is_class_need_to_be_skipped($cls) {
    global $skip_classes_that_contains;
    foreach($skip_classes_that_contains as $elem) {
      if(strpos($cls, $elem) !== false) return true;
    }
    return false;
  }
  
  //Deprecating this function now. Use generateFileNamePrefixNumberFor()
  function generateFileNamePrefixNumber() {
    // Below logic based on file modified date
    global $working_dir;
    $latest_ctime = 0;
    $latest_filename = false;    
    $d = dir($working_dir);
    while(false !== ($entry = $d->read())) {
    $filepath = "{$working_dir}/{$entry}";
      if(is_file($filepath) && filemtime($filepath) > $latest_ctime) {
        $latest_ctime = filemtime($filepath);
        $latest_filename = $entry;
      }
    }
    if($latest_filename) {
      $f_nm_ar = explode(".", $latest_filename);
      $new_file_num = intval($f_nm_ar[0]) + 1;
      if($new_file_num >=0 && $new_file_num <= 9) return "00$new_file_num.";
      if($new_file_num >=10 && $new_file_num <= 99) return "0$new_file_num.";
      else return "$new_file_num.";
    } else {
      return "001.";
    }
  }
  
  function generateFileNamePrefixNumberFor($caption_link) {   //e.g. 1^Create ...^https://www.verywell.....
    $tmp = explode("^", $caption_link);
    if(count($tmp) === 3) {
      $num = intval($tmp[0]);
      if($num) {
        if($num >=0 && $num <= 9) return "00$num.";
        if($num >=10 && $num <= 99) return "0$num.";
        return "$num.";
      } else {
        return "001.";
      }
    } else {
      return "001.";
    }
  }
  
  function getSanitizedFileName($file, $caption_link) {
    $file = str_replace("’", "", $file);
    $file = str_replace(":", "-", $file);
    $file = str_replace("?", "", $file);
    $file = str_replace("#", "", $file);
    $file = str_replace("(", "", $file);
    $file = str_replace(")", "", $file);
    $file = str_replace("<", "", $file);
    $file = str_replace(">", "", $file);
    $file = str_replace("/", "-", $file);
    $file = str_replace("\\", "-", $file);
    $file = str_replace("\"", "-", $file);
    //return generateFileNamePrefixNumber() . " $file.htm";   //Deprecated the function generateFileNamePrefixNumber()
    return generateFileNamePrefixNumberFor($caption_link) . " $file.htm";
  }
  
  function printError($error_msg) {
    print("\n========= ERROR BELOW ====================================================\n");
    print("$error_msg");
    print("\n========= ERROR ABOVE ====================================================\n");
  }
  
  function curl_get_contents($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
  }
  
  function secondsToTime($s) {
    $h = floor($s / 3600);
    $s -= $h * 3600;
    $m = floor($s / 60);
    $s -= $m * 60;
    return sprintf('%02d', $h) . ':' . sprintf('%02d', $m) . ':' . sprintf('%02d', $s);
  }
  
  $caption_link = getCaptionAndLinkFromFile();   //e.g. 1^Create ...^https://www.verywell.....
  $valid_files_created = 0;
  $valid_files_not_created = 0;
  $invalid_files = 0;
  $starttime = microtime(true);
  print("\n\nStarted Processing... Kindly wait...\n");
  while($caption_link !== false && strlen($caption_link) > 6) {
    $tmp = explode("^", $caption_link);
    if(count($tmp) === 3 && strlen($tmp[1]) > 2 && strlen($tmp[2]) > 4) {
      $file_name = $tmp[1];
      $page_link = $tmp[2];
      $all_oknotok = '';
      $html = file_get_html($page_link);
      $heading_elem = $html->find('#article-heading_1-0', 0);
      $heading = '';
      if($heading_elem) {
        $heading = $heading_elem->plaintext;
      } else {
        printError("Heading id 'article-heading_1-0' NOT FOUND for $caption_link. So using filename as title!!");
        $heading = $file_name;
      }
      if($html) {
        $ret = $html->find('[id^=mntl-sc-block_]');   //getting all elements having attribute 'id' that starts with mntl-sc-block_*
        if($ret) {
          $fileToCreate = getSanitizedFileName($file_name, $caption_link);
          $myNewFile = fopen("$working_dir/$fileToCreate", "w");
          if(!$myNewFile) {
            printError("Unable to open file to write: $working_dir/$fileToCreate");
            $all_oknotok = "notok";
          } else {
            fwrite($myNewFile, "$one\n");
            fwrite($myNewFile, "$two\n");
            fwrite($myNewFile, "$three\n");
            fwrite($myNewFile, "$four\n");
            fwrite($myNewFile, "$five\n");
            fwrite($myNewFile, "$six\n");
            fwrite($myNewFile, "$heading\n");
            fwrite($myNewFile, "$fourthlast\n");
            foreach($ret as $elem) {
              $array = $elem->attr;
              if(is_class_need_to_be_skipped($array['class']) === false) {
                $elem->innertext = trim($elem->innertext);
                $elem->innertext = str_replace("<a", "<well", $elem->innertext);   //One crude way to suppress anchor links
                $elem->innertext = str_replace("</a>", "</well>", $elem->innertext);
                $elem->innertext = str_replace('<p>For more mental health resources, see','<p style="display:none;">For more mental health resources, see', $elem->innertext);
                $elem->innertext = str_replace("<h4", "<span style='font-size:16pt;color:#0000FF;'", $elem->innertext);
                $elem->innertext = str_replace("</h4>", "</span>", $elem->innertext);
                $elem->innertext = str_replace("<h3", "<span style='font-size:16pt;color:#FF00FF;'", $elem->innertext);
                $elem->innertext = str_replace("</h3>", "</span>", $elem->innertext);
                $elem->innertext = str_replace("<strong", "<span style='font-size:16pt;color:#008000;'", $elem->innertext);
                $elem->innertext = str_replace("</strong>", "</span>", $elem->innertext);
                $elem->innertext = str_replace('<figure','<figure style="display:none;"', $elem->innertext);
                $elem->innertext = str_replace('<span class="mntl-sc-block-starrating__label"', '<span style="display:none;" class="mntl-sc-block-starrating__label"', $elem->innertext);
                $elem->innertext = str_replace('<iframe', '<iframe style="display:none;"', $elem->innertext);
                //writing <h1/h2..hn> tags
                if(substr($elem->tag, 0, 1) === "h") {
                  if(!fwrite($myNewFile, "<font color='#FF0000'>{$elem->innertext}</font>\n")) {
                    printError("Unable to write 'h1' {$elem->innertext} into file : $working_dir/$fileToCreate");
                    $all_oknotok = "notok";
                  }
                } else {
                  //writing rest of the content
                  if(!fwrite($myNewFile, "{$elem->innertext}\n")) {
                    printError("Unable to write {$elem->innertext} into file : $working_dir/$fileToCreate");
                    $all_oknotok = "notok";
                  }
                }
              }
            }
            fwrite($myNewFile, "$thirdlast\n");
            fwrite($myNewFile, "$secondlast\n");
            fwrite($myNewFile, "$last\n");
            fclose($myNewFile);
            if(strlen($all_oknotok) < 3) {
              $valid_files_created++;
            }
          }
        } else {
          printError("Error finding 'id^=mntl-sc-block_1-0' for $caption_link!!");
          $valid_files_not_created++;
        }
        commentCaptionLinkInFile($caption_link);   //Let's comment the row irrespective of file created or not!
      } else {
        printError("Could not read from Link $page_link!!");
      }
    } else {
      $file_name = '';
      $page_link = '';
      $invalid_files++;
    }
    $caption_link = getCaptionAndLinkFromFile();
  }
  
  if($valid_files_created === 0) {
    printError("There is no record to read from $links_filename !!");
  } else {
    print("\nValid Files Created: $valid_files_created\nValid Files NOT Created: $valid_files_not_created\nInvalid Files: $invalid_files\n");
  }
  
  $endtime = microtime(true);
  $timediff = $endtime - $starttime;
  print("\n\nTime taken: [" . secondsToTime($timediff) . "]\n"); 
  
?>
