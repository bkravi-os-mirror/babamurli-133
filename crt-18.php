<?php
  
  //HTM Creator ------------------------
  //Usage: php crt-18.php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  
  $curr_dir = __DIR__;   //give the PWD i.e. /var/www/vhosts/omshantiworld.com/OSW/000-Ravi-DontDelete
  $root_dir = "$curr_dir/../..";   //give the root dir i.e. /var/www/vhosts/omshantiworld.com
  $up_file = "./templates/up.htm";   //[SECTION_TITLE, SECTION_OWNER]
  $down_file = "./templates/down.htm";
  $htm_write_dir = "";
  if(!(is_file($up_file) && filesize($up_file) > 10 && is_file($down_file) && filesize($down_file) > 10)) {
    print("\n\nERROR: Missing template file(s): \n\t1)$up_file\n\t2)$down_file\n\nQuitting Now...\n");
    die();
  }
  
  // ============ BELOW INPUT PLACE ==============================================================
  $owner_id = "131. Dr Satish Gupta";
  $owner_name = trim(preg_replace("/^(\d{1,3}[\.\-\_])/", "", $owner_id));   //removing starting number followed by "." or "-" or "_" 
  
  //exclude_dir will be excluded during page creation
  $exclude_dirs = array(
    "$root_dir/BKDRLUHAR/$owner_id/00. Htm",
  );
  
  //below means $source_dirs[0] will be a directory and it will have at least one or more sub-dirs from where we'll pic data files
  $source_dirs = array(
    "$root_dir/BKDRLUHAR/$owner_id",
    "$root_dir/BKDRLUHAR/$owner_id/New Classes",
  );
  
  //this associative array has all the parents with prefix value. all theier children will get this prefix on title
  //key=> parent, value=> all key's children will use this prefix to show in title
  $children_heading_prefix_array = array(
    "$root_dir/BKDRLUHAR/$owner_id/New Classes" => " - Classes",
  );
  // ============ ABOVE INPUT PLACE ==============================================================
  
  //Let's create the htm folder if not existing where we'll keep the generated file
  if(!is_dir("$root_dir/BKDRLUHAR/$owner_id/00. Htm")) {
    if(!mkdir("$root_dir/BKDRLUHAR/$owner_id/00. Htm")) {
      print("\nERROR: unable to create htm folder $owner_id/00. Htm!!\nQuitting now...\n");
      die();
    } else {
      print("\nCreated folder $owner_id/00. Htm as it was not there!\n");
    }
  }
  $htm_write_dir = "$root_dir/BKDRLUHAR/$owner_id/00. Htm";
  
  //[LINK_HREF, DOWNLOAD_IMG_HREF]
  $file_link_template = "<p align=\"center\"><a href=\"LINK_HREF\"><img border=\"0\" src=\"DOWNLOAD_IMG_HREF\" width=\"24\" height=\"24\"></a></p>";
  
  //[HEADER_SERIAL, HEADER_CODE, HEADER_TITLE, HEADER_1st_COL, HEADER_2nd_COL]
  $header_template = ""
    . "                <tr style=\"color:#800000;font-size:10pt;font-family:Arial;\">\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"50\" height=\"26\"><b>HEADER_SERIAL&nbsp;</b></td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"70\" height=\"26\"><b>HEADER_CODE&nbsp;</b></td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" width=\"498\" height=\"26\"><b>HEADER_TITLE&nbsp;</b></td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"66\" height=\"26\">\r\n"
    . "                  <b><font color=\"#008080\" size=\"2\" face=\"Arial\">HEADER_1st_COL&nbsp;</font></b></td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"74\" height=\"26\">\r\n"
    . "                  <b><font color=\"#008080\" size=\"2\" face=\"Arial\">HEADER_2nd_COL&nbsp;</font></b></td>\r\n"
    . "                </tr>\r\n";
  
  //[ROW_TITLE, ROW_1st_HREF, ROW_2nd_HREF]
  $general_row_template = ""
    . "                <tr style=\"color:#00000;font-size:10pt;font-family:Arial;\">\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"50\" height=\"26\">&nbsp;</td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"70\" height=\"26\">&nbsp;</td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" width=\"498\" height=\"26\">ROW_TITLE&nbsp;</td>\r\n"
    . "                  <td style=\"height: 26px; border: 1 solid #C0C0C0\" valign=\"middle\" align=\"center\" width=\"66\">\r\n"
    . "                    ROW_1st_HREF\r\n"
    . "                  </td>\r\n"
    . "                  <td style=\"height: 26px; border: 1 solid #C0C0C0\" valign=\"middle\" align=\"center\" width=\"74\">\r\n"
    . "                    ROW_2nd_HREF\r\n"
    . "                  </td>\r\n"
    . "                </tr>\r\n";
  
  $blank_row_template = ""
    . "                <tr>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"50\" height=\"26\">&nbsp;</td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" align=\"center\" width=\"70\" height=\"26\">&nbsp;</td>\r\n"
    . "                  <td style=\"border: 1 solid #C0C0C0\" width=\"498\" height=\"26\">&nbsp;</td>\r\n"
    . "                  <td style=\"height: 26px; border: 1 solid #C0C0C0\" valign=\"middle\" align=\"center\" width=\"66\">&nbsp;</td>\r\n"
    . "                  <td style=\"height: 26px; border: 1 solid #C0C0C0\" valign=\"middle\" align=\"center\" width=\"74\">&nbsp;</td>\r\n"
    . "                </tr>\r\n";
  
  $header_tag_array = array("HEADER_SERIAL", "HEADER_CODE", "HEADER_TITLE", "HEADER_1st_COL", "HEADER_2nd_COL");
  $general_row_tag_array = array("ROW_TITLE", "ROW_1st_HREF", "ROW_2nd_HREF");
  $file_link_tag_array = array("LINK_HREF", "DOWNLOAD_IMG_HREF");
  
  $final_title_dirs = array();   //all array to read directories from. i.e. we will pick all data direct from these dirs
  $tmp_new_class_holder = array();
  
  // "/a/b/c/d" => returns: "/a/b/c"
  function getParentDir($child_dir_path) {
    return substr($child_dir_path, 0, strrpos($child_dir_path, "/"));
  }
  
  // "/a/b/c/d" => returns: "d"
  function getLastDir($dir_path) {
    $tmp = explode("/", $dir_path);
    return end($tmp);
  }
  
  function writeToFile($all_section_array) {   //it will be an array of array
    global $owner_id, $owner_name, $htm_write_dir, $up_file, $down_file;
    if(!(isset($all_section_array) && !empty($all_section_array))) {
      print("\nERROR: Nothing to write!! Quitting now...\n");
      die();
    }
    $myfile = fopen("$htm_write_dir/$owner_id.htm", "w") or die("\nUnable to open file $htm_write_dir/$owner_id.htm!\n");
    $up_section = file_get_contents($up_file);   //[SECTION_TITLE, SECTION_OWNER]
    $up_section = str_replace("SECTION_TITLE", $owner_name, $up_section);   //<title>
    $up_section = str_replace("SECTION_OWNER", $owner_id, $up_section);   //top heading of the page
    if(!fwrite($myfile, $up_section)) {
      print("\nERROR: Unable to write up_section into file !!\n");
    }
    foreach($all_section_array as $this_section) {
      foreach($this_section as $val) {   //we will replace any DELAYED_BLANK_* value with &nbsp;
        $val = str_replace("DELAYED_BLANK_MP3", "&nbsp;", $val);
        $val = str_replace("DELAYED_BLANK_MP4", "&nbsp;", $val);
        $val = str_replace("DELAYED_BLANK_PDF", "&nbsp;", $val);
        $val = str_replace("DELAYED_BLANK_HTM", "&nbsp;", $val);
        if(!fwrite($myfile, $val)) {
          print("\nERROR: Unable to write $val into file !!\n");
        }
      }
    }
    if(!fwrite($myfile, file_get_contents($down_file))) {
      print("\nERROR: Unable to write down_section into file !!\n");
    }
    if(!fclose($myfile)) {
      print("\nERROR: Closing file $full_file_name!!\n");
    }
  }
  
  $pattern = "/(\d{1,10}[\-\._\s])+/";
  function getCode($string) {
    global $pattern;
    preg_match($pattern, $string, $match);
    if(isset($match) && !empty($match)) {
      return trim(trim(trim($match[0]), "-"), ".");
    } else {
      return "";
    }
  }
  
  function getTitle($title) {
    global $pattern;
    return trim(preg_replace($pattern, "", $title));
  }
  
  foreach($source_dirs as $source) {
    $children_dirs = glob("$source/*");
    if(isset($children_dirs) && !empty($children_dirs)) {
      foreach($children_dirs as $child) {
        if(is_dir($child) && !in_array($child, $exclude_dirs) && !in_array($child, $source_dirs)) {
          if(preg_match("/new class/i", $child) !== false) {
            $tmp_new_class_holder[] = $child;
          }
          else {
            $final_title_dirs[] = $child;
          }
        }
      }
    } else {
      print("\nError: Nothing found inside $source/\n");
    }
  }
  
  sort($final_title_dirs);
  sort($tmp_new_class_holder);
  $final_title_dirs = array_merge($final_title_dirs, $tmp_new_class_holder);
  
  $counter = 0;
  $all_sections = array();
  foreach($final_title_dirs as $curr_reading_dir) {
    $this_section = array();
    if($counter > 0) {
      $this_section[] = $blank_row_template;   //adding blank row at begining. but from second section
    }
    //[HEADER_SERIAL, HEADER_CODE, HEADER_TITLE, HEADER_1st_COL, HEADER_2nd_COL]
    $serial = sprintf("%02d", $counter+1);
    $last_dir = getLastDir($curr_reading_dir);
    $code = getCode($last_dir);
    $title = getTitle($last_dir);
    $my_parent = getParentDir($curr_reading_dir);
    if(array_key_exists("$my_parent", $children_heading_prefix_array)) {
      $title = $title . $children_heading_prefix_array["$my_parent"];
    }
    $fst_col = $counter == 0 ? "" : "MP4";
    $scnd_col = $counter == 0 ? "" : "MP3";
    $cooked_head = array($serial, $code, $title, $fst_col, $scnd_col);
    $head = str_replace($header_tag_array, $cooked_head, $header_template);
    $this_section[] = $head;
    $curr_dir_all_files = glob("$curr_reading_dir/*.*");
    $r_title = ""; $fst_href = ""; $scnd_href;
    $curr_filenm_only = ""; $prev_filenm_only = "";
    if(isset($curr_dir_all_files) && !empty($curr_dir_all_files)) {
      $my_idx = 1;   //NOTE: [0] is blank row, [1] is heading title. Next element should got into [2]. Thats why $my_idx set to "1"
      foreach($curr_dir_all_files as $file) {
        $ext = strtolower(onlyExt($file));
        if(in_array($ext, array("mp3", "mp4"))) {   //only allowed extensions are mp3/mp4
          $curr_filenm_only = onlyFileName($file);
          if($curr_filenm_only === $prev_filenm_only) {   //i.e. it is the same file but another extension. e.g. a.mp4, a.mp3, a.pdf, a.htm
            //[LINK_HREF, DOWNLOAD_IMG_HREF]
            $link_href = str_replace(" ", "%20", "../" . getLastDir($curr_reading_dir) . "/" . fileWithExt($file));
            $dwnld_img_href = "../../jpg/download.jpg";
            $cooked_link = array($link_href, $dwnld_img_href);
            $href = str_replace($file_link_tag_array, $cooked_link, $file_link_template);
            if($ext === "mp3") {   //current ext got is mp3, hence replace DELAYED_BLANK_MP3 
              $this_section[$my_idx] = str_replace("DELAYED_BLANK_MP3", $href, $this_section[$my_idx]);   //updating previous index val
            } else if($ext === "mp4") {   //current ext got is mp4, hence replace DELAYED_BLANK_MP4
              $this_section[$my_idx] = str_replace("DELAYED_BLANK_MP4", $href, $this_section[$my_idx]);   //updating previous index val
            }   //NOTE: We won't increment $my_idx here!!
          }
          else {
            //[ROW_TITLE, ROW_1st_HREF, ROW_2nd_HREF]
            $r_title = $curr_filenm_only;
            //[LINK_HREF, DOWNLOAD_IMG_HREF]
            $link_href = str_replace(" ", "%20", "../" . getLastDir($curr_reading_dir) . "/" . fileWithExt($file));
            $dwnld_img_href = "../../jpg/download.jpg";
            $cooked_link = array($link_href, $dwnld_img_href);
            if($ext === "mp3") {
              $fst_href = "DELAYED_BLANK_MP4";   //i.e. instead of making in blank here, will blank it later on
              $scnd_href = str_replace($file_link_tag_array, $cooked_link, $file_link_template);
            } else if($ext === "mp4") {
              $fst_href = str_replace($file_link_tag_array, $cooked_link, $file_link_template);
              $scnd_href = "DELAYED_BLANK_MP3";
            }
            $cooked_row = array($r_title, $fst_href, $scnd_href);
            $row = str_replace($general_row_tag_array, $cooked_row, $general_row_template);
            $my_idx++;
            $this_section[$my_idx] = $row;
          }
          $prev_filenm_only = $curr_filenm_only;
        }
      }
    }
    $this_section[] = $blank_row_template;   //adding blank row at end
    $all_sections[] = $this_section;
    writeToFile($all_sections);
    $counter++;
    //die();
  }
  print("\n\nDONE!!!\n");
?>