<?php 
  /*
  This code geenrates the rm commands to remove older months files from server
  */
  session_start();
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  //== Set below variable before run ======================================
  $date_filter = ".03.20*";
  //== Set above variable before run ======================================
  
  $folders = glob("$rootdir/01. Daily Murli/*");
  $message = '';
  foreach($folders as $folder) {
    $tmp = explode("/", $folder);
    $only_folder = end($tmp);
    $message = "$message#### $only_folder<br>";
    $cmd = 'ls -d "' . $folder . '"/*|awk -F ~ ' . "'{print " . '"rm -f \"" $0 "/\"*' . $date_filter . '" }' . "'";
    unset($output);
    exec($cmd, $output, $result);
    if($output && count($output) > 0) {
      foreach($output as $o) {
        $message = "$message$o<br>";
      }
    } else {
      $message = "$message ERROR!!<br>";
    }
    $message = "$message<br>";
  }
  echo $message;

?>