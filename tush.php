<!DOCTYPE html>
<?php
  //Reference: https://www.codediesel.com/php/downloading-gmail-attachments-in-php-an-update/
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  $message = '';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  $sender_list = array("tushar.k1@gmail.com");
  $display_value = 'none';
  
  $file_loc = array(
    "tushar.k1@gmail.com_ZIP" => "$rootdir/000-Ravi-DontDelete/Tushar/Tushar_ZIP",
    "tushar.k1@gmail.com_MISC" => "$rootdir/000-Ravi-DontDelete/Tushar/Tushar_MISC"
  );
  
  $valid_ext = array();
  $valid_ext["tushar.k1@gmail.com"] = array("zip", "pdf", "htm", "mp3");
  
  sort($sender_list);
  $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
  $username = 'bkravi.os.mirror@gmail.com';
  $password = 'bkravi$os$mirror';
  
  function startsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), 0, strlen($needle)) === 0;
  }
  
  function endsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
  }  
  
  function getAllAttachments($structure, $inbox, $email_number) {
    $attachments = array();
    if(isset($structure->parts) && count($structure->parts)) {  //if any attachments found
      for($i = 0; $i < count($structure->parts); $i++) {
        $attachments[$i] = array('is_attachment' => false, 'filename' => '', 'name' => '', 'attachment' => '');
        if($structure->parts[$i]->ifdparameters) {
          foreach($structure->parts[$i]->dparameters as $object) {
            if(strtolower($object->attribute) == 'filename') {
              $attachments[$i]['is_attachment'] = true;
              $attachments[$i]['filename'] = $object->value;
            }
          }
        }

        if($structure->parts[$i]->ifparameters) {
          foreach($structure->parts[$i]->parameters as $object) {
            if(strtolower($object->attribute) == 'name') {
              $attachments[$i]['is_attachment'] = true;
              $attachments[$i]['name'] = $object->value;
            }
          }
        }

        if($attachments[$i]['is_attachment']) {
            $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
            if($structure->parts[$i]->encoding == 3) {  //3 = BASE64 encoding
                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
            }
            elseif($structure->parts[$i]->encoding == 4) {   //4 = QUOTED-PRINTABLE encoding
                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
            }
        }
      }
    }
    return $attachments;
  }
  
  function downloadTushar($attachment, $final_file_name, $target_loc, $overwrite) {
    $attached_files = '';
    if(strlen($final_file_name) > 1) {
      if($overwrite === 0 && file_exists("$target_loc/$final_file_name") && filesize("$target_loc/$final_file_name") > 0) {
        $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$final_file_name has already been downloaded. Won't download again!!!</span></li>";
      } else {
        try {
          $action = '';
          if($overwrite === 1 && file_exists("$target_loc/$final_file_name") && filesize("$target_loc/$final_file_name") > 0) {
            $action = "<span class='bg-dark text-white'>Overwritten!</span>";
          } else {
            $action = "<span class='bg-success text-white'>Newly Downloaded!</span>";
          }
          $fp = fopen("$target_loc/$final_file_name", "w+");
          fwrite($fp, $attachment['attachment']);
          fclose($fp);
          if(endsWith($final_file_name, 'zip')) {
            //below unzipping it
            $zip = new ZipArchive;
            $res = $zip->open("$target_loc/$final_file_name");
            if($res === TRUE) {
              $path = "$target_loc/" . str_replace('.zip', '', $final_file_name);   // Unzip path
              $zip->extractTo($path);
              $zip->close();
              if(!unlink("$target_loc/$final_file_name")) {   //this is to delete the .zip file after unzipping
                $attached_files = $attached_files . "<li>$final_file_name $action and unzipped but <span class='bg-danger text-white'>failed to delete $final_file_name</span></li>";
              } else {
                $attached_files = $attached_files . "<li>$final_file_name $action and unzipped and <span class='bg-success text-white'>deleted $final_file_name successfully</span></li>";
              }
            } else {
              $attached_files = $attached_files . "<li>$final_file_name $action but <span class='bg-danger text-white'>failed unzipping!!</span></li>";
            }
          } else {
            $attached_files = $attached_files . "<li>$final_file_name $action</li>";
          }
        } catch(Exception $e) {
          $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$final_file_name ERROR/Exception " . $e->getMessage() . "</span></li>";
        }
      }
    } else {
      $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Wrong FILENAME $final_file_name</span></li>";
    }
    return $attached_files;
  }
  
  function stringContainsAll($string_to_test, $array_of_substrs) {
    if(count($array_of_substrs) < 1) return true;
    for($i = 0; $i < count($array_of_substrs); $i++) {
      if(strpos($string_to_test, $array_of_substrs[$i]) !== false);
      else return false;
    }
    return true;
  }
  
  function processAttachments($attachments, $mail_from, $mail_udate, $overwrite) {
    global $rootdir, $valid_ext, $file_postfix, $file_loc;
    $attached_files = '';
    if(count($attachments) > 0) {
      foreach($attachments as $attachment) {
        if($attachment['is_attachment'] == 1) {
          $filename_as_rcvd = $attachment['name'];
          if(empty($filename_as_rcvd)) $filename_as_rcvd = $attachment['filename'];
          if(!empty($filename_as_rcvd)) {
            if($mail_from === "tushar.k1@gmail.com") {
              $lowercase_file_name = strtolower($filename_as_rcvd);
              $ext = substr($lowercase_file_name, strrpos($lowercase_file_name, ".", -1) + 1);   //file extension
              if(in_array($ext, $valid_ext[$mail_from])) {
                if($ext === "zip") {
                  $file_name_without_ext = str_replace('.zip', '', $filename_as_rcvd);
                  $target_loc = $file_loc[$mail_from . '_ZIP'];
                  $final_file_name = "$file_name_without_ext-$mail_udate.zip";
                  $attached_files = $attached_files . downloadTushar($attachment, $final_file_name, $target_loc, $overwrite);
                } else {
                  $target_loc = $file_loc[$mail_from . '_MISC'];
                  $attached_files = $attached_files . downloadTushar($attachment, $filename_as_rcvd, $target_loc, $overwrite);
                }
              } else {
                $attached_files = $attached_files . "<li><span class='bg-danger text-white'>$filename_as_rcvd $ext not valid from $mail_from!</span></li>";
              }
            }
            else {
              $attached_files = $attached_files . "<li><span class='bg-danger text-white'>Sender $mail_from is not yet configured!</span></li>";
            }
          } else {
            $attached_files = $attached_files . '<li>Empty file name attachment found!</li>';
          }
        }
      }
      return $attached_files;
    } else {
      return '<li><span class="bg-danger text-white">No attachments found!</span></li>';
    }
  }
  
  if(isset($_POST['proceed'])) {   //this means proceed button on form is pressed
    $filter = '';
    
    //1) setting email from filter
    if($_POST['email_from'] === 'ALL') $filter = $filter . 'ALL ';
    else $filter = $filter . 'FROM "' . $_POST['email_from'] . '" ';
    
    //2) adding up date filter
    $curr_date = date_create(date("Y-m-d"));
    $prev_date = $curr_date;
    date_sub($prev_date, date_interval_create_from_date_string($_POST['since'] . " days"));
    $filter = $filter . 'SINCE "' . $prev_date->format('d M Y') . '" ';
    
    //3) adding up seen/unseen filter
    $filter = $filter . $_POST['type'];
    $filter = trim($filter);
    try {
      $inbox = imap_open($hostname, $username, $password);
      if($inbox) {
        $emails = imap_search($inbox, $filter);
        $mail_from = '';
        $attachments = array();
        if($emails) {
          rsort($emails);   //put the newest emails on top
          foreach($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $detailed_overview = imap_headerinfo($inbox, $email_number);
            //echo json_encode($detailed_overview) . "<br><br>";
            $mail_from = $detailed_overview->from[0]->mailbox . '@' . $detailed_overview->from[0]->host;
            $structure = imap_fetchstructure($inbox, $email_number);
            //echo json_encode($structure) . "<br><br>";
            $attachments = getAllAttachments($structure, $inbox, $email_number);
            $message = $message . "<span style='color:yellow;'>From: $mail_from on " . $detailed_overview->date . "</span><ul>";
            $message = $message . processAttachments($attachments, $mail_from, $detailed_overview->udate, intval($_POST['mode']));
            $message = $message . '</ul>';
          }
          $message = $message . '<span class="bg-warning text-white p-2">Total ' . count($emails) . ' email(s) processed!!</span>';
          $display_value = 'block';
        } else {
          $message = $message . '<br><br><span class="bg-danger text-white">No emails found using filter: <span class="text-warning">' . $filter . '</span></span>';
        }
        imap_close($inbox);
      } else {
        $message = $message . '<br><br><span class="bg-danger text-white">Cannot connect to Gmail: ' . imap_last_error() . '</span>';
      }
    } catch(Exception $e) {
      $message = $message . '<br><br><span class="bg-danger text-white">ERROR/EXCEPTION: ' . $e->getMessage() . '</span>';
    }
    $message = startsWith($message, '<br><br>') ? str_replace('<br><br>', '', $message) : $message;
  }

