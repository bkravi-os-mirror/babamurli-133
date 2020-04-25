<!DOCTYPE html>
<?php
  /*
    References:
    https://simplehtmldom.sourceforge.io/manual.htm
    http://www.gambit.ph/converting-relative-urls-to-absolute-urls-in-php/
    Useful curl command:
      curl -Is "http://www.babamurli.com/00.%20Htm/../01.%20Daily%20Murli/37.%20Greek/PDF-Greek/29.02.20-Greek.pdf" | head -n 1
        HTTP/1.1 404 Not Found
        HTTP/1.1 200 OK
  */
  
  session_start();
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];  
  $message = '';
  
  $skip_links_like = array("contact.htm", "payumoney", "contribution.htm", "javascript:history", "#top");
  $skip_links_exact = array("http://www.bkdrluhar.com");
  $links_file = "$rootdir/000-Ravi-DontDelete/xenu_links.txt";   //writing all links to current directory
  $links_with_parent_file = "$rootdir/000-Ravi-DontDelete/xenu_links_wp.txt";   //writing all links to current directory
  $curl_broken_only_file = "$rootdir/000-Ravi-DontDelete/curl_broken_only.out";

  function stringContainsAny($string_to_test) {
    global $skip_links_like;
    $string_to_test_lwr = strtolower($string_to_test);
    foreach($skip_links_like as $tmp) {
      if(strpos($string_to_test_lwr, $tmp) !== false) return true;
    }
    return false;
  }
  
  function stringContainsExactly($string_to_test) {
    global $skip_links_exact;
    $string_to_test_lwr = strtolower($string_to_test);
    foreach($skip_links_exact as $tmp) {
      if($string_to_test_lwr === $tmp) return true;
    }
    return false;
  }
  
  function relative2absPath( $rel, $base ) {
    extract( parse_url( $base ) );
    if ( strpos( $rel,"//" ) === 0 ) {
      return $scheme . ':' . $rel;
    }
    if ( parse_url( $rel, PHP_URL_SCHEME ) != '' ) {
      return $rel;
    }
    if ( $rel[0] == '#' || $rel[0] == '?' ) {
      return $base . $rel;
    }
    $path = preg_replace( '#/[^/]*$#', '', $path );
    if ( $rel[0] ==  '/' ) {
      $path = '';
    }
    $abs = $host . $path . "/" . $rel;
    $abs = preg_replace( "/(\/\.?\/)/", "/", $abs );
    $abs = preg_replace( "/\/(?!\.\.)[^\/]+\/\.\.\//", "/", $abs );
    return $scheme . '://' . $abs;
  }
  
  $final_links_array = array();
  $links_to_check = array(
    'http://www.babamurli.com/00.%20Htm/01.%20Hindi.html',
    'http://www.babamurli.com/00.%20Htm/02.%20English.html',
    'http://www.babamurli.com/00.%20Htm/03.%20Tamil.html',
    'http://www.babamurli.com/00.%20Htm/04.%20Telugu.html',
    'http://www.babamurli.com/00.%20Htm/05.%20Kannada.html',
    'http://www.babamurli.com/00.%20Htm/06.%20Malayalam.html',
    'http://www.babamurli.com/00.%20Htm/07.%20Bengali.html',
    'http://www.babamurli.com/00.%20Htm/08.%20Assame.html',
    'http://www.babamurli.com/00.%20Htm/10.%20Odiya.html',
    'http://www.babamurli.com/00.%20Htm/11.%20Punjabi.html',
    'http://www.babamurli.com/00.%20Htm/12.%20Marathi.html',
    'http://www.babamurli.com/00.%20Htm/13.%20Gujarati.html',
    'http://www.babamurli.com/00.%20Htm/30.%20Nepali.html',
    'http://www.babamurli.com/00.%20Htm/31.%20Deutsch.html',
    'http://www.babamurli.com/00.%20Htm/32.%20Spanish.html',
    'http://www.babamurli.com/00.%20Htm/33.%20Italian.html',
    'http://www.babamurli.com/00.%20Htm/34.%20Chinese.html',
    'http://www.babamurli.com/00.%20Htm/35.%20Tamil-Lanka.html',
    'http://www.babamurli.com/00.%20Htm/36.%20French.html',
    'http://www.babamurli.com/00.%20Htm/37.%20Greek.html',
    'http://www.babamurli.com/00.%20Htm/38.%20Hungarian.html',
    'http://www.babamurli.com/00.%20Htm/39.%20Korean.html',
    'http://www.babamurli.com/00.%20Htm/40.%20Polish.html',
    'http://www.babamurli.com/00.%20Htm/41.%20Portuguese.html',
    'http://www.babamurli.com/00.%20Htm/42.%20Sindhi.html',
    'http://www.babamurli.com/00.%20Htm/43.%20Thai.html',
    'http://www.babamurli.com/00.%20Htm/44.%20Sinhala.html',
    //'http://www.babamurli.com/00-htm/Todays%20Calendar.htm',
    //'http://www.babamurli.com/00-htm/Todays%20Commentary.htm',
    //'http://www.babamurli.com/00-htm/Todays%20Thoughts.htm',
    //'http://www.babamurli.com/00-htm/Todays%20Purusharth%20-%20Suraj%20Bhai.htm',
    //'http://www.babamurli.com/00-htm/Todays%20Moti.htm',
    //'http://www.babamurli.com/00-htm/Full%20Stop.htm',
    //'http://www.babamurli.com/00-htm/Avyakt%20Palna.htm',
    
  );
  
  $actual_fileloc_parent = array(
    "http://www.babamurli.com/00.%20Htm/01.%20Hindi.html" => "$rootdir/00. Htm/01. Hindi.html",
    "http://www.babamurli.com/00.%20Htm/02.%20English.html" => "$rootdir/00. Htm/02. English.html",
    "http://www.babamurli.com/00.%20Htm/03.%20Tamil.html" => "$rootdir/00. Htm/03. Tamil.html",
    "http://www.babamurli.com/00.%20Htm/04.%20Telugu.html" => "$rootdir/00. Htm/04. Telugu.html",
    "http://www.babamurli.com/00.%20Htm/05.%20Kannada.html" => "$rootdir/00. Htm/05. Kannada.html",
    "http://www.babamurli.com/00.%20Htm/06.%20Malayalam.html" => "$rootdir/00. Htm/06. Malayalam.html",
    "http://www.babamurli.com/00.%20Htm/07.%20Bengali.html" => "$rootdir/00. Htm/07. Bengali.html",
    "http://www.babamurli.com/00.%20Htm/08.%20Assame.html" => "$rootdir/00. Htm/08. Assame.html",
    "http://www.babamurli.com/00.%20Htm/10.%20Odiya.html" => "$rootdir/00. Htm/10. Odiya.html",
    "http://www.babamurli.com/00.%20Htm/11.%20Punjabi.html" => "$rootdir/00. Htm/11. Punjabi.html",
    "http://www.babamurli.com/00.%20Htm/12.%20Marathi.html" => "$rootdir/00. Htm/12. Marathi.html",
    "http://www.babamurli.com/00.%20Htm/13.%20Gujarati.html" => "$rootdir/00. Htm/13. Gujarati.html",
    "http://www.babamurli.com/00.%20Htm/30.%20Nepali.html" => "$rootdir/00. Htm/30. Nepali.html",
    "http://www.babamurli.com/00.%20Htm/31.%20Deutsch.html" => "$rootdir/00. Htm/31. Deutsch.html",
    "http://www.babamurli.com/00.%20Htm/32.%20Spanish.html" => "$rootdir/00. Htm/32. Spanish.html",
    "http://www.babamurli.com/00.%20Htm/33.%20Italian.html" => "$rootdir/00. Htm/33. Italian.html",
    "http://www.babamurli.com/00.%20Htm/34.%20Chinese.html" => "$rootdir/00. Htm/34. Chinese.html",
    "http://www.babamurli.com/00.%20Htm/35.%20Tamil-Lanka.html" => "$rootdir/00. Htm/35. Tamil-Lanka.html",
    "http://www.babamurli.com/00.%20Htm/36.%20French.html" => "$rootdir/00. Htm/36. French.html",
    "http://www.babamurli.com/00.%20Htm/37.%20Greek.html" => "$rootdir/00. Htm/37. Greek.html",
    "http://www.babamurli.com/00.%20Htm/38.%20Hungarian.html" => "$rootdir/00. Htm/38. Hungarian.html",
    "http://www.babamurli.com/00.%20Htm/39.%20Korean.html" => "$rootdir/00. Htm/39. Korean.html",
    "http://www.babamurli.com/00.%20Htm/40.%20Polish.html" => "$rootdir/00. Htm/40. Polish.html",
    "http://www.babamurli.com/00.%20Htm/41.%20Portuguese.html" => "$rootdir/00. Htm/41. Portuguese.html",
    "http://www.babamurli.com/00.%20Htm/42.%20Sindhi.html" => "$rootdir/00. Htm/42. Sindhi.html",
    "http://www.babamurli.com/00.%20Htm/43.%20Thai.html" => "$rootdir/00. Htm/43. Thai.html",
    "http://www.babamurli.com/00.%20Htm/44.%20Sinhala.html" => "$rootdir/00. Htm/44. Sinhala.html",
    //"http://www.babamurli.com/00-htm/Todays%20Calendar.htm" => "$rootdir/00-htm/Todays Calendar.htm",
    //"http://www.babamurli.com/00-htm/Todays%20Commentary.htm" => "$rootdir/00-htm/Todays Commentary.htm",
    //"http://www.babamurli.com/00-htm/Todays%20Thoughts.htm" => "$rootdir/00-htm/Todays Thoughts.htm",
    //"http://www.babamurli.com/00-htm/Todays%20Purusharth%20-%20Suraj%20Bhai.htm" => "$rootdir/00-htm/Todays Purusharth - Suraj Bhai.htm",
    //"http://www.babamurli.com/00-htm/Todays%20Moti.htm" => "$rootdir/00-htm/Todays Moti.htm",
    //"http://www.babamurli.com/00-htm/Full%20Stop.htm" => "$rootdir/00-htm/Full Stop.htm",
    //"http://www.babamurli.com/00-htm/Avyakt%20Palna.htm" => "$rootdir/00-htm/Avyakt Palna.htm",
    
  );
  
  $choosen_value = '';
  $find_broken_display = 'none';
  $curl_broken_only_array = array();
  
  if(file_exists($curl_broken_only_file) && filesize($curl_broken_only_file) > 0) {   //reading all broken links from previous run i.e. from curl_broken_only.out file
    $tmp = array();
    $curl_broken_only_array = file($curl_broken_only_file);   //this reads entire file lines into array
    foreach($curl_broken_only_array as $link) {
      if(!preg_match("/^#/", $link)) {   //discarding lines that start with #
        array_push($tmp, $link);
      }
    }
    sort($tmp);
    $curl_broken_only_array = $tmp;
  }
  
  if(isset($_POST['proceed']) && isset($_POST['source_link']) && !empty($_POST['source_link'])) {   //this means the 'proceed' submit button is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    
    $choosen_encoded_value = $_POST['source_link'];   //it will be a "." to "^" encoded value
    $choosen_links = $choosen_encoded_value === "ALL" ? $links_to_check : array(str_replace("^", ".", $choosen_encoded_value));
    $message = "Below Results<ul>";
    $find_broken_display = 'inline-block';
    //Step 1) Below fetching all <a> links from the choosen source link
    foreach($choosen_links as $link) {
      $html = file_get_html($link);
      if(is_object($html)) {
        $a_links_tmp = array();
        foreach($html->find('a') as $element) {   // Find all links
          if(!stringContainsAny($element->href) && !stringContainsExactly($element->href)) {
            $anchor = relative2absPath($element->href, $link);
            array_push($a_links_tmp, $anchor);
          }
        }
        sort($a_links_tmp);
        $final_links_array[$link] = $a_links_tmp;
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>Error opening $link</span></li>";
      }
    }
    
    //Step 2) Now writing all <a> links into a file for further processing
    $myNewFile = fopen($links_file, "w");
    $myNewFile_2 = fopen($links_with_parent_file, "w");
    if((!$myNewFile) || (!$myNewFile_2)) {
      $message = $message . "<li><span class='bg-danger text-white'>Unable to open $links_file and/or $links_with_parent_file for writing!</span></li>";
    } else {
      foreach($final_links_array as $parent => $child_array) {
        $actual_file_loc = "ACTUAL_FILE_LOC_NOT_FOUND_FOR: $parent";
        if(array_key_exists($parent, $actual_fileloc_parent))
          $actual_file_loc = $actual_fileloc_parent[$parent];
        foreach($child_array as $child_links) {
          $content = str_replace(" ", "%20", str_replace("\\", "/", $child_links));
          $content = str_replace("http://babamurli.com", "http://www.babamurli.com", $content);
          if(!fwrite($myNewFile, "$content\n")) {
            $message = $message . "<li><span class='bg-danger text-white'>Unable to write into $links_file !</span></li>";
          }
          if(!fwrite($myNewFile_2, "$content^$actual_file_loc\n")) {
            $message = $message . "<li><span class='bg-danger text-white'>Unable to write into $links_with_parent_file !</span></li>";
          }
        }
      }
      fclose($myNewFile);
      fclose($myNewFile_2);
    }
    $total_links_written = 0;
    $fil = file($links_file);
    if(file_exists($links_file) && filesize($links_file) > 0) {
      $total_links_written = count($fil) - count(preg_grep('/^#/', $fil));
    }
    $message = "$message</ul>Total links written: <mark>$total_links_written</mark>";
  } else if(isset($_POST['remove_broken'])) {   //means remove_broken button is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $total_broken_links_array = array();
    foreach ($_POST as $key => $value) {   //key is: http://www_babamurli_com/01_%20Daily%20Murli/01_%20Hindi/08_%20Hindi%20Murli%20-%20OSB%20-%20MP4/02_03_20-OSB_mp4^1
      if($key !== 'remove_broken') {   //not interested in submit button's value
        $tmp = str_replace("_", ".", $key);   //decoding "_" to "." As it was encoded before POSTing
        $tmp = explode("^", $tmp);
        array_push($total_broken_links_array, $tmp[0]);
      }
    }
    sort($total_broken_links_array);
    $_SESSION['broken_links'] = $total_broken_links_array;
    header("Location: repx.php");
  }
  
