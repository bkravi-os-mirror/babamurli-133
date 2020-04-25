<?php 
  
  session_start();
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $distro_dir = "$rootdir/000-Ravi-DontDelete/mfm";
  $message = '';
  
  //as:Assame, bn:Bengali, de:Deutsch, el:Greek, en:English, es:Spanish, fr:French, gu:Gujarati, hi:Hindi, hu:Hungarian
  //it:Italian, kn:Kannada, ko:Korean, ml:Malayalam, mr:Marathi, ne:Nepali, or:Odia, pl:Polish, pt:Portuguese, pa:Punjabi
  //si:Sinhala, th:Thai, ta_my:TamilLanka, ta:Tamil, te:Telugu, ro:Romanian, 
  $code_lang_map = array(
    "hi" => "Hindi", "te" => "Telugu", "en" => "English", "as" => "Assame", "bn" => "Bengali", "de" => "Deutsch",
    "fr" => "French", "el" => "Greek", "gu" => "Gujarati", "ta" => "Tamil", "it" => "Italian", "ko" => "Korean",
    "es" => "Spanish", "hu" => "Hungarian", "kn" => "Kannada", "ml" => "Malayalam", "mr" => "Marathi", "ne" => "Nepali",
    "or" => "Odia", "pl" => "Polish", "pt" => "Portuguese", "pa" => "Punjabi", "th" => "Thai", "si" => "Sinhala",
    "ta_my" => "TamilLanka",
  );
 
 asort($code_lang_map);   //ascending sort based on 'value'
  
  function getSanitizedStr($str) {
    $str = str_replace("\"'", '"', $str);
    $str = str_replace(" ''", '"', $str);
    $str = str_replace("“ ", '"', $str);
    $str = str_replace("“", '"', $str);
    $str = str_replace(" ,", ',', $str);
    $str = str_replace(" :- ", ':- ', $str);
    return $str;
  }
  
  //http://madhubanmurli.org/murlis/hi/html/murli-2020-04-04.html
  function getThotForDate($lang_code, $yyyy_mm_dd_date) {
    $htm_URL = "http://madhubanmurli.org/murlis/$lang_code/html/murli-$yyyy_mm_dd_date.html";
    $html = file_get_html($htm_URL);
    $thot = '';
    if($html) {
      //to-do: find blessing here
      $elem = $html->find('td[class=blessing-txt]', 0);
      if($elem) {
        $thot = $elem->innertext;
        $tmp = explode("<br>", $thot);
        foreach($tmp as $key => $value) {   //trimming whitespaces
          $tmp[$key] = trim($value);
        }
        if(count($tmp) >= 2) {   //i.e. vardan and vardan detail is separated by <br>
          $tmp[0] = str_replace("<b>", "", $tmp[0]);   //$thot[0] MUST be the vardan and rest should be vardan details
          $tmp[0] = str_replace("</b>", "", $tmp[0]);
          $thot = implode("\r\n", $tmp);   //for wondows notepad, \r\n is the line break
        } else {
          $thot = str_replace("<b>", "", $thot);
          $thot = str_replace("</b>", "", $thot);
          $thot = trim($thot);
        }
      } else {
        return "'td[class=blessing-txt]' NOT found in $htm_URL";
      }
      return $thot;
    } else {
      return false;
    }
  }
  
  $curr_timestamp = strtotime(date('Y-m-d'));   //this approach will make $curr_timestamp constant first.
  $day = date('d', $curr_timestamp);
  $month = date('m', $curr_timestamp);
  $year = date('Y', $curr_timestamp);
  $l_code = "hi";
  $how_many_days = 7;
  $sent_files_array = myScan("$distro_dir", "*thot*sent", 1, 1);
  $is_this_for_email_DL = false;
  $file_to_write = '';
  
  if(isset($_POST['proceed'])) {   //i.e. "Create Thoughts" is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "Below Results:<ul>";
    $day = $_POST['st_date'];  $month = $_POST['st_month'];  $year = $_POST['st_year'];
    $l_code = $_POST['l_code'];   $how_many_days = (int)$_POST['days'];
    $for_date = "$year-$month-$day";
    $entire_thought = '';
    $start_date_range = $for_date;
    $end_date_range = '';
    for($loop = 1; $loop <= $how_many_days; $loop++) {
      $result = getThotForDate($l_code, $for_date);   //date must be YYYY-MM-DD format
      if($result === false) {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> <span class='bg-danger text-white'> {$code_lang_map[$l_code]} Htm</span> NOT available for $for_date!!</li>";
      } else {
        $entire_thought = "$entire_thought---$for_date---\r\n$result\r\n----------\r\n";
        $end_date_range = $for_date;
      }
      $for_date = addDaysToDate(1, $for_date, 'Y-m-d', 'Y-m-d');
    }
    //Now, below writing the entire thought contents into a file
    $entire_thought = str_replace("बाप", "ईश्वर", $entire_thought);
    if(strlen($entire_thought) > 20) {   //at least some valid thoughts entries are there
      $tmp = explode("-", $start_date_range); $st_dt = "{$tmp[2]}-{$tmp[1]}";
      $tmp = explode("-", $end_date_range); $end_dt = "{$tmp[2]}-{$tmp[1]}";
      $file_to_write = "$distro_dir/$st_dt" . "_TO_" . $end_dt . "_$l_code" . "_thot.txt";
      $file = @fopen($file_to_write, "w");
      if($file) {
        if(fwrite($file, $entire_thought)) {
          $message = $message . "<li>$file_to_write <span class='bg-success text-white'>READY</span></li>";
          $is_this_for_email_DL = true;
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> writing into <span class='bg-danger text-white'>$file_to_write</span></li>";
        }
        fclose($file);
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> opening <span class='bg-danger text-white'>$file_to_write</span> to write!!</li>";
      }
    }
    $message = "$message</ul>";
  } else if(isset($_POST['email'])) {   //"Files are ready! Email Now..." is clicked
    $message = "Below Results:<ul>";
    if(isset($_POST['hidden_created_file']) && !empty($_POST['hidden_created_file'])) {
      if(file_exists($_POST['hidden_created_file']) && filesize($_POST['hidden_created_file']) > 3*1024) {   //filesize at least 3KB
        $result = sendEmail(array("bkravi.os@gmail.com"), onlyFileName($_POST['hidden_created_file']), "Dost Thoughts...", array($_POST['hidden_created_file']));
        foreach($result as $res) {
          $message = $message . "<li>$res</li>";
        }
        if(strpos($message, "Email sent successfully") !== false) {
          rename($_POST['hidden_created_file'], "{$_POST['hidden_created_file']}.sent");
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> {$_POST['hidden_created_file']} is zero filesize!</li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> No hidden file found!!</li>";
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>Thoughts Generator</title>
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
    <script src="js/rav-util.js"></script>
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
      $brand_name = '<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;Dost Thought Generator';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" id="sent_files_holder" style="margin-top:10px;">
          <label for="sent_files_holder_sel" class="col-sm-4 col-form-label" id="sent_label">Old Sent Files:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="sent_files_holder_sel" name="sent_files_holder_sel" style="width:100%;">
                <?php
                  foreach($sent_files_array as $sent_file) {
                    echo "<option value='$sent_file'>$sent_file</option>";
                  }
                ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="st_date" class="col-sm-4 col-form-label" id="date_label">Start Date:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="st_date" name="st_date" onchange="onDateSelected()">
              <?php
                for($i = 1; $i <= 31; $i++) {
                  $dt = $i <= 9 ? "0$i" : $i;
                  $selected = $day == $dt ? "selected" : "";
                  echo "<option value='$dt' $selected>$dt</option>";
                }
              ?>
            </select>
            <select class="form-control" id="st_month" name="st_month" onchange="onDateSelected()">
              <option value='01' <?php echo $month === '01' ? "selected" : "";?>>Jan</option>
              <option value='02' <?php echo $month === '02' ? "selected" : "";?>>Feb</option>
              <option value='03' <?php echo $month === '03' ? "selected" : "";?>>Mar</option>
              <option value='04' <?php echo $month === '04' ? "selected" : "";?>>Apr</option>
              <option value='05' <?php echo $month === '05' ? "selected" : "";?>>May</option>
              <option value='06' <?php echo $month === '06' ? "selected" : "";?>>June</option>
              <option value='07' <?php echo $month === '07' ? "selected" : "";?>>July</option>
              <option value='08' <?php echo $month === '08' ? "selected" : "";?>>Aug</option>
              <option value='09' <?php echo $month === '09' ? "selected" : "";?>>Sep</option>
              <option value='10' <?php echo $month === '10' ? "selected" : "";?>>Oct</option>
              <option value='11' <?php echo $month === '11' ? "selected" : "";?>>Nov</option>
              <option value='12' <?php echo $month === '12' ? "selected" : "";?>>Dec</option>
            </select>
            <select class="form-control" id="st_year" name="st_year" onchange="onDateSelected()">
              <?php
                for($i = 2018; $i <= 2030; $i++) {
                  $selected = $year == $i ? "selected" : "";   //dont compare with === because $year is string and $i is integer
                  echo "<option value='$i' $selected>$i</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="l_code" class="col-sm-4 col-form-label">Language: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="l_code" name="l_code" onchange="onLangSelected()" style="width:100%;">
              <?php
                foreach($code_lang_map as $code => $lang) {
                  $selected = $l_code == $code ? "selected" : "";
                  echo "<option value='$code' $selected>$lang</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="days" class="col-sm-4 col-form-label">For How Many Days: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="days" name="days" onchange="onDaysSelected()" style="width:100%;">
              <?php
                for($i = 1; $i <= 14; $i++) {
                  $selected = $how_many_days === $i ? "selected" : "";
                  echo "<option value=$i $selected>$i Day(s)</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Create Thoughts
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <button type="submit" id="email" name="email" class="btn btn-secondary text-white mt-3 mb-3 " style="width:100%;display:<?php echo $is_this_for_email_DL ? 'inline-block' : 'none'; ?>;">
          <span id="loading_spinner_email" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Files are ready! Email Now...
        </button>
        <input type="hidden"  name="hidden_created_file" id="hidden_created_file" value=<?php echo "'$file_to_write'"; ?>>
      <form>
      <a href="i.php" id="go_home" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      var selectedDay = '';
      var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      
      function getMsgBasedOnSelection() {
        dt = document.getElementById("st_date").value;
        mn = document.getElementById("st_month").value;
        yr = document.getElementById("st_year").value;
        lg = document.getElementById("l_code");
        dy = document.getElementById("days").value;
        selectedDay = weekday[new Date(yr, parseInt(mn) - 1, dt).getDay()];
        return "<mark>" + dy + " Day(s)</mark> <kbd>" + (lg.options[lg.selectedIndex].text) + " Thoughts</kbd> will be created starting from <abbr title='For This Date...'>" + dt + "-" + mn + "-" + yr + "</abbr>";
      }
      
      function onDateSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
        document.getElementById("date_label").innerHTML = "Start Date <kbd style='background-color:#8611bb !important;'>(" + selectedDay + ")</kbd> : ";
      }
      
      function onDaysSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
      }
      
      function onLangSelected() {
        document.getElementById("id_card").innerHTML = getMsgBasedOnSelection();
      }
      
      function validateForm1(form) {
        return true;
      }
      
      $("#proceed").click(function(){
        $(document).ready(function(){
          document.getElementById("loading_spinner").style.display = "inherit";
          $('#proceed').addClass('disabled');
          $('#email').addClass('disabled');
        });
      });
      
      $("#email").click(function(){
        $(document).ready(function(){
          document.getElementById("loading_spinner_email").style.display = "inherit";
          $('#email').addClass('disabled');
          $('#proceed').addClass('disabled');
        });
      });
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        dt = document.getElementById("st_date").value;
        mn = document.getElementById("st_month").value;
        yr = document.getElementById("st_year").value;
        lg = document.getElementById("l_code");
        dy = document.getElementById("days").value;
        selectedDay = weekday[new Date(yr, parseInt(mn) - 1, dt).getDay()];
        document.getElementById("date_label").innerHTML = "Start Date <kbd style='background-color:#8611bb !important;'>(" + selectedDay + ")</kbd> : ";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>