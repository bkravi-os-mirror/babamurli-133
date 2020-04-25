<?php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];  
  $shell_script = "$rootdir/000-Ravi-DontDelete/ytd.sh";
  $shell_script_outfile = "$rootdir/000-Ravi-DontDelete/ytd.log";
  $OSB_MP3_LOC = "$rootdir/01. Daily Murli/01. Hindi/09. Hindi Murli - OSB - MP3";
  
  $mustHaveKeyWordsAtStart = array("#STARTED", "#FINISHED");
  
  function ifAllReqdKeyWordExistInArray($myReadAll) {
    global $mustHaveKeyWordsAtStart;
    foreach($mustHaveKeyWordsAtStart as $keyword) {
      $found = false;
      foreach($myReadAll as $row) {
        if(preg_match("/^$keyword/", $row)) {
          $found = true;
          break;
        }
      }
      if(!$found) return false;
    }
    $st = trim(str_replace("#STARTED", "", reset($myReadAll)));
    $end = trim(str_replace("#FINISHED", "", end($myReadAll)));
    
    $tmp = explode("IST", $st);
    $tmp = trim($tmp[0]) . " " . trim($tmp[1]);
    $st_timestamp = date_create_from_format("D M j H:i:s Y", $tmp)->format('U');

    $tmp = explode("IST", $end);
    $tmp = trim($tmp[0]) . " " . trim($tmp[1]);
    $end_timestamp = date_create_from_format("D M j H:i:s Y", $tmp)->format('U');

    $time_diff = ($end_timestamp - $st_timestamp);
    $time_diff = (int)($time_diff / 60) . "m:" . ($time_diff % 60) . "s";
    
    return json_encode(array("start"=>"$st","end"=>"$end","msg"=>"Processed in <kbd>$time_diff</kbd>"));
  }
  
  //==== BELOW running shell script to get output file ==========================
  //http://babamurli.com/000-Ravi-DontDelete/ytd-p1.php?execshell=no&videoID=fq6utUvQijI&outputDir=/var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete&outputFileOnlyWithoutExt=temp&formatCode=18&whatType=1
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_GET as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  
  if(isset($_GET['execshell']) && !empty($_GET['execshell']) && $_GET['execshell'] === "yes") {
    @unlink($shell_script_outfile);   //delete the file first
    if(file_exists($shell_script) && @filesize($shell_script) > 0) {
      if(isset($_GET['videoID']) && !empty($_GET['videoID']))
        $videoID = $_GET['videoID'];
      if(isset($_GET['outputDir']) && !empty($_GET['outputDir']))
        $outputDir = $_GET['outputDir'];
      if(isset($_GET['outputFileOnlyWithoutExt']) && !empty($_GET['outputFileOnlyWithoutExt']))
        $outputFileOnlyWithoutExt = $_GET['outputFileOnlyWithoutExt'];
      if(isset($_GET['formatCode']) && !empty($_GET['formatCode']))
        $formatCode = $_GET['formatCode'];
      if(isset($_GET['whatType']) && !empty($_GET['whatType']))   //e.g. [1-MP3, 2-MP4, 3-BOTH]
        $whatType = $_GET['whatType'];
      if(!empty($videoID) && !empty($outputDir) && !empty($outputFileOnlyWithoutExt) && !empty($formatCode) && !empty($whatType)) {
        $shell_cmd = "nohup $shell_script -v $videoID -d \"$outputDir\" -o \"$outputFileOnlyWithoutExt\" -f $formatCode -t $whatType > /dev/null 2>&1 &";
        //echo $shell_cmd;
        shell_exec($shell_cmd);
      }
    }
  }
  //==== ABOVE running shell script to get output file ==========================
  
  //==== BELOW reading script output file and returning as appropriate ==========================
  $msg = '{"msg":"Kindly wait..."}';
  $readAll = @file("$shell_script_outfile", FILE_SKIP_EMPTY_LINES);   //For Validity: [first line^: #STARTED, last line^: #FINISHED]
  if($readAll !== false && count($readAll) > 0) {   //file() returns array()
    if(($res = ifAllReqdKeyWordExistInArray($readAll)) !== false) {   //if true means, shell script has finished processing the file successfully
      $msg = $res;
      if(isset($_GET['outputDir']) && !empty($_GET['outputDir']) &&
        isset($_GET['outputFileOnlyWithoutExt']) && !empty($_GET['outputFileOnlyWithoutExt']) &&
        strpos($_GET['outputFileOnlyWithoutExt'], "OSB") !== false
        ) {   //moving OSB MP3 to its correct location
        $from_file = $_GET['outputDir'] . "/" . $_GET['outputFileOnlyWithoutExt'] . ".mp3";
        $to_file = $OSB_MP3_LOC . "/" . $_GET['outputFileOnlyWithoutExt'] . ".mp3";
        rename($from_file, $to_file);
      }
    } else {
      $msg = end($readAll);
      if(strpos($msg, "[ffmpeg] Destination") !== false) {   //means MP4 to MP3 conversion is in process
        $file = $_GET['outputDir'] . "/" . $_GET['outputFileOnlyWithoutExt'] . ".mp3";
        if(($f_size = @filesize($file)) !== false)
          $msg = $_GET['outputFileOnlyWithoutExt'] . ".mp3 <kbd>" . round($f_size/(1024*1024), 2) . " MB</kbd> copied";
        else
          $msg = $_GET['outputFileOnlyWithoutExt'] . ".mp3 <kbd>0 MB</kbd> copied";
      } else if(strpos($msg, "[youtube]") !== false) {
        $msg = "YouTube downloading webpage...";
      } else if(strpos($msg, "[download]") !== false && strpos($msg, "%") !== false) {
        $tmp = explode("%", $msg);
        $tmp = explode("]", $tmp[0]);
        $msg = "<kbd>" . trim($tmp[1]) . "%</kbd> downloaded";
      }
      $msg = json_encode(array("msg"=>"$msg"));
    }
  }
  //==== ABOVE reading script output file and returning as appropriate ==========================
  echo $msg;
  
?>