?>
<html lang="en">
  <head>
    <title>Find Broken Links</title>
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
      $brand_name = "Find Broken <i class='fa fa-chain-broken' aria-hidden='true'></i> Links";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="source_link" class="col-sm-4 col-form-label">Find Broken Links In: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="source_link" name="source_link" style="width:100%;">
              <option value='ALL'>ALL</option>
              <?php
                foreach($links_to_check as $link) {
                  $sanitized_link_for_POST = str_replace(".", "^", $link);   //since during POST "." is converted into "_"
                  $selected = $choosen_encoded_value === $sanitized_link_for_POST ? 'selected' : '';
                  echo "<option value='$sanitized_link_for_POST' $selected>$link</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;margin-left:0px;margin-bottom:0px;">
          <div class="form-group form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" id="reuse" onclick="onReuseClick();"> Use Existing Files To Check Broken Links
            </label>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Read All Links First
        </button>
      </form>
      <div class="card bg-light text-dark mt-3" id="reuse-holder" style="display:none;">
        <div class="card-body" style="padding-bottom:0px;">
          <?php 
            $ct = count($curl_broken_only_array);
            echo "<span class='text-danger'><b>[$ct]</b> broken links found from last run</span><ul>";
            foreach($curl_broken_only_array as $link) {
              $file_name_only = pathinfo($link, PATHINFO_FILENAME) . "." . pathinfo($link, PATHINFO_EXTENSION);
              echo "<li>$file_name_only</li>";
            }
            echo "</ul>";
          ?>
        </div>
      </div>
      <div class="card bg-info text-white mt-3" id="message-holder" style="display:inherit;">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <button id="find_broken" name="find_broken" class="btn btn-success mt-3" <?php echo "style='width:100%; display:$find_broken_display;'";?>>
        <span id="loading_spinner_1" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
        Find Broken <i class='fa fa-chain-broken' aria-hidden='true'></i> Links Now
      </button>
      
      <form id="final_form" method="post" action="" onsubmit="return validateFormFinal(this);" class="mt-3" style="display:none;">
        <div id="ins_0" class="form-group row" style="margin-top:10px;margin-left:20px">
        </div>
        <button type="submit" id="remove_broken" name="remove_broken" class="btn btn-primary" style="width:100%;margin-bottom: 20px;">
          <span id="loading_spinner_2" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Remove Broken Links With 'x'
        </button>
      </form>
      
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
      <button id="terminate" name="terminate" class="btn btn-danger mt-3" <?php echo "style='position: fixed;bottom: 3px;left: 3px;display:$find_broken_display'";?>>
        Click To Terminate
      </button>
    </div>
    <script language="javascript">
      
      var timeOut; //How frequently need to refresh
      var needToStop = false;
      
      //Set executeShellScript to yes for Prod
      var executeShellScript = "yes";   //GET param to set. This setting will execute curl system shell script for the first time
      var beforeOrAfterEnd = 'beforeend';
      var retryBroken = "no";
      
      var ok_json = {};
      var notok_json = {};
      
      $(document).ready(function(){
      });
      
      var callMyFunction = function() {
        $.ajax({
          dataType: "json",
          url: "http://www.babamurli.com/000-Ravi-DontDelete/xenu-p1.php?retrybroken=" + retryBroken + "&execshell=" + executeShellScript,
          timeout: 10000,
          error: function (xhr, status, error) {
            if (status === "timeout" || status === "error") {
              console.log("RAVI_ERROR", status, error);
              alert("Error: " + status + error);
              document.getElementById("loading_spinner_1").style.display = "none";
              $('#proceed').prop("disabled", false);
              $('#proceed').removeClass("disabled");
              $('#find_broken').prop("disabled", false);
              $('#find_broken').removeClass("disabled");
            }
          },
          success: function (msg) {
            //console.log("RAVI_SUCCESS", msg);
            jsonObj = JSON.parse(JSON.stringify(msg));   //{"link":"status code", "start":"time", "end":"time"}
            if(jsonObj.hasOwnProperty('start') && jsonObj.hasOwnProperty('end')) {   //means a complete and correct json data received
              needToStop = true;   //we have received a valid json data hence no more call to it
              counter = 0;
              if(Object.keys(jsonObj).length == 2) {   //means json data length is only 2. i.e. no broken links in that
                document.getElementById('ins_0').insertAdjacentHTML('beforeend', "<span class='bg-secondary text-white p-2'>Hurray!! No Broken Links!!!");
              } else {
                ok_json = {}; notok_json = {};
                
                //below making notok and ok json objects separately
                for(key in jsonObj) {
                  if(jsonObj.hasOwnProperty(key) && key != 'start' && key != 'end') {   //avoiding start and end key to show
                    tmp = key.split("^");
                    if(tmp.length == 2) {
                      if(tmp[1] == "notok") {
                        notok_json[key] = jsonObj[key];
                      } else {
                        ok_json[key] = jsonObj[key];
                      }
                    }
                  }
                }
                notok_ct = Object.keys(notok_json).length;
                ok_ct = Object.keys(ok_json).length;
                //above making notok and ok json objects separately
                
                jsonObj = $.extend(ok_json, notok_json);   //concatenating both json objects into one using jQuery
                for(key in jsonObj) {
                  //console.log(key, jsonObj[key]);
                  if(counter == 0) {
                    beforeOrAfterEnd = 'beforeend';
                    document.getElementById('ins_' + counter).insertAdjacentHTML(beforeOrAfterEnd, '<span id="ins_' + (counter+1) + '" class="text-danger" style="margin-left:-20px;margin-bottom:5px;"><kbd style="background-color:blue;"><b>[' + ok_ct + ']</b></kbd> broken links, <kbd style="background-color:gray;"><b>[' + notok_ct + ']</b></kbd> replaced by x</span><br>');
                    counter++;
                    beforeOrAfterEnd = 'afterend';
                  }
                  tmp = key.split("^");
                  checkbox_name = lbl_name = '';
                  disabled = '';
                  if(tmp.length == 2) {
                    checkbox_name = tmp[0];
                    lbl_name = tmp[0].substr(tmp[0].lastIndexOf("/") + 1);
                    if(tmp[1] == "notok") {
                      lbl_name = '<span style="text-decoration:line-through;color:#ccc">' + lbl_name + '</span>';
                      disabled = 'disabled';
                    }
                  }
                  document.getElementById('ins_' + counter).insertAdjacentHTML(beforeOrAfterEnd, '<label id="ins_' + (counter+1) + '" class="form-check-label col-sm-12" style="word-wrap: break-word;color:' + 'red' + ';"><input class="form-check-input" type="checkbox" name="' + checkbox_name + '^' + (counter+1) + '" id="broken_lnk_' + (counter+1) + '" ' + disabled + '>' + lbl_name + '</label>');
                  counter++;
                }
              }
              document.getElementById("final_form").style.display = "inherit";   //showing the final form now
            }
          },
          complete: function (jqXHR, status) {
            if(status !== "timeout" && status !== "error") {
              //console.log("RAVI_COMPLETE", status);
              if(!needToStop) {
                executeShellScript = "no";   //Do not execute shell script again!
                setTimeout(callMyFunction, 2000);   //call this function again after these many miliseconds
              }
              else {
                document.getElementById("loading_spinner_1").style.display = "none";
                $('#proceed').prop("disabled", false);
                $('#proceed').removeClass("disabled");
                $('#find_broken').prop("disabled", false);
                $('#find_broken').removeClass("disabled");
                document.getElementById("find_broken").innerHTML = "<span id='loading_spinner_1' class='spinner-border spinner-border-sm' role='status' aria-hidden='true' style='display:none;'>&nbsp;</span> Retry Broken <i class='fa fa-chain-broken' aria-hidden='true'></i> Links";
                retryBroken = "yes";
              }
            }
          }
        });
      }
      
      $("#terminate").click(function(){
        needToStop = true;
      });
      
      $("#find_broken").click(function(){
        $(document).ready(function(){
          document.getElementById("ins_0").innerHTML = '';   //removing all children under div id "ins_0"
          document.getElementById("loading_spinner_1").style.display = "inline-block";
          $('#proceed').prop('disabled', true);
          $('#find_broken').prop('disabled', true);
          executeShellScript = "yes";
          needToStop = false;
          beforeOrAfterEnd = 'beforeend';
          retryBroken = document.getElementById("reuse").checked ? "yes" : "no";
          callMyFunction();
        });
      });
      
      function onReuseClick() {
        if(document.getElementById("reuse").checked) {
          document.getElementById("reuse-holder").style.display = "inherit";
          document.getElementById("find_broken").style.display = "inherit";
          document.getElementById("message-holder").style.display = "none";
          $('#proceed').prop("disabled", true);
        } else {
          document.getElementById("reuse-holder").style.display = "none";
          document.getElementById("find_broken").style.display = "none";
          document.getElementById("message-holder").style.display = "inherit";
          $('#proceed').prop("disabled", false);
          $('#proceed').removeClass("disabled");
        }
      }
      
      function validateFormFinal() {
        var c = document.getElementById("final_form").getElementsByTagName('input');
        var checked_count = 0;
        for(var i = 0; i < c.length; i++) {
          if(c[i].type == 'checkbox' && c[i].checked) {
            checked_count++;
          }
        }
        if(checked_count == 0) {
          alert("Kindly select any one link to proceed!");
          return false;
        }
        document.getElementById("loading_spinner_2").style.display = "inline-block";
        $('#proceed').prop('disabled', true);
        $('#find_broken').prop('disabled', true);
        $('#remove_broken').addClass("disabled");
        return true;
      }
      
      function validateForm(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#proceed').addClass("disabled");
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>
