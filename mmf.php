<!DOCTYPE html>
<?php
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  $message = '';   //[FORALL]
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $madhuban_murli_base_url = 'http://madhubanmurli.org/murlis';
  $date_lbl = '';
  $languages = array("Thai", "Deutsch", "Italian", "Mal", "Marathi", "Nep", "Polish", "Port", "Sinhala", "Spanish", 
                      "Gujarati", "K", "Pun", "Telugu", "TamilLanka", "Assame", "Bengali", "Greek", "French", "Hungarian",
                      "Korean", "Tamil", "h", "E", "Odia",
                );
  $languages_code = array("Thai"=>"th", "Deutsch"=>"de", "Italian"=>"it", "Mal"=>"ml", "Marathi"=>"mr",
                          "Nep"=>"ne", "Polish"=>"pl", "Port"=>"pt", "Sinhala"=>"si", "Spanish"=>"es",
                          "Gujarati"=>"gu", "K"=>"kn", "Pun"=>"pa", "Telugu"=>"te", "TamilLanka"=>"ta_my",
                          "Assame"=>"as", "Bengali"=>"bn", "Greek"=>"el", "French"=>"fr", "Hungarian"=>"hu",
                          "Korean"=>"ko", "Tamil"=>"ta", "h" => "hi", "E" => "en", "Odia" => "or",
                );
  $language_serverfileloc = array(
    "Thai"=>"$rootdir/01. Daily Murli/43. Thai/PDF-Thai",
    "Deutsch"=>"$rootdir/01. Daily Murli/31. Deutsch/PDF-Deutsch",
    "Italian"=>"$rootdir/01. Daily Murli/33. Italian/PDF-Italiano",
    "Mal"=>"$rootdir/01. Daily Murli/06. Malayalam/02. Malayalam Murli - Pdf",
    "Marathi"=>"$rootdir/01. Daily Murli/12. Marathi/03. Marathi Murli - PDF",
    "Nep"=>"$rootdir/01. Daily Murli/30. Nepali/02. Nepali Murli - Pdf",
    "Polish"=>"$rootdir/01. Daily Murli/40. Polish/PDF-Polish",
    "Port"=>"$rootdir/01. Daily Murli/41. Portuguese/PDF-Portuguese",
    "Sinhala"=>"$rootdir/01. Daily Murli/44. Sinhala/PDF-Sinhala",
    "Spanish"=>"$rootdir/01. Daily Murli/32. Spanish/PDF-Spanish",
    "Gujarati"=>"$rootdir/01. Daily Murli/09. Gujarati/03. Gujarati Murli - PDF",
    "K"=>"$rootdir/01. Daily Murli/05. Kannada/02. Kannada Murli - Pdf",
    "Pun"=>"$rootdir/01. Daily Murli/11. Punjabi/02. Punjabi Murli - PDF",
    "Telugu"=>"$rootdir/01. Daily Murli/04. Telugu/02. Telugu - Murli - Pdf",
    "TamilLanka"=>"$rootdir/01. Daily Murli/35. Tamil-Lanka/PDF-Tamil-Lanka",
    "Assame"=>"$rootdir/01. Daily Murli/08. Assame/01. Assame Murli - Pdf",
    "Bengali"=>"$rootdir/01. Daily Murli/07. Bengali/02. Bengali Murli - Pdf",
    "Greek"=>"$rootdir/01. Daily Murli/37. Greek/PDF-Greek",
    "French"=>"$rootdir/01. Daily Murli/36. French/PDF-French",
    "Hungarian"=>"$rootdir/01. Daily Murli/38. Hungarian/PDF-Hungarian",
    "Korean"=>"$rootdir/01. Daily Murli/39. Korean/PDF-Korian",
    "Tamil"=>"$rootdir/01. Daily Murli/03. Tamil/02. Tamil Murli - Pdf",
    "h"=>"$rootdir/01. Daily Murli/01. Hindi/02. Hindi Murli - Pdf",
    "E"=>"$rootdir/01. Daily Murli/02. English/02. Eng Murli - Pdf",
    "Odia"=>"$rootdir/01. Daily Murli/10. Odiya/02. Odiya Murli - Pdf",
    
  );
  
  $languages_chk_unchk = array();
  $success_count = 0;
  $failure_count = 0;
  
  sort($languages);
  ksort($languages_code);
  
  $curr_date = date("Y-m-d");
  $curr_date_1 = strtotime($curr_date);
  
  $dates = array(
    date('Y-m-d', strtotime("+4 day", $curr_date_1)),
    date('Y-m-d', strtotime("+3 day", $curr_date_1)),
    date('Y-m-d', strtotime("+2 day", $curr_date_1)),
    date('Y-m-d', strtotime("+1 day", $curr_date_1)),
    date('Y-m-d', strtotime("+0 day", $curr_date_1)),   //special character at the begininng to indicate the current date
    date('Y-m-d', strtotime("-1 day", $curr_date_1)),
    date('Y-m-d', strtotime("-2 day", $curr_date_1)),
    date('Y-m-d', strtotime("-3 day", $curr_date_1)),
    date('Y-m-d', strtotime("-4 day", $curr_date_1)),
    date('Y-m-d', strtotime("-5 day", $curr_date_1)),
    date('Y-m-d', strtotime("-6 day", $curr_date_1)),
  );
  
  function startsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), 0, strlen($needle)) === 0;
  }
  
  function endsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
  }  
  
  function ifNonZeroFileExists($full_file_name) {
    try {
      if(file_exists($full_file_name) && filesize($full_file_name) > 1024) {   //filesize returns file size in bytes
        return true;
      } else{
        return false;
      }
    } catch(Exception $e) {
      return false;
    }
  }
  
  function get_DD_MM_YY_format_date($dt_lbl) {   //2020-02-19
    try {
      $dt_array = explode('-', $dt_lbl);   //2020-02-19
      return("$dt_array[2].$dt_array[1]." . substr($dt_array[0], 2));   //19.02.20
    } catch(Exception $e) {
      return '01.01.99';
    }
  }
  $selected_date = $curr_date;
  if(isset($_POST['datelabel']) && !empty($_POST['datelabel'])) {   //Proceed button clicked
    $message = 'FORALL';
    $date_lbl = $_POST['datelabel'];
    $selected_date = $date_lbl;
    $date_dd_mm_yy = get_DD_MM_YY_format_date($date_lbl);
    //Below setting form checkboxes based on their existance in our server. If file already there, uncheck the checkbox
    foreach($languages_code as $lang => $code) {
      $file_to_check = $language_serverfileloc[$lang] . "/$date_dd_mm_yy-$lang.pdf";
      $languages_chk_unchk[$lang] = ifNonZeroFileExists($file_to_check) ? "" : "checked";
    }
  }
  else if(isset($_POST['download'])) {   //Download button clicked
    $message = 'FORALL';
    $languages_chk_unchk = array();
    if(isset($_POST['datelabel_hidden']) && !empty($_POST['datelabel_hidden'])) {
      $date_lbl = $_POST['datelabel_hidden'];
      $selected_date = $date_lbl;
    }
    $message = $message . "<ul>";
    $success_count = 0;
    $failure_count = 0;
    foreach($languages_code as $lang => $code) {
      if(isset($_POST[$code]) && !empty($_POST[$code]) && $_POST[$code] === "on" && 
        isset($_POST['datelabel_hidden']) && !empty($_POST['datelabel_hidden'])) {
        $languages_chk_unchk[$lang] = "checked";
        try {
          $dt_array = explode('-', $_POST['datelabel_hidden']);   //2020-02-19
          $target_file_name = "$dt_array[2].$dt_array[1]." . substr($dt_array[0], 2) . "-$lang.pdf";   //18.02.20-Deutsch.pdf
          $download_URL = "$madhuban_murli_base_url/$code/pdf/murli-" . $_POST['datelabel_hidden'] . ".pdf";
          $upload_URL = $language_serverfileloc[$lang] . "/" . $target_file_name;
          //Below downloading from madhubanmurli.org and uploading to our server
          if($result = @file_get_contents($download_URL)) {
            if(file_put_contents($upload_URL, $result)) {
              $message = $message . "<li>$target_file_name <span class='bg-success text-white'>Done</span></li>";
              $success_count++;
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>File $download_URL write ERROR at $target_file_name!!</span></li>";
              $failure_count++;
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>$target_file_name @ $download_URL: ${http_response_header[0]}</span></li>";
            $failure_count++;
          }
          if(file_exists($upload_URL) && filesize($upload_URL) < 20) unlink($upload_URL);
        } catch(Exception $e) {
          $message = $message . '<li><span class="bg-danger text-white">ERROR/EXCEPTION ' . $e->errorMessage() . '</span></li>';
        }
      }
    }
    $message = $message . "</ul>";
    if(count($languages_chk_unchk) < 1) {
      $message = 'FORALLNothing to download! Please select any one file to download<br>';
    } else {
      $message = $message . '<span class="bg-warning text-white p-2">Total Success: ' . $success_count . ', Total Failure: ' . $failure_count . '</span>';
    }
  }
  
?>
<html lang="en">
  <head>
    <title>Downloader - PDF madhubanmurli.org</title>
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
      $brand_name = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF - Madhuban ORG';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="datelabel" class="col-sm-4 col-form-label">Date (e.g. format YYYY-MM-DD)</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="datelabel" name="datelabel" style="width:100%;">
              <?php
                foreach($dates as $date) {
                  $selected = '';
                  if($date == $selected_date) {$selected = 'selected';}
                  echo "<option value='$date' $selected>$date</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          Proceed
        </button>
      </form>
      <span <?php if(strpos($message, 'FORALL') !== false) echo 'style="display:block;"'; else echo 'style="display:none;"';?>>
        <form method="post" action="" onsubmit="return validateForm2(this);" style="margin-top:10px;">
          <table class="table table-hover table-striped table-sm">
            <tbody>
              <tr style="background-color: gainsboro;">
                <td style="width: 90%;"><b>Language</b></td>
                <td>
                  <div class="form-group form-check">
                    <input class="form-check-input ravicheck" type="checkbox" id="selectall" name="selectall">
                  </div>
                </td>
              </tr>
              <?php
                for($i = 0; $i < count($languages); $i++) {
                  $code = $languages_code[$languages[$i]];
                  echo '<tr>';
                    echo "<td style='width:90%;'>$date_lbl $languages[$i] PDF</td>";
                    echo '<td>';
                      echo '<div class="form-group form-check">';
                        echo '<input class="form-check-input ravicheck" type="checkbox" id="' . $code . '" name="' . $code . '" ' . $languages_chk_unchk[$languages[$i]] . '>';
                      echo '</div>';
                    echo '</td>';
                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>
          <input type="hidden" id="datelabel_hidden" name="datelabel_hidden" <?php echo 'value="' . $date_lbl . '"'; ?>>
          <button type="submit" id="download" name="download" class="btn btn-danger" style="width:100%;margin-top:10px;">
            <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
            Download
          </button>
        </form>
      </span>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo str_replace('FORALL', '', $message); else echo 'Output here...' ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      var d = new Date();
      var dd = (d.getDate()) <= 9 ? ("0" + (d.getDate())) : (d.getDate());
      var mm = (d.getMonth() + 1) <= 9 ? ("0" + (d.getMonth() + 1)) : (d.getMonth() + 1);
      //document.getElementById("datelabel").value = d.getFullYear() + "-" + mm + "-" + dd;   //e.g. 2020-02-18
      
      function validateForm1(form) {   //Nothing to do as such
        return true;
      }
      
      function validateForm2(form) {
        disableFormControls();
        if(document.getElementById('datelabel_hidden').value.length != 10) {   //if hidden date field is not set by any reason, set manually here
          document.getElementById('datelabel_hidden').value = d.getFullYear() + "-" + mm + "-" + dd;
        }
        return true;
      }
      
      $("#selectall").click(function () {
        $(".ravicheck").prop('checked', $(this).prop('checked'));
      });
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#proceed').addClass('disabled');
        $('#download').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>