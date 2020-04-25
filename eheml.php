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
  $hindi_pdf_dir = "$rootdir/01. Daily Murli/01. Hindi/02. Hindi Murli - Pdf";
  $hindi_file_postfix = "-h.pdf";
  $eng_pdf_dir = "$rootdir/01. Daily Murli/02. English/02. Eng Murli - Pdf";
  $eng_file_postfix = "-E.pdf";
  
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
  
  function trySplittingAndMovePDFs($sourceFileFullNameArray) {   //argument must be an array (../../E-Batch-2020-04-27-unlocked.pdf)
    global $hindi_pdf_dir, $hindi_file_postfix;
    global $eng_pdf_dir, $eng_file_postfix;
    $sam_msg_array = array();
    foreach($sourceFileFullNameArray as $sourceFileFullName) {
      $o_f_n = onlyFileName($sourceFileFullName);
      $command_output = '';
      $return_val = '';
      $exec_command_to_get_total_pages = "gs -q -dNODISPLAY -c \"($sourceFileFullName) "
                                        . "(r) file runpdfbegin pdfpagecount = quit\"";
      exec($exec_command_to_get_total_pages, $command_output, $return_val);
      if(!$return_val) {
        $pages = (int)implode("", $command_output);
        if($pages !== 21) {
          $sam_msg_array[] = "<li><span class='bg-warning text-white'>SORRY!!</span> At present I support PDFs having exactly 21 pages! <kbd>$o_f_n PDF has $pages pages!!</kbd></li>";
        } else {
          $tmp = explode("-", $o_f_n);   //splitting E-Batch-2020-04-27-unlocked
          if(count($tmp) != 6) {
            $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> $o_f_n must be in this format: E-Batch-2020-04-27-unlocked</li>";
            continue;
          }
          $yy = substr($tmp[2], 2, 2); $mm = $tmp[3]; $dd = $tmp[4];
          $start_date = "$dd.$mm.$yy";
          for($loop = 1; $loop <= 21; $loop += 3) {   //dividing 21 page PDF into 7 sub pdfs
            if(strpos(strtolower($o_f_n), "h-batch") !== false) {$reqd_dir = $hindi_pdf_dir; $reqd_postfix = $hindi_file_postfix;}
            else if(strpos(strtolower($o_f_n), "e-batch") !== false) {$reqd_dir = $eng_pdf_dir; $reqd_postfix = $eng_file_postfix;}
            else {
              $sam_msg_array[] = "<li><kbd>$o_f_n neither contains 'hindi' nor 'english'!</kbd></li>";
              break;
            }
            $exec_command_to_split = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=\"$reqd_dir\"/$start_date$reqd_postfix "
                            . "-dFirstPage=$loop -dLastPage=" . ($loop+2) ." -dPDFSettings=/prepress -dAutoRotatePages=/None "
                            . "-f \"$sourceFileFullName\"";
            exec($exec_command_to_split, $command_output, $return_val);
            if($return_val === 0) {   //command success
              $sam_msg_array[] = "<li><kbd>$start_date$reqd_postfix</kbd> created <span class='bg-success text-white'>Successfully</span></li>";
            } else {
              $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> splitting PDF for $start_date$reqd_postfix</li>";
              break;
            }
            $start_date = addDaysToDate(1, $start_date, 'd.m.y', 'd.m.y');
          }
          if($loop == 22) {   //i.e. above for loop ran OK
            $sam_msg_array[] = "<li>$o_f_n all split<span class='bg-success text-white'> Successfully</span></li>";
          } else {
            $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> Something went wrong for itirating 7 times! for <kbd style='background-color:red;'>$o_f_n</kbd></li>";
          }
        }
      } else {
        $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> counting PDF pages!</li>";
      }
    }
    
    //On success: "PDF split and moved successfully"
    //NOTE: Do not change this success message as it is being used in many places to check if all went OK
    return $sam_msg_array;
  }
  
  function removePWDProtectionAndEmail($pdf_full_filename_array) {
    global $outputDir;
    $return_msg = "";
    $outputFilesArray = array();
    foreach($pdf_full_filename_array as $pdf_full_filename) {
      if(!preg_match("/(pdf)$/", strtolower($pdf_full_filename))) {
        $return_msg = $return_msg . "<li><span class='bg-danger text-white'>File '$pdf_full_filename' is NOT a PDF file!!</span></li>";
      } else {
        $path_parts = pathinfo($pdf_full_filename);
        $tmp = $path_parts['filename'];   //e.g. Murli_Batch_Hindi_2020-04-27.pdf, Murli_Batch_English_2020-04-27.pdf
        $tmp = str_replace("Murli", "", $tmp);
        $tmp = str_replace("Batch", "", $tmp);
        $tmp = str_replace("_", "", $tmp);
        $tmp = str_replace("Hindi", "H-Batch-", $tmp);
        $tmp = str_replace("English", "E-Batch-", $tmp);
        $finalFileNameOnly = "$tmp-unlocked.{$path_parts['extension']}";
        $outputFileFullName = "{$path_parts['dirname']}/$finalFileNameOnly";
        $exec_command = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 "
                        . "-sOutputFile=$outputFileFullName -c .setpdfwrite -f $pdf_full_filename";
        exec($exec_command, $command_output, $return_val);
        foreach($command_output as $line) {
          $return_msg = $return_msg . "<li>$line</li>";
        }
        if(!$return_val) {
          $return_msg = $return_msg . "<li><kbd style='background-color:#7160bb;'>" . fileWithExt($pdf_full_filename) . " Protection removed</kbd> <span class='bg-success text-white'>Sucecssfully!</span></li>";
          $outputFilesArray[] = $outputFileFullName;
        } else {
          $return_msg = $return_msg . "<li><span class='bg-danger text-white'>ERROR!!</span> removing PDF protection for " . fileWithExt($pdf_full_filename) . "!</li>";
        }
      }
    }
    if(count($outputFilesArray) > 0) {
      $emailee_list = array(
        "ekta.khakhar@gmail.com", "uma1507@gmail.com", "ggmggm95@gmail.com", 
        "kelmadhu@gmail.com", "sandeep.cse09@gmail.com", "tarunluthra1000@gmail.com", "yogeshwar_k@hotmail.com", 
        "sonamgpt18@gmail.com", "ashana.vasudeva123@gmail.com", "bkmukesh1973@gmail.com", 
        "hardik.acharya2012@gmail.com", "bkamitaithaca@yahoo.com", "bksujitmshhr6@gmail.com", 
        "shivbabamurlitamil@gmail.com", "sethi12126@gmail.com", "bksagartudu@gmail.com", "shefu224@gmail.com", 
        "reach.pragya@gmail.com", "mala.khetarpal@rediffmail.com", "bknavrang@gmail.com", "bkshivam150@gmail.com", 
        "madhvi.lamba09@gmail.com", "kv.kavyashree@gmail.com", "amola51@gmail.com", 
        "sudipa.mandal@cse.iitkgp.ernet.in", "murlimylife@gmail.com", "bkrameshkumaran@gmail.com", 
        "bk.subham1234@gmail.com", "mrksetty@isac.gov.in", "himthanineelam@gmail.com", 
        "bkteluguclasses@gmail.com", "bkchandan255@gmail.com", "bkdl@live.in", "kabidash@gmail.com",
      );
      //before email, lets see if we can split 21 pages PDF into 7 days sub pdfs
      $return_msg = $return_msg . implode("", trySplittingAndMovePDFs($outputFilesArray));
      $subject = "Files: [" . str_replace("$outputDir/", "", implode(", ", $outputFilesArray)) . "] - [DO NOT REPLY!! AUTO GEN E-MAIL!]<os>";
      $email_msg_array = sendEmail($emailee_list, $subject, "<b>IT'S AN AUTO GENERATED E-MAIL. PLEASE DO NOT REPLY!!</b>", $outputFilesArray);
      foreach($email_msg_array as $email_msg) {
        $return_msg = $return_msg . "<li>$email_msg</li>";
      }
      if(strpos(strtolower($return_msg), "email sent successfully") !== false) {   //e-mail send successfully
        foreach($outputFilesArray as $file) {
          if(rename($file, "$file.sent")) {
            $return_msg = $return_msg . "<li><kbd>Renamed</kbd> to " . fileWithExt("$file.sent") . "</li>";
          } else {
            $return_msg = $return_msg . "<li><span class='bg-danger text-white'>Renaming </span> to " . fileWithExt("$file.sent") . " failed!!</li>";
          }
        }
      }
    } else {
      $return_msg = $return_msg . "<li><span class='bg-danger text-white'>ERROR!!</span> No PDF file to attach!!</li>";
    }
    return "$return_msg";
  }
  
  $sent_files_hin_array = myScan($outputDir, "*H-Batch*.sent", 1, 1);   //filtering Hindi sent files
  $sent_files_eng_array = myScan($outputDir, "*E-Batch*.sent", 1, 1);   //filtering English sent files
  $sent_files_array = array_merge($sent_files_hin_array, $sent_files_eng_array);
  
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {   //"Upload French Murli PDF" button is clicked
    if(isset($_FILES['uploader_h']) && isset($_FILES['uploader_e'])) {
      $uploaded_file_name_array = array();
      $message = "Below results:<ol>";
      foreach($_FILES as $key => $value) {
        if($_FILES[$key]['error'] === UPLOAD_ERR_OK) {
          $fileTmpPath = $_FILES[$key]['tmp_name'];
          $fileName = $_FILES[$key]['name'];
          $fileSize = $_FILES[$key]['size'];
          $fileType = $_FILES[$key]['type'];
          $fileNameCmps = explode(".", $fileName);
          $fileExtension = strtolower(end($fileNameCmps));
          $newFileName = str_replace(" ", "", $fileName);   //sanitize the filename
          if(in_array($fileExtension, $allowedfileExtensions)) {
            if(move_uploaded_file($fileTmpPath, "$outputDir/$newFileName")) {
              $message = $message . "<li><kbd>$newFileName</kbd> <span class='bg-success text-white'>Successfully</span> Uploaded!!</li>";
              $uploaded_file_name_array[] = "$outputDir/$newFileName";
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> moving file $fileName from $fileTmpPath to $outputDir</li>";
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> File extension $fileExtension is not yet supported in $fileName!</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-warning text-white'><kbd>NOTE for $key:</kbd> " . $upload_error_values[$_FILES[$key]['error']] . "</span></li>";
        }
      }
      if(count($uploaded_file_name_array) > 0) {
        $message = $message . removePWDProtectionAndEmail($uploaded_file_name_array);
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span><kbd>[0]</kbd> files successfully uploaded for further processing!!</li>";
      }
      $message = "$message</ol>";
    } else {
      $message = "<span class='bg-danger text-white'>ERROR!!</span> Wrong form POSTing!";
    }
  } else {
    $message = "Output Here...";
  }
  
?>
<html lang="en">
  <head>
    <title>Eng Hin PDF Emailer</title>
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
      $brand_name = 'Email Eng Hin Murli PDF';
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
          <label for="uploader_h" class="col-sm-4 col-form-label">Upload Hindi Murli PDF:</label>
          <div class="col-sm-8 form-inline">
            <input type="file" name="uploader_h" id="uploader_h" class="form-control-file border"/>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="uploader_e" class="col-sm-4 col-form-label">Upload English Murli PDF:</label>
          <div class="col-sm-8 form-inline">
            <input type="file" name="uploader_e" id="uploader_e" class="form-control-file border"/>
          </div>
        </div>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger" style="width:100%;" disabled>
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Upload Murli PDFs
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
      
      function isAtLeastOneFileInputIsOK() {
        h_val = document.getElementById("uploader_h").value;
        e_val = document.getElementById("uploader_e").value;
        if(h_val.length > 5 && !(h_val.toLowerCase().includes("indi"))) return false;   //hindi field must have hindi PDF
        if(e_val.length > 5 && !(e_val.toLowerCase().includes("nglish"))) return false;   //Eng field must have Eng PDF
        return h_val.toLowerCase().endsWith("pdf") || e_val.toLowerCase().endsWith("pdf");
      }
      
      $(document).ready(
        function(){
          $('input:file').change(
            function(){
              if($(this).val()) {
                if(!$(this).val().toLowerCase().endsWith("pdf")) {
                  alert("Please upload a valid Hindi or English PDF file to the corresponding file input fields only!!!");
                  $('#initiater').prop("disabled", true);
                } else {
                  $('#initiater').prop("disabled", false);
                  $('#initiater').removeClass("disabled");
                }
              } else {
                if(isAtLeastOneFileInputIsOK()) {
                  $('#initiater').prop("disabled", false);
                  $('#initiater').removeClass("disabled");
                } else {
                  $('#initiater').prop("disabled", true);
                }
              }
            }
          );
      });
      
      function validateForm0(form) {
        $('#initiater').prop("disabled", true);
        if(!isAtLeastOneFileInputIsOK()) {
          alert("Please upload a valid Hindi or English PDF file to the corresponding file input fields only!!!");
          return false;
        } else {
          document.getElementById("id_card").innerHTML = 'Processing your request... Please wait a moment..';
          document.getElementById("initiater").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Initiating Your Request...';
          $('#initiater').prop("disabled", false);   //this is essential otherwise form _POST wont get submit button key
          $('#initiater').removeClass("disabled");   //this is essential otherwise form _POST wont get submit button key
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