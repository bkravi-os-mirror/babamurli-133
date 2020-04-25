<?php 
  
  /*
    heading:
      article-heading_1-0
    cpmplete page:
      mntl-sc-page_1-0

    mntl-sc-block_1-0
    mntl-sc-block_1-0-1
    mntl-sc-block_1-0-2
    mntl-sc-block_1-0-3
  */
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log.log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  $skip_classes_that_contains = array("adslot", "featuredlink", "block-image", "video", "guide");
  $links_filename = "./generated_links.txt";
  $message = '';
  
  $working_dir = "./x-verywellmind/03. Psychology/05. Theories/04. Personality Psychology/01. Myers-Briggs Type Indicator";
  
  function getHeading() {
    global $working_dir;
    $tmp = explode("/", $working_dir);
      if(count($tmp) > 2) {
        $tmp = $tmp[2];
          $tmp = explode(".", $tmp);
          if(count($tmp) > 1) {
            return trim($tmp[1]);
          } else {
            return trim($tmp[0]);
          }
      } else {
        return "Mind";
      }
  }

  function getSubHeading() {
    global $working_dir;
    $tmp = explode("/", $working_dir);
      if(count($tmp) > 3) {
        $tmp = $tmp[3];
          $tmp = explode(".", $tmp);
          if(count($tmp) > 1) {
            return trim($tmp[1]);
          } else {
            return trim($tmp[0]);
          }
      } else {
        return "Main";
      }
  }
  
  $one = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
  $two = "<title>Brahmakumaris BK DR Luhar</title></head><style>table, th, td {border: 2px solid #000080; border-collapse:collapse;width:80%;} th,td {width:40%;text-align:left;padding: 5px;} ul{text-align:justify;} p{text-align:justify;} tr{font-family:Arial; font-size:16pt; color:#000080;}</style>";
  $three = "<body bgcolor='#ffebcc'>";
  $four = "<blockquote><blockquote><hr><p style='text-align:center;' dir='ltr'><font face='Arial' color='#FF00FF' size='5'>"
          . getHeading() . " - </font>";
  $five = "<font face='Arial' color='#000080' size='5'>"
          . getSubHeading() . " - </font>";
  $six = "<font face='Arial' color='#008000' size='5'>";
  //title here after six
  $fourthlast = "</font></p><hr><font face='Arial' style='font-size: 16pt' color='#000080'><p align='justify' dir='ltr'>";
  //detail matter here after fourthlast
  $thirdlast = "</p></font>";
  $secondlast = "<font face='Arial' style='font-size: 16pt' color='#000080' color='#000080'>";
  $last = "<br><hr></font></blockquote></blockquote></body></html>";
  
  function commentCaptionLinkInFile($line) {
    global $links_filename;
    $readAll = file_get_contents($links_filename);
    $readAll = str_replace($line, "#$line", $readAll);
    $myNewFile = fopen("$links_filename", "w");
    fwrite($myNewFile, "$readAll");
    fclose($myNewFile);
  }
  
  function getCaptionAndLinkFromFile() {   //returns e.g. 1^Create ...^https://www.verywell.....
    global $links_filename;
    $fn = fopen("$links_filename","r");
    if($fn) {
      while(!feof($fn)) {
        $result = trim(fgets($fn));
        if(strpos($result, "#") === 0) {   //skip reading line those start with #
          continue;
        } else {
          return $result;
        }
      }
      fclose($fn);
      return '';   //it will reach here iff nothing to read from $links_filename i.e. all lines start with '#' of are blank
    } else {
      return false;
    }
  }
  
  function is_class_need_to_be_skipped($cls) {
    global $skip_classes_that_contains;
    foreach($skip_classes_that_contains as $elem) {
      if(strpos($cls, $elem) !== false) return true;
    }
    return false;
  }
  
  //Deprecating this function now. Use generateFileNamePrefixNumberFor()
  function generateFileNamePrefixNumber() {
    // Below logic based on file modified date
    global $working_dir;
    $latest_ctime = 0;
    $latest_filename = false;    
    $d = dir($working_dir);
    while(false !== ($entry = $d->read())) {
    $filepath = "{$working_dir}/{$entry}";
      if(is_file($filepath) && filemtime($filepath) > $latest_ctime) {
        $latest_ctime = filemtime($filepath);
        $latest_filename = $entry;
      }
    }
    if($latest_filename) {
      $f_nm_ar = explode(".", $latest_filename);
      $new_file_num = intval($f_nm_ar[0]) + 1;
      if($new_file_num >=0 && $new_file_num <= 9) return "00$new_file_num.";
      if($new_file_num >=10 && $new_file_num <= 99) return "0$new_file_num.";
      else return "$new_file_num.";
    } else {
      return "001.";
    }
  }
  
  function generateFileNamePrefixNumberFor($caption_link) {   //e.g. 1^Create ...^https://www.verywell.....
    $tmp = explode("^", $caption_link);
    if(count($tmp) === 3) {
      $num = intval($tmp[0]);
      if($num) {
        if($num >=0 && $num <= 9) return "00$num.";
        if($num >=10 && $num <= 99) return "0$num.";
        return "$num.";
      } else {
        return "001.";
      }
    } else {
      return "001.";
    }
  }
  
  function getSanitizedFileName($file, $caption_link) {
    $file = str_replace("’", "", $file);
    $file = str_replace(":", "-", $file);
    $file = str_replace("?", "", $file);
    $file = str_replace("#", "", $file);
    $file = str_replace("(", "", $file);
    $file = str_replace(")", "", $file);
    $file = str_replace("<", "", $file);
    $file = str_replace(">", "", $file);
    $file = str_replace("/", "-", $file);
    $file = str_replace("\\", "-", $file);
    $file = str_replace("\"", "-", $file);
    //return generateFileNamePrefixNumber() . " $file.htm";   //Deprecated the function generateFileNamePrefixNumber()
    return generateFileNamePrefixNumberFor($caption_link) . " $file.htm";
  }
  
  if(isset($_POST['create']) && isset($_POST['file_name']) && isset($_POST['page_link']) &&
    !empty($_POST['file_name']) && !empty($_POST['page_link'])) {
    $all_oknotok = '';
    $message = 'Below Results<ul>';
    $html = file_get_html($_POST['page_link']);
    $heading_elem = $html->find('#article-heading_1-0', 0);
    $heading = '';
    if($heading_elem) {
      $heading = $heading_elem->plaintext;
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>Heading id 'article-heading_1-0' NOT FOUND. So using filename as heading</span></li>";
      $heading = $_POST['file_name'];
    }
    if($html) {
      $ret = $html->find('[id^=mntl-sc-block_]');   //getting all elements having attribute 'id' that starts with mntl-sc-block_*
      if($ret) {
        $fileToCreate = getSanitizedFileName($_POST['file_name'], $_POST['caption_link_hidden']);
        $myNewFile = fopen("$working_dir/$fileToCreate", "w");
        if(!$myNewFile) {
          $message = $message . "<li><span class='bg-danger text-white'>Unable to open file to write: $working_dir/$fileToCreate</span></li>";
          $all_oknotok = "notok";
        } else {
          fwrite($myNewFile, "$one\n");
          fwrite($myNewFile, "$two\n");
          fwrite($myNewFile, "$three\n");
          fwrite($myNewFile, "$four\n");
          fwrite($myNewFile, "$five\n");
          fwrite($myNewFile, "$six\n");
          fwrite($myNewFile, "$heading\n");
          fwrite($myNewFile, "$fourthlast\n");
          foreach($ret as $elem) {
            $array = $elem->attr;
            if(is_class_need_to_be_skipped($array['class']) === false) {
              $elem->innertext = trim($elem->innertext);
              $elem->innertext = str_replace("<a", "<well", $elem->innertext);   //One crude way to suppress anchor links
              $elem->innertext = str_replace("</a>", "</well>", $elem->innertext);
              $elem->innertext = str_replace('<p>For more mental health resources, see','<p style="display:none;">For more mental health resources, see', $elem->innertext);
              $elem->innertext = str_replace("<h4", "<span style='font-size:16pt;color:#0000FF;'", $elem->innertext);
              $elem->innertext = str_replace("</h4>", "</span>", $elem->innertext);
              $elem->innertext = str_replace("<h3", "<span style='font-size:16pt;color:#FF00FF;'", $elem->innertext);
              $elem->innertext = str_replace("</h3>", "</span>", $elem->innertext);
              $elem->innertext = str_replace("<strong", "<span style='font-size:16pt;color:#008000;'", $elem->innertext);
              $elem->innertext = str_replace("</strong>", "</span>", $elem->innertext);
              $elem->innertext = str_replace('<figure','<figure style="display:none;"', $elem->innertext);
              $elem->innertext = str_replace('<span class="mntl-sc-block-starrating__label"', '<span style="display:none;" class="mntl-sc-block-starrating__label"', $elem->innertext);
              $elem->innertext = str_replace('<iframe', '<iframe style="display:none;"', $elem->innertext);
              
              //writing <h1/h2..hn> tags
              if(substr($elem->tag, 0, 1) === "h") {
                if(!fwrite($myNewFile, "<font color='#FF0000'>{$elem->innertext}</font>\n")) {
                  $message = $message . "<li><span class='bg-danger text-white'>Unable to write 'h1' {$elem->innertext} into file : $working_dir/$fileToCreate</span></li>";
                  $all_oknotok = "notok";
                }
              } else {
                //writing rest of the content
                if(!fwrite($myNewFile, "{$elem->innertext}\n")) {
                  $message = $message . "<li><span class='bg-danger text-white'>Unable to write {$elem->innertext} into file : $working_dir/$fileToCreate</span></li>";
                  $all_oknotok = "notok";
                }
              }
            }
          }
          fwrite($myNewFile, "$thirdlast\n");
          fwrite($myNewFile, "$secondlast\n");
          fwrite($myNewFile, "$last\n");
          fclose($myNewFile);
          if(strlen($all_oknotok) < 3) {
            $message = $message . "<li><span class='bg-success text-white'>Successfully written $working_dir/$fileToCreate!!</span></li>";
            commentCaptionLinkInFile($_POST['caption_link_hidden']);
          }
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>Error finding 'id^=mntl-sc-block_1-0'!!</span></li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>Could not read from Link {$_POST['page_link']}!!</span></li>";
    }
    $message = "$message</ul>";
  } else if(isset($_POST['create'])){
    $message = $message . "<span class='bg-danger text-white'>Invalid POST Values</span>";
  }

  $caption_link = getCaptionAndLinkFromFile();   //e.g. 1^Create ...^https://www.verywell.....
  $tmp = explode("^", $caption_link);
  if(count($tmp) === 3) {
    $caption = $tmp[1];
    $link = $tmp[2];
  } else {
    $caption = '';
    $link = '';
  }

?>
<html lang="en">
  <head>
    <title>Verywell Mind Generator</title>
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
        <kbd style="width:100%;">CURRENT WORKING DIR: <?php echo $working_dir;?></kbd>
        <div class="form-group row" style="margin-top:10px;">
          <label for="file_name" class="col-sm-4 col-form-label">File Name</label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="file_name" name="file_name" <?php echo 'value="' . $caption . '"'; ?> style="width:100%;">
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;">
          <label for="page_link" class="col-sm-4 col-form-label">Page Link</label>
          <div class="col-sm-8 form-inline">
            <input type="text" class="form-control" id="page_link" name="page_link" <?php echo 'value="' . $link . '"'; ?> style="width:100%;">
          </div>
        </div>
        <input type="hidden" id="caption_link_hidden" name="caption_link_hidden" <?php echo 'value="' . $caption_link . '"'; ?> >
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
        document.getElementById("file_name").focus();
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>
