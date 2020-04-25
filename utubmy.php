<?php
  
  //https://github.com/ytdl-org/youtube-dl/blob/master/README.md
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');

  //$playlist = "PLJPUJP8Cfhw1uBlSlTPBhkAQt76cvVbO9";   //Full videos
  $playlist = "PLJPUJP8Cfhw2iCcYE_x0LCzYpCi7bQVJ4";   //Small-Whatsapp Status Videos
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $destination_dir = "$rootdir/000-Ravi-DontDelete/umy";   //all resultant/essential output will go here
  $final_result_file = "$destination_dir/finalinfo.txt";
  $my_playlist_videos_info_file = "./utubmyinfo.txt";   //This contanis raw info about all videos in a playlist
  $playlist_info_URL = "https://www.youtube.com/playlist?list=$playlist";
  
  $message = "All results Here...";
  $video_info_array = array();
  
  function getPreparedTitle($raw_title) {
    $title = preg_replace('/[[:^print:]]/', '', $raw_title);   //Removing all non-ascii characters
    $title = trim($title);
    $title = preg_replace("/[[:blank:]]+/", " ", $title);   //removing multiple spaces into a single space
    $title = strlen($title) <= 6 ? "Om Shanti" : $title;
    $title = mb_strimwidth($title, 0, 18, "...");   //add "..." at end and make total string length = 18
    return $title;
  }
  
  function readFileAndPopulateVideoInfoArray($file_to_read) {   //passing array by reference
    global $video_info_array, $destination_dir, $final_result_file;
    try{
      if(!is_dir($destination_dir)) {
        if(!mkdir($destination_dir)) {
          return false;
        }
      }
      if(!is_dir($destination_dir)) {return false;}
      if(!file_exists($file_to_read) || filesize($file_to_read) < 3) {return false;}
      $file = fopen($file_to_read, "r");
      $result_file = fopen("$final_result_file", "w");
      if(!$result_file) {return false;}
      $record_ct = 1;
      shell_exec("rm -f \"$destination_dir\"/*.jpg");   //let's delete all jpg files first
      while(!feof($file)) {
        $row_array = json_decode(fgets($file), true);
        if(isset($row_array) && strpos($row_array["title"], "[Private video]") === false) {   //this means video is not private
          $title = getPreparedTitle($row_array["title"]);
          if(fwrite($result_file, "$record_ct^{$row_array['id']}^$title\n")) {
            $video_info_array[$row_array["id"]] = array("title" => "$title");
            $thumbnail = "https://i.ytimg.com/vi/{$row_array['id']}/mqdefault.jpg";
            file_put_contents("$destination_dir/$record_ct.jpg", file_get_contents($thumbnail));
            $record_ct++;
          } else {
            return false;
          }
        }
      }
    } catch(Exception $e) {
      return false;
    }
    if($result_file) {fclose($result_file);}
    return true;
  }
  
  if(isset($_POST['refresh_list'])) {   //"Process My Youtube" button is clicked
    $message = "Below results:<ul>";
    $youtube_dl_details_cmd = "youtube-dl -j --playlist-reverse --flat-playlist $playlist_info_URL --force-ipv4 > $my_playlist_videos_info_file";
    shell_exec($youtube_dl_details_cmd);
    if(readFileAndPopulateVideoInfoArray($my_playlist_videos_info_file) !== false) {
      $jpg_files = count(glob("$destination_dir/*.jpg"));
      $message = $message . "<li><kbd style='background-color:#008000'>$playlist <b><span style='color:#FF00FF;'>[#" . count($video_info_array) . " video(s), #$jpg_files thumbnail(s)]</span></b></kbd></li><ol>";
      foreach($video_info_array as $key => $val) {
        $thumbnail = "https://i.ytimg.com/vi/$key/mqdefault.jpg";
        $message = $message . "<li><kbd>$key</kbd> {$val['title']} <kbd style='background-color:blue'>Thumbnail $thumbnail</kbd></li>";
      }
      if(count($video_info_array) > 0) {
        $message = "$message</ol>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white>ERROR</span> reading $my_playlist_videos_info_file !!!</li>";
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>My Youtube Utility</title>
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
      $brand_name = 'My Youtube <i class="fa fa-youtube-play" aria-hidden="true"></i>&nbsp;Utility';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);">
        <div class="mt-4"><kbd style="background-color:red;">Reading Playlist: <b><?php echo "$playlist"; ?></b></kbd></div>
        <div class="form-group row" style="margin-top:20px;height:25%;">
          <button type="submit" id="refresh_list" name="refresh_list" class="btn btn-danger" style="width:100%;margin:2%;">
            <span id="loading_spinner_0" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
            Process My Youtube
          </button>
        </div>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      function validateForm0(form) {
        $('#refresh_list').addClass('disabled');
        document.getElementById("refresh_list").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Refreshing Video List...';
        return true;
      }

      function enableFormControls() {
        document.getElementById("loading_spinner_0").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>