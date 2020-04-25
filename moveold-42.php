<?php 
  //Usage: [php moveold.php]
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log.log");
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $message = 'Please select any one folder and submit';
  $processing_zip = '';
  
  //$zip_files = myScan(".", "*.zip", 0, 0);
  //Please provide all folders which need to processed
  //NOTE: .zip MUST be the post for each folder name!
  $zip_files = array(
    "03. Hindi Murli - MP3.zip",
    "05. Eng Murli - MP3 - UK.zip",
    "13. Murli Chintan - Suraj Bhai.zip",
  );
  sort($zip_files);
  
  $month_array = array(
    "01-January", "02-February", "03-March", "04-April", "05-May", "06-June",
    "07-July", "08-August", "09-September", "10-October", "11-November", "12-December",
  );
  
  function printError($error_msg) {
    print("\n========= ERROR BELOW ====================================================\n");
    print("$error_msg");
    print("\n========= ERROR ABOVE ====================================================\n");
  }
  
  function secondsToTime($s) {
    $h = floor($s / 3600);
    $s -= $h * 3600;
    $m = floor($s / 60);
    $s -= $m * 60;
    return sprintf('%02d', $h) . ':' . sprintf('%02d', $m) . ':' . sprintf('%02d', $s);
  }

  if(isset($_POST['proceed'])) {   //'Move Files To Correct Folders' is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = '';
    $processing_zip = $_POST['zip'];
    $main_folders = array(onlyFileName($processing_zip));
    //Uncomment below if you want to force assign the main_folder. It is the folder from where we will pick files to process
    $main_folders = array(
      "$rootdir/0000-Old Daily/01. Hindi/26. Murli Poems/x",
      "$rootdir/0000-Old Daily/01. Hindi/28. Murli Saar Commentaries/x",
      "$rootdir/0000-Old Daily/01. Hindi/29. Murli Dharna Commentaries/x",
      "$rootdir/0000-Old Daily/01. Hindi/30. Murli Vardan Commentaries/x",
      "$rootdir/0000-Old Daily/01. Hindi/31. Murli Slogan Commentaries/x",
    );
    
    foreach($main_folders as $main_folder) {
      $message = "$message<br>Below Results:<ul>";
      if(!is_dir($main_folder)) {
        $message = $message . "<li><span class='bg-danger text-white'>Folder $main_folder doesnt exist!!</span></li>";
      } else {
        $files = myScan($main_folder, "*.*");
        $total_files = count($files);
        $total_successfully_processed = 0;
        foreach($files as $file) {   //e.g. 01.01.18-E.htm
          if(filesize("$main_folder/$file") > (5*1024)) {   //filesize is more than 5 KB
            $year_folder = '';
            $month_folder = '';
            $orig_file = $file;
            $file = str_replace("-", ".", $file);
            $tmp = explode(".", $file);
            if($tmp >= 3) {
              $month = intval($tmp[1]);
              if($month >= 1 && $month <= 12) {
                $month_folder = $month_array[$month - 1];
              } else {
                $message = $message . "<li><span class='bg-danger text-white'>Invalid month in $orig_file!!</span></li>";
              }
              if(strlen($tmp[2]) === 2) {
                $year = intval($tmp[2]);
                if($year > 22) $year_folder = "19$year";
                else if($year >= 0 && $year <= 9) $year_folder = "200$year";
                else $year_folder = "20$year";
              } else if(strlen($tmp[2]) !== 4) {
                $message = $message . "<li><span class='bg-danger text-white'>Invalid year in $orig_file!!</span></li>";
              } else {
                $year_folder = $tmp[2];
              }
              if(!empty($month_folder) && !empty($year_folder)) {
                if(!is_dir("$main_folder/$year_folder")) {
                  if(!mkdir("$main_folder/$year_folder")) {
                    $message = $message . "<li><span class='bg-danger text-white'>Error creating $main_folder/$year_folder folder</span></li>";
                  }
                  if(!mkdir("$main_folder/$year_folder/$month_folder")) {
                    $message = $message . "<li><span class='bg-danger text-white'>Error creating $main_folder/$year_folder/$month_folder folder</span></li>";
                  }
                } 
                if(!is_dir("$main_folder/$year_folder/$month_folder")) {
                  if(!mkdir("$main_folder/$year_folder/$month_folder")) {
                    $message = $message . "<li><span class='bg-danger text-white'>Error creating $main_folder/$year_folder/$month_folder folder</span></li>";
                  }
                } 
                if(is_dir("$main_folder/$year_folder/$month_folder")) {   //all required folders exists. go ahead!!
                  if(!rename("$main_folder/$orig_file", "$main_folder/$year_folder/$month_folder/$orig_file")) {
                    $message = $message . "<li><span class='bg-danger text-white'>Copy failed from $main_folder/$orig_file To $main_folder/$year_folder/$month_folder/$orig_file</span></li>";
                  } else {
                    $total_successfully_processed++;
                  }
                } else {
                  $message = $message . "<li><span class='bg-danger text-white'>$main_folder/$year_folder/$month_folder folder missing!!</span></li>";
                }
              } else {
                $message = $message . "<li><span class='bg-danger text-white'>Any one is empty in $orig_file--> month_folder:$month_folder, year_folder:$year_folder</span></li>";
              }
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>$orig_file is not in DD.MM.YY format file!!</span></li>";
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>$main_folder/$file filesize is < 5 KB</span></li>";
          }
        }
      }
      $message = "$message</ul><kbd>[$total_successfully_processed/$total_files]</kbd> Successfully processed for $main_folder";
    }
  }

?>
<html lang="en">
  <head>
    <title>Move Old Data</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/png" href="../000-Ravi-DontDelete/images/bks/sb_72x72.png"/>
    <link rel="stylesheet" type="text/css" href="../000-Ravi-DontDelete/css/bootstrap.min.4_3_1.css">
    <link rel="stylesheet" type="text/css" href="../000-Ravi-DontDelete/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu"/>
    <link rel="stylesheet" href="../000-Ravi-DontDelete/js/flash/dist/flash.css">
    <script src="../000-Ravi-DontDelete/js/jquery.slim.min.3_4_1.js"></script>
    <script src="../000-Ravi-DontDelete/js/jquery.min.js"></script>
    <script src="../000-Ravi-DontDelete/js/bootstrap.bundle.min.4_3_1.js"></script>
    <script src="../000-Ravi-DontDelete/js/flash/dist/flash.min.js"></script>
    <script src="../000-Ravi-DontDelete/js/flash/dist/flash.jquery.min.js"></script>
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
      $brand_name = 'Move Old Data';
      include('../000-Ravi-DontDelete/nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="zip" class="col-sm-4 col-form-label">Choose Folder: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="zip" name="zip" style="width:100%;">
              <?php 
                for($i = 0; $i < count($zip_files); $i++) {
                  $selected = $zip_files[$i] === $processing_zip ? 'selected' : '';
                  echo "<option value='$zip_files[$i]' $selected>" . str_replace(".zip", "", $zip_files[$i]) . "</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Move Files To Correct Folders
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
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
        document.getElementById("zip").focus();
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>


