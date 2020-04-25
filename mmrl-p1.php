<?php 
  
  //This program reads the log mmrl-shell.log(this log is created by mmrl-shell.php), emails the consolidated files
  //   and returns json obeject back to mmrl.php to show the status on UI
  //The shell script, mentioned above, concatenates multiple files into one.
  //  e.g. [2020-04-13-H.htm.txt, 2020-04-14-H.htm.txt..] into One file e.g. E-27-Apr_TO_03-May_qz.txt
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $shell_script = "$rootdir/000-Ravi-DontDelete/ml.sh";
  $shell_script_outfile = "$rootdir/000-Ravi-DontDelete/mmrl-shell.log";
  $messages_array = array();
  $final_array = array();
  
  if(isset($_GET['execshell']) && !empty($_GET['execshell']) && $_GET['execshell'] === "yes") {
    @unlink($shell_script_outfile);   //delete the file first
    if(file_exists($shell_script) && filesize($shell_script) > 0) {
      if(isset($_GET['distroFileDir']) && !empty($_GET['distroFileDir'])) {
        $distro_dir = $_GET['distroFileDir'];
        if(is_dir($distro_dir)) {
          $shell_cmd = "nohup $shell_script -d \"$distro_dir\" > /dev/null 2>&1 &";
          shell_exec($shell_cmd);
          $messages_array[] = "Running script: <kbd>" . pathinfo($shell_script, PATHINFO_BASENAME) . "</kbd>";
        } else {
          $messages_array[] = "$distro_dir is NOT a valid directory!";
        }
      } else {
        $messages_array[] = "<span class='bg-danger text-white'>FATAL ERROR:</span> email Distro folder is blank in query string!!";
      }
    } else {
      $messages_array[] = "<span class='bg-danger text-white'>FATAL ERROR:</span> script $shell_script doesn't exist!!";
    }     
  }
  
  //Below reading log file and generating appropriate json object
  if(isset($_GET['distroFileDir']) && !empty($_GET['distroFileDir'])) {
    $distro_dir = $_GET['distroFileDir'];
  }
  
  $log_file_contents_array = @file($shell_script_outfile, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
  if($log_file_contents_array !== false) {
    foreach($log_file_contents_array as $row) {
      if(preg_match("#(^start:)#i", $row)) {   //start flag must be start:
        $final_array["start"] = trim(str_replace("start:", "", $row));
      } else if(preg_match("#(^end:)#i", $row)) {   //found "end". Now start email. When email done, then only send "end" json key
        $file_attachments = glob("$distro_dir/*_qz.txt");
        if($file_attachments && count($file_attachments) > 0) {
          $subject = "Files: [" . str_replace("$distro_dir/", "", implode(", ", $file_attachments)) . "] - [DO NOT REPLY!! AUTO GEN E-MAIL!]<os>";
          $emailee_list = array(
            "tarunluthra1000@gmail.com", "yogeshwar_k@hotmail.com", "shefu224@gmail.com", 
            "malarinfo@gmail.com", "sudharani.kuram@gmail.com", "murlimylife@gmail.com",
          );
          $messages_array[] = implode("<br>", sendEmail($emailee_list, $subject, "<b>IT'S AN AUTO GENERATED E-MAIL. PLEASE DO NOT REPLY!!</b>", $file_attachments));
          if(strpos(strtolower(implode("", $messages_array)), "email sent successfully") !== false) {
            foreach($file_attachments as $attach) {   //lets rename all consolidated files to .sent extension
              rename($attach, "$attach.sent");
            }
          }
        } else {
          $messages_array[] = "<span class='bg-danger text-white'>ERROR No files found to attach!!</span>";
        }
        $final_array["end"] = trim(str_replace("end:", "", $row));
      } else {
        $messages_array[] = $row;
      }
    }
  }
  
  if(count($messages_array) <=0 ) {
    $final_array["msg"] = array("Waiting for <kbd> " . pathinfo($shell_script_outfile, PATHINFO_BASENAME) . "</kbd> to get generated!");
  }
  else {
    $final_array["msg"] = $messages_array;
  }
  
  echo json_encode($final_array);

?>
