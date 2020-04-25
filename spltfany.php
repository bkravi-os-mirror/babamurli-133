<?php 
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $parent_dir = "$rootdir/01. Daily Murli";
  $split_freq_default_array = array(3,3,3,3,3,3,3);
  $freq_0 = $split_freq_default_array[0];  $freq_1 = $split_freq_default_array[1];  $freq_2 = $split_freq_default_array[2];
  $freq_3 = $split_freq_default_array[3];  $freq_4 = $split_freq_default_array[4];  $freq_5 = $split_freq_default_array[5];
  $freq_6 = $split_freq_default_array[6];
  $dir_array = array();
  $finalFileNameExtOnly = '';   //PDFname-unlocked.pdf
  
  $allowedfileExtensions = array('pdf');
  $upload_error_values = array(
    "0" => "No Error",   //UPLOAD_ERR_OK
    "1" => "The uploaded file exceeds the upload_max_filesize directive in php.ini",   //UPLOAD_ERR_INI_SIZE
    "2" => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",   //UPLOAD_ERR_FORM_SIZE
    "3" => "The uploaded file was only partially uploaded",   //UPLOAD_ERR_PARTIAL
    "4" => "No file was uploaded",   //UPLOAD_ERR_NO_FILE
    "6" => "Missing a temporary folder",   //UPLOAD_ERR_NO_TMP_DIR
    "7" => "Failed to write file to disk",   //UPLOAD_ERR_CANT_WRITE
    "8" => "A PHP extension stopped the file upload. PHP does not provide a way to ascertain "
           . "which extension caused the file upload to stop; examining the list of loaded "
           . "extensions with phpinfo() may help",   //UPLOAD_ERR_EXTENSION
  );
  
  //Below function populates $dir_array with below example key/value pair:
  // [var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/02. Hindi Murli - Pdf] => [-h.pdf]
  function getOnlyOnePDFInsideAllDirSubDir() {
    global $parent_dir, $dir_array;
    $cmd = "find \"$parent_dir\" -name \"*.pdf\" -type f|sort|awk -F \"/\" '{curr = \"\";split($0, arr, \"/\");for(i = 1; i <= length(arr) - 1; i++) {curr = curr arr[i-1];}if(curr != prev) {print $0; prev = curr;}}'";
    //echo($cmd);
    exec($cmd, $files_array);
    foreach($files_array as $file) {   //assuming PDF file name format is like [dd.mm.yy-POSTFIX.pdf]
      $tmp = onlyFileName($file);
      $file_post_fix = strlen($tmp) > 8 ? (substr($tmp, 8) . "." . onlyExt($file)) : ("-RavCustom" . "." . onlyExt($file));
      $dir_array[onlyDir($file)] = $file_post_fix;
    }
  }
  
  getOnlyOnePDFInsideAllDirSubDir();
  $selected_filename_postfix = '';
  $tmp = explode(".", date('d.m.Y'));
  $day = $tmp[0];
  $month = $tmp[1];
  $year = $tmp[2];
  $selected_dir = '';
  $message = '<kbd style="background-color:navy; color:white;">Please Choose PDF Murli File</kbd>';
  
  function getPDFPagesCount($sourceFileFullName) {
    $exec_command_to_get_total_pages = "gs -q -dNODISPLAY -c \"($sourceFileFullName) "
                                      . "(r) file runpdfbegin pdfpagecount = quit\"";
    exec($exec_command_to_get_total_pages, $command_output, $return_val);
    if(!$return_val) {
      return (int)implode("", $command_output);
    } else {
      return 0;
    }
  }
  
  function removePWDProtection($pdf_full_filename) {
    global $finalFileNameExtOnly, $message;
    if(!preg_match("/(pdf)$/", strtolower($pdf_full_filename))) {
      $message = $message . "<li><span class='bg-danger text-white'>File '$pdf_full_filename' is NOT a PDF file!!</span></li>";
      return false;
    }
    $path_parts = pathinfo($pdf_full_filename);
    $finalFileNameExtOnly = "{$path_parts['filename']}-unlocked.{$path_parts['extension']}";
    $outputFileFullName = "{$path_parts['dirname']}/$finalFileNameExtOnly";
    $return_msg = "";
    $exec_command = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 "
                    . "-sOutputFile=\"$outputFileFullName\" -dPDFSettings=/prepress -dAutoRotatePages=/None "
                    . "-c \"<</Orientation 0>> setpagedevice\" -f \"$pdf_full_filename\"";
    exec($exec_command, $command_output, $return_val);
    foreach($command_output as $line) {
      $message = $message . "$line<br>";
    }
    if($return_val === 0) {   //command success
      $message = $message . "<li><kbd style='background-color:#7160bb;'>Protection removed</kbd> <span class='bg-success text-white'>Sucecssfully!</span></li>";
      if(!unlink($pdf_full_filename)) {
        $message = $message . "<li><span class='bg-warning text-white'>WARNING!!</span> Couldn't delete original protected file $pdf_full_filename!</li>";
      } else {
        $message = $message . "<li><kbd style='background-color:#7160bb;'>Deleted protected file</kbd> <span class='bg-success text-white'>Sucecssfully!</span></li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> removing PDF protection!</li>";
      return false;
    }
    return true;
  }
  
  function splitPDF($sourceFileFullName, $split_freq, $start_date) {   //$split_freq must be an array of integers
    global $message, $selected_dir, $selected_filename_postfix;
    $pdf_in_process = onlyFileName($sourceFileFullName);
    $st_pg = 1;
    foreach($split_freq as $split) {
      $splt_val = $split;
      if($splt_val > 0) {
        $lst_pg = $st_pg + $splt_val - 1;
        $output_full_file = "$selected_dir/$start_date$selected_filename_postfix";
        $exec_command_to_split = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=\"$output_full_file\" "
                        . "-dFirstPage=$st_pg -dLastPage=$lst_pg -dPDFSettings=/prepress -dAutoRotatePages=/None "
                        . "-f \"$sourceFileFullName\"";
        exec($exec_command_to_split, $command_output, $return_val);
        if($return_val === 0) {   //command success
          $message = $message . "<li>File $start_date$selected_filename_postfix created</li>";
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> "
            . "Split failed for [$pdf_in_process, FirstPage=$st_pg, LastPage=$lst_pg]"
            . "</li>";
          return false;
        }
        $start_date = addDaysToDate(1, $start_date, 'd.m.y', 'd.m.y');
        $st_pg = $lst_pg + 1;
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> invalid split_freq number $split for $pdf_in_process!</li>";
        return false;
      }
    }
    if(unlink($sourceFileFullName)) {
      $message = $message . "<li><span style='color:chartreuse;'>Deleted unlocked file " . fileWithExt($sourceFileFullName) . " Successfully!</span></li>";
    } else {
      $message = $message . "<li><span class='bg-warning text-white'>WARNING!!</span> Unable to delete unlocked file $sourceFileFullName! But this should not be a show stopper. PDF has been split successfully though!!</li>";
    }
    return true;
  }
  
  if(isset($_POST['proceed'])) {
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    //die();
    $message = "Below Results<ul>";
    if(isset($_FILES['uploader'])) {
      if($_FILES['uploader']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['uploader']['tmp_name'];
        $fileName = $_FILES['uploader']['name'];
        $fileSize = $_FILES['uploader']['size'];
        $fileType = $_FILES['uploader']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $selected_dir = $_POST['destination_dir'];   //it will come as "^" in place of ".". So need to convert back to "."
        $selected_dir = str_replace("^", ".", $selected_dir);
        $day = $_POST['st_date'];   $month = $_POST['st_month'];   $year = $_POST['st_year'];   //Note that year is 4 digit!
        $selected_filename_postfix = $_POST['filename_postfix'];
        $freq_0 = intval($_POST['freq_0']);  $freq_1 = intval($_POST['freq_1']);  $freq_2 = intval($_POST['freq_2']);
        $freq_3 = intval($_POST['freq_3']);  $freq_4 = intval($_POST['freq_4']);  $freq_5 = intval($_POST['freq_5']);
        $freq_6 = intval($_POST['freq_6']);
        
        if(in_array($fileExtension, $allowedfileExtensions)) {
          if(move_uploaded_file($fileTmpPath, "$selected_dir/$fileName")) {
            $message = $message . "<li><kbd>$fileName</kbd> <span class='bg-success text-white'>Successfully</span> Uploaded!!</li>";
            if(removePWDProtection("$selected_dir/$fileName")) {   //All OK- go ahead
              $start_date = "$day.$month." . substr($year, 2, 2);
              $pdf_pg = getPDFPagesCount("$selected_dir/$finalFileNameExtOnly");
              $sum_split_freq = $freq_0 + $freq_1 + $freq_2 + $freq_3 + $freq_4 + $freq_5 + $freq_6;
              if($pdf_pg === $sum_split_freq) {
                if(splitPDF("$selected_dir/$finalFileNameExtOnly", array($freq_0, $freq_1, $freq_2, $freq_3, $freq_4, $freq_5, $freq_6), $start_date)) {
                  $message = $message . "<li><kbd>$fileName</kbd> <span class='bg-success text-white'>Split Successfully</span></li>";
                } else {
                  $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> during split process!</li>";
                }
              } else {
                $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Split Frequency <kbd style='background-color:red;'>Sum# [$sum_split_freq]</kbd><kbd> doesn't match</kbd> with total <kbd style='background-color:red;'>pages# [$pdf_pg]</kbd> in PDF!</li>";
              }
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Failed during password breaking process!</li>";
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> moving file from $fileTmpPath to $selected_dir</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> File extension $fileExtension is not yet supported!</li>";
        } 
      } else {
        $message = "<span class='bg-danger text-white'>ERROR during upload: " . $upload_error_values[$_FILES['uploader']['error']] . "</span>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Wrong form POSTing! No 'uploader' !!</li>";
    }
    $message = "$message</ul>";
  }
  
?>
<html lang="en">
  <head>
    <title>Split Any PDF</title>
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
      $brand_name = "Upload & Split <i class='fa fa-scissors' aria-hidden='true'></i>&nbsp;Any PDF";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm(this);" enctype="multipart/form-data">
        <div class="form-group row" style="margin-top:10px;">
          <label for="uploader" class="col-sm-4 col-form-label">Upload Murli PDF:</label>
          <div class="col-sm-8 form-inline">
            <input type="file" name="uploader" id="uploader" class="form-control-file border"/>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="destination_dir" class="col-sm-4 col-form-label">Destination Dir: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="destination_dir" name="destination_dir" onchange="onDirChange()" style="width:100%;">
              <?php
                foreach($dir_array as $dir => $postfx) {   //$dir will have dot (i.e. ".")
                  $selected = $selected_dir == $dir ? "selected" : "";   //$selected_dir will have dot (i.e. ".")
                  $dir_sanitized = str_replace(".", "^", $dir);   //$dir_sanitized won't have dot. During POST, "." is changed so caution here!
                  echo "<option value='" . $dir_sanitized . "' $selected>" . str_replace("$parent_dir/","", $dir) . " [=>] $postfx</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="st_date" class="col-sm-4 col-form-label" id="date_label">Start Date:</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="st_date" name="st_date">
              <?php
                for($i = 1; $i <= 31; $i++) {
                  $dt = $i <= 9 ? "0$i" : $i;
                  $selected = $day == $dt ? "selected" : "";
                  echo "<option value='$dt' $selected>$dt</option>";
                }
              ?>
            </select>
            <select class="form-control" id="st_month" name="st_month">
              <option value='01' <?php echo $month === '01' ? "selected" : "";?>>Jan</option>
              <option value='02' <?php echo $month === '02' ? "selected" : "";?>>Feb</option>
              <option value='03' <?php echo $month === '03' ? "selected" : "";?>>Mar</option>
              <option value='04' <?php echo $month === '04' ? "selected" : "";?>>Apr</option>
              <option value='05' <?php echo $month === '05' ? "selected" : "";?>>May</option>
              <option value='06' <?php echo $month === '06' ? "selected" : "";?>>June</option>
              <option value='07' <?php echo $month === '07' ? "selected" : "";?>>July</option>
              <option value='08' <?php echo $month === '08' ? "selected" : "";?>>Aug</option>
              <option value='09' <?php echo $month === '09' ? "selected" : "";?>>Sep</option>
              <option value='10' <?php echo $month === '10' ? "selected" : "";?>>Oct</option>
              <option value='11' <?php echo $month === '11' ? "selected" : "";?>>Nov</option>
              <option value='12' <?php echo $month === '12' ? "selected" : "";?>>Dec</option>
            </select>
            <select class="form-control" id="st_year" name="st_year">
              <?php
                for($i = 2000; $i <= 2030; $i++) {
                  $selected = $year == $i ? "selected" : "";   //dont compare with === because $year is string and $i is integer
                  echo "<option value='$i' $selected>$i</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="filename_postfix" class="col-sm-4 col-form-label">Filename Postfix: </label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="filename_postfix" name="filename_postfix" <?php echo "value='$selected_filename_postfix';"?>>
          </div>
        </div>
        <div class="form-group row">
          <label for="freq_1" class="col-sm-4 col-form-label" id="date_label">Split Frequency:</label>
          <div class="col-sm-8 form-inline">
            <select class='form-control' id='freq_0' name='freq_0'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_0 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_1' name='freq_1'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_1 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_2' name='freq_2'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_2 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_3' name='freq_3'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_3 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_4' name='freq_4'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_4 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_5' name='freq_5'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_5 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <select class='form-control' id='freq_6' name='freq_6'>
              <?php for($loop = 1; $loop <= 9; $loop++) {$selected = $freq_6 == $loop ? "selected" : "";echo "<option value='$loop' $selected>$loop</option>";}?>
            </select>
            <?php
            ?>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;" disabled>
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Upload & Split
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <a href="i.php" id="go_home" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">

      $(document).ready(
        function(){
          $('input:file').change(
            function(){
              if($(this).val()) {
                if(!$(this).val().toLowerCase().endsWith("pdf")) {
                  alert("Please upload a valid PDF file only!!!");
                  $('#proceed').prop("disabled", true);
                } else {
                  $('#proceed').prop("disabled", false);
                  $('#proceed').removeClass("disabled");
                }
              } else {
                $('#proceed').prop("disabled", true);
              }
            }
          );
      });
      
      function onDirChange() {
        dr = document.getElementById("destination_dir");
        selected_dir = dr.options[dr.selectedIndex].text;
        document.getElementById("filename_postfix").value = selected_dir.split(" [=>] ")[1];
      }
      
      function validateForm(form) {
        $('#proceed').prop("disabled", true);
        lower_val = document.getElementById("uploader").value.toLowerCase();
        if(!lower_val.endsWith("pdf")) {
          alert("Please upload a valid Sindhi PDF file only!!!");
          return false;
        } else {
          document.getElementById("id_card").innerHTML = "<kbd style='background-color:#7160bb;'>Processing started... Kindly wait a moment!</kbd>";
          document.getElementById("proceed").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Initiating Your Request...';
          $('#proceed').prop("disabled", false);
          $('#proceed').removeClass("disabled");
          return true;
        }
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("destination_dir").focus();
        onDirChange();
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>