?>
<html lang="en">
  <head>
    <title>Tushar Bhai's Zip Processor</title>
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
      $brand_name = "Tushar Bhai's Zip <i class='fa fa-file-archive-o' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="email_from" class="col-sm-4 col-form-label">From: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="email_from" name="email_from">
              <option value="tushar.k1@gmail.com">tushar.k1@gmail.com</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="since" class="col-sm-4 col-form-label">Since: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="since" name="since">
              <option value="0">Today</option>
              <option value="1">Yesterday</option>
              <option value="2">1 Day Before Yesterday</option>
              <option value="3">2 Days Before Yesterday</option>
              <option value="4">3 Days Before Yesterday</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="mode" class="col-sm-4 col-form-label">Mode: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="mode" name="mode">
              <!--<option value="0">Do Not Overwrite</option>-->
              <option value="1">Overwrite</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="type" class="col-sm-4 col-form-label">Type: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="type" name="type">
              <option value="SEEN">Seen</option>
              <option value="UNSEEN">UnSeen</option>
              <option value="" selected>Both</option>
            </select>
          </div>
        </div>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Download And Unzip The ZIP
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
      <div class="mt-3" <?php echo "style='display:$display_value';";  ?>>
        <a class="btn btn-success" style="width:100%;" href="tush-p1.php">Click To Move These Downloaded Files</a>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
        Go Home
      </a>
    </div>
    <script language="javascript">
      
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