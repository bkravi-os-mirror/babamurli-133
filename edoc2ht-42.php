<?php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  include './util.php';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  //Set below variables ==============================
  $source_doc_file = "$rootdir/0000-Old Daily/02. English/01. Eng Murli - Htm/2016/10-October/English-03-Oct-To-09-Oct.doc";
  $target_folder = "$rootdir/0000-Old Daily/02. English/01. Eng Murli - Htm/2016/10-October";   //All generated files will go here
  $start_date = "2016-10-03";   //MUST BE a valid(as no further check in coding!!) YYYY-MM-DD date. This will be used to generate first file name
  $file_name_postfix = "-E.htm";
  //$separator = "*  *  *  O  M  S  H  A  N  T  I  *  *  *";   //This is the last line in a doc. After this, a new page is considered to be started
  $separator = "* * * O M S H A N T I * * *";   //This is the last line in a doc. After this, a new page is considered to be started
  
  //Set above variables ==============================
  
  $initial_file_name = date("d.m.y", strtotime($start_date)) . "$file_name_postfix"; 
  
  $final = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Brahma Kumaris</title></head>\r\n";
  $final = "$final<body link='#800000' vlink='#800000' bgcolor='#FFEBCC' topmargin='0' leftmargin='0'>\r\n";
  $final = "$final<table cellspacing='2' cellpadding='0' width='980' border='0' style='letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;'>\r\n";
  $final = "$final<tr><td valign='top' width='980'><table width='980'><colgroup><col width='325' style='width: 244pt;'><col width='97' style='width: 73pt;'></colgroup>\r\n";
  $final = "$final<tr><td class='xl26' valign='middle' align='left' width='980' style='border: 0px solid rgb(192, 192, 192);'>\r\n";
  $final = "$final<blockquote>\r\n";
  $final = "$final<span style='font-size:16pt; color:#006600; lang=HI; font-family:Mangal; text-align:justify; line-height:normal'>\r\n";
  $post = "</span></p>";
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {   //"Split Murli PDF" button is clicked
    $message = "Below Results:<ul>";
    if(is_file("$source_doc_file") && filesize("$source_doc_file") > 10) {
      if(!is_dir("$target_folder")) {
        if(!mkdir("$target_folder")) {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> creating directory $target_folder!!</li>";
        }
      }
      if(is_dir("$target_folder")) {   //All OK now. Go ahead...
        $target_txt_file = $target_folder . "/" . onlyFileName($source_doc_file) . ".txt";
        $cmd = "/usr/bin/wvText \"$source_doc_file\" \"$target_txt_file\"";
        shell_exec($cmd);
        if(file_exists("$target_txt_file") && filesize("$target_txt_file") > 10) {   //Doc converted into txt OK
          $text = file_get_contents($target_txt_file);
          $text = preg_replace("/[[:blank:]]+/"," ", $text);
          $text = str_replace("\n ","\n", $text);
          $text = str_replace(" \n","\n", $text);
          $total_murlis_in_doc = substr_count($text, $separator);
          $message = $message . "<li>Total# murlis based on separator: <kbd>$total_murlis_in_doc</kbd> </li>";
          if($total_murlis_in_doc === 7) {
            $murlis_array = explode($separator, $text);
            for($loop = 0; $loop < $total_murlis_in_doc; $loop++) {
              $line_by_line_murli = explode("\n\n", $murlis_array[$loop]);
              $head = date("w", strtotime($start_date)) === 0 ? 
                  "&nbsp;&nbsp;&nbsp;Madhuban&nbsp;&nbsp;&nbsp;Avyakt BapDada&nbsp;&nbsp;&nbsp;Om Shanti"
                  : "&nbsp;&nbsp;&nbsp;Morning Murli&nbsp;&nbsp;&nbsp;Om Shanti&nbsp;&nbsp;&nbsp;BapDada&nbsp;&nbsp;&nbsp;Madhuban";
              $murli_header = "<p align=center><span style='color:red;'>" 
                  . date("d/m/y", strtotime($start_date)) 
                  . "$head$post"
                  . "\n<hr size='1' width='100%' noshade style='color:maroon' align='center'>"
                  . "<span style='color:navy;'>";   //This span to color full html body
              $murli = '';
              $dharna_label_crossed = false; $blessing_label_crossed = false;
              foreach($line_by_line_murli as $idx => $val) {
                $val = preg_replace('/\s+/', ' ', $val);
                if(strpos($val, "Essence:") !== false) {
                  $format = "style='color:blue;'";
                } else if(strpos($val, "Question:") !== false) {
                  $format = "style='color:#800080;'";
                } else if(strpos($val, "Answer:") !== false) {
                  $format = "style='color:#800000;'";
                } else if(strpos($val, "Song:") !== false) {
                  $format = "style='color:#FF0000;'";
                } else if(strpos($val, "Essence for dharna:") !== false) {
                  $format = "style='color:#008000;'";
                  $dharna_label_crossed = true;
                } else if(strpos($val, "Blessing:") !== false) {
                  $format = "style='color:#FF00FF;'";
                  $blessing_label_crossed = true;
                } else if(strpos($val, "Slogan:") !== false) {
                  $format = "style='color:#0000FF;'";
                } else if($blessing_label_crossed){
                  $format = "style='color:#800000;'";
                } else if($dharna_label_crossed){
                  $format = "style='color:blue;'";
                } else {
                  $format = "style='color:navy;'";
                }
                $murli = "$murli<p $format>$val\n";
              }
              $final_murli = "$final$murli_header\r\n<p>$murli</p>\r\n";
              $final_murli = "$final_murli</span></span></blockquote></td></tr></table></td></tr></table></body></html>";
              $start_file_name = date("d.m.y", strtotime($start_date)) . "$file_name_postfix"; 
              if(!file_put_contents("$target_folder/$start_file_name", $final_murli)) {
                $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Creating file $target_folder/$start_file_name!!</li>";
              } else {
                $message = $message . "<li>$start_file_name created <span class='bg-success text-white'>successfully !</span></li>";
              }
              $start_date = addDaysToDate(1, $start_date, "Y-m-d");
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Currently I can process exactly 7 Murli Doc!!</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>"
              . fileWithExt($source_doc_file) . " To " . fileWithExt($target_txt_file) . " conversion failed!!"
              . "</span></li>";
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> can't proceed as $target_folder is not a directory any more!!</li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> $source_doc_file file not found!!</li>";
    }
    $message = "$message</ul>";
  } else {
    $message = "Output Here...";
  }
  
?>
<html lang="en">
  <head>
    <title>Eng-Doc 2 Htm Creator</title>
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
      $brand_name = "Eng-Doc 2 Htm Creator <i class='fa fa-file-word-o' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <div><kbd>Source Doc File: <?php echo "[". str_replace("$rootdir/", "", "$source_doc_file") . "]"; ?></kbd></div>
        <div class="mt-4"><kbd style="background-color:#008000;">Separator: <?php echo "[$separator]"; ?></kbd></div>
        <div class="mt-4"><kbd style="background-color:blue;">Target Dir: <?php echo "[". str_replace("$rootdir/", "", "$target_folder") . "]"; ?></kbd></div>
        <div class="mt-4"><kbd style="background-color:goldenrod;">Initial File: <?php echo "[$initial_file_name]"; ?></kbd></div>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger mt-4" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Click Me If Everything Setup OK
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
    </div>
    <script language="javascript">
      
      function validateForm0(form) {
        document.getElementById("id_card").innerHTML = "<kbd style='background-color:#7160bb;'>Processing started... Kindly wait a moment!</kbd>";
        document.getElementById("initiater").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Initiating Your Request...';
        return true;
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>