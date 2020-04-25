<?php

  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include('./libs/phpdom/simple_html_dom.php');
  include('./util.php');
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $message = '';
  $dwn_img = "/jpg/download.jpg";   //green download button url for <img> tag
  
  $data = json_decode(file_get_contents("php://input"));
  
  $choosen_date = $data -> choosen_date;
  $file_to_process = $data -> file_to_process;
  $new_link = $data -> new_link;
  $col = (int)($data -> col);
  
  function replaceXwithLinkIn() {
    global $dwn_img, $new_link, $choosen_date, $col, $file_to_process;
    $_sourceFileNameOnly = pathinfo($file_to_process, PATHINFO_BASENAME);
    $message = "Below Results:<ul style='line-height:1.9rem;'>";
    $id = "BABA-" . time();   //jsut to tracking purpose, adding an ID attribute
    $anchor_tag = "<a href='$new_link' id='$id'><img border='0' src='$dwn_img' width='24' height='24'></a>";
    $myfile = fopen("$file_to_process", "r");
    $is_separate_series = false;
    if(stringContainsAll($new_link, array("hindi", "moti"))) $is_separate_series = true;
    else if(stringContainsAll($new_link, array("hindi", "calendar"))) $is_separate_series = true;
    else if(stringContainsAll($new_link, array("hindi", "commentary"))) $is_separate_series = true;
    else if(stringContainsAll($new_link, array("hindi", "palna"))) $is_separate_series = true;
    else if(stringContainsAll($new_link, array("hindi", "purushrath"))) $is_separate_series = true;
    if(!$myfile) {
      $message = $message . "<li><span class='bg-danger text-white'>Unable to open $file_to_process for reading!</span></li>";
    } else {
      $readAll = fread($myfile, filesize("$file_to_process"));
      fclose($myfile);
      $search_str = $choosen_date;
      $idx_daily_section_id = 0;
      if($is_separate_series) {
        $idx_daily_section_id = strpos($readAll, "id='daily-section'");
        $idx_daily_section_id = $idx_daily_section_id === false ? 0 : $idx_daily_section_id;
      }
      if(($idx = strpos($readAll, $search_str, $idx_daily_section_id)) !== false) {
        $idx_of_rqd_open_tr = strripos(substr($readAll, 0, $idx), "<tr");
        $idx_of_rqd_closing_tr = strpos($readAll, "</tr>", $idx) + strlen("</tr>");
        $first_part = substr($readAll, 0, $idx_of_rqd_open_tr);
        $second_part = substr($readAll, $idx_of_rqd_closing_tr);
        $stringTRBeforeProcess = substr($readAll, $idx_of_rqd_open_tr, ($idx_of_rqd_closing_tr - $idx_of_rqd_open_tr));
        $my_DOM_obj = str_get_html($stringTRBeforeProcess);
        if(count($my_DOM_obj->find('tr')) < 0) {
          $message = $message . "<li><span class='bg-danger text-white'>"
            . "No row found to process!! Please check!"
            . "</span></li>";
        }
        else if(count($my_DOM_obj->find('tr')) > 1) {
          $message = $message . "<li><span class='bg-danger text-white'>"
            . "More than 1 rows found to process!! Currently only 1 rows is processed at a time!"
            . "</span></li>";
        }
        $tds_array = $my_DOM_obj->find('td');
        if(count($tds_array) <= $col) {  //tds MUST be more than the col. Note that first col is just date label
          $message = $message . "<li><span class='bg-danger text-white'>"
            . "Column no. <mark>$col</mark> seems more than the actual column counts in <mark>$_sourceFileNameOnly</mark>"
            . "</span></li>";
        }
        else if(trim(strtolower($my_DOM_obj->find('td', $col)->innertext)) !== 'x') {   //'find' indexing starts at 0
          $message = $message . "<li><span class='bg-danger text-white'>"
            . "Column no. <mark>$col</mark> is not 'x' in <mark>$_sourceFileNameOnly</mark> !!"
            . "</span></li>";
        } else {   //all OK, final processing here
          $my_DOM_obj->find('td', $col)->innertext = $anchor_tag;
          $stringTRAfterProcess = str_replace("<td", "\n						<td", $my_DOM_obj);
          $stringTRAfterProcess = str_replace("<a", "\n						<a", $stringTRAfterProcess);
          $stringTRAfterProcess = str_replace("<img", "\n						<img", $stringTRAfterProcess);
          $stringTRAfterProcess = str_replace("<font", "\n						<font", $stringTRAfterProcess);
          $stringTRAfterProcess = str_replace("$choosen_date<", "\n						$choosen_date<", $stringTRAfterProcess);
          $stringTRAfterProcess = str_replace("</tr>", "\n						</tr>", $stringTRAfterProcess);
          $new_file_content = $first_part . $stringTRAfterProcess . $second_part;
          takeBackup($file_to_process);
          $myNewFile = fopen("$file_to_process", "w");
          if(!$myNewFile) {
            $message = $message . "<li><span class='bg-danger text-white'>"
              . "Error <mark>re-opening</mark> the file $file_to_process to write!"
              . "</span></li>";
          } else {
            if(fwrite($myNewFile, $new_file_content)) {
              $message = "$message<li>Before<br><mark style='padding:0.32em;'>$stringTRBeforeProcess</mark></li>";
              $message = "$message<li>After<br><mark style='padding:0.32em;'>$stringTRAfterProcess</mark></li>";
              $message = "$message<li><span class='bg-warning text-white'>Successfully Processed!!!</span></li>";
            } else {
              $message = $message . "<li><span class='bg-danger text-white'>"
                . "Error <mark>writing</mark> into file $file_to_process !"
                . "</span></li>";
            }
            fclose($myNewFile);
          }
        }
      } else {
        $message = $message . "<li>Date $search_str <span class='bg-danger text-white'>NOT FOUND</span> in $file_to_process !!!</li>";
      }
    }
    $message = "$message</ul>";
    return $message;
  }
  
  if(empty($choosen_date) || empty($file_to_process) || empty($new_link) || empty($col)) {
    echo json_encode(array(
      "msg" => "Missing Input!!",
    ));
  } else {
    $message = replaceXwithLinkIn();
    echo json_encode(array(
      "msg" => "$message",
    ));
  }
  
?>