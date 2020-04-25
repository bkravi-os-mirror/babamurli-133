<?php 
  //Usage: [php moveold.php]
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log.log");
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $message = 'Please select any one zip and submit';
  $processing_zip = '';
  
  $zip_files = myScan(".", "*.zip", 0, 0);
  
  function getName($str, $language) {   //e.g. $str=SakarMurliSindhi-2015-04-10.pdf  $language="Sindi"
    $ext = onlyExt($str);
    $fn = onlyFileName($str);
    $tmp = explode("-", $fn);
    if(count($tmp) >= 4) {
      if(strlen($tmp[1] > 2)) $tmp[1] = substr($tmp[1], 2);
      return $tmp[3] . "." . $tmp[2] . "." . $tmp[1] . "-$language.$ext";
    } else {
      return false;
    }
  }
  
  function desiredFileName($raw_file_name_with_ext) {   //e.g. SakarMurliSindhi-2015-04-10.pdf
    $raw_file_name_with_ext = strtolower($raw_file_name_with_ext);
    if(strpos($raw_file_name_with_ext, "sakar") !== false && strpos($raw_file_name_with_ext, "sindhi") !== false) {
      return(getName($raw_file_name_with_ext, "Sindhi"));
    }
    else if(strpos($raw_file_name_with_ext, "sunday") !== false && strpos($raw_file_name_with_ext, "sindhi") !== false) {
      return(getName($raw_file_name_with_ext, "Sindhi"));
    }
    else if(strpos($raw_file_name_with_ext, "sakar") !== false && strpos($raw_file_name_with_ext, "thai") !== false) {
      return(getName($raw_file_name_with_ext, "Thai"));
    }
    else if(strpos($raw_file_name_with_ext, "sunday") !== false && strpos($raw_file_name_with_ext, "thai") !== false) {
      return(getName($raw_file_name_with_ext, "Thai"));
    }
    else if(strpos($raw_file_name_with_ext, "sakar") !== false && strpos($raw_file_name_with_ext, "port") !== false) {
      return(getName($raw_file_name_with_ext, "Port"));
    }
    else if(strpos($raw_file_name_with_ext, "sunday") !== false && strpos($raw_file_name_with_ext, "port") !== false) {
      return(getName($raw_file_name_with_ext, "Port"));
    }
    return false;
  }
  
  if(isset($_POST['proceed'])) {   //'Unzip Old Data' button is clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "Below Results:<ul>";
    $processing_zip = $_POST['zip'];
    $main_folder = onlyFileName($processing_zip);
    if(!is_dir($main_folder)) {
      if(!mkdir($main_folder)) {
        $message = $message . "<li><span class='bg-danger text-white'>Error creating directory '$main_folder'!!</span></li>";
      } else {
        $message = $message . "<li><span class='bg-success text-white'>Dir '$main_folder' created</span></li>";
      }
    }
    if(is_dir($main_folder)) {
      $zip = new ZipArchive;
      $res = $zip->open("$processing_zip");
      if($res === TRUE) {
        //$zip->extractTo($main_folder);
        $counter = 0;
        for($i = 0; $i < $zip->numFiles; $i++) {
          $filename = $zip->getNameIndex($i);
          $fileinfo = pathinfo($filename);
          if(copy("zip://".$processing_zip."#".$filename, "$main_folder/".$fileinfo['basename'])) {
            $counter++;
          }
          //echo("$filename<br>" . "zip://".$processing_zip."#".$filename . "<br>$main_folder/".$fileinfo['basename']);
          //die();
        }
        $zip->close();
        $message = $message . "<li><span class='bg-success text-white'>$counter unzipped under $main_folder</span></li>";
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>Unzipping failed for $processing_zip</span></li>";
      }
    }
    $message = "$message</ul>";
  } else if(isset($_POST['clean'])) {   //'Clean Folders' button is clicked
    $message = "Below Results:<ul>";
    $zip_files = myScan(".", "*.zip");
    //$zip_files = array("30-Nepali Htm.zip");
    foreach($zip_files as $zip_file) {
      $processing_zip = $_POST['zip'];
      $working_folder = onlyFileName($zip_file);
      if(!is_dir($working_folder)) {
        $message = $message . "<li><span class='bg-danger text-white'>Folder $working_folder doesn't exist!!</span></li>";
      } else {
        $cdir = scandir($working_folder);
        $deleted_zerosize_file = 0;
        $couldnt_delete_zero_size_file = 0;
        foreach($cdir as $key => $value) {
          if(!in_array($value, array(".","..")) && is_file("$working_folder/$value") && filesize("$working_folder/$value") < (5*1024)) {   //less than 5 KB
            if(unlink("$working_folder/$value")) {
              $deleted_zerosize_file++;
            } else {
              $couldnt_delete_zero_size_file++;
            }
          }
        }
        $message = $message . "<li><span class='bg-success text-white'>Process $working_folder</span>"
                  . "<ul><li>Deleted zero Size File: $deleted_zerosize_file</li>"
                  . "<li>Couldnt delete zero Size File: $couldnt_delete_zero_size_file</li>"
                  . "</ul></li>";
      }
    }
    $message = "$message</ul>";
  } else if(isset($_POST['clean_filenames'])) {   //'Clean File Names' is clicked
    $message = "Below Results:<ul>";
    //$zip_files = myScan(".", "*.zip");
    $zip_files = array("43-Thai pdf.zip");
    foreach($zip_files as $zip_file) {
      $processing_zip = $_POST['zip'];
      $working_folder = onlyFileName($zip_file);
      if(!is_dir($working_folder)) {
        $message = $message . "<li><span class='bg-danger text-white'>Folder $working_folder doesn't exist!!</span></li>";
      } else {
        $cdir = scandir($working_folder);
        $renamed_file = 0;
        $couldnt_rename_file = 0;
        foreach($cdir as $key => $value) {
          if(!in_array($value, array(".",".."))) {
            $desired_name = desiredFileName($value);
            if($desired_name !== false) {
              if(rename("$working_folder/$value","$working_folder/$desired_name")) {
                $renamed_file++;
              } else {
                $couldnt_rename_file++;
              }
            }
          }
        }
        $message = $message . "<li><span class='bg-success text-white'>Process $working_folder</span>"
                  . "<ul><li>Renamed Files: $renamed_file</li>"
                  . "<li>Couldnt Rename Files: $couldnt_rename_file</li>"
                  . "</ul></li>";
      }
    }
    $message = "$message</ul>";
  }

