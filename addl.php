<!DOCTYPE html>
<?php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include 'addl-p1.php';   //all arrays and variables are declared here
  
  $message = '';
  
  function addDaysToDate($days_to_add, $string_date, $date_fmt, $date_return_fmt="") {   //NOTE: $string_date must be in $date_fmt format. otherwise you won't get expected results
    $date_return_fmt = empty($date_return_fmt) ? $date_fmt : $date_return_fmt;
    $date_obj = datetime::createfromformat($date_fmt, $string_date);
    date_add($date_obj, date_interval_create_from_date_string("$days_to_add days"));
    return date_format($date_obj, $date_return_fmt);
  }
  
  $start_from = addDaysToDate(1, date('d.m.y'), 'd.m.y', 'd.m.y');
  for($i = 0; $i < 59; $i++) {
    $dates[$i] = addDaysToDate($i*-1, $start_from, 'd.m.y', 'd.m.y');
  }
  
  $choosen_date = date('d.m.y');
  $choosen_lang = '';
  $choosen_opt = '';
  $verify_display = 'none';
  if(isset($_POST['replace'])) {   //"Remove x And Place Link" button clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "Below Results<ul style='line-height:1.69rem;'>";
    if(isset($_POST['fordate']) && !empty($_POST['fordate']) &&
      isset($_POST['lang']) && !empty($_POST['lang']) &&
      isset($_POST['opts']) && !empty($_POST['opts'])
      ) {
      $choosen_date = str_replace("^", ".", $_POST['fordate']);
      $choosen_lang = $_POST['lang'];
      $choosen_opt = $_POST['opts'];
      $file_to_process = $file_source_file_array[$choosen_opt];
      $new_link = str_replace(" ", "%20", $file_dir_array[$choosen_opt]) . "/$choosen_date{$file_postfix_array[$choosen_opt]}";
      $col = $file_col_array[$choosen_opt];
      $message = $message . "<li>File to process: <mark> $file_to_process </mark></li>";
      $message = $message . "<li>New link to add: <mark> $new_link </mark>&nbsp;&nbsp;<button onclick='copyClip()' type='button' class='btn btn-warning btn-sm' style='line-height:1.1rem;'>Copy</button></li>";
      $message = $message . "<li>Column no. to change: <mark> $col </mark></li>";
      $verify_display = 'inherit';
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>In valid selection! Please select correct options!</span></li>";
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>Xenu Link Adder</title>
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
      $brand_name = 'Add Link <i class="fa fa-link" aria-hidden="true"></i> Replacing x';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return preProcess(this);">
        <div class="form-group row">
          <label for="fordate" class="col-sm-4 col-form-label">Choose Date: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="fordate" name="fordate" style="width:100%">
              <?php
                foreach($dates as $date) {
                  $val = str_replace(".", "^", $date);
                  $selected = $choosen_date === $date ? "selected" : "";
                  echo "<option value='$val' $selected>$date</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="lang" class="col-sm-4 col-form-label">Choose Language: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="lang" name="lang" style="width:100%" onchange="onLanguageSelection()">
              <?php
                foreach($languages as $lang) {
                  $selected = $choosen_lang === $lang ? "selected" : "";
                  echo "<option value='$lang' $selected>$lang</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="opts" class="col-sm-4 col-form-label">Language Options: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="opts" name="opts" style="width:100%">
            </select>
          </div>
        </div>
        <input type="hidden" id="choosen_opt" <?php echo " value='$choosen_opt'"; ?>>
        <input type="hidden" id="new_link" <?php echo " value='$new_link'"; ?>>
        <input type="hidden" id="col" <?php echo " value='$col'"; ?>>
        <input type="hidden" id="file_to_process" <?php echo " value='$file_to_process'"; ?>>
        <input type="hidden" id="choosen_date" <?php echo " value='$choosen_date'"; ?>>
        <button type="submit" id="replace" name="replace" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Remove x And Place Link <i class="fa fa-link" aria-hidden="true"></i>
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
      <button id="verify" name="verify" class="btn btn-success mt-3" onclick="verifyAndProcess()" <?php echo "style='width:100%;display:$verify_display;'"; ?>>
        <span id="loading_spinner_1" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
        I Verified The Link! Go Ahead!!
      </button>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
      Go Home
      </a>
    </div>
    <script language="javascript">
      
      var lang_options_array = {
        "Assame"          : ["Assame_Murli_Htm", "Assame_Murli_Pdf"],
        "Bengali"         : ["Bengali_Murli_Htm", "Bengali_Murli_Pdf"],
        "Chinese"         : ["MP3_Chinese"],
        "Deutsch"         : ["Htm_Deutsch", "PDF_Deutsch", "Mp3_Deutsch"],
        "English"         : ["Eng_Murli_Htm", "Eng_Murli_Pdf", "Eng_Murli_Ess_SMS", "Eng_Murli_MP3_UK", "Eng_Murli_Ess_MP3_UK",
                            "Eng_Murli_MP3_2", "Eng_Murli_Ess_MP3", "Eng_Murli_Hindi_Words_Amola", "Eng_Murli_Vardan_jpg",
                            "Eng_Murli_Vardan2_jpg", "Eng_Murli_Swaman_jpg", "Eng_Today_Calendar", "Eng_Todays_Thought"
                            ],
        "French"          : ["Htm_French", "PDF_French", "Mp3_French"],
        "Greek"           : ["Htm_Greek", "PDF_Greek"],
        "Gujarati"        : ["Gujarati_Murli_Htm", "Gujarati_Murli_PDF", "Gujarati_Murli_Mp3"],
        "Hindi"           : ["Hindi_Murli_Htm", "Hindi_Murli_Pdf", "Hindi_Murli_MP3", "Hindi_Murli_MP3_2", "Hindi_Murli_Saar_MP3",
                            "Hindi_Murli_Saar_MP3_2", "Hindi_Murli_Saar_SMS", "Hindi_Murli_OSB_MP3", "Hindi_Murli_OSB_MP4",
                            "Hindi_Murli_Mumbai", "Murli_Preeti_Bahen", "Murli_Chintan_Suraj_Bhai_H", "Murli_Swaman_jpg",
                            "Murli_Vardan_jpg", "Murli_Vardan_2jpg", "Murli_Chart_Htm", "Murli_Chart_pdf"
                            ],
        "Hungarian"       : ["Htm_Hungarian", "PDF_Hungarian"],
        "Italiano"        : ["Htm_Italiano", "PDF_Italiano", "Mp3_Italiano"],
        "Kannada"         : ["Kannada_Murli_Htm", "Kannada_Murli_Pdf", "Kannada_Murli_MP3", "Kannada_Murli_V2_MP3",
                            "Kannada_Murli_Ess_MP3", "Hindi_To_Kannada_Murli_Mp3", "Kannada_AKP"
                            ],
        "Korean"          : ["Htm_Korian", "PDF_Korian"],
        "Malayalam"       : ["Malayalam_Murli_Htm", "Malayalam_Murli_Pdf", "Malayalam_Murli_MP3"],
        "Marathi"         : ["MarathiMurli-Htm", "MarathiMurli-PDF", "MarathiMurli-Mp3"],
        "Nepali"          : ["Nepali_Murli_Htm", "Nepali_Murli_Pdf", "Nepali_Murli_MP3"],
        "Odiya"           : ["Odiya_Murli_Htm", "Odiya_Murli_Pdf", "Odiya_Murli_MP3"],
        "Polish"          : ["Htm-Polish", "PDF-Polish", "MP3-Polish"],
        "Portuguese"      : ["Htm_Portuguese", "PDF_Portuguese", "MP3_Portuguese"],
        "Punjabi"         : ["Punjabi_Murli_Htm", "Punjabi_Murli_PDF", "Punjabi_Murli_MP3"],
        "Separate Series in Hindi" : 
                            ["Today_Calendar_H", "Todays_Commentary_MP3", "Avyakt_Palna", "Hindi_Aaj_Ka_Purushrath", 
                            "Today_Moti"
                            ],
        "Sindhi"          : ["Pdf-Sindhi"],
        "Sinhala"         : ["Htm_Sinhala", "PDF_Sinhala", "Mp3_Sinhala"],
        "Spanish"         : ["Htm_Spanish", "PDF_Spanish", "Mp3_Spanish"],
        "Tamil"           : ["TamilMurli-Htm", "TamilMurli-Pdf", "TamilMurli-MP3", "TamilMurli-Ess-MP3", "TamilMurli-Vizual-Pdf",
                            "TamilThoughts"
                            ],
        "Tamil-Lanka"     : ["Htm_Tamil_Lanka", "MP3_Tamil_Lanka", "PDF_Tamil_Lanka"],
        "Telugu"          : ["Telugu_Murli_Htm", "Telugu_Murli_Pdf", "Telugu_Murli_MP3", "Telugu_Murli_Ess_MP3",
                            "Murli_Chintan_Suraj_Bhai_T", "Telugu_Murli_Viz_Pdf", "Telugu_Murli_Ess_Jpg",
                            "Telugu_Murli_Vardan_Jpg", "Telugu_Murli_Slogan_Jpg", "Telugu_Aaj_Ka_Purusharth",
                            "Telugu_Today_Calendar"
                            ],
        "Thai"            : ["Htm-Thai", "PDF-Thai", "Mp3-Thai"],
      };
      
      function onLanguageSelection() {
        tmp = document.getElementById("lang");
        if(tmp.length > 2) {   //its a valid language selection
          parent = document.getElementById('opts');
          parent.innerHTML = '';
          tmp_len = lang_options_array[tmp.value].length;
          lang_options_array[tmp.value].sort();
          for(i = 0; i < tmp_len; i++) {
            opts = document.createElement("option");
            opts.text = lang_options_array[tmp.value][i];
            opts.value = lang_options_array[tmp.value][i];
            if(opts.value == document.getElementById('choosen_opt').value) {
              opts.selected = true;
            }
            parent.appendChild(opts);
          }
        }
      }
      
      function copyClip() {
        const el = document.createElement('textarea');
        el.value = document.getElementById("new_link").value;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
      }
      
      function verifyAndProcess() {
        document.getElementById("loading_spinner_1").style.display = "inline-block";
        $('#verify').prop('disabled', true);
        choosen_date = document.getElementById("choosen_date").value;
        file_to_process = document.getElementById("file_to_process").value;
        new_link = document.getElementById("new_link").value;
        col = document.getElementById("col").value;
        $.ajax({
          type: "POST",
          contentType: "application/json",
          url: "addl-p2.php",
          data: JSON.stringify({
              "choosen_date" : choosen_date,
              "file_to_process" : file_to_process,
              "new_link" : new_link,
              "col" : col,
              }),
          cache: false,
          complete: function(jqXHR, textStatus) {
            document.getElementById("loading_spinner_1").style.display = "none";
            $('#verify').prop("disabled", false);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            document.getElementById("id_card").innerHTML = "<span class='bg-danger text-white'>" + errorThrown + "</span>";
          },
          success: function (data) {
            //console.log("data: ", data);
            var obj = JSON.parse(data);
            msg = obj.msg;
            document.getElementById("id_card").innerHTML = msg;
          }
        });
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
        onLanguageSelection();   //populate appropriate options on page load
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>