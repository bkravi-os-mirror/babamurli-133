<!DOCTYPE html>
<?php
  session_start();
  $_SESSION['token'] = 16108;
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  $titles_primary = array(   //CAUTION: last element of this row should not have link_display_flag as none 
      "<i class='fa fa-cloud-download' aria-hidden='true'></i> From <mark>Ravi Mirror <i class='fa fa-google'></i></mark> And Move To Server" => "eml.php",
      "<i class='fa fa-cloud-download' aria-hidden='true'></i> <i class='fa fa-music' aria-hidden='true'></i> From <mark>madhubanmurli.org</mark> And Move To Server" => "mm3.php",
      "<i class='fa fa-cloud-download' aria-hidden='true'></i> <i class='fa fa-file-pdf-o' aria-hidden='true'></i> From <mark>madhubanmurli.org</mark> And Move To Server" => "mmf.php",
      "<i class='fa fa-cloud-download' aria-hidden='true'></i> OSB & Murli Chintan <mark>From <i class='fa fa-youtube-play' style='color:red;' aria-hidden='true'></i>&nbsp;</mark> And Upload To Server" => "ytd.php",
      "Create(& or Move) <mark>Advance HTMs</mark> <i class='fa fa-file-code-o' aria-hidden='true'></i> (e.g. for /00. Htm etc.)" => "adv.php",
      "<i class='fa fa-cloud-download' aria-hidden='true'></i> <mark>Tushar Bhai</mark> Files From <mark>Ravi Mirror <i class='fa fa-google'></i></mark> And Move To Server" => "tush.php",
    );
    
  $titles_secondary = array(   //CAUTION: last element of this row should not have link_display_flag as none 
      "<mark>Find Broken</mark> <i class='fa fa-chain-broken' aria-hidden='true'></i> Links" => "xenu.php",
      "Fix Broken <i class='fa fa-chain-broken' aria-hidden='true'></i> By Putting 'x'" => "repx.php",
      "<mark>Remove x</mark> And Place Link <i class='fa fa-link' aria-hidden='true'></i>" => "addl.php",
      "HTMs Move From Adv Folders <i class='fa fa-reply-all' aria-hidden='true'></i> (e.g. from /00. Htm/18.02 etc.)" => "mh.php",
      "<mark>Remove Previous Month's Row</mark> <i class='fa fa-retweet' aria-hidden='true'></i>" => "remr.php",
    );
    
  $titles_misc = array(   //CAUTION: last element of this row should not have link_display_flag as none 
      "Emergency <mark><i class='fa fa-ambulance' aria-hidden='true'></i> Murli HTM Creator</mark>" => "mmrl.php",
      "Ravi's <mark>Font <i class='fa fa-font' aria-hidden='true'></i></mark> Converter" => "atou.php",
      "<mark>French Murli <i class='fa fa-language' aria-hidden='true'></i></mark> Sender" => "freml.php",
      "<mark>English & Hindi Murli <i class='fa fa-trophy' aria-hidden='true'></i></mark> Sender" => "eheml.php",
      "<mark>Sindhi Murli Splitter <i class='fa fa-gavel' aria-hidden='true'></i></mark> and Move" => "sind.php",
      "<mark>Any Murli Splitter <i class='fa fa-scissors' aria-hidden='true'></i></mark> and Move" => "spltfany.php",
      "Go-To <i class='fa fa-external-link' aria-hidden='true'></i> <mark>Madhuban Murli ORG<mark>" => "https://murli.brahmakumaris.org/iservices/murlis/Download.do",
      "<mark><i class='fa fa-bookmark' aria-hidden='true'></i> Dost</mark> Thought Generator" => "thoteml.php",
      "PHP <mark style='color:crimson;'>error_log <i class='fa fa-info-circle' aria-hidden='true'></i></mark> Viewer" => "errphp.php",
      "<mark>Jewels <i class='fa fa-diamond' aria-hidden='true'></i></mark> Murli Downloader" => "jwdn.php",
    );
    
  $link_display_flag = array(
      "adv.php" => "block", "mh.php" => "none", "mm3.php" => "block", "remr.php" => "block", "mmf.php" => "block",
      "eml.php" => "block", "repx.php" => "none", "xenu.php" => "block", "ytd.php" => "block", "tush.php" => "block",
      "mmrl.php" => "block", "addl.php" => "block", "atou.php" => "block", "freml.php" => "block", "eheml.php" => "block",
      "sind.php" => "block", "https://murli.brahmakumaris.org/iservices/murlis/Download.do" => "block",
      "thoteml.php" => "block", "errphp.php" => "block", "spltfany.php" => "block", "jwdn.php" => "block",
    );
    
  $button_classes = array("btn btn-primary", "btn btn-success text-white", "btn btn-info",
      "btn btn-warning text-white", "btn btn-danger text-white", "btn btn-dark text-white", "btn btn-light",
    );  
