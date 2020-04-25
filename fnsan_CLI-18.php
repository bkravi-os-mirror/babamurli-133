<?php
  
  //Filename Sanitizer Script ------------------------
  //Usage: php fnsan_CLI-18.php "/BKDRLUHAR/132. Dr Girish Patel/New Classes"
  //  Will sanitize all files inside '/BKDRLUHAR/132. Dr Girish Patel/New Classes/*.*'
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  date_default_timezone_set('Asia/Calcutta');
  include('./util.php');
  
  if(count($argv) !== 2) {
    print "\nERROR!!!\n";
    print "[Usage:] php " . fileWithExt(__FILE__) . " <<Source Directory>>\n\n";
    die();
  }
  $source_dir = trim($argv[1], "/");   //lets remove "/" from begining and ending
  
  $curr_dir = __DIR__;   //give the PWD i.e. /var/www/vhosts/omshantiworld.com/OSW/000-Ravi-DontDelete
  $root_dir = "$curr_dir/../..";   //give the root dir i.e. /var/www/vhosts/omshantiworld.com
  $working_dir = "$root_dir/$source_dir";
  $tmp_output_dir = "./sanitized_names.txt";
  $default_far_future_date = "2025-12-31";   //far future date. its a catch all date
  $default_far_future_date_fmt = "Y-m-d";
  
  $prefixed_nm_PERFECT = array();   //assiciative array. key=>original raw file name. value=>YYYY_MM_DD prefixed sanitized filename
  $prefixed_nm_FINE = array();   //assiciative array. key=>original raw file name. value=>$default_far_future_date prefixed sanitized filename
  
  $working_files = glob("$working_dir/*.*");
  //$working_files = array(end($working_files));
  //$working_files = array("When Life Hurts It Teaches_Dr Hina_03 March 2017.mp3");
  if(!(isset($working_files) && !empty($working_files))) {
    print("\nNo files to process for filter $working_dir/*.*\n\nExiting now...\n");
    die();
  }
  foreach($working_files as $working_file) {
    $str = fileWithExt($working_file);
    $original_str = $str;
    $date_found = "$default_far_future_date";
    $date_format = "$default_far_future_date_fmt";
    
    //Initial necessary replace/remove
    $str = preg_replace("/[;,\"']/", '', $str);   //removing unwanted characters
    $str = preg_replace("/[__]/", '_', $str);   //double underscore to single underscore
    $str = preg_replace("/[_]/", ' ', $str);   //replace some characters with single space
    $str = preg_replace('!\s+!', ' ', $str);   //replacing multiple space with one
    $str = str_replace(" -", "-", $str);
    $str = str_replace("- ", "-", $str);
    $str = preg_replace("/(\sJanuary\s)|(\sJan\s)/i", "-01-", $str);
    $str = preg_replace("/(\sFebruary\s)|(\sFeb\s)/i", "-02-", $str);
    $str = preg_replace("/(\sMarch\s)|(\sMar\s)/i", "-03-", $str);
    $str = preg_replace("/(\sApril\s)|(\sApr\s)/i", "-04-", $str);
    $str = preg_replace("/(\sMay\s)/i", "-05-", $str);
    $str = preg_replace("/(\sJune\s)|(\sJun\s)/i", "-06-", $str);
    $str = preg_replace("/(\sJuly\s)|(\sJul\s)/i", "-07-", $str);
    $str = preg_replace("/(\sAug\s)|(\sAugust\s)/i", "-08-", $str);
    $str = preg_replace("/(\sSeptember\s)|(\sSept\s)/i", "-09-", $str);
    $str = preg_replace("/(\sOctober\s)|(\sOct\s)/i", "-10-", $str);
    $str = preg_replace("/(\sNovember\s)|(\sNov\s)/i", "-11-", $str);
    $str = preg_replace("/(\sDecember\s)|(\sDec\s)/i", "-12-", $str);
    
    //finding the date either "-" formatted or "." formatted
    //variety [yyyy-/.mm-/.dd], [dd-/.mm-/.yyyy], [yy-/.mm-/.dd], [dd-/.mm-/.yy]
    preg_match("/(\d{1,4}\-\d{1,2}\-\d{1,4})|(\d{1,4}\.\d{1,2}\.\d{1,4})/", $str, $date);   //i.e. $date[0] will be the first matched string
    
    if(isset($date[0]) && !empty($date[0])) {
      $original_match = $date[0];
      $date[0] = str_replace(".", "-", $date[0]);   //lets replace all dots from date to "-". This is to make all dates in same "-" formats
      $tmp_1 = explode("-", $date[0]);
      if(count($tmp_1) === 3) {   //here I am handling if either day or month is a single digit. Then convert to 2 digits
        $date[0] = strlen($tmp_1[0]) == 1 ? "0{$tmp_1[0]}" : $tmp_1[0];
        $date[0] = $date[0] . "-" . (strlen($tmp_1[1]) == 1 ? "0{$tmp_1[1]}" : $tmp_1[1]);
        $date[0] = $date[0] . "-" . (strlen($tmp_1[2]) == 1 ? "0{$tmp_1[2]}" : $tmp_1[2]);
      }
      if(strlen($date[0]) === 8) {
        $date_found = $date[0];
        $date_format = 'd-m-y';
      } else if(strlen($date[0]) === 10) {   //i.e. year is 4 digit formatted
        $tmp = array();
        if(preg_match("/\-/", $date[0]) !== false) {   //i.e. date is "-" formatted
          $tmp = explode("-", $date[0]);
        }
        if(count($tmp) === 3) {   //should have exactly 3 components
          if(strlen($tmp[0]) === 4) {   //i.e. first component is year
            $date_found = "{$tmp[2]}-{$tmp[1]}-" . substr($tmp[0], 2, 2);   //if first component is year, move it to last and move the last component to first. This is to make the string looks like d-m-y format always
            $date_format = "d-m-y";
          } else if(strlen($tmp[2]) === 4) {   //i.e. last component is year
            $date_found = "{$tmp[0]}-{$tmp[1]}-" . substr($tmp[2], 2, 2);   //if last component is year, don't move anything. This is to make the string looks like d-m-y format always
            $date_format = "d-m-y";
          }   //we assume middle element can never be the year
        }
      }
    }
    //print("date_found: $date_found\n\n");
    //die();
    $found_YYYY_MM_DD_date = getAnyFormatDate($date_found, $date_format, "Y-m-d");

    $result = preg_replace("/^\d+/", "", $str);   //removing starting number
    $result = str_replace("&", "and", $result);
    $result = ucwords(trim(preg_replace("/^[_\-\.]/", "", trim($result))));   //remove starting . or - or _ and spaces and first letter capital
    $result = str_replace("And", "and", $result);
    if(isset($date[0]) && !empty($date[0])) {
      $result = str_replace($original_match, "RAVI_TEMP", $result);   //temporarily replacing date
      $result = str_replace("-", " - ", $result);
      $result = str_replace("RAVI_TEMP", $date_found, $result);
    } else {
      $result = str_replace("-", " - ", $result);
    }
    
    if("$date_found" === "$default_far_future_date") {   //If not a valid date found in filename
      $prefixed_nm_FINE[$original_str] = "$default_far_future_date" . "_" . "$result";
    } else {
      $prefixed_nm_PERFECT[$original_str] = "$found_YYYY_MM_DD_date" . "_" . "$result";
    }
  }
  
  asort($prefixed_nm_PERFECT);   //ascending sort on value
  asort($prefixed_nm_FINE);   //ascending sort on value
  
  //below writing raw results into file for user confirmation
  $myfile = fopen("$tmp_output_dir", "w") or die("Unable to open file $tmp_output_dir!");
  $file_pref_counter = 0;
  $curr_nm = ''; $prev_nm = '';
  foreach($prefixed_nm_PERFECT as $key => $value) {   //first writing perfect data
    $curr_nm = onlyFileName($value);
    if($curr_nm != $prev_nm) {
      $file_pref_counter++;
    }
    $file_new_nm = sprintf("%03d", $file_pref_counter) . ". " . substr($value, 11);
    fwrite($myfile, "[$key]\n\t\tRename To => [$file_new_nm]\n\n");   //first 11 characters are YYYY_MM_DD_ date
    $prev_nm = $curr_nm;
  }
  fwrite($myfile, "==================\n\n");
  foreach($prefixed_nm_FINE as $key => $value) {   //first writing perfect data
    $curr_nm = onlyFileName($value);
    if($curr_nm != $prev_nm) {
      $file_pref_counter++;
    }
    $file_new_nm = sprintf("%03d", $file_pref_counter) . ". " . substr($value, 11);
    fwrite($myfile, "[$key]\n\t\tRename To => [$file_new_nm]\n\n");   //first 11 characters are YYYY_MM_DD_ date
    $prev_nm = $curr_nm;
  }
  fclose($myfile) or die("Unable to close file $tmp_output_dir!");
  
  $line = readline("\nTotal records processed# " . (count($prefixed_nm_PERFECT) + count($prefixed_nm_FINE)) . "\n\nCheck $tmp_output_dir if all OK & press y|Y|n|N for further processing.... ");
  
  if(strtolower($line)[0] == "y") {
    //below renaming files
    $file_pref_counter = 0;
    $curr_nm = ''; $prev_nm = '';
    foreach($prefixed_nm_PERFECT as $key => $value) {
      $curr_nm = onlyFileName($value);
      if($curr_nm != $prev_nm) {
        $file_pref_counter++;
      }
      $file_new_nm = sprintf("%03d", $file_pref_counter) . ". " . substr($value, 11);
      if(!rename("$working_dir/$key", "$working_dir/$file_new_nm")) {
        print "\nRename ERROR: $key => $file_new_nm\n";
      }
      $prev_nm = $curr_nm;
    }
    print "\n##################\n";
    foreach($prefixed_nm_FINE as $key => $value) {   //first writing perfect data
      $curr_nm = onlyFileName($value);
      if($curr_nm != $prev_nm) {
        $file_pref_counter++;
      }
      $file_new_nm = sprintf("%03d", $file_pref_counter) . ". " . substr($value, 11);
      if(!rename("$working_dir/$key", "$working_dir/$file_new_nm")) {
        print "\nRename ERROR: $key => $file_new_nm\n";
      }
      $prev_nm = $curr_nm;
    }
  } else {
    print "You did not press y|Y. So quitting now...\n";
    die();
  }
  
  print("\n\nDONE\n\n");
  
?>