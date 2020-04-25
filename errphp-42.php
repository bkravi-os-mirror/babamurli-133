<?php
  
  /*
  This code is to show filter results from error_log file
  */
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  date_default_timezone_set('Asia/Calcutta');
  include './util.php';
  $msg = "Provide <u style='background-color:navy; padding-left:5px; padding-right:5px;'>comma(',') separated values</u>"
      . "<b style='background-color:crimson; padding-left:10px; padding-right:10px;'>All values will be inclusive</b>";
  $message = "Welcome...";
  $error_log_file = "./error_log";
  $selected_filter = "error, php";
  $my_cwd = getcwd();
  
  //Below force initializing POST values
  if(!isset($_POST['find'])) {   //when it comes without pressing "Show Error Log" button
    $_POST['find'] = "";
    $_POST['filter'] = "error, php";
  }
  if(isset($_POST['find'])) {   //"Show Error Log" button clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "Below Results:<ul>";
    if(isset($_POST['filter']) && !empty($_POST['filter'])) {
      $filter_string = trim($_POST['filter']);
    } else {
      $filter_string = "error, php";
    }
    $selected_filter = $filter_string;
    if(!file_exists($error_log_file) || filesize($error_log_file) < 1) {
      $message = $message . "<li><span class='bg-danger text-white'>Error file $error_log_file is empty or not existing!!! </span></li>";
      $message = "$message</ul>";
    } else {
      $tmp = explode(",", $filter_string);
      $tmp[0] = "grep -i \"" . trim($tmp[0]) . "\" " . $error_log_file;
      $cmd = $tmp[0];
      foreach($tmp as $idx => $val) {
        if($idx > 0) {   //since idx 0 is already done before this loop. so do it for rest
          $cmd = $cmd . " | grep -i \"" . trim($val) . "\"";
        }
      }
      $message = $message . "<li>Executing command: <kbd style='background-color:blue;'>$cmd</kbd></li><br>";
      exec("$cmd 2>&1", $output, $return_val);
      if($return_val === 0) {   //i.e. grep success! found data
        $output = array_reverse($output);   //lets read file from last to first
        foreach($output as $idx => $val) {
          $val = str_replace(__FILE__, "./" . fileWithExt(__FILE__), $val);   //__FILE__ gives the full path of the currently running script
          $val = str_replace($my_cwd, ".", $val);
          $color = $idx % 2 === 0 ? 'yellow' : 'lavenderblush';
          $message = $message . "<span style='color:$color;font-family:SFMono-Regular,Menlo,Monaco,Consolas,monospace'>$val</span><br>";
          if($idx > 25) {break;}   //I am not interested in very old data. This much is sufficient
        }
      } else {
        $message = $message . "No data found using above filter<br>";
      }
      $message = $message . "<br>Return Code: $return_val</ul>";
    }
  }
?>
<html lang="en">
  <head>
    <title>PHP error_log Viewer</title>
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
      $brand_name = "PHP error_log <i class='fa fa-info-circle' aria-hidden='true'></i> Viewer";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return preProcess(this);">
        <div class="mt-4"><?php echo "<kbd style='background-color:blue; font-size:larger;'>$msg</kbd>";?></div>
        <div class="form-group row" style="margin-top:20px;">
          <label for="filter" class="col-sm-4 col-form-label">Comma(,) Separated Values (Ignore Case): </label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="filter" name="filter" <?php echo "value='$selected_filter'"; ?>>
          </div>
        </div>
        <button type="submit" id="find" name="find" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Show Error Log
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      function preProcess(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#find').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("filter").focus();
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>