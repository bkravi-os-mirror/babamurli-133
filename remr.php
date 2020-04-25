<?php
  
  //https://github.com/ytdl-org/youtube-dl/blob/master/README.md
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');

  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $main_dirs = array("00. Htm", "00-htm");
  
  $valid_files = array (
    $main_dirs[0] => array (   //i.e. for "00. Htm"
      "01. Hindi.html",
      "02. English.html",
      "03. Tamil.html",
      "04. Telugu.html",
      "05. Kannada.html",
      "06. Malayalam.html",
      "07. Bengali.html",
      "08. Assame.html",
      "10. Odiya.html",
      "11. Punjabi.html",
      "12. Marathi.html",
      "13. Gujarati.html",
      "30. Nepali.html",
      "31. Deutsch.html",
      "32. Spanish.html",
      "33. Italian.html",
      "34. Chinese.html",
      "35. Tamil-Lanka.html",
      "36. French.html",
      "37. Greek.html",
      "38. Hungarian.html",
      "39. Korean.html",
      "40. Polish.html",
      "41. Portuguese.html",
      "42. Sindhi.html",
      "43. Thai.html",
      "44. Sinhala.html",
    ),
    /* Uncomment this if want to remove from 00-htm also. Since we are not using these files, hence commented
    $main_dirs[1] => array (   //i.e. for "00-htm"
      "Avyakt Palna.htm",
      "Full Stop.htm",
      "Suraj Bhai Classes - Tamil.htm",
      "Suraj Bhai Classes 1- Tamil.htm",
      "Todays Calendar.htm",
      "Todays Commentary.htm",
      "Todays Moti.htm",
      "Todays Purusharth - Suraj Bhai.htm",
      "Todays Thoughts.htm",
    ),
    */
  );
  
  $message = '';
  
  function addMonthsToDate($months_to_add, $string_date, $date_fmt, $date_return_fmt="") {   //NOTE: $string_date must be in $date_fmt format. otherwise you won't get expected results
    $date_return_fmt = empty($date_return_fmt) ? $date_fmt : $date_return_fmt;
    $date_obj = datetime::createfromformat($date_fmt, $string_date);
    date_add($date_obj, date_interval_create_from_date_string("$months_to_add month"));
    return date_format($date_obj, $date_return_fmt);
  }
  
  $months = array();   //e.g. Key: "01.12.19^31.12.19", Val: "December"
  $curr_date = date('Y-m-d');
  $how_many_past_months = 2;
  $begin_from = addMonthsToDate($how_many_past_months*-1, $curr_date, 'Y-m-d', 'Y-m-d');
  for($i = 0; $i <= $how_many_past_months; $i++) {
    $f_date = date("01.m.y", strtotime($begin_from));
    $m_name = date("F", strtotime($begin_from));
    $l_date = date("t.m.y", strtotime($begin_from));
    $months["$f_date^$l_date"] = $m_name;
    $begin_from = addMonthsToDate(1, $f_date, 'd.m.y', 'Y-m-d');
  }
  $choosen_value = "$f_date^$l_date";
  $counter_array = array();
  
  if(isset($_POST['proceed']) && isset($_POST['remove_month']) && !empty($_POST['remove_month'])) {   //this means the 'proceed' submit button is clicked
    $message = "Below Results<ul>";
    $choosen_value = $_POST['remove_month'];
    try {
      $dt_array = explode("^", $_POST['remove_month']);
      $begin_dt = $dt_array[0];  $end_dt = $dt_array[1];
      foreach($valid_files as $main_dir => $array_of_files) {
        $counter = 0;
        foreach($array_of_files as $file) {
          $myfile = fopen("$rootdir/$main_dir/$file", "r");
          if(!$myfile) {
            $message = $message . "<li><span class='bg-danger text-white'>Unable to open $main_dir/$file for reading!</span></li>";
          } else {
            $readAll = fread($myfile, filesize("$rootdir/$main_dir/$file"));
            fclose($myfile);
            //Below confirming that whether we have complete month data i.e. start and end date of a month is there
            if(($end_idx = strpos($readAll, $begin_dt)) === false || ($beg_idx = strpos($readAll, $end_dt)) === false) {
              $message = $message . "<li><span class='bg-danger text-white'>Date range [$begin_dt, $end_dt] NOT found in $main_dir/$file!</span></li>";
            } else {
              $top_part = substr($readAll, 0, strripos(substr($readAll, 0, $beg_idx), "<tr>"));
              $tmp = substr($readAll, $end_idx);
              $bottom_part = substr($tmp, strpos($tmp, "</table>"));
              $new_file_content = $top_part . $bottom_part;
              
              $myNewFile = fopen("$rootdir/$main_dir/$file", "w");
              if(!$myNewFile) {
                $message = $message . "<li><span class='bg-danger text-white'>Unable to open $main_dir/$file for writing!</span></li>";
              } else {
                if(fwrite($myNewFile, $new_file_content)) {
                  $message = $message . "<li>Processed $main_dir/$file Successfully!</span></li>";
                  $counter++;
                } else {
                  $message = $message . "<li><span class='bg-danger text-white'>Unable to write into $main_dir/$file !</span></li>";
                }
                fclose($myNewFile);
              }
            }
          }
        }
        $counter_array[$main_dir] = $counter;
      }
    } catch(Exception $e) {
      $message = $message . '<li><span class="bg-danger text-white">ERROR/EXCEPTION ' . $e->errorMessage() . '</span></li>';
    }
    $message = "$message</ul><div>";
    foreach($counter_array as $dir => $ct) {
      $message = $message . "<span class='bg-warning text-white p-2' style='line-height:40px;'>Total for <mark>$dir: $ct</mark></span>";
    }
    $message = "$message</div>";
  }
  
?>
<html lang="en">
  <head>
    <title>Remove Old Month Row</title>
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
      $brand_name = 'Remove Old Month Row';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="remove_month" class="col-sm-4 col-form-label">Remove This Month's Row: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="remove_month" name="remove_month" onchange="onSelectionChange()" style="width:70%;">
              <?php
                foreach($months as $f_l_date => $m) {   //e.g. Key: "01.12.19^31.12.19", Val: "December"
                  $selected = $choosen_value === $f_l_date ? 'selected' : '';
                  echo "<option value='$f_l_date' $selected>$m</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Remove Rows
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      function onSelectionChange() {
      }
      
      function validateForm(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#proceed').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>