?>

<html lang="en">
  <head>
    <title>bkdrluhar.com utility</title>
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
    <nav class="navbar navbar-light fixed-top" style="background-color: #e3f2fd;">
      <a class="navbar-brand" href="#">
        <img src="images/bks/sb_72x72.png" width="30" height="30" class="d-inline-block align-top mr-0" alt="">
        <span class="mb-0" style="font-size: 17px;">Utilities for bkdrluhar.com</span>
      </a>
    </nav>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <h4 class="text-danger">Utilities for bkdrluhar.com</h4>
      <div class="card bg-secondary text-white mt-3">
        <div class="card-body">
          <center style="margin-bottom:15px;"><h5>Primary Card</h5></center>
          <?php
            $loop = 0;
            $ct = count($button_classes);
            foreach($titles_primary as $key => $value) {
              $cls = $button_classes[$loop % $ct];
              $display = "display:" . $link_display_flag[$value];
              $misc = '';
              if($display !== "none") {
                echo "<a href='$value' $misc class='$cls' style='width:100%; $display;'>";
                  echo $key;
                echo "</a>";
                if($loop < (count($titles_primary) - 1)) echo "<div class='mt-4' style='$display;'></div>";
                $loop++;
              }
            }
          ?>
        </div>
      </div>
      <center><img src="images/bks/sb_72x72.png" class="mt-3"></center>
      <div class="card text-white mt-3" style="background-color:#a7b784;">
        <div class="card-body">
          <center style="margin-bottom:15px;"><h5>Secondary Card</h5></center>
          <?php
            $loop = 0;
            $ct = count($button_classes);
            foreach($titles_secondary as $key => $value) {
              $cls = $button_classes[$loop % $ct];
              $display = "display:" . $link_display_flag[$value];
              $misc = '';
              if($display !== "none") {
                echo "<a href='$value' $misc class='$cls' style='width:100%; $display;'>";
                  echo $key;
                echo "</a>";
                if($loop < (count($titles_secondary) - 1)) echo "<div class='mt-4' style='$display;'></div>";
                $loop++;
              }
            }
          ?>
        </div>
      </div>
      <center><img src="images/bks/sb_72x72.png" class="mt-3"></center>
      <div class="card text-white mt-3" style="background-color:#936a94;">
        <div class="card-body">
          <center style="margin-bottom:15px;"><h5>Miscellaneous Card</h5></center>
          <?php
            $loop = 0;
            $ct = count($button_classes);
            foreach($titles_misc as $key => $value) {
              $cls = $button_classes[$loop % $ct];
              $display = "display:" . $link_display_flag[$value];
              $misc = '';
              if($display !== "none") {
                if($value === "https://murli.brahmakumaris.org/iservices/murlis/Download.do")
                  $misc = "target='_blank'";
                echo "<a href='$value' $misc class='$cls' style='width:100%; $display;'>";
                  echo $key;
                echo "</a>";
                if($loop < (count($titles_misc) - 1)) echo "<div class='mt-4' style='$display;'></div>";
                $loop++;
              }
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>