?>
<html lang="en">
  <head>
    <title>Unzip Old Data</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/png" href="../000-Ravi-DontDelete/images/bks/sb_72x72.png"/>
    <link rel="stylesheet" type="text/css" href="../000-Ravi-DontDelete/css/bootstrap.min.4_3_1.css">
    <link rel="stylesheet" type="text/css" href="../000-Ravi-DontDelete/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu"/>
    <link rel="stylesheet" href="../000-Ravi-DontDelete/js/flash/dist/flash.css">
    <script src="../000-Ravi-DontDelete/js/jquery.slim.min.3_4_1.js"></script>
    <script src="../000-Ravi-DontDelete/js/jquery.min.js"></script>
    <script src="../000-Ravi-DontDelete/js/bootstrap.bundle.min.4_3_1.js"></script>
    <script src="../000-Ravi-DontDelete/js/flash/dist/flash.min.js"></script>
    <script src="../000-Ravi-DontDelete/js/flash/dist/flash.jquery.min.js"></script>
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
      $brand_name = 'Unzip Old Data';
      include('../000-Ravi-DontDelete/nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="zip" class="col-sm-4 col-form-label">Choose Zip: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="zip" name="zip" style="width:100%;">
              <?php 
                for($i = 0; $i < count($zip_files); $i++) {
                  $selected = $zip_files[$i] === $processing_zip ? 'selected' : '';
                  echo "<option value='$zip_files[$i]' $selected>$zip_files[$i]</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Step 1) Unzip Old Data
        </button>
        <button type="submit" id="clean" name="clean" class="btn btn-primary" style="width:100%;margin-top:10px;">
          <span id="loading_spinner_1" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Step 2) Clean Folders
        </button>
        <button type="submit" id="clean_filenames" name="clean_filenames" class="btn btn-success" style="width:100%;margin-top:10px;">
          <span id="loading_spinner_2" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Step 3) Clean File Names
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
    </div>
    <script language="javascript">
      
      function validateForm1(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        document.getElementById("loading_spinner_1").style.display = "inline-block";
        document.getElementById("loading_spinner_2").style.display = "inline-block";
        $('#proceed').addClass('disabled');
        $('#clean').addClass('disabled');
        $('#clean_filenames').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("loading_spinner_1").style.display = "none";
        document.getElementById("loading_spinner_2").style.display = "none";
        document.getElementById("zip").focus();
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>
