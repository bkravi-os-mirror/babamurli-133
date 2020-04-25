<!DOCTYPE html>
<?php
  
  //Below code downloads the PDF file saved in Google drive under public shared folder
  //We need Google API key of the project ravinimbus@gmail.com where "Google Drive" API is enabled
  
  require_once './libs/vendor-google-api/autoload.php';
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  
  $client = new Google_Client();
  $client->setApplicationName("Project From ravinimbus@gmail.com");
  $client->setDeveloperKey("AIzaSyDMB0wLYBS5wZ3VtcYSF6Vrn8vDDEZqGok");   //Google API key of the project ravinimbus@gmail.com where "Google Drive" API is enabled
  $driveService = new Google_Service_Drive($client);
  
  $message = '';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $downloadFolder = "$rootdir/000-Ravi-DontDelete/jwdn";
  
  $jw_languages_map = array(
    "h" => "Hindi", "e" => "English", "f" => "French", "d" => "German", "g" => "Greek", "u" => "Hungarian",
    "a" => "Indonesian", "i" => "Italian", "j" => "Japanese", "k" => "Kannada", "o" => "Korean", "y" => "Malayalam",
    "m" => "Mandarin", "n" => "Nepali", "x" => "Polish", "p" => "Portuguese", "r" => "Russian", "q" => "Sindhi",
    "c" => "Sinhala", "s" => "Spanish", "t" => "Tamil(Sri Lanka)", "l" => "Tamil(Chennai)", "b" => "Telugu",
    "v" => "Thai",
  );
  
  $murli_months = array(
    "JAN", "FEB", "MAR", "APR", "MAY", "JUNE", "JULY", "AUG", "SEP", "OCT", "NOV", "DEC",
  );
  
  $jw_mimetype_array = array("application/pdf",);
  
  $jw_murli_folders = array();
  
  $jw_folder_map = "http://jewels.brahmakumaris.org/js/accordion.html";
  $jw_folder_map_read_all = file_get_contents($jw_folder_map);
  
  if(!($jw_folder_map_read_all && strlen($jw_folder_map_read_all)) > 10) {
    $message = "ERROR Below:<ul>";
    $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> reading $jw_folder_map</li>";
    $message = "$message</ul>";
  } else {
    $sub_str = substr($jw_folder_map_read_all, strpos($jw_folder_map_read_all, "<li>Murli"));
    $tmp_idx = strpos($sub_str, "</ul></li><li>") + strlen("</ul></li><li>");
    $final_idx = strpos($sub_str, "</ul></li><li>", $tmp_idx) + strlen("</ul></li><li>") - strlen("<li>");
    $final_sub_str = substr($sub_str, 0, $final_idx);
    $html = str_get_html($final_sub_str);
    foreach($html->find('a') as $element) {   //e.g. javascript:searchFolder(\"0B4qWz7YYoTVpODRpU1NiVU43UlE\",\"2010-2011\", \"Murli/Avyakt Murli/2010-2011\" )
      $href = str_replace("javascript:searchFolder(", "", $element->href);
      $href = str_replace(")", "", $href);
      $href = str_replace("\\\"", "", $href);
      $tmp = explode(",", $href);   //e.g. 0B4qWz7YYoTVpODRpU1NiVU43UlE,2010-2011, Murli/Avyakt Murli/2010-2011
      if(count($tmp) > 2) {
        $jw_murli_folders[trim($tmp[0])] = trim($tmp[2]) . " => " . trim($tmp[1]);
      }
    }
  }
  
  function downloadFileFromGDrive($fileID, $destination_file_full_path) {
    global $driveService, $message;
    try {
      $response = $driveService->files->get("$fileID", array('alt' => 'media'));   //'alt' => 'media' means we are asking to download the file
      $content = $response->getBody()->getContents();
      if(file_put_contents($destination_file_full_path, $content)) {return true;}
      else {
        $message = $message . "<li><kbd style='background-color:red; color:white;'>ERROR!! GDrive not able to write file using file_put_contents</kbd></li>";
        return false;
      }
    } catch(Exception $e) {
      $message = $message . "<li>" . $e->getMessage() . "</li>";
      return false;
    }
  }
  
  $selected_jw_folder = '';
  $selected_jw_lang_code = '';
  $selected_murli_month = '';
  $selected_jw_mimetype = end($jw_mimetype_array); reset($jw_mimetype_array);   //reset is MUST! as end() will move the internal pointer
  
  if(isset($_POST['proceed'])) {   //Proceed button clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "Below Output<ul>";
    $selected_jw_folder = $_POST['jw_folder'];
    $selected_jw_lang_code = $_POST['lang'];
    $selected_jw_mimetype = $_POST['mimetype'];
    $selected_murli_month = $_POST['murli_month'];
    $counter = 0;
    $success_count = 0;
    $failure_count = 0;
    
    if(!is_dir($downloadFolder)) {
      $message = $message . "<li><kbd style='background-color:red; color:white;'>ERROR!! Folder $rootdir/000-Ravi-DontDelete/jwdn doesn't exists!!</kbd></li>";
    } else {
      //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
      foreach ($_POST as $key => $value) {   //key: GOOGLE_ID^0B4qWz7YYoTVpV3lTYWR1VEVib2c^AvyaktMurli16Mar2011-HindiRAVIDOTpdf
        if(preg_match("/^GOOGLE_ID/", $key)) {
          $tmp = str_replace("RAVIDOT", ".", $key);
          $tmp = explode("^", $tmp);
          if(count($tmp) === 3) {
            $downloadURL = "https://drive.google.com/uc?key=AIzaSyDMB0wLYBS5wZ3VtcYSF6Vrn8vDDEZqGok&export=download&id=" . $tmp[1];
            $downloadFileName = $tmp[2];
            
            if(downloadFileFromGDrive($tmp[1], "$downloadFolder/$downloadFileName")) {
              $message = $message . "<li>$downloadFileName downloaded <span class='bg-success text-white'>OK</span></li>";
              $success_count++;
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>Could not download $downloadFileName !!</span></li>";
              $failure_count++;
            }
            
            /*
            if($result = @file_get_contents($downloadURL)) {
              if(file_put_contents("$downloadFolder/$downloadFileName", $result)) {
                $message = $message . "<li>$downloadFileName downloaded <span class='bg-success text-white'>OK</span></li>";
                $success_count++;
              } else {
                $message = $message . "<li><span class='bg-danger text-white'>File $downloadFileName write ERROR inside $downloadFolder!!</span></li>";
                $failure_count++;
              }
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>[ERROR>>file_get_contents>>] For $downloadFileName @ $downloadURL : ${http_response_header[0]}</span></li>";
              $failure_count++;
            }
            */
            
            $counter++;
          } else {
            $message = $message . "<li><kbd style='background-color:crimson; color:white;'>Invalid $key !</kbd></li>";
          }
        }
      }
      if($counter === 0) {
        $message = $message . "<li><kbd style='background-color:crimson; color:white;'>Nothing to Do!! No Google ID found to download</kbd></li>";
      } else {
        $message = $message . "<li><kbd>Successful Download# <kbd style='background-color:mediumvioletred; color:white;'>[$success_count]</kbd>. Failure Download# <kbd style='background-color:red; color:white;'>[$failure_count]</kbd></kbd></li>";
      }
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>Jewels Murli Download</title>
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
    <!-- below js is for Jewels firebase-->
    <script type="text/javascript" src="js/firebase_3_4_0.js"></script>
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
      $brand_name = "Jewels <i class='fa fa-diamond' aria-hidden='true'></i> Murli Downloader";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="jw_folder" class="col-sm-4 col-form-label">Choose Jewels Murli Folder:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="jw_folder" name="jw_folder" style="width:100%;">
              <?php
                foreach($jw_murli_folders as $key => $val) {
                  $selected = '';
                  if($selected_jw_folder == $key) {$selected = 'selected';}
                  echo "<option value='$key' $selected>$val</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="murli_month" class="col-sm-4 col-form-label">Which Month Murli?</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="murli_month" name="murli_month" style="width:100%;">
              <?php
                foreach($murli_months as $idx => $mnth) {
                  $selected = '';
                  $mnth_num = sprintf("%02d", $idx+1);
                  if($selected_murli_month === $mnth_num) {$selected = 'selected';}
                  echo "<option value='$mnth_num' $selected>$mnth</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="lang" class="col-sm-4 col-form-label">Select Language:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="lang" name="lang" style="width:100%;">
              <?php
                foreach($jw_languages_map as $code => $lang) {
                  $selected = '';
                  if($selected_jw_lang_code == $code) {$selected = 'selected';}
                  echo "<option value='$code' $selected>$lang</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="mimetype" class="col-sm-4 col-form-label">Select Document Type:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="mimetype" name="mimetype" style="width:100%;">
              <?php
                foreach($jw_mimetype_array as $type) {
                  $selected = '';
                  if($selected_jw_mimetype == $type) {$selected = 'selected';}
                  echo "<option value='$type' $selected>$type</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <input type="button" id="show_available" name="show_available" class="btn btn-primary mt-4" style="width:100%;" onclick="showAvailable();" value="Show Available Files">
        <div class="card bg-info text-white mt-4">
          <div class="card-body" id="id_card">
            <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger mt-4" style="width:100%;display:none;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Proceed To Download
        </button>
        <div class="form-group form-check mt-4">
          <label class="form-check-label col-sm-12" style="word-wrap: break-word;color:darkblue;background-color:greenyellow;padding-left:0px;width:95px;border-radius:10px;">
            <input class="form-check-input ravicheck" type="checkbox" id="selectall" name="selectall"><span style="padding-left:12px;">Select All</span>
          </label>
        </div>
        <div id="ins_0" class="form-group row" style="margin-top:10px;margin-left:20px;background-color:azure;margin-left:0px;margin-right:0px;padding-left:15px;padding-right:15px;border-radius:8px;">
        </div>
      </form>
      <span style="position: fixed;bottom: 3px;left: 3px;" id="con_discon_icon">
        <span class='btn btn-secondary'>Firebase <i class='fa fa-chain-broken' aria-hidden='true'></i></span>
      </span>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
      var pageSize = 6;
      var fbOffline = false;
      var t;
      var responseRef;
      var langCode;
      var parentNode;
      var url;
      var json;
      var fileNode;
      var i;
      var dbInitialStatus;
      var beforeOrAfterEnd;
      var counter;
      var fileNameArray, tmpFileNameArray, fileName, displayFileName;
      var tagName, tagID;
      var langCode_postFix_Map = [];
      var totalChecked;
      var choosenMonthNumb, choosenMonthName, mnthElement;
      
      langCode_postFix_Map["h"] = "-h.pdf";      langCode_postFix_Map["e"] = "-E.pdf";      langCode_postFix_Map["f"] = "-French.pdf";
      langCode_postFix_Map["d"] = "-Deutsch.pdf";      langCode_postFix_Map["g"] = "-Greek.pdf";      langCode_postFix_Map["u"] = "-Hungarian.pdf";
      langCode_postFix_Map["a"] = "-Indonesian.pdf";      langCode_postFix_Map["i"] = "-Italian.pdf";      langCode_postFix_Map["j"] = "-Japanese.pdf";
      langCode_postFix_Map["k"] = "-K.pdf";      langCode_postFix_Map["o"] = "-Korean.pdf";      langCode_postFix_Map["y"] = "-Mal.pdf";
      langCode_postFix_Map["m"] = "-Mandarin.pdf";      langCode_postFix_Map["n"] = "-Nep.pdf";      langCode_postFix_Map["x"] = "-Polish.pdf";
      langCode_postFix_Map["p"] = "-Port.pdf";      langCode_postFix_Map["r"] = "-Russian.pdf";      langCode_postFix_Map["q"] = "-Sindhi.pdf";
      langCode_postFix_Map["c"] = "-Sinhala.pdf";      langCode_postFix_Map["s"] = "-Spanish.pdf";      langCode_postFix_Map["t"] = "-TamilLanka.pdf";
      langCode_postFix_Map["l"] = "-Tamil.pdf";      langCode_postFix_Map["b"] = "-Telugu.pdf";      langCode_postFix_Map["v"] = "-Thai.pdf";
      
      // Initialize Jewels Firebase
      var config = {
        apiKey: "AIzaSyCQlCa-1Pj2lh2aepzS_5ARrDAfwt2jswU",
        authDomain: "madhuban-jewels.firebaseapp.com",
        databaseURL: "https://madhuban-jewels.firebaseio.com",
        storageBucket: "madhuban-jewels.appspot.com",
        messagingSenderId: "508511777966"
      };
      
      firebase.initializeApp(config);
      document.getElementById("con_discon_icon").innerHTML = "<span class='btn btn-danger'>Firebase <i class='fa fa-link' aria-hidden='true'></i></span>";

      function doOff() {
        if(!fbOffline) {
          firebase.database().goOffline();
          fbOffline = true;
          console.log("Firebase Going Offline");
          enableButtons(true);
          document.getElementById("con_discon_icon").innerHTML = "<span class='btn btn-secondary'>Firebase <i class='fa fa-chain-broken' aria-hidden='true'></i></span>";
        }
      }

      function doOn() {
        if(fbOffline) {
          firebase.database().goOnline();
          fbOffline = false;
          console.log("Firebase Going Online");
          enableButtons(true);
          document.getElementById("con_discon_icon").innerHTML = "<span class='btn btn-danger'>Firebase <i class='fa fa-link' aria-hidden='true'></i></span>";
        }
      }

      function firebaseOffOnTimer(l) {
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer; 
        window.onclick = resetTimer;     
        window.onscroll = resetTimer;    
        window.onkeypress = resetTimer;
        function resetTimer() {
          doOn();
          clearTimeout(t);
          t = setTimeout(doOff, l);  
        }
      }

      function startup() {
        //firebaseOffOnTimer(60000); // 1 minute of inactivity
        firebaseOffOnTimer(20000);
        displayList(0);	
      }

      function showAvailable() {
        enableButtons(false);
        //below (re)initializing all variables before firebase works on those
        dbInitialStatus = "NA";
        document.getElementById('ins_0').innerHTML = '';
        document.getElementById('ins_0').innerHTML = '';
        document.getElementById('id_card').innerHTML = "Total Selected# <kbd>[0/0]</kbd>";        
        totalChecked = 0;
        startup();
      }
      
      function enableButtons(isEnable) {
        if(isEnable) {
          document.getElementById("show_available").value = "Show Available Files";
          document.getElementById("show_available").disabled = false;
        } else {
          document.getElementById("show_available").value = "Fetching from Firebase DB... Please wait...";
          document.getElementById("show_available").disabled = true;
        }
      }
      
      //Returns e.g. AvyaktMurli16Mar2011-HindiRAVIDOTpdf, 31RAVIDOT12RAVIDOT16-hRAVIDOTpdf
      function prepareFileName(rawFileName, _langCode) {   //e.g. SakarMurliHindi-2016-12-31.pdf, AvyaktMurliHindi-2016-12-25.pdf
        tmpFileNameArray = rawFileName.split(".");
        if(tmpFileNameArray.length != 2) {
          displayFileName = rawFileName;
          rawFileName = rawFileName.replace(/\./g, "RAVIDOT");   //replace all dots with RAVIDOT. because POST will change "." to "_"
          return rawFileName.replace(/\s/g, '');   //removing all spaces
        }
        fileNameArray = (tmpFileNameArray[0]).split("-");
        if(fileNameArray.length == 4) {
          if(fileNameArray[1] > 2) {   //i.e. 4 digit array
            fileName = fileNameArray[3] + "." + fileNameArray[2] + "." + fileNameArray[1].substr(2, 2);
          } else {
            fileName = fileNameArray[3] + "." + fileNameArray[2] + "." + fileNameArray[1];
          }
          fileName = fileName + langCode_postFix_Map[_langCode];
          displayFileName = fileName;
          fileName = fileName.replace(/\./g, "RAVIDOT");   //replace all dots with RAVIDOT. because POST will change "." to "_"
          fileName = fileName.replace(/\s/g, '');   //removing all spaces
          return fileName;
        } else {
          displayFileName = rawFileName;
          rawFileName = rawFileName.replace(/\./g, "RAVIDOT");   //replace all dots with RAVIDOT. because POST will change "." to "_"
          return rawFileName.replace(/\s/g, '');   //removing all spaces
        }
      }
      
      function updateCounter(chkbox) {
        if(chkbox.checked) totalChecked++;
        else totalChecked--;
        document.getElementById('id_card').innerHTML = "Total Selected# <kbd>[" + totalChecked + "/" + counter + "]</kbd>";
      }
      
      $("#selectall").click(function () {
        $(".ravicheck").prop('checked', $(this).prop('checked'));
      });
      
      //RAVI: This is the main starting function
      function displayList(startIndex) {
        
        //NOTE BELOW to generate a URL:
        //start => startIndex
        //type => a(udio) / v(video) / f(ile)
        //language => h, e, f, t [single character]
        //sort => [spkr => Sort by Speaker, vws => Sort by Views, cDt => Sort by Class Date, addDt => Sort by Class add date]
        //qry => searchKeyword
        //ctg => searchTag
        //parentNode => This is used to get data inside this. i.e. it acts as a folder name. 
        //    parentNode can be derived from js/accordion.html
        //    check href attribute of any <a tag. The first ID will be the folder ID i.e. parentNode
        //    e.g. javascript:searchFolder(\"0B4qWz7YYoTVpeGJrdWVmcDJFbm8\",\"2014\", \"Murli/Daily Murli/2014\")
        //        in this, parentNode will be 0B4qWz7YYoTVpeGJrdWVmcDJFbm8
        //        So, if you'll use parentNode in the URL, you'll get data inside parentNode folder only. A good way to get filtered data!
        
        //SEE BELOW language codes:
        // h => Hindi, e => English, f => French, d => German, g => Greek, 
        // u => Hungarian, a => Indonesian, i => Italian, j => Japanese, 
        // k => Kannada, o => Korean, y => Malayalam, m => Mandarin, n => Nepali, 
        // x => Polish, p => Portuguese, r => Russian, q => Sindhi, c => Sinhala, 
        // s => Spanish, t => Tamil(Sri Lanka), l => Tamil(Chennai), b => Telugu, v => Thai  
        
        //Lets get all hindi, english, tamil Daily Murli pdfs for year 2014. 
        //If you see js/accordion.html, you will find that folder "2014" for "Daily Murli" has NodeID as 0B4qWz7YYoTVpeGJrdWVmcDJFbm8
        //Therefore the URL will look like below:
        //url = "type=avf&start=0&limit=6&language=hel&sort=addDt&parentNode=0B4qWz7YYoTVpeGJrdWVmcDJFbm8";
        
        langCode = document.getElementById("lang").value;
        parentNode = document.getElementById("jw_folder").value;
        
        url = "type=f&start=0&limit=999&language=" + langCode + "&sort=addDt&parentNode=" + parentNode;
        
        firebase.database().ref('requestsNew/' + url).set({
          lastRequested: new Date().getTime()
        });
        
        if(typeof responseRef !== 'undefined') {   //lets reset firebase obj if not
          responseRef.off();
        }
          
        responseRef = firebase.database().ref('responsesNew/' + url);
        responseRef.on('value', function(snapshot) {
          json = snapshot.val();
          if(json == null)
            return;

          if(dbInitialStatus != "STATUS_OK") {   //Since due to some bug, for the first time, firebase returns the results twice. Avoid it!
            if(json.total == 0) {
              document.getElementById('ins_0').insertAdjacentHTML('beforeend', "<kbd style='background-color:#dc3545; color:white;'>Sorry!!! No matching murlis found!!</kbd>");
              enableButtons(true);
              return;
            }	
            //json.list.length, json.total, json.list[0], json.list[1]...
            //Google drive download URL: "https://drive.google.com/uc?export=download&id=" + googleId;
            counter = 0;
            document.getElementById('ins_0').innerHTML = '';
            
            for (i = 0; i < json.list.length; i++) {
              fileNode = json.list[i];   //imp keys=> [downloadUrl, googleId, mimetype, name(it is the filename)]
              //console.log("[fileNode>>]", fileNode);
              //Now filtering out data based on choosen month
              mnthElement = document.getElementById("murli_month");
              choosenMonthNumb = mnthElement.value;   //01, 02...12
              choosenMonthName = mnthElement.options[mnthElement.selectedIndex].text;   //JAN, FEB...DEC
              if(fileNode.name.toUpperCase().includes(choosenMonthName) || fileNode.name.toUpperCase().includes("-" + choosenMonthNumb + "-")) {
                fileName = prepareFileName(fileNode.name, langCode);
                if(counter == 0) beforeOrAfterEnd = "beforeend";
                else  beforeOrAfterEnd = "afterend";
                tagName = "GOOGLE_ID^" + fileNode.googleId + "^" + fileName;   //e.g. GOOGLE_ID^0B4qWz7YEVib2c^AvyaktMurli16Mar2011-HindiRAVIDOTpdf
                tagID = tagName;
                document.getElementById('ins_' + counter).insertAdjacentHTML(beforeOrAfterEnd, '<div style="float:left;width:20%;font-size:11pt;" id="ins_' + (counter+1) + '"><label id="lbl_ins_' + (counter+1) + '" class="form-check-label col-sm-12" style="word-wrap: break-word;color:' + 'red' + ';"><input onchange="updateCounter(this);" class="form-check-input ravicheck" type="checkbox" name="' + tagName + '" id="' + tagID + '">' + displayFileName + '</label></div>');
                counter++;
              }
            }
            document.getElementById('id_card').innerHTML = "Total Selected# <kbd>[" + totalChecked + "/" + counter + "]</kbd>";
            document.getElementById("proceed").style.display = "inline-block";
            enableButtons(true);
            dbInitialStatus = "STATUS_OK";
          }
        });
      }
      
      function validateForm1(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#proceed').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>