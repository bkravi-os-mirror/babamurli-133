<?php
  
  //https://github.com/ytdl-org/youtube-dl/blob/master/README.md
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include './util.php';

  $OSB_Youtube_Channel = 'UC3Zh6bmql06zE0R7zE17abQ';
  //$OSB_Youtube_Playlist = 'PLuNHWYr_UbvvxVXOIXfPB7a7YRgKLf93e';
  $OSB_Youtube_Playlist = 'PLuNHWYr_UbvulmTnRw_oJhZHxJNWg9WRs';
  $osb_all_videos_info = './osb.txt';
  
  $Chintan_Youtube_Channel = 'UCyQ9-moQs_SEDMhacrHLPQQ';
  //$Chintan_Youtube_Playlist = 'PLZ9m1SVuVuYYjSPQm521yGoEkt5pS0Mpv';   //Feb 2020
  //$Chintan_Youtube_Playlist = 'PLZ9m1SVuVuYY7cibzPJTAbF0t02Lt51Pf';   //Mar 2020
  $Chintan_Youtube_Playlist = 'PLZ9m1SVuVuYZpZXEYBQYSmSvnmNm1Bu_H';   //April 2020
  $chintan_all_videos_info = './chintan.txt';
  
  $OSB_Playlist_Info_URL = "https://www.youtube.com/playlist?list=$OSB_Youtube_Playlist";
  $Chintan_Playlist_Info_URL = "https://www.youtube.com/playlist?list=$Chintan_Youtube_Playlist";
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $message = '';
  $form0_display = 'block';
  $form1_display = 'none';
  $form2_display = 'none';
  $card_display = 'none';
  
  $location_array = array(
    "MCSB_LOC" => "$rootdir/01. Daily Murli/01. Hindi/13. Murli Chintan - Suraj Bhai",
    "OSB3_LOC" => "$rootdir/01. Daily Murli/01. Hindi/09. Hindi Murli - OSB - MP3",
    "OSB4_LOC" => "$rootdir/01. Daily Murli/01. Hindi/08. Hindi Murli - OSB - MP4",
    "ALL43_LOC" => "$rootdir/000-Ravi-DontDelete/udwnlds",
    "ALL4_LOC" => "$rootdir/000-Ravi-DontDelete/udwnlds",
  );
  
  $postfix_array = array(
    "MCSB_POSTFX" => "-Murli Chintan.mp4",   //for download purpose, using .mp4. youtube-dl will convert that into mp3
    "OSB3_POSTFX" => "-OSB.mp4",   //for download purpose, using .mp4. youtube-dl will convert that into mp3
    "OSB4_POSTFX" => "-OSB.mp4",
    "ALL43_POSTFX" => "-My.mp4",   //for download purpose, using .mp4. youtube-dl will convert that into mp3
    "ALL4_POSTFX" => "-My.mp4",
  );
  
  $curr_date = date("Y-m-d");
  $curr_date_1 = strtotime($curr_date);
  
  $dates = array(
    date('d.m.y', strtotime("+4 day", $curr_date_1)),
    date('d.m.y', strtotime("+3 day", $curr_date_1)),
    date('d.m.y', strtotime("+2 day", $curr_date_1)),
    date('d.m.y', strtotime("+1 day", $curr_date_1)),
    "^" . date('d.m.y', strtotime("+0 day", $curr_date_1)),   //special character at the begininng of the current date
    date('d.m.y', strtotime("-1 day", $curr_date_1)),
    date('d.m.y', strtotime("-2 day", $curr_date_1)),
    date('d.m.y', strtotime("-3 day", $curr_date_1)),
    date('d.m.y', strtotime("-4 day", $curr_date_1)),
    date('d.m.y', strtotime("-5 day", $curr_date_1)),
    date('d.m.y', strtotime("-6 day", $curr_date_1)),
  );
  
  function startsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), 0, strlen($needle)) === 0;
  }
  
  function endsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
  }  
  
  $video_info = array("id" => "", "fulltitle" => "", "format" => "", "filesize" => 0, "format_id" => "", "ext" => "");
  
  $osb_video_array = array();
  $chintan_video_array = array();
  
  function readFileAndPopulateVideoArray($file_to_read, &$array_to_populate) {   //passing array by reference
    try{
      $max_rows_to_read = 5;
      $loop = 1;
      $file = fopen($file_to_read, "r");
      while(!feof($file) && $loop <= $max_rows_to_read) {
        $row_array = json_decode(fgets($file), true);
        if(isset($row_array) && strpos($row_array["title"], "[Private video]") === false) {   //this means video is not provate
          $array_to_populate[$row_array["id"]] = array("title" => $row_array["title"]);
          $loop++;
        }
      }
    } catch(Exception $e) {
    }
  }
  
  function getNameFromTitle($title) {
    $title = trim($title);
    $title = preg_replace('!\s+!', ' ', $title);   //replace multiple spaces into single space
    $title = removeEmojis($title);
    $title = removeNonPrintableChars($title);
    $title = str_replace("&", "and", $title);   //otherwise it may interrupt GET query string down below
    $title = str_replace("?", "", $title);   //otherwise it may interrupt GET query string down below
    $title = trim($title);
    if(strlen($title) > 30) {
      $title = substr($title, 0, 30);
    }
    else if(strlen($title) < 5) {
      $title = "custom-" . strtotime(date('Y-m-d'));
    }
    return $title;
  }
  
  $full_file_path = '';
  $selected_option = '';
  
  if(isset($_POST['refresh_list'])) {   //this means the form0 submit button is clicked
    if(!(isset($_POST['skip_refresh_list']) && $_POST['skip_refresh_list'] === "on")) {   //i.e. dont do below activity if skip_refresh_list checkbox is checked
      $youtube_dl_osb_playlist_cmd = "youtube-dl -j --playlist-reverse --flat-playlist $OSB_Playlist_Info_URL --force-ipv4 > $osb_all_videos_info";
      $youtube_dl_chintan_playlist_cmd = "youtube-dl -j --playlist-reverse --flat-playlist $Chintan_Playlist_Info_URL --force-ipv4 > $chintan_all_videos_info";
      shell_exec($youtube_dl_osb_playlist_cmd);
      shell_exec($youtube_dl_chintan_playlist_cmd);
    }
    readFileAndPopulateVideoArray($osb_all_videos_info, $osb_video_array);
    readFileAndPopulateVideoArray($chintan_all_videos_info, $chintan_video_array);
    $form0_display = 'none';
    $form1_display = 'block';
    $form2_display = 'none';
    $card_display = 'block';
  }
  else if(isset($_POST['proceed'])) {   //this means proceed button on form is pressed i.e. "Show Available Files" button
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $form0_display = 'none';
    $form1_display = 'none';
    $form2_display = 'block';
    $card_display = 'block';
    
    $message = '<ul>';
    if(!empty($_POST['youtube_link'])) {
      $youtube_URL = $_POST['youtube_link'];   //https://www.youtube.com/watch?v=AKka2XEkZ-4 OR https://youtu.be/oXk7DCh97oA
      $tmp_v = explode("/", $youtube_URL);
      $vid = end($tmp_v);
      $vid = str_replace("watch?v=", "", $vid);   //e.g. watch?v=AKka2XEkZ-4
      $vid = str_replace("v=", "", $vid);   //e.g. v=AKka2XEkZ-4
      $tmp = explode("^", $_POST['file_dir']);   //"OSB3_LOC^OSB3_POSTFX"
      $target_dir = $location_array[$tmp[0]];
      //below I am overwriting filename if its a general video i.e. neither OSB nor Chintan
      $final_filename = $_POST['date_for'] . $postfix_array[$tmp[1]];
      $full_file_path = "$target_dir/$final_filename";
      $full_file_path = str_replace(".", "^", $full_file_path);   //because dring POST, '.' is changed to '_'. So to avoid that
      $selected_option = $_POST['file_dir'];   //e.g. OSB3_LOC^OSB3_POSTFX
      $youtube_dl_getjson_cmd = "youtube-dl -s https://www.youtube.com/watch?v=$vid --force-ipv4 -j";
      $result_json = shell_exec($youtube_dl_getjson_cmd);
      $result_array = json_decode($result_json, true);
      $result_array = is_null($result_array) ? array() : $result_array;
      $counter = 0;
      $required_ext = endsWith($final_filename, "mp3") ? "m4a" : "mp4";
      if(array_key_exists("formats", $result_array) && array_key_exists("id", $result_array) && array_key_exists("fulltitle", $result_array)){
        //below overwriting filename in case if we have general video to download
        if(startsWith($_POST['file_dir'], "ALL")) {
          $final_filename = getNameFromTitle($result_array['fulltitle']) . "." . onlyExt($final_filename);
          $full_file_path = "$target_dir/$final_filename";
          $full_file_path = str_replace(".", "^", $full_file_path);   //because dring POST, '.' is changed to '_'. So to avoid that
        }
        foreach($result_array["formats"] as $format) {
          if(array_key_exists("ext", $format) && array_key_exists("format", $format) && array_key_exists("format_id", $format)) {
            if($format['ext'] === $required_ext) {
              if(($required_ext === "m4a" && $format['acodec'] !== "none" && $format['vcodec'] === "none") ||   //its an audio only
                ($required_ext === "mp4" && $format['acodec'] !== "none")   //if acodec is "none", its a video with no audio
              ) {
                  $video_info[$counter] = array(
                    "id" => $result_array['id'], 
                    "fulltitle" => $result_array['fulltitle'], 
                    "format" => $format['format'], 
                    "filesize" => (array_key_exists("filesize", $format) && $format['filesize'] > 10) ? $format['filesize'] : 0,
                    "format_id" => $format['format_id'], 
                    "ext" => $format['ext']
                  );
                $counter++;
              }
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>Some of the key(s){ext:{$format['ext']}, format:{$format['format']}, format_id:{$format['format_id']}} not found in json - Inner Failed!<br><br>Command: $youtube_dl_getjson_cmd</span></li>";
          }
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>Some of the key(s){formats:{$result_array['formats']}, id:{$result_array['id']}, fulltitle:{$result_array['fulltitle']}} not found in json - Outer failed!<br><br>Command: $youtube_dl_getjson_cmd</span></li>";
      }
      $message = $message . "<ul>";
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>Youtube video URL can not be blank!</span></li>";
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>Youtube Downloader - OSB - Chintan</title>
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
      $brand_name = '<i class="fa fa-youtube-play" aria-hidden="true"></i>&nbsp;OSB & Murli Chintan';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" <?php echo "style='display:$form0_display;'"; ?>>
        <div class="form-group row" style="margin-top:10px;">
          <label class="form-check-label" style="margin-left:45px;">
            <input class="form-check-input" type="checkbox" id="skip_refresh_list" name="skip_refresh_list">
            <u>Skip Refresing Video List</u> (This is useful when downloading general video/audio)
          </label>
        </div>
        <div class="form-group row" style="margin-top:10px;height:25%;">
          <button type="submit" id="refresh_list" name="refresh_list" class="btn btn-danger" style="width:100%;margin:2%;">
            <span id="loading_spinner_0" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
            Click To Refresh Video List & Proceed
          </button>
        </div>
      </form>
      <form method="post" action="" onsubmit="return validateForm1(this);" <?php echo "style='display:$form1_display;'"; ?>>
        <div class="form-group row" style="margin-top:10px;">
          <label for="file_dir" class="col-sm-4 col-form-label">File Type: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="file_dir" name="file_dir" onchange="showAppropriateHint()">
              <option value="MCSB_LOC^MCSB_POSTFX">Murli Chintan MP3 - Suraj Bhai</option>
              <option value="OSB4_LOC^OSB4_POSTFX">OSB Murli MP4/MP3</option>
              <option value="ALL43_LOC^ALL43_POSTFX">Any Video MP4/MP3</option>
              <option value="ALL4_LOC^ALL4_POSTFX">Any Video MP4</option>
            </select>
          </div>
        </div>
        <div class="form-group row" id="for_chintan" style="display:;">
          <label for="hint_video_chintan" class="col-sm-4 col-form-label">Hint Video: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="hint_video_chintan" name="hint_video_chintan" style="width:100%;" onclick="setRestURL()">
              <?php
                foreach($chintan_video_array as $key => $value) {
                  if($key) {
                    $val = $value["title"];
                    echo "<option value='$key' $selected>$val</option>";
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" id="for_osb" style="display:none;">
          <label for="hint_video_osb" class="col-sm-4 col-form-label">Hint Video: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="hint_video_osb" name="hint_video_osb" style="width:80%;" onclick="setRestURL()">
              <?php
                foreach($osb_video_array as $key => $value) {
                  if($key) {
                    $val = $value["title"];
                    echo "<option value='$key' $selected>$val</option>";
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" id="for_date" style="display:;">
          <label for="date_for" class="col-sm-4 col-form-label">For Date: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="date_for" name="date_for" style="width:120px;">
              <?php
                foreach($dates as $date) {
                  $selected = '';
                  if(startsWith($date, '^')) {$selected = 'selected'; $date = substr($date, 1);}
                  echo "<option value='$date' $selected>$date</option>";
                }
              ?>
            </select>
            <label class="form-check-label" style="margin-left:40px;">
              <input class="form-check-input" type="checkbox" id="hint_checkbox" onclick="setRestURL()">Use Hint
            </label>
          </div>
        </div>
        <div class="form-group row">
          <label for="youtube_link" class="col-sm-4 col-form-label">Youtube Link: </label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="youtube_link" name="youtube_link" style="width:100%;" placeholder="Youtube video URL">
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Show Available Files
        </button>
      </form>
      <span <?php echo "style='display:$form2_display;'"; ?>>
        <h5>Title: <span class="bg-success text-white m-2" style="line-height:40px;padding:8px;">
          <?php echo $counter > 0 ? $video_info[0]["fulltitle"] : 'ERROR in fetching video. Not a single video found!'; ?></span>
        </h5>
        <?php 
          if($counter > 0) {
            echo "<img src='https://i.ytimg.com/vi/{$video_info[0]['id']}/mqdefault.jpg'><br>";
          }
        ?>
        <div class="form-group row">
          <label for="choose_for" class="col-sm-4 col-form-label">Choose One: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="choose_for" name="choose_for">
              <?php
                for($loop = 0; $loop < $counter; $loop++) {
                  //$fmt_fsz = $video_info[$loop]["format"] . '(' . round($video_info[$loop]["filesize"]/(1024*1024), 2) . ' MB)';
                  $fsz = array_key_exists("filesize", $video_info[$loop]) ? round($video_info[$loop]["filesize"]/(1024*1024), 2) : "N/A";
                  $fmt_fsz = $video_info[$loop]["format"] . '(' . $fsz . ' MB)';
                  $fmtid = $video_info[$loop]["format_id"];
                  $vid = $video_info[$loop]["id"];
                  echo "<option value='$vid^$fmtid'>$fmt_fsz</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <input type="hidden" id="full_file_path_hidden" name="full_file_path_hidden" <?php echo "value='$full_file_path'"; ?>>
        <input type="hidden" id="selected_option_hidden" name="selected_option_hidden" <?php echo "value='$selected_option'"; ?>>
        <button type="submit" id="download_upload" name="download_upload" class="btn btn-primary" style="width:100%;">
          <span id="loading_spinner_2" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Download From <span style="color:red;"><i class="fa fa-youtube-play" aria-hidden="true"></i></span> & Upload
        </button>
      </span>
      <div class="card bg-info text-white mt-3" <?php echo "style='display:$card_display;'"; ?>>
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
      <button id="terminate" name="terminate" class="btn btn-danger mt-3" style='position: fixed;bottom: 3px;left: 3px;display:none;'>
        Click To Terminate
      </button>
    </div>
    <script language="javascript">
      
      var choosen_hint_Video = '';
      var timeOut; //How frequently need to refresh
      var needToStop = false;
      var executeShellScript = "yes";   //GET param to set. This setting will execute curl system shell script for the first time
      var videoID = '', outputDir = '', outputFileOnlyWithoutExt = '', formatCode = '', whatType = '';
      
      function setRestURL() {
        if(document.getElementById("file_dir").value == "MCSB_LOC^MCSB_POSTFX") {
          choosen_hint_Video = document.getElementById("hint_video_chintan").value;
        } else if((document.getElementById("file_dir").value == "OSB3_LOC^OSB3_POSTFX") || 
          (document.getElementById("file_dir").value == "OSB4_LOC^OSB4_POSTFX")) {
          choosen_hint_Video = document.getElementById("hint_video_osb").value;
        } else {
          choosen_hint_Video = '';
        }
        a = "https://www.youtube.com/watch?v=" + choosen_hint_Video;
        document.getElementById("youtube_link").value = document.getElementById("hint_checkbox").checked ? a : '';
      }
      
      function showAppropriateHint() {
        if(document.getElementById("file_dir").value == "MCSB_LOC^MCSB_POSTFX") {   //means Murli chintan is selected
          document.getElementById("for_chintan").style.display = "";
          document.getElementById("for_osb").style.display = "none";
          document.getElementById("for_date").style.display = "";
          choosen_hint_Video = document.getElementById("hint_video_chintan").value;
        } else if((document.getElementById("file_dir").value == "OSB3_LOC^OSB3_POSTFX") ||
          (document.getElementById("file_dir").value == "OSB4_LOC^OSB4_POSTFX")) {
          document.getElementById("for_chintan").style.display = "none";
          document.getElementById("for_osb").style.display = "";
          document.getElementById("for_date").style.display = "";
          choosen_hint_Video = document.getElementById("hint_video_osb").value;
        } else {
          document.getElementById("for_chintan").style.display = "none";
          document.getElementById("for_osb").style.display = "none";
          document.getElementById("for_date").style.display = "none";
          choosen_hint_Video = '';
        }
        a = "https://www.youtube.com/watch?v=" + choosen_hint_Video;
        document.getElementById("youtube_link").value = document.getElementById("hint_checkbox").checked ? a : '';
      }
      
      function validateForm0(form) {
        $('#refresh_list').addClass('disabled');
        document.getElementById("refresh_list").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Refreshing Video List...';
        return true;
      }
      
      function validateForm1(form) {
        val = document.getElementById("youtube_link").value;
        if(val.length < 15 || !(val.startsWith("http")) || !(val.includes("/"))) {
          alert('Invalid Youtube video URL!!');
          return false;
        } else {
          document.getElementById("proceed").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Converting...';
          disableFormControls();
          return true;
        }
      }
      
      function disableTerminate() {   //i.e. hide terminate button, spinner etc and make button clickable
        document.getElementById("loading_spinner_2").style.display = "none";
        document.getElementById("terminate").style.display = "none";
        $('#download_upload').prop("disabled", false);
        $('#download_upload').removeClass("disabled");
      }
      
      function enableTerminate() {   //i.e. show terminate button, spinner etc and make button non-clickable
        document.getElementById("loading_spinner_2").style.display = "inherit";
        document.getElementById("terminate").style.display = "inherit";
        $('#download_upload').prop('disabled', true);
      }
      
      function prepareMsgToShow(jsonObj) {
        msg_to_print = 'Below Progress<br><blockquote style="margin-left:10px;color:greenyellow;">';
        if(jsonObj.hasOwnProperty('msg'))
          msg_to_print = msg_to_print + jsonObj["msg"];
        msg_to_print = msg_to_print + "</blockquote>";
        return msg_to_print;
      }
      
      var callMyFunction = function() {
        $.ajax({
          dataType: "json",
          url: "http://www.babamurli.com/000-Ravi-DontDelete/ytd-p1.php?execshell=" + executeShellScript + "&videoID=" + videoID + "&outputDir=" + outputDir + "&outputFileOnlyWithoutExt=" + outputFileOnlyWithoutExt + "&formatCode=" + formatCode + "&whatType=" + whatType,
          timeout: 10000,
          error: function (xhr, status, error) {
            if (status === "timeout" || status === "error") {
              console.log("RAVI_ERROR", status, error);
              alert("Error: " + status + error);
              disableTerminate();
            }
          },
          success: function (msg) {
            //console.log("RAVI_SUCCESS", msg);
            jsonObj = JSON.parse(JSON.stringify(msg));
            if(jsonObj.hasOwnProperty('start') && jsonObj.hasOwnProperty('end')) {
              needToStop = true;   //we have received a valid json data hence no more call to it
            }
            msg_sh = prepareMsgToShow(jsonObj);
            document.getElementById("id_card").innerHTML = msg_sh;
            //console.log(msg_sh);
          },
          complete: function (jqXHR, status) {
            if(status !== "timeout" && status !== "error") {
              //console.log("RAVI_COMPLETE", status);
              if(!needToStop) {
                executeShellScript = "no";   //Do not execute shell script again!
                setTimeout(callMyFunction, 3000);   //call this function again after these many miliseconds
              }
              else {
                disableTerminate();
              }
            }
          }
        });
      }
      
      $("#download_upload").click(function(){
        $(document).ready(function(){
          enableTerminate();
          executeShellScript = "yes";
          needToStop = false;
          tmp = document.getElementById("choose_for").value.split("^");   //VIDEOID^VIDEOFORMAT
          videoID = tmp[0];
          formatCode = tmp[1];
          tmp = document.getElementById("full_file_path_hidden").value.replace(/\^/g, "\.");   ///var/www/.../00^ Htm/abc.mp4
          outputDir = tmp.substring(0, tmp.lastIndexOf("/"));
          tmp = tmp.substring(tmp.lastIndexOf("/") + 1);
          outputFileOnlyWithoutExt = tmp.substring(0, tmp.lastIndexOf("."));
          whatType = outputFileOnlyWithoutExt.toLowerCase().includes("chintan") ? 1 : 3;   //i.e. if murli chintan then, only MP3. else both MP3 & MP4
          selOpt = document.getElementById("selected_option_hidden").value;
          if(selOpt.startsWith("ALL43_")) {   //i.e. intend to download both mp4 and mp3
            whatType = 3;
          }
          if(selOpt.startsWith("ALL4_")) {   //i.e. intend to download only mp4
            whatType = 2;
          }
          callMyFunction();
        });
      });
      
      $("#terminate").click(function(){
        needToStop = true;
        disableTerminate();
      });
      
      function disableFormControls() {
        $('#proceed').addClass('disabled');
        $('#download_upload').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner_0").style.display = "none";
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("loading_spinner_2").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>