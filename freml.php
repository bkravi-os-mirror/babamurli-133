<?php
  
  /*
  Reference: https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763
  When a file is uploaded, the $_FILES superglobal variable is populated with all the information
  about the uploaded file. It's initialized as an array and may contain the following information for
  successful file upload:
    tmp_name: The temporary path where the file is uploaded is stored in this variable.
    name: The actual name of the file is stored in this variable.
    size: Indicates the size of the uploaded file in bytes.
    type: Contains the mime type of the uploaded file.
    error: If there’s an error during file upload, this variable is populated with the appropriate error
      message. In the case of successful file upload, it contains 0, which you can compare by using the
      UPLOAD_ERR_OK constant.
  */
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  include './util.php';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $outputDir = "$rootdir/000-Ravi-DontDelete/uplds";
  $finalFileNameOnly = '';
  
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
  
  function removePWDProtectionAndEmail($pdf_full_filename) {
    global $finalFileNameOnly, $outputDir;
    if(!preg_match("/(pdf)$/", strtolower($pdf_full_filename))) {
      return "<span class='bg-danger text-white'>File '$pdf_full_filename' is NOT a PDF file!!</span>";
    }
    $path_parts = pathinfo($pdf_full_filename);
    $tmp = $path_parts['filename'];   //e.g. Murli_Batch_French_2020-04-27.pdf
    $tmp = str_replace("Murli", "", $tmp);
    $tmp = str_replace("Batch", "", $tmp);
    $tmp = str_replace("_", "", $tmp);
    $tmp = str_replace("French", "F-Batch-", $tmp);
    $finalFileNameOnly = "$tmp-unlocked.{$path_parts['extension']}";
    $outputFileFullName = "{$path_parts['dirname']}/$finalFileNameOnly";
    $return_msg = "";
    $exec_command = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 "
                    . "-sOutputFile=\"$outputFileFullName\" -c .setpdfwrite -f \"$pdf_full_filename\"";
    exec($exec_command, $command_output, $return_val);
    foreach($command_output as $line) {
      $return_msg = $return_msg . "$line<br>";
    }
    if(!$return_val) {
      $return_msg = $return_msg . "<kbd style='background-color:#7160bb;'>Protection removed</kbd> <span class='bg-success text-white'>Sucecssfully!</span>";
      $subject = "Files: [" . str_replace("$outputDir/", "", $outputFileFullName) . "] - [DO NOT REPLY!! AUTO GEN E-MAIL!]<os>";
      $email_msg_array = sendEmail(array("bkravi.os@gmail.com", "prema_french@rediffmail.com"), $subject, "<h3>IT'S AN AUTO GENERATED E-MAIL. PLEASE DO NOT REPLY!!</h3>", array($outputFileFullName));
      //$email_msg_array = sendEmail(array("bkravi.os@gmail.com"), $subject, "<h3>IT'S AN AUTO GENERATED E-MAIL. PLEASE DO NOT REPLY!!</h3>", array($outputFileFullName));
      foreach($email_msg_array as $email_msg) {
        $return_msg = $return_msg . "<br>$email_msg";
      }
    } else {
      $return_msg = $return_msg . "<span class='bg-danger text-white'>ERROR!!</span> removing PDF protection!";
    }
    return $return_msg;
  }
  
  $sent_files_array = myScan($outputDir, "*F-Batch*.sent", 1, 1);   //filtering French sent files
  
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {   //"Upload French Murli PDF" button is clicked
    if(isset($_FILES['uploader'])) {
      if($_FILES['uploader']['error'] === UPLOAD_ERR_OK) {
        $message = "Below results:<ol>";
        $fileTmpPath = $_FILES['uploader']['tmp_name'];
        $fileName = $_FILES['uploader']['name'];
        $fileSize = $_FILES['uploader']['size'];
        $fileType = $_FILES['uploader']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = str_replace(" ", "", $fileName);   //sanitize the filename
        if(in_array($fileExtension, $allowedfileExtensions)) {
          if(move_uploaded_file($fileTmpPath, "$outputDir/$newFileName")) {
            $message = $message . "<li><kbd>$newFileName</kbd> <span class='bg-success text-white'>Successfully</span> Uploaded!!</li>";
            $message = $message . "<li>" . removePWDProtectionAndEmail("$outputDir/$newFileName") . "</li>";
            if(strpos(strtolower($message), "email sent successfully") !== false) {   //e-mail send successfully
              if(rename("$outputDir/$newFileName", "$outputDir/$finalFileNameOnly.sent")) {
                $message = $message . "<li>PDF renamed as <kbd>.sent</kbd> <span class='bg-success text-white'>Successfully</span></li>";
              } else {
                $message = $message . "<li>Rename to <kbd>$outputDir/$finalFileNameOnly.sent</kbd> <span class='bg-danger text-white'>Failed!!</span></li>";
              }
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> moving file from $fileTmpPath to $outputDir</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> File extension $fileExtension is not yet supported!</li>";
        }
        $message = "$message</ol>";
      } else {
        $message = "<span class='bg-danger text-white'>ERROR during upload: " . $upload_error_values[$_FILES['uploader']['error']] . "</span>";
      }
    } else {
      $message = "<span class='bg-danger text-white'>ERROR!!</span> Wrong form POSTing! No 'uploader' !!";
    }
  } else {
    $message = "Output Here...";
  }
  
?>
<html lang="en">
  <head>
    <title>French PDF Emailer</title>
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
      $brand_name = 'Email French Murli PDF';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <div class="form-group" id="sent_files_holder">
          <select class="form-control" id="sent_files_holder_sel" name="sent_files_holder_sel">
              <?php
                foreach($sent_files_array as $sent_file) {
                  echo "<option value='$sent_file'>$sent_file</option>";
                }
              ?>
          </select>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="uploader" class="col-sm-4 col-form-label">Upload French Murli PDF:</label>
          <div class="col-sm-8 form-inline">
            <input type="file" name="uploader" id="uploader" class="form-control-file border"/>
          </div>
        </div>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger" style="width:100%;" disabled>
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Upload French Murli PDF
        </button>
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
      
      $(document).ready(
        function(){
          $('input:file').change(
            function(){
              if($(this).val()) {
                if(!$(this).val().toLowerCase().endsWith("pdf")) {
                  alert("Please upload a valid PDF file only!!!");
                  $('#initiater').prop("disabled", true);
                } else {
                  $('#initiater').prop("disabled", false);
                  $('#initiater').removeClass("disabled");
                }
              } else {
                $('#initiater').prop("disabled", true);
              }
            }
          );
      });
      
      function validateForm0(form) {
        $('#initiater').prop("disabled", true);
        if(!document.getElementById("uploader").value.toLowerCase().endsWith("pdf")) {
          alert("Please upload a valid PDF file only!!!");
          return false;
        } else {
          document.getElementById("initiater").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Initiating Your Request...';
          $('#initiater').prop("disabled", false);
          $('#initiater').removeClass("disabled");
          return true;
        }
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>