<!DOCTYPE html>
<?php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');

  $message = '';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess', '.mp5');
    $files = array();    
    foreach(scandir($dir) as $file) {
      if(in_array($file, $ignored)) continue;
      $files[$file] = filemtime($dir . '/' . $file);
    }
    ksort($files);
    $files = array_keys($files);
    return ($files) ? $files : false;
  }
  
  function is_dir_empty($dir) {
    if(!is_readable($dir)) return false;
    return (count(scandir($dir)) == 2);   //ignoring default 2 directories '.' & '..'
  }
  
  if(isset($_POST['dir']) && !empty($_POST['dir']) && isset($_POST['datelabel']) && !empty($_POST['datelabel'])) {
    try {
      //Printing all $_POST keys and values
      //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
      $file_from_dir = $rootdir . $_POST['dir'] . '/' . $_POST['datelabel'];
      $file_to_dir = $rootdir . $_POST['dir'];
      if(is_dir($file_from_dir) && is_dir($file_to_dir)) {
        $file_name_array = scan_dir($file_from_dir);
        if($file_name_array) {   //there is at least one file exists
          $file_count = count($file_name_array);
          $message = '<span class="bg-dark text-white">Total # of files inside ' . $file_from_dir . ': <b><u>' . $file_count . '</u></b></span><ul>';
          $files_actually_moved = 0;
          for($i = 0; $i < $file_count; $i++) {
            //if(rename($file_from_dir . '/' . $file_name_array[$i], $rootdir . '/tmp/' . $file_name_array[$i])) {
            if(rename($file_from_dir . '/' . $file_name_array[$i], $file_to_dir . '/' . $file_name_array[$i])) {
              $message = $message . '<li>' . $file_name_array[$i] . ' <span class="bg-success text-white">MOVED SUCCESSFULLY</span></li>';
              $files_actually_moved++;
            } else {
              $message = $message . '<li>' . $file_name_array[$i] . ' <span class="bg-danger text-white">ERROR MOVING</span></li>';
            }
          }
          $message = $message . '</ul><span class="bg-warning text-white p-2">TOTAL ' . $files_actually_moved . ' FILES MOVED SUCCESSFULLY</span>';
        } else {
          $message = '<span class="bg-danger text-white">No files inside ' . $file_from_dir . ' !!</span>';
        }
        if(is_dir_empty($file_from_dir)) {
          if(!rmdir($file_from_dir)) {
            $message = $message . '<br/><br/><span class="bg-danger text-white p-2">Directory ' . $_POST['dir'] . '/' . $_POST['datelabel'] . ' was empty but error deleting it!!</span>';
          } else {
            $message = $message . '<br/><br/><span class="bg-success text-white p-2">Directory ' . $_POST['dir'] . '/' . $_POST['datelabel'] . ' was empty so deleted it successfully!</span>';
          }
        }        
      } else {
        $message = "ERROR: Please make sure all below folders exists in server:<ul><li>$file_from_dir</li><li>$file_to_dir</li></ul>";
      }
    } catch(Exception $e) {
      $message = 'ERROR/Exception: ' . $e->getMessage();
    }
  }    
?>
<html lang="en">
  <head>
    <title>HTM Move</title>
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
      $brand_name = 'HTM Move <i class="fa fa-share-square-o" aria-hidden="true"></i>';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateAnyFormFieldHere(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="dir" class="col-sm-4 col-form-label">Move For</label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="dir" name="dir">
              <option value="/00. Htm">/00. Htm</option>
              <option value="/00-htm">/00-htm</option>
              <option value="/00-Murlis/Mobile">/00-Murlis/Mobile</option>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="datelabel" class="col-sm-4 col-form-label">Folder To Move (e.g. 18.02)</label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="datelabel" name="datelabel" placeholder="Folder to move(e.g. 18.02)">
          </div>
        </div>
        <button type="submit" id="move" name="move" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Move
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
      Go Home
      </a>
    </div>
    <script language="javascript">
      var d = new Date();
      var dd = (d.getDate() + 1) < 9 ? ("0" + (d.getDate() + 1)) : (d.getDate() + 1);
      var mm = (d.getMonth() + 1) < 9 ? ("0" + (d.getMonth() + 1)) : (d.getMonth() + 1);
      document.getElementById("datelabel").value = dd + "." + mm;
      
      function validateAnyFormFieldHere(form) {
        disableFormControls();
        if(document.getElementById('datelabel').value.length < 1) {
          alert('Folder To Move value can not be empty !!');
          return false;
        }
        else {
          return true;
        }
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#move').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>