<?php 
  
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
  $skip_classes_that_contains = array("adslot", "featuredlink", "block-image", "video", "guide");
  $links_filename = "./generated_links.txt";
  $message = '';
  
  function myScan($dir, $file_filter, $asc_or_dsc = 1, $sortOnName_or_sortOnTime = 0) {
    $files = array();
    $array_of_files = glob("$dir/$file_filter");
    if(!$array_of_files) return $files;   //if no match for the filter, return blank array
    foreach($array_of_files as $file) {
      $files[pathinfo($file, PATHINFO_BASENAME)] = filemtime($file);
    }
    if($asc_or_dsc === 0) {   //asc sort
      if($sortOnName_or_sortOnTime === 0) {   //sort on filename
        ksort($files);
      } else if($sortOnName_or_sortOnTime === 1) {   //sort on filetime
        asort($files);
      }
    } else if($asc_or_dsc === 1) {   //desc sort
      if($sortOnName_or_sortOnTime === 0) {   //sort on filename
        krsort($files);
      } else if($sortOnName_or_sortOnTime === 1) {   //sort on filetime
        arsort($files);
      }
    }
    $files = array_keys($files);
    return ($files) ? $files : array();
  }
  
  echo 'You need to comment out the foreach loop section!!<br><br>';
  
  // comment out below loop when you want to execute it
  
  /*
  echo 'Great!! you commented out the section<br><br>';
  $dir_array = array(
    //"./x-verywellmind/01. Disorders/04. Social Anxiety Disorder/03. Treatment and Therapy/00. Main",
    //"./x-verywellmind/01. Disorders/04. Social Anxiety Disorder/03. Treatment and Therapy/01. Social Skills",
    "./x-verywellmind/02. Self-Improvement/05. Holistic Health/00. Main",
  );
  $from = "Social Anxiety Disorder - </font>";
  $to = "Holistic Health - </font>";
  echo '';
  foreach($dir_array as $dir) {
    $files = myScan($dir, "*.htm");
    $counter = 0;
    foreach($files as $file) {
      $readAll = file_get_contents("$dir/$file");
      $readAll = str_replace($from, $to, $readAll);
      $myNewFile = fopen("$dir/$file", "w");
      fwrite($myNewFile, "$readAll");
      fclose($myNewFile);
      $counter++;
    }
    echo "[$counter] files changed inside $dir<br>";
  }
  */
  
?>
