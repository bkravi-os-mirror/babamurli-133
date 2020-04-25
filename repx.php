<!DOCTYPE html>
<?php
  session_start();
  include('./util.php');
  $message = 'Below Results<ul>';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $values_for_txtbox = '';
  $is_from_prev = false;
  
  if(isset($_SESSION['broken_links'])) {
    $is_from_prev = true;
    foreach($_SESSION['broken_links'] as $links) {
      $values_for_txtbox = "$values_for_txtbox$links\n";
    }
  }
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  $main_dir = "00. Htm";
  $valid_files = array (
      
      "suraj%20bhai%20class" => "00-htm/Suraj Bhai Classes - Tamil.htm",
      "suraj%20bhai%20class%201" => "00-htm/Suraj Bhai Classes 1- Tamil.htm",
      
      //"dharmraj%20ki%20adalat" => "00-htm/Full Stop.htm",
      //"avyakt%20palna" => "00-htm/Avyakt Palna.htm",
      //"today%20moti" => "00-htm/Todays Moti.htm",
      //"hindi%20-%20aaj%20ka%20purushrath" => "00-htm/Todays Purusharth - Suraj Bhai.htm",
      //"todays%20commentary%20-%20mp3" => "00-htm/Todays Commentary.htm",
      //"english/30.%20todays%20thought" => "00-htm/Todays Thoughts.htm",
      //"25.%20today%20calendar" => "00-htm/Todays Calendar.htm",
      
      //modified above links as we have moved everything to Hindi.html now
      "avyakt%20palna" => "$main_dir/01. Hindi.html",
      "today%20moti" => "$main_dir/01. Hindi.html",
      "hindi%20-%20aaj%20ka%20purushrath" => "$main_dir/01. Hindi.html",
      "todays%20commentary%20-%20mp3" => "$main_dir/01. Hindi.html",
      "english/30.%20todays%20thought" => "$main_dir/02. English.html",
      "hindi/25.%20today%20calendar" => "$main_dir/01. Hindi.html",
      "english/25.%20today%20calendar" => "$main_dir/02. English.html",
      "telugu/25.%20today%20calendar" => "$main_dir/04. Telugu.html",
      
      "hindi" => "$main_dir/01. Hindi.html",
      "tamil" => "$main_dir/03. Tamil.html",
      "english" => "$main_dir/02. English.html",
      "telugu" => "$main_dir/04. Telugu.html",
      "kannada" => "$main_dir/05. Kannada.html",
      "malayalam" => "$main_dir/06. Malayalam.html",
      "bengali" => "$main_dir/07. Bengali.html",
      "assame" => "$main_dir/08. Assame.html",
      "odiya" => "$main_dir/10. Odiya.html",
      "punjabi" => "$main_dir/11. Punjabi.html",
      "marathi" => "$main_dir/12. Marathi.html",
      "gujarati" => "$main_dir/13. Gujarati.html",
      "nepali" => "$main_dir/30. Nepali.html",
      "deutsch" => "$main_dir/31. Deutsch.html",
      "spanish" => "$main_dir/32. Spanish.html",
      "italian" => "$main_dir/33. Italian.html",
      "chinese" => "$main_dir/34. Chinese.html",
      "tamil-lanka" => "$main_dir/35. Tamil-Lanka.html",
      "french" => "$main_dir/36. French.html",
      "greek" => "$main_dir/37. Greek.html",
      "hungarian" => "$main_dir/38. Hungarian.html",
      "korean" => "$main_dir/39. Korean.html",
      "polish" => "$main_dir/40. Polish.html",
      "portuguese" => "$main_dir/41. Portuguese.html",
      "sindhi" => "$main_dir/42. Sindhi.html",
      "thai" => "$main_dir/43. Thai.html",
      "sinhala" => "$main_dir/44. Sinhala.html",
  );
  
  function getFileToModify($link) {
    global $valid_files;
    foreach($valid_files as $key_word => $file) {
      if(strpos(strtolower($link), $key_word) !== false) return $file;
    }
    return false;
  }
  
  if(isset($_POST['replace'])) {   //means 'replace' submit button is clicked
    try {
      //Printing all $_POST keys and values
      //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>$value</td></tr>";} echo '</table></blockquote>';
      if(isset($_POST['brokenlinks']) && !empty($_POST['brokenlinks'])) {
        $links_array = explode("\n", trim($_POST['brokenlinks']));
        foreach($links_array as $link) {
          if(($file_to_process = getFileToModify($link)) !== false ) {
            $myfile = fopen("$rootdir/$file_to_process", "r");
            if(!$myfile) {
              $message = $message . "<li><span class='bg-danger text-white'>Unable to open $rootdir/$file_to_process for reading!</span></li>";
            } else {
              $readAll = fread($myfile, filesize("$rootdir/$file_to_process"));
              fclose($myfile);
              $search_str = trim(substr($link, strripos($link, "/") + 1));
              if(($idx = strpos($readAll, $search_str)) !== false) {
                $first_part = substr($readAll, 0, strripos(substr($readAll, 0, $idx), "<a"));
                $second_part = substr($readAll, strpos($readAll, "</a>", $idx) + strlen("</a>"));
                $new_file_content = $first_part . "x" . $second_part;
                takeBackup("$rootdir/$file_to_process");
                $myNewFile = fopen("$rootdir/$file_to_process", "w");
                if(!$myNewFile) {
                  $message = $message . "<li><span class='bg-danger text-white'>Unable to open $file_to_process for writing!</span></li>";
                } else {
                  if(fwrite($myNewFile, $new_file_content)) {
                    $message = $message . "<li>Removed $search_str in $file_to_process Successfully!</span></li>";
                  } else {
                    $message = $message . "<li><span class='bg-danger text-white'>Unable to write into $file_to_process !</span></li>";
                  }
                  fclose($myNewFile);
                }
              } else {
                $message = $message . "<li>$link <span class='bg-danger text-white'>NOT FOUND</span> in $file_to_process !!!</li>";
              }
            }
          } else {
            $message = $message . "<li>$link <span class='bg-danger text-white'>NOT configured yet! Don't know which file to modify!!</span></li>";
          }
        }
      }
    } catch(Exception $e) {
      $message = $message . "<span class='bg-danger text-white'>ERROR/Exception: " . $e->getMessage() . "</span><br>";
    }
  }
?>
<html lang="en">
  <head>
    <title>Xenu x Replacer</title>
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
      $brand_name = 'Replacer For Broken <i class="fa fa-chain-broken" aria-hidden="true"></i>';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return preProcess(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="brokenlinks" class="col-sm-4 col-form-label">All Broken Links Line By Line:</label>
          <div class="col-sm-8 form-inline">
            <textarea class="form-control" style="width:100%;resize: none;" id="brokenlinks" name="brokenlinks" rows="5"><?php echo "$values_for_txtbox"; ?></textarea>
          </div>
        </div>
        <button type="submit" id="replace" name="replace" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Replace All
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
      <button id="go_back" onclick="goBack();" class="btn btn-danger" style="display:<?php echo $is_from_prev ? 'inline-block;' : 'none;'; ?> position: fixed;bottom: 3px;right: 110px;">
      Go Back
      </button>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
      Go Home
      </a>
    </div>
    <script language="javascript">
      
      function goBack() {
        //window.history.back();
        window.location.replace("xenu.php");
      }        
      
      function preProcess(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#replace').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>