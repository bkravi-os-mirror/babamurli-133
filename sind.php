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
  $sindhi_pdf_dir = "$rootdir/01. Daily Murli/42. Sindhi/Pdf-Sindhi";
  $sindhi_file_postfix = "-Sindhi.pdf";
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
  
  function splitAndMovePDF($sourceFileFullName) {   //argument must be like: ../../Murli_Batch_Sindhi_2020-04-20-Final.pdf
    global $sindhi_pdf_dir, $sindhi_file_postfix;
    $sam_msg_array = array();
    $exec_command_to_get_total_pages = "gs -q -dNODISPLAY -c \"($sourceFileFullName) "
                                      . "(r) file runpdfbegin pdfpagecount = quit\"";
    exec($exec_command_to_get_total_pages, $command_output, $return_val);
    if(!$return_val) {
      $pages = (int)implode("", $command_output);
      if($pages !== 28) {
        $sam_msg_array[] = "<li><span class='bg-warning text-white'>SORRY!!</span> At present I support PDFs having exactly 28 pages! This PDF has <kbd>$pages pages!!</kbd></li>";
      } else {
        $tmp = explode("_", onlyFileName($sourceFileFullName));   //splitting Murli_Batch_Sindhi_2020-04-20-Final
        $tmp = explode("-", $tmp[3]);   //splitting 2020-04-20-Final
        $yy = substr($tmp[0], 2, 2); $mm = $tmp[1]; $dd = $tmp[2];
        $start_date = "$dd.$mm.$yy";
        for($loop = 1; $loop <= 28; $loop += 4) {   //dividing 28 page PDF into 7 sub pdfs
          $exec_command_to_split = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=\"$sindhi_pdf_dir\"/$start_date$sindhi_file_postfix "
                          . "-dFirstPage=$loop -dLastPage=" . ($loop+3) ." -dPDFSettings=/prepress -dAutoRotatePages=/None "
                          . "-f \"$sourceFileFullName\"";
          exec($exec_command_to_split, $command_output, $return_val);
          if($return_val === 0) {   //command success
            $sam_msg_array[] = "<li><kbd>$start_date-Sindhi.pdf</kbd> created <span class='bg-success text-white'>Successfully</span></li>";
          } else {
            $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> splitting PDF for $start_date-Sindhi.pdf</li>";
            break;
          }
          $start_date = addDaysToDate(1, $start_date, 'd.m.y', 'd.m.y');
        }
        if($loop == 29) {   //i.e. above for loop ran OK
          if(rename("$sourceFileFullName", "$sourceFileFullName.processed")) {
            $sam_msg_array[] = "<li>PDF renamed as <kbd>" . fileWithExt("$sourceFileFullName.processed") . "</kbd> <span class='bg-success text-white'>Successfully</span></li>";
          } else {
            $sam_msg_array[] = "<li>Rename to <kbd>$sourceFileFullName.processed</kbd> <span class='bg-danger text-white'>Failed!!</span></li>";
          }
        } else {
          $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!! Something went wrong for itirating 7 times!</span></li>";
        }
      }
    } else {
      $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> counting PDF pages!</li>";
    }
    
    //On success: "PDF split and moved successfully"
    //NOTE: Do not change this success message as it is being used in many places to check if all went OK
    return $sam_msg_array;
  }
  
  function removePWDProtectionAndSplit($pdf_full_filename) {
    global $finalFileNameOnly;
    if(!preg_match("/(pdf)$/", strtolower($pdf_full_filename))) {
      return "<li><span class='bg-danger text-white'>File '$pdf_full_filename' is NOT a PDF file!!</span></li>";
    }
    $path_parts = pathinfo($pdf_full_filename);
    $finalFileNameOnly = "{$path_parts['filename']}-Final.{$path_parts['extension']}";
    $outputFileFullName = "{$path_parts['dirname']}/$finalFileNameOnly";
    $return_msg = "";
    $exec_command = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 "
                    . "-sOutputFile=\"$outputFileFullName\" -dPDFSettings=/prepress -dAutoRotatePages=/None "
                    . "-c \"<</Orientation 0>> setpagedevice\" -f \"$pdf_full_filename\"";
    exec($exec_command, $command_output, $return_val);
    foreach($command_output as $line) {
      $return_msg = $return_msg . "$line<br>";
    }
    if($return_val === 0) {   //command success
      $return_msg = $return_msg . "<li><kbd style='background-color:#7160bb;'>Protection removed</kbd> <span class='bg-success text-white'>Sucecssfully!</span></li>";
      $sam_msg_array = splitAndMovePDF($outputFileFullName);
      foreach($sam_msg_array as $sam_msg) {
        $return_msg = $return_msg . "$sam_msg";
      }
    } else {
      $return_msg = $return_msg . "<li><span class='bg-danger text-white'>ERROR!!</span> removing PDF protection!</li>";
    }
    return $return_msg;
  }
  
  $processed_files_array = myScan($outputDir, "*indhi*.processed", 1, 1);   //filtering Sindhi processed files
  
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {   //"Upload Sindhi Murli PDF" button is clicked
    if(isset($_FILES['uploader'])) {
      if($_FILES['uploader']['error'] === UPLOAD_ERR_OK) {
        $message = "Below results:<ol>";
        $fileTmpPath = $_FILES['uploader']['tmp_name'];
        $fileName = $_FILES['uploader']['name'];
        $fileSize = $_FILES['uploader']['size'];
        $fileType = $_FILES['uploader']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = str_replace(" ", "", $fileName);   //sanitize filename now must be Murli_Batch_Sindhi_2020-04-20.pdf
        $onlyFN = onlyFileName($newFileName);   //you get Murli_Batch_Sindhi_2020-04-20
        if(substr_count($onlyFN, "_") === 3 && substr_count($onlyFN, "-") === 2) {   //must have 3 '_' and 2 '-' in filename
          if(in_array($fileExtension, $allowedfileExtensions)) {
            if(move_uploaded_file($fileTmpPath, "$outputDir/$newFileName")) {
              $message = $message . "<li><kbd>$newFileName</kbd> <span class='bg-success text-white'>Successfully</span> Uploaded!!</li>";
              $message = $message . removePWDProtectionAndSplit("$outputDir/$newFileName");
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> moving file from $fileTmpPath to $outputDir</li>";
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> File extension $fileExtension is not yet supported!</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> PDF filename should look similar to Murli_Batch_Sindhi_2020-04-20.pdf!</li>";
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
    <title>Sindhi PDF Splitter</title>
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
      $brand_name = "Sindhi Murli Splitter <i class='fa fa-gavel' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <div class="form-group" id="sent_files_holder">
          <select class="form-control" id="sent_files_holder_sel" name="sent_files_holder_sel">
              <?php
                if($processed_files_array && count($processed_files_array) > 0) {
                  foreach($processed_files_array as $processed_file) {
                    echo "<option value='$processed_file'>$processed_file</option>";
                  }
                }
              ?>
          </select>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="uploader" class="col-sm-4 col-form-label">Upload Sindhi Murli PDF:</label>
          <div class="col-sm-8 form-inline">
            <input type="file" name="uploader" id="uploader" class="form-control-file border"/>
          </div>
        </div>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger" style="width:100%;" disabled>
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Upload Sindhi Murli PDF
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
      
      function validateForm0(form) {   //Correct filename: Murli_Batch_Sindhi_2020-04-20.pdf
        $('#initiater').prop("disabled", true);
        lower_val = document.getElementById("uploader").value.toLowerCase();
        if(!lower_val.endsWith("pdf")) {
          alert("Please upload a valid Sindhi PDF file only!!!");
          return false;
        } else if(!lower_val.includes("sindhi")) {
          alert("Please upload a valid Sindhi PDF file only!!!");
          return false;
        } else if(!(lower_val.split("_").length == 4 && lower_val.split("-").length == 3)) {
          alert("Sindhi PDF name format should exactly look like: Murli_Batch_Sindhi_2020-04-20.pdf");
        } else {
          document.getElementById("id_card").innerHTML = "<kbd style='background-color:#7160bb;'>Processing started... Kindly wait a moment!</kbd>";
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