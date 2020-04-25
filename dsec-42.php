<?php
  

  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  include './util.php';
  include './dsec-config-42.php';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  //Set below variables ==============================
  $target_folder = "$rootdir/000-Ravi-DontDelete/htms";   //All generated files will go here
  $template_file = "$rootdir/000-Ravi-DontDelete/tmplts/section_files.txt";   //This is the template file which we will read
  //NOTE: This template file has 2 places where we'll insert data: [SECTION_TITLE] && [SECTION_OWNER]
  //Set above variables ==============================
  
  function getTotalHashesAndSections(&$hashes, &$sections) {
    global $section_array;
    $hashes = 0; $sections = 0;
    foreach($section_array as $idx => $val) {
      if(strpos($val, "------") !== false) {$hashes++;}
      else {$sections++;}
    }
  }
  
  //Note for below function:
  //  1) A section header immediately starts after "-------". below example
  //    "------------",
  //    "012. Daily Murli Sections",
  //    "01. Murli MahaVakya",
  
  //  2) If there is no further sub-section, then parent and all children MUST have same prefix number. below example
  //    "-------------------------------------",
  //    "014. Avyakt Murli Project",
  //    "014. Avyakt Murli Project",
  //    "-------------------------------------",
  function makeHtms() {
    global $message, $target_folder, $template_file, $section_array;
    $read_all_template = file_get_contents($template_file);
    $just_found_hash = true;
    $total_parent_sections = 0;
    $files_created_counts = 0;
    if(count($section_array) > 2) {
      getTotalHashesAndSections($hash_counts, $section_counts);   //call be reference to get hash and sections counts in the array
      foreach($section_array as $idx => $val) {
        if($just_found_hash) {   //means a new section is going to start
          $curr_section = $val;   //e.g. 010. Daily Section
          $total_parent_sections++;
          $just_found_hash = false;
          continue;
        }
        if(strpos($val, "-----") !== false) {   //hash found!
          $just_found_hash = true;
          continue;
        }
        $child_of_curr_section = $val;   //e.g. 01. Daily Murli
        $tmp_curr = explode(".", $curr_section);
        if(count($tmp_curr) >= 2) {
          $number_prefix_curr_section = trim($tmp_curr[0]);
          $tmp_child_curr = explode(".", $child_of_curr_section);
          if(count($tmp_child_curr) >= 2) {
            $number_prefix_child_curr_section = trim($tmp_child_curr[0]);
            $needed_page_title = trim($tmp_child_curr[1]);
            $needed_page_header = $needed_page_title;
            if($number_prefix_curr_section === $number_prefix_child_curr_section) {   //i.e. there is no sub-section
              $needed_file_name = "$val.htm";
            } else {
              $needed_file_name = "$number_prefix_curr_section.$val.htm";
            }
            $my_data = str_replace("[SECTION_TITLE]", $needed_page_title, $read_all_template);
            $my_data = str_replace("[SECTION_OWNER]", $needed_page_header, $my_data);
            if(!file_put_contents("$target_folder/$needed_file_name", $my_data)) {
              $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> creating $val.htm!</li>";
            } else {
              $files_created_counts++;
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Child section name $child_of_curr_section has no dots!!</li>";
          }
        } else {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Parent section name $curr_section has no dot!!</li>";
        }
      }
      $message = $message . "<li>Total Hashes: $hash_counts, Total Sections: $section_counts, Total Parent Sections: $total_parent_sections, Total Files Created: $files_created_counts</li>";
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Not enough sections to proceed!</li>";
    }
  }
  
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {
    $message = "Below Results:<ul>";
    if(file_exists($template_file) && filesize($template_file) > 2) {
      makeHtms();
      shell_exec("zip -r all_sections.zip htms");   //zipping entire htm folder
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Template file $template_file doesn't exist!</li>";
    }
    $message = "$message</ul>";
  } else {
    $message = "Output Here...";
  }
  
?>
<html lang="en">
  <head>
    <title>Murli Section Files Creator</title>
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
      $brand_name = "Murli Section Files Creator";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <div class="mt-4"><kbd style="background-color:blue;">Target Dir: <?php echo "[". str_replace("$rootdir/", "", "$target_folder") . "]"; ?></kbd></div>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger mt-4" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Click Me If Setup OK
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...'; ?>
        </div>
      </div>
    </div>
    <script language="javascript">
      
      function validateForm0(form) {
        document.getElementById("id_card").innerHTML = "<kbd style='background-color:#7160bb;'>Processing started... Kindly wait a moment!</kbd>";
        document.getElementById("initiater").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Initiating Your Request...';
        return true;
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>