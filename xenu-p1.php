<?php
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];  
  $curl_shell_script = "$rootdir/000-Ravi-DontDelete/xenu.sh";
  $curl_out_file = "$rootdir/000-Ravi-DontDelete/curl.out";
  $curl_out_broken_only_file = "$rootdir/000-Ravi-DontDelete/curl_broken_only.out";
  $links_file = "$rootdir/000-Ravi-DontDelete/xenu_links.txt";   //writing all links to current directory
  $xenu_links_wp_file = "$rootdir/000-Ravi-DontDelete/xenu_links_wp.txt";
  $global_link_parent_map_array = array();
  
  //==== BELOW creating curl.out from shell script by running system command ==========================
  if(isset($_GET['execshell']) && !empty($_GET['execshell']) && $_GET['execshell'] === "yes") {
    if(isset($_GET['retrybroken']) && !empty($_GET['retrybroken']) && $_GET['retrybroken'] === "yes"
      && file_exists($curl_out_broken_only_file) && filesize($curl_out_broken_only_file) > 0) {   //if retry broken is choosen, first copy curl_broken_only.out to xenu_links.txt and then execute the curl shell script
      copy($curl_out_broken_only_file, $links_file);
    }
    unlink($curl_out_file);   //delete the file first
    if(file_exists($curl_shell_script) && filesize($curl_shell_script) > 0) {
      $curl_cmd = "nohup $curl_shell_script > /dev/null 2>&1 &";
      shell_exec($curl_cmd);
    }
  }
  //==== ABOVE creating curl.out from shell script by running system command ==========================
  
  //==== BELOW reading curl.out and returning json ==========================
  
  function linkFoundInFile($link, $file_nm) {
    $file_name_only_from_link = pathinfo($link, PATHINFO_FILENAME) . "." . pathinfo($link, PATHINFO_EXTENSION);
    $file_nm = trim($file_nm);
    if(file_exists($file_nm) && filesize($file_nm) > 0) {
      $content = file_get_contents($file_nm);
      if($content !== false) {
        if(strpos($content, $file_name_only_from_link) !== false) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
    return false;
  }
  
  function getOkOrNotOK($key) {
    global $global_link_parent_map_array;
    if(array_key_exists($key, $global_link_parent_map_array)) {
      if(linkFoundInFile($key, $global_link_parent_map_array[$key])) {
        return "ok";
      } else {
        return "notok";
      }
    }
    return "notok";
  }
  
  function appendExistanceAtEnd($jsonOBJ) {
    global $global_link_parent_map_array;
    global $xenu_links_wp_file;
    $xenu_links_wp_array = array();
    if(file_exists($xenu_links_wp_file) && filesize($xenu_links_wp_file) > 0)
      $xenu_links_wp_array = file($xenu_links_wp_file);
    foreach($xenu_links_wp_array as $row) {
      $tmp = explode("^", $row);
      if(count($tmp) === 2) {
        $global_link_parent_map_array[$tmp[0]] = $tmp[1];
      }
    }
    //Below main processing
    $tmp_array = json_decode($jsonOBJ, true);
    $final_array = array();
    foreach($tmp_array as $key => $val) {
      if($key === "start" || $key === "end") {
        $final_array["$key"] = $val;
      } else {
        $final_array["$key^" . getOkOrNotOK($key)] = $val;
      }
    }
    return json_encode($final_array);
  }
  
  $fn = @fopen("curl.out", "r");   //first line: #STARTED, second line: {valid json with start and end keys}, last line: #FINISHED
  $msg = '{"na":"na"}';
  
  if($fn) {   //able to open file for read
    $result = trim(fgets($fn));   //read first line
    if($result === "#STARTED") {   //first line MUST be #STARTED
      $jsonOBJ = $result = trim(fgets($fn));   //read second line
      $tmp = json_decode($jsonOBJ, true);
      if(json_last_error() == JSON_ERROR_NONE) {   //second line must be a single line json value
        if($tmp && array_key_exists('start', $tmp) && array_key_exists('end', $tmp)) {
          $result = trim(fgets($fn));   //read third line
          if($result === "#FINISHED") {   //third line MUST be #FINISHED
            //now we are good to hold this json object as read from line#2 in file
            $msg = appendExistanceAtEnd($jsonOBJ);
          }
        }
      }
    }
    fclose($fn);
  }
  echo $msg;
  //==== ABOVE reading curl.out and returning json ==========================
  
?>