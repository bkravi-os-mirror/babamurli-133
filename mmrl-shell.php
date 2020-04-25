<?php 
  
  //This php script is called from shell script ml.sh
  //It creates consolidated murli file to be sent over e-mail
  //It will write all its results into file mmrl-shell.log
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  
  $this_file_dir = dirname(__FILE__);   //this gives upto /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete i.e. own file loc
  $log_file = "$this_file_dir/mmrl-shell.log";
  $logFile = fopen($log_file, "w");
  if(count($argv) !== 2) {
    exit("Wrong arguments counts for this PHP script!\nExiting now...");
  }
  if($logFile === false) {
    exit("Unable to open file $log_file to write!\nExiting now...");
  }
  
  function sanitizeContent($contents) {
    if(preg_match("#(^Brahma Kumaris)#i", $contents)) {
      $contents = preg_replace('/Brahma Kumaris/', "", $contents, 1);   //i.e. if starts with Brahma Kumaris, remove it
    }
    $contents = str_replace("                ", " ", $contents);
    $contents = str_replace("  ", " ", $contents);
    return $contents;
  }
  
  function getConsolidatedFileNamePrefix($array_of_files) {   //array of file names like 2020-03-15-H.htm.txt etc
    if(count($array_of_files) > 0) {
      sort($array_of_files);
      $first_filename = pathinfo($array_of_files[0], PATHINFO_BASENAME);
      $tmp = explode("-", $first_filename);
      $from = date('d-M', strtotime("{$tmp[0]}-{$tmp[1]}-{$tmp[2]}"));
      $last_filename = pathinfo($array_of_files[count($array_of_files) - 1], PATHINFO_BASENAME);
      $tmp = explode("-", $last_filename);
      $to = date('d-M', strtotime("{$tmp[0]}-{$tmp[1]}-{$tmp[2]}"));
      return "$from" . "_TO_" . $to . "_qz";
    } else {
      return "ERROR";
    }
  }
  
  //Below processing the script
  $distro_dir = $argv[1];
  $start = DateTime::createFromFormat('U.u', microtime(TRUE));
  fwrite($logFile, "start: ". $start->format('Y-m-d H:i:s.u') . "\n");
  
  $map = array("*-E.htm.txt" => "E", "*-H.htm.txt" => "H",); 
  $filter_file_types_to_email = array("*-E.htm.txt", "*-H.htm.txt");
  foreach($filter_file_types_to_email as $file_type) {   //These are the files which we are going to consolidate [e.g. 2020-04-14-H.htm.txt, 2020-04-14-E.htm.txt...]
    $files = glob("$distro_dir/$file_type");
    if($files !== false && count($files) > 0) {
      $consolidated_file_name_only = $map[$file_type] . "_" . getConsolidatedFileNamePrefix($files) . ".txt";
      $consolidateFile = fopen("$distro_dir/$consolidated_file_name_only", "w");
      if($consolidateFile !== false) {
        fwrite($logFile, "<span class='bg-secondary text-white'>Creating</span> $consolidated_file_name_only\n");
        foreach($files as $file){
          $in = fopen($file, "r");
          if($in !== false) {
            $contents = fread($in, filesize($file));
            if($contents !== false) {
              $contents = sanitizeContent($contents);
              if(fwrite($consolidateFile, $contents)) {
                fwrite($logFile, "<span class='bg-success text-white'>Processed</span> " . pathinfo($file, PATHINFO_BASENAME) . "\n");
              } else {
                fwrite($logFile, "<span class='bg-danger text-white'>Error writing</span> $file into $consolidated_file_name_only\n");
              }
            } else {
              fwrite($logFile, "<span class='bg-danger text-white'>Error reading</span> $file\n");
            }
            fclose($in);
          } else {
            fwrite($logFile, "<span class='bg-danger text-white'>Error opening</span>> $file\n");
          }
          fwrite($consolidateFile, "\n==========================================================\n");
          fwrite($consolidateFile, "==========================================================\n");
        }
        fclose($consolidateFile);
        fwrite($logFile, "<span class='bg-warning text-white'>Finished</span> $consolidated_file_name_only\n");
        shell_exec("rm -rf \"$distro_dir\"/$file_type");   //removing single files e.g. [2020-04-13-H.htm.txt, 2020-04-13-E.htm.txt...] 
      } else {
        fwrite($logFile, "<span class='bg-danger text-white'>Error creating</span> $distro_dir/$consolidated_file_name_only\n");
      }
    } else {
      fwrite($logFile, "No file with filter: <span class='bg-danger text-white'>$file_type</span>\n");
    }
  }
  
  $end = DateTime::createFromFormat('U.u', microtime(TRUE));
  fwrite($logFile, "end: ". $end->format('Y-m-d H:i:s.u') . "\n");
  fclose($logFile);

?>
