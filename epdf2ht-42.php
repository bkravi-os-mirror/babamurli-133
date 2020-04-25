<?php
  //NOTE: DO NOT Run this for a PDF that has more than one Murli merged
  //This program will work only for one murli in one PDF
  //If you have more than one murlis in a PDF, first split that into multiple murlis then run this code
  
  //PDF Parser Reference: https://www.pdfparser.org/documentation?utm_source=GitHub&utm_medium=documentation&utm_campaign=GitHub
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  include './util.php';
  include './libs/vendor-pdfparser/vendor/autoload.php';
  require './libs/vendor-pdf-to-text/autoload.php';  
  use Spatie\PdfToText\Pdf;
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];

  //Set below variables ==============================
  //Note: PDF file locaction must be relative value. As pdfparser libs was failing otherwise
  //01-January, 02-February, 03-March, 04-April, 05-May, 06-June, 07-July, 08-August, 09-September, 10-October
  $pdf_source_files_array = glob("./04.07.17-h.pdf");  
  //$pdf_source_files_array = glob("$rootdir/0000-Old Daily/02. English/02. Eng Murli - Pdf/2013/12-December/*.pdf");
  //$pdf_source_files_array = array(
  //  "../0000-Old Daily/02. English/02. Eng Murli - Pdf/2014/11-November/18.11.14-E.pdf";
  //);
  $htm_destination_dir = "$rootdir/000-Ravi-DontDelete/htms";
  $eof_str = "* * * O M S H A N T I * * *";   //This is the last line in the pdf
  //Set above variables ==============================
  
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
  
  $exclusion_array = array(
    "essence",
    "question",
    "answer",
    "song",
    "blessing",
    "essence for dharna",
    "slogan",
  );
  
  function isAnyofOne($val) {
    global $exclusion_array;
    $val = strtolower($val);
    foreach($exclusion_array as $v) {
      $v = strtolower($v);
      if(strpos($val, $v) !== false) {
        return true;
      }
    }
    return false;
  }
  
  if(isset($_POST['initiater'])) {   //"Click Me If Everything Setup OK" button is clicked
    $selected_pdf = $_POST['pdf'];   //e.g. Full path i.e. /var/www/..../xyz/23.10.20-E.pdf
    $message = "Below Results:<ul>";
    $parser = new \Smalot\PdfParser\Parser();
    $d_lf = "^D_LF^";
    if(file_exists($selected_pdf) && filesize($selected_pdf) > 10) {
      if(!is_dir("$htm_destination_dir")) {
        if(!mkdir("$htm_destination_dir")) {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> creating directory $htm_destination_dir!!</li>";
        }
      }
      if(is_dir("$htm_destination_dir")) {   //All OK now. Go ahead...
        $rWithExt = fileWithExt($selected_pdf);
        $wWithExt = str_replace(".pdf", ".htm", $rWithExt);
        $message = $message . "<li>Processing $rWithExt</li>";
        $text = Pdf::getText("$selected_pdf");
        if(strlen($text) < 100) {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> $rWithExt is not Extractable!!</li>";
        } else {
          if(strlen($rWithExt) < 8) {
            $murli_date = "XX.XX.XX";
          } else {
            $murli_date = substr($rWithExt, 0, 2) . "/" . substr($rWithExt, 3, 2) . "/" . substr($rWithExt, 6, 2);
          }
          if(date("w", strtotime(getAnyFormatDate($murli_date, 'd/m/y', 'Y-m-d'))) == 0) {   //i.e. sunday
            $murli_header = "$murli_date Madhuban Avyakt BapDada Om Shanti";
          } else {
            $murli_header = "$murli_date Morning Murli Om Shanti BapDada Madhuban";
          }
          $all_pages = explode("\f", $text);
          $full_text = '';
          foreach($all_pages as $pg_idx => $text) {
            $text = preg_replace("/[[:blank:]]+/"," ", $text);
            $text = str_replace("\n ","\n", $text);
            $text = str_replace(" \n","\n", $text);
            $tmp_text = explode("\n", $text);
            $ct = count($tmp_text);
            foreach($tmp_text as $idx => $val) {
              if($idx <= 15 || $idx > ($ct - 15)) {   //towards the begining or end of the file
                if(strlen($val) < 15 && !isAnyofOne($val)) {continue;}   //i.e. no need to concatenate
              }
              if(strlen($val) === 0) {$val = "<p>";}   //i.e. its a new line.
              $val = str_replace("Essence:", "<p style='color:blue;'>Essence:", $val);
              $val = str_replace("Question:", "<p style='color:#800080;'>Question:", $val);
              $val = str_replace("Answer:", "<p style='color:#800000;'>Answer:", $val);
              $val = str_replace("Song:", "<p style='color:#FF0000;'>Song:", $val);
              $val = str_replace("Essence for dharna:", "<p style='color:#008000;'>Essence for dharna:", $val);
              $val = str_replace("Blessing:", "<p style='color:#FF00FF;'>Blessing:", $val);
              $val = str_replace("Slogan:", "<p style='color:#0000FF;'>Slogan:", $val);
              $full_text = "$full_text $val ";
            }
          }
          //file_put_contents("$htm_destination_dir/$wWithExt", $full_text);
          //die();
          $murli_header = "<p align=center><span style='color:red;'>" 
              . "$murli_header$post"
              . "\n<hr size='1' width='100%' noshade style='color:maroon' align='center'>"
              . "<span style='color:navy;'>";   //This span to color full html body
          $murli = $full_text;
          $final_murli = "$final$murli_header\r\n<p>$murli</p>\r\n";
          $final_murli = "$final_murli</span></span></blockquote></td></tr></table></td></tr></table></body></html>";
          if(!file_put_contents("$htm_destination_dir/$wWithExt", $final_murli)) {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Creating file $htm_destination_dir/$wWithExt!!</li>";
          } else {
            $message = $message . "<li>$wWithExt created <span class='bg-success text-white'>successfully !</span></li>";
            //echo "$final_murli";
          }
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> can't proceed as $htm_destination_dir is not a directory any more!!</li>";
      }
    } else{
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> PDF $selected_pdf not found!!</li>";
    }
    $message = "$message</ul>";
  } else {
    $message = "Output Here...";
  }

?>
<html lang="en">
  <head>
    <title>Eng-PDF 2 Htm Creator</title>
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
      $brand_name = "Eng-Doc 2 Htm Creator <i class='fa fa-file-pdf-o' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <center>
          <div class="mt-4"><kbd style="background-color:red; font-size:125%;">NOTE: The resulting htm might not be 100% formatted. You might need to format post generation. Alternatively, you could opt <kbd>doc->htm</kbd> generation program which is 100% accurate!</kbd></div>
        </center>
        <div class="mt-4"><kbd style="background-color:blue;">Target Dir: <?php echo "[". str_replace("$rootdir/", "", "$htm_destination_dir") . "]"; ?></kbd></div>
        <div class="mt-4"><kbd style="background-color:#008000;">EOF String: <?php echo "[$eof_str]"; ?></kbd></div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="pdf" class="col-sm-4 col-form-label">Select PDF:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="pdf" name="pdf" style="width:100%;">
              <?php
                foreach($pdf_source_files_array as $pdf_source) {
                  $selected = '';
                  $only_file_nm_ex = fileWithExt($pdf_source);
                  if($pdf_source === $selected_pdf) {$selected = 'selected';}
                  echo "<option value='$pdf_source' $selected>$only_file_nm_ex</option>";
                }
              ?>
            </select>
          </div>
        </div>
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
        document.getElementById("pdf").focus();
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>