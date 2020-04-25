<?php 
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log.log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  $generated_links_file = "./generated_links.txt";
  $message = '';
  
  function getFirstThreeCaptionLink($URL) {
    $lines = "";
    $html = file_get_html($URL);
    $elems = $html->find('.spotlight-blocks', 0)->children();
    $first_anchor = trim($elems[0]->href);
    if(count(($elems[0]->children())[1]->children()) > 1) {
      $first_caption = trim((($elems[0]->children())[1]->children())[0]->plaintext);
    }
    else $first_caption = trim(($elems[0]->children())[1]->plaintext);
    $first_caption = str_replace("&#39;", "'", $first_caption);
    $first_caption = str_replace("&#34;", "'", $first_caption);
    $first_caption = str_replace("&amp;", "and", $first_caption);
    $first_caption = str_replace(":", "-", $first_caption);
    $first_caption = str_replace("—", "-", $first_caption);
    $first_caption = str_replace(".", "", $first_caption);
    
    $lines = "1^$first_caption^$first_anchor\n";
    
    $children = $elems[1]->children();
    $second_anchor = trim($children[0]->href);
    $third_anchor = trim($children[1]->href);
    if(count(($children[0]->children())[1]->children()) > 1) {
      $second_caption = trim((($children[0]->children())[1]->children())[0]->plaintext);
    }
    else $second_caption = trim(($children[0]->children())[1]->plaintext);
    $second_caption = str_replace("&#39;", "'", $second_caption);
    $second_caption = str_replace("&#34;", "'", $second_caption);
    $second_caption = str_replace("&amp;", "and", $second_caption);
    $second_caption = str_replace(":", "-", $second_caption);
    $second_caption = str_replace("—", "-", $second_caption);
    $second_caption = str_replace(".", "", $second_caption);
    if(count(($children[1]->children())[1]->children()) > 1) {
      $third_caption = trim((($children[1]->children())[1]->children())[0]->plaintext);
    }
    else $third_caption = trim(($children[1]->children())[1]->plaintext);
    $third_caption = str_replace("&#39;", "'", $third_caption);
    $third_caption = str_replace("&#34;", "'", $third_caption);
    $third_caption = str_replace("&amp;", "and", $third_caption);
    $third_caption = str_replace(":", "-", $third_caption);
    $third_caption = str_replace("—", "-", $third_caption);
    $third_caption = str_replace(".", "", $third_caption);
    
    $lines = "$lines" . "2^$second_caption^$second_anchor\n3^$third_caption^$third_anchor\n";
    return $lines;
  }
  
  if(isset($_POST['create']) && isset($_POST['page_link']) && !empty($_POST['page_link'])) {
    $message = 'Below Results<ul>';
    $html = file_get_html($_POST['page_link']);
    $list = $html->find("ul[id=block-list_1-0] li");   //find all li in ul where ul id is block-list_1-0
    $counter = 0;
    if($list && count($list) > 0) {
      $myNewFile = fopen("$generated_links_file", "w");
      if(!$myNewFile) {
        $message = $message . "<li><span class='bg-danger text-white'>Unable to open $generated_links_file to write!!</span></li>";
      } else {
        if(!fwrite($myNewFile, getFirstThreeCaptionLink($_POST['page_link']))) {
          $message = $message . "<li><span class='bg-danger text-white'>Unable to write first 3 links into file $generated_links_file</span></li>";
        } else {
          $counter = 3;
        }
        foreach($list as $li) {
          $caption_div = $li->find('div[class=block__title]', 0);
          if($caption_div && !empty($caption_div)) {
            $caption = trim($caption_div->plaintext);
          }
          else $caption = trim($li->plaintext);
          $caption = str_replace("&#39;", "'", $caption);
          $caption = str_replace("&#34;", "'", $caption);
          $caption = str_replace("&amp;", "and", $caption);
          $caption = str_replace(":", "-", $caption);
          $caption = str_replace("—", "-", $caption);
          $caption = str_replace(".", "", $caption);
          $tmp = $li->find('a', 0);
          if($tmp) {
            $href = trim($li->find('a', 0)->href);
            $content_to_write = ($counter+1) . "^$caption^$href\n";
            if(!fwrite($myNewFile, $content_to_write)) {
              $message = $message . "<li><span class='bg-danger text-white'>Unable to write $content_to_write into file $generated_links_file!!</span></li>";
            } else {
              $counter++;
            }
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>anchor tag not found in one of this li!!</span></li>";
          }
        }
        fclose($myNewFile);
        $message = $message . "<li>Processing done! Total <kbd>$counter</kbd> links written into $generated_links_file</li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>Failed to filter 'ul[id=block-list_1-0] li'</span></li>";
    }
    $message = "$message</ul>";
  } else if(isset($_POST['create'])){
    $message = $message . "<span class='bg-danger text-white'>Invalid POST Values</span>";
  }

 
?>
<html lang="en">
  <head>
    <title>Gen Links</title>
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
  </head>
  <body style="padding-top: 70px;font-family: Ubuntu;background-color: #FFEBCC">
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return preProcess(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="page_link" class="col-sm-4 col-form-label">Page Link</label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="page_link" name="page_link" style="width:100%;">
          </div>
        </div>
        <button type="submit" id="create" name="create" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Create
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
    </div>
    <script language="javascript">
      
      function preProcess(form) {
        if(!document.getElementById("page_link").value.startsWith("http")) {
          alert("Invalid Page Link!! Must start with http://");
          return false;
        }
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#create').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("page_link").focus();
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>
