<?php
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  $message = '';
  $display_status = 'block';
  $display_status_for_do_it_again = 'none';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $filename_filepath = array();
  
  $file_destination_array = array(
    //Desktop Murli htm locations
    'Assame.htm' => "$rootdir/01. Daily Murli/08. Assame/02. Assam3 - Htm",
    'Bengali.htm' => "$rootdir/01. Daily Murli/07. Bengali/01. Bengali Murli - Htm",
    'Deutsch.htm' => "$rootdir/01. Daily Murli/31. Deutsch/Htm-Deutsch",
    'French.htm' => "$rootdir/01. Daily Murli/36. French/Htm-French",
    'Greek.htm' => "$rootdir/01. Daily Murli/37. Greek/Htm-Greek",
    'Gujarati.htm' => "$rootdir/01. Daily Murli/09. Gujarati/01. Gujarati Murli - Htm",
    'Hungarian.htm' => "$rootdir/01. Daily Murli/38. Hungarian/Htm-Hungarian",
    'Italian.htm' => "$rootdir/01. Daily Murli/33. Italian/Htm-Italiano",
    'K.htm' => "$rootdir/01. Daily Murli/05. Kannada/01. Kannada Murli - Htm",
    'Korean.htm' => "$rootdir/01. Daily Murli/39. Korean/Htm-Korian",
    'Mal.htm' => "$rootdir/01. Daily Murli/06. Malayalam/01. Malayalam Murli - Htm",
    'Marathi.htm' => "$rootdir/01. Daily Murli/12. Marathi/01. Marathi Murli - Htm",
    'Nep.htm' => "$rootdir/01. Daily Murli/30. Nepali/03. Nepali Murli - Htm",
    'Odia.htm' => "$rootdir/01. Daily Murli/10. Odiya/01. Odiya Murli - Htm",
    'Polish.htm' => "$rootdir/01. Daily Murli/40. Polish/Htm-Polish",
    'Port.htm' => "$rootdir/01. Daily Murli/41. Portuguese/Htm-Portuguese",
    'Pun.htm' => "$rootdir/01. Daily Murli/11. Punjabi/01. Punjabi Murli - Htm",
    'Sinhala.htm' => "$rootdir/01. Daily Murli/44. Sinhala/Htm-Sinhala",
    'Spanish.htm' => "$rootdir/01. Daily Murli/32. Spanish/Htm-Spanish",
    'TamilLanka.htm' => "$rootdir/01. Daily Murli/35. Tamil-Lanka/Htm-Tamil-Lanka",
    'Telugu.htm' => "$rootdir/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm",
    'Thai.htm' => "$rootdir/01. Daily Murli/43. Thai/Htm-Thai",

    //Mobile Murli htm locations
    'Assame-Mob.htm' => "$rootdir/01. Daily Murli/08. Assame/03. Assam3 - Mobile Htm",
    'Bengali-Mob.htm' => "$rootdir/01. Daily Murli/07. Bengali/05. Bengali Murli - Mobile Htm",
    'Deutsch-Mob.htm' => "$rootdir/01. Daily Murli/31. Deutsch/Mobile Htm-Deutsch",
    'French-Mob.htm' => "$rootdir/01. Daily Murli/36. French/Mobile Htm-French",
    'Greek-Mob.htm' => "$rootdir/01. Daily Murli/37. Greek/Mobile Htm-Greek",
    'Gujarati-Mob.htm' => "$rootdir/01. Daily Murli/09. Gujarati/02. Gujarati Mobile - Htm",
    'Hungarian-Mob.htm' => "$rootdir/01. Daily Murli/38. Hungarian/Mobile Htm-Hungarian",
    'Italian-Mob.htm' => "$rootdir/01. Daily Murli/33. Italian/Mobile Htm-Italiano",
    'K-Mob.htm' => "$rootdir/01. Daily Murli/05. Kannada/06. Kannada Murli - Mobile -  Htm",
    'Korean-Mob.htm' => "$rootdir/01. Daily Murli/39. Korean/Mobile Htm-Korian",
    'Mal-Mob.htm' => "$rootdir/01. Daily Murli/06. Malayalam/04. Malayalam Murli -Mobile",
    'Marathi-Mob.htm' => "$rootdir/01. Daily Murli/12. Marathi/02. Marathi Mobile - Htm",
    'Nep-Mob.htm' => "$rootdir/01. Daily Murli/30. Nepali/03. Nepali Murli - Mobile Htm",
    'Odia-Mob.htm' => "$rootdir/01. Daily Murli/10. Odiya/04. Odiya Murli - Mobile Htm",
    'Polish-Mob.htm' => "$rootdir/01. Daily Murli/40. Polish/Mobile Htm-Polish",
    'Port-Mob.htm' => "$rootdir/01. Daily Murli/41. Portuguese/Mobile Htm-Portuguese",
    'Pun-Mob.htm' => "$rootdir/01. Daily Murli/11. Punjabi/03. Punjabi Murli - Mobile Htm",
    'Sinhala-Mob.htm' => "$rootdir/01. Daily Murli/44. Sinhala/Mobile Htm-Sinhala",
    'Spanish-Mob.htm' => "$rootdir/01. Daily Murli/32. Spanish/Mobile Htm-Spanish",
    'TamilLanka-Mob.htm' => "$rootdir/01. Daily Murli/35. Tamil-Lanka/Mobile Htm-Tamil-Lanka",
    'Telugu-Mob.htm' => "$rootdir/01. Daily Murli/04. Telugu/36. Mobile Htm",
    'Thai-Mob.htm' => "$rootdir/01. Daily Murli/43. Thai/Mobile Htm-Thai",
    
    //Desktop Murli.pdf locations
    'Assame.pdf' => "$rootdir/01. Daily Murli/08. Assame/01. Assame Murli - Pdf",
    'Bengali.pdf' => "$rootdir/01. Daily Murli/07. Bengali/02. Bengali Murli - Pdf",
    'Deutsch.pdf' => "$rootdir/01. Daily Murli/31. Deutsch/PDF-Deutsch",
    'French.pdf' => "$rootdir/01. Daily Murli/36. French/PDF-French",
    'Greek.pdf' => "$rootdir/01. Daily Murli/37. Greek/PDF-Greek",
    'Gujarati.pdf' => "$rootdir/01. Daily Murli/09. Gujarati/03. Gujarati Murli - PDF",
    'Hungarian.pdf' => "$rootdir/01. Daily Murli/38. Hungarian/PDF-Hungarian",
    'Italian.pdf' => "$rootdir/01. Daily Murli/33. Italian/PDF-Italiano",
    'K.pdf' => "$rootdir/01. Daily Murli/05. Kannada/02. Kannada Murli - Pdf",
    'Korean.pdf' => "$rootdir/01. Daily Murli/39. Korean/PDF-Korian",
    'Mal.pdf' => "$rootdir/01. Daily Murli/06. Malayalam/02. Malayalam Murli - Pdf",
    'Marathi.pdf' => "$rootdir/01. Daily Murli/12. Marathi/03. Marathi Murli - PDF",
    'Nep.pdf' => "$rootdir/01. Daily Murli/30. Nepali/02. Nepali Murli - Pdf",
    'Odia.pdf' => "$rootdir/01. Daily Murli/10. Odiya/02. Odiya Murli - Pdf",
    'Polish.pdf' => "$rootdir/01. Daily Murli/40. Polish/PDF-Polish",
    'Port.pdf' => "$rootdir/01. Daily Murli/41. Portuguese/PDF-Portuguese",
    'Pun.pdf' => "$rootdir/01. Daily Murli/11. Punjabi/02. Punjabi Murli - PDF",
    'Sinhala.pdf' => "$rootdir/01. Daily Murli/44. Sinhala/PDF-Sinhala",
    'Spanish.pdf' => "$rootdir/01. Daily Murli/32. Spanish/PDF-Spanish",
    'TamilLanka.pdf' => "$rootdir/01. Daily Murli/35. Tamil-Lanka/PDF-Tamil-Lanka",
    'Telugu.pdf' => "$rootdir/01. Daily Murli/04. Telugu/02. Telugu - Murli - Pdf",
    'Thai.pdf' => "$rootdir/01. Daily Murli/43. Thai/PDF-Thai",
  );
  
  function endsWith($haystack, $needle) {
    return substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
  }  
  
  function dirtree($dir, $regex='', $ignoreEmpty=false) {
    if (!$dir instanceof DirectoryIterator) {
      $dir = new DirectoryIterator((string)$dir);
    }
    $dirs  = array();
    $files = array();
    foreach ($dir as $node) {
      if ($node->isDir() && !$node->isDot()) {
        $tree = dirtree($node->getPathname(), $regex, $ignoreEmpty);
        if (!$ignoreEmpty || count($tree)) {
          $dirs[$node->getFilename()] = $tree;
        }
      } elseif ($node->isFile()) {
        $name = $node->getFilename();
        if ('' == $regex || preg_match($regex, $name)) {
          $files[] = $name;
        }
      }
    }
    asort($dirs);
    sort($files);
    return array_merge($dirs, $files);
  }  
  
  function listArrayRecursive($someArray) {
    global $message, $filename_filepath;
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($someArray), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $k => $v) {
      $indent = str_repeat('&nbsp;', 2 * $iterator->getDepth());
      if ($iterator->hasChildren()) {   //means its a directory
        $message = $message . "$indent$k :<br>";
      } else {   //means we've reached the leaf. its a file
        if(!endsWith($v, "zip")) {   //ignoring zip files
          for($p = array(), $i = 0, $z = $iterator->getDepth(); $i <= $z; $i++) {
            $p[] = $iterator->getSubIterator($i)->key();
          }
          $path = implode('/', $p);
          $message = $message . "$indent$k : $v<br>";   //$v will give the file name
          $filename_filepath[$v] = substr($path, 0, strrpos($path, '/'));
        }
      }
    }
  }  
  
  $files_moved_OK = 0;
  $files_moved_NOTOK = 0;
  $files_pending = 0;
  
  if(isset($_POST['proceed'])) {   //this means proceed button on form is pressed
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $message = "<span class='bg-warning text-white p-2'>Below is the complete move log:</span><br><br><ul>";
    $display_status = 'none';
    $display_status_for_do_it_again = 'block';
    $files_moved_OK = 0;
    $files_moved_NOTOK = 0;
    $files_pending = 0;
    foreach ($_POST as $key => $value) {   //looping through all $_POST keys. This means looping through all form hidden input fields
      if($key !== 'proceed') {   //means we dont need to do anything for proceed key. its the key for form button. we are interested only in hidden input controls only
        $tmp_array = explode('^', $value);   //$value is 'filename^filepath as set this value in hidden input field'
        $filename = $tmp_array[0];
        $filepath = $tmp_array[1];
        if(strpos(strtolower($filepath), 'pending') !== false) {
          $message = $message . '<li>' . $filename . ' <span class="bg-secondary text-white">PENDING</span></li>';
          $files_pending++;
        } else {
          try {
            $targetdir = $file_destination_array[substr($filename, strpos($filename, '-') + 1)];
            if($targetdir) {
              if(rename("./Tushar/$filepath/$filename", "$targetdir/$filename")) {
                $message = $message . '<li>' . $filename . ' <span class="bg-success text-white">OK</span></li>';
                $files_moved_OK++;
              } else {
                $message = $message . '<li>' . $filename . ' <span class="bg-danger text-white">ERROR</span></li>';
                $files_moved_NOTOK++;
              }
            } else {
              $message = $message . '<li>' . $filename . ' <span class="bg-danger text-white">ERROR-NO-DESTINATION</span></li>';
              $files_moved_NOTOK++;
            }
          } catch(Exception $e) {
            $message = $message . '<li><span class="bg-danger text-white">' . $filename . ' got ERROR/EXCEPTION ' . $e->errorMessage() . '</span></li>';
            $files_moved_NOTOK++;
          }
        }
      }
    }
    $message = $message . "</ul><span class='bg-warning text-white p-2' style='line-height:2.5rem;'>TOTAL <span class='bg-success'>$files_moved_OK files moved OK</span> , <span class='bg-danger'>$files_moved_NOTOK files NOT OK</span> & <span class='bg-secondary'>$files_pending files PENDING</span></span>";
    shell_exec("rm -rf \"$rootdir/000-Ravi-DontDelete/Tushar/Tushar_ZIP\"/*");   //empty Tushar_ZIP
    shell_exec("rm -rf \"$rootdir/000-Ravi-DontDelete/Tushar/Tushar_MISC\"/*");   //empty Tushar_MISC
  } else {
    try {
      
      //One way
      listArrayRecursive(dirtree('./Tushar'));
      
      /*
      //Another way
      $targetdir1 = './Tushar';
      $file = json_encode(dirtree($targetdir1));
      $message = "$message $file<br>";
      */
      
      //Another way
      /*
      $targetdir1 = './Tushar';
      $rdi = new RecursiveDirectoryIterator($targetdir1, RecursiveDirectoryIterator::KEY_AS_PATHNAME);
      foreach(new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::SELF_FIRST) as $file => $info) {
        if(endsWith($file, ".") || endsWith($file, "..") || endsWith($file, "zip"));
        else $message = is_dir($file) ? ("$message $file<br>") : ("$message " . substr($file, strrpos($file, "/", -1) + 1) . "<br>");
      }
      */
      
    } catch(Exception $e) {
      $message = $message . 'ERROR/EXCEPTION: ' . $e->getMessage();
    }
  }
?>
<html lang="en">
  <head>
    <title>Tushar Bhai's Zip Processor-Part</title>
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
      $brand_name = "Tushar Bhai's <b>Zip-Part</b> <i class='fa fa-file-archive-o' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm1(this);" style="position:relative;">
        <div class="card bg-info text-white" style="margin-top:35px;">
          <div class="card-body">
            <?php if(strlen($message) > 2) echo $message; else echo "<span class='bg-danger text-white'>No Tushar Files To Process!!</span>"; ?>
          </div>
        </div>
        <?php 
          $id = 0;
          foreach($filename_filepath as $filename => $filepath) {
            $id++;
            echo "<input type='hidden' name='hidden_$id' value='$filename^$filepath'>";
          }
        ?>
        <button type="submit" id="proceed" name="proceed" class="btn btn-danger mt-3" <?php echo "style='display:$display_status; position: fixed; top: 50px; width:70%;'" ?>>
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Confirm & Move Files Now
        </button>
      </form>
      <a href="i.php" class="btn btn-success" <?php echo "style='position: fixed;bottom: 3px;right: 3px;display:$display_status_for_do_it_again;'";  ?>>
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