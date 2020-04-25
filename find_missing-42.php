<?php
  //This writes data into Excel also!
  //Reference: https://phpspreadsheet.readthedocs.io/en/latest/#installation
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  
  require './libs/vendor-phpexcel/autoload.php';
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  //use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  use PhpOffice\PhpSpreadsheet\Writer\Xls;
  include 'util.php';
  $parent_folder_to_search = "$rootdir/0000-Old Daily";
  $missing_folder = "$rootdir/Missing Files";
  $message = '';
  
  $folders = array(
    "01. Hindi",  "02. English",  "03. Tamil",  "04. Telugu",  
    "05. Kannada",  "06. Malayalam",  "07. Bengali",  "08. Assame",  "09. Gujarati",  
    "10. Odiya",  "11. Punjabi",  "12. Marathi",  "30. Nepali",  "31. Deutsch",  
    "32. Spanish",  "33. Italian",  "35. Tamil-Lanka",  "36. French",  
    "37. Greek",  "38. Hungarian",  "39. Korean",  "40. Polish",  "41. Portuguese",  
    "42. Sindhi",  "43. Thai",  "44. Sinhala",  
  );
  sort($folders);
  $month_folders_array = array(
    "01-January", "02-February", "03-March", "04-April", "05-May", "06-June", "07-July",
    "08-August", "09-September", "10-October", "11-November", "12-December",
  );
  $default_min_year = array(
    "01. Hindi" => "2005", "02. English" => "2013", "03. Tamil" => "2009", "04. Telugu" => "2013",
    "05. Kannada" => "2013", "06. Malayalam" => "2013", "07. Bengali" => "2016", "08. Assame" => "2017",
    "09. Gujarati" => "2019", "10. Odiya" => "2018", "11. Punjabi" => "2019", "12. Marathi" => "2018",
    "30. Nepali" => "2014", "31. Deutsch" => "2014", "32. Spanish" => "2014", "33. Italian" => "2014",
    "35. Tamil-Lanka" => "2014", "36. French" => "2014", "37. Greek" => "2014", "38. Hungarian" => "2014",
    "39. Korean" => "2014", "40. Polish" => "2014", "41. Portuguese" => "2014", "42. Sindhi" => "2014",
    "43. Thai" => "2014", "44. Sinhala" => "2017",
  );
  
  $match_pattern_for_postfix = array (
    //For Assame
    "/(Assame)(.*)(htm)/i" => "-Assame.htm",
    "/(Assame)(.*)(pdf)/i" => "-Assame.pdf",
    
    //For Bengali
    "/(bengali)(.*)(htm)/i" => "-Bengali.htm",
    "/(bengali)(.*)(pdf)/i" => "-Bengali.pdf",
    
    //For Deutsch
    "/(Deutsch)(.*)(htm)/i" => "-Deutsch.htm",
    "/(Deutsch)(.*)(pdf)/i" => "-Deutsch.pdf",
    
    //For Eng
    "/(english)(.*)(htm)/i" => "-E.htm",
    "/(02. Eng Murli - Pdf)/i" => "-E.pdf",
    "/(english)(.*)(mp3)(.*)(uk)/i" => "-E-UK.mp3",
    "/(english)(.*)(swaman)/i" => "-Swa-Eng.jpg",
    "/(english)(.*)(vardan)/i" => "-Var.jpg",
    "/(english)(.*)(visual)/i" => "-Eng-Viz.pdf",
    
    //For French
    "/(French)(.*)(htm)/i" => "-French.htm",
    "/(French)(.*)(pdf)/i" => "-French.pdf",
    
    //For Greek
    "/(Greek)(.*)(htm)/i" => "-Greek.htm",
    "/(Greek)(.*)(pdf)/i" => "-Greek.pdf",
    
    //For Gujarati
    "/(Gujarati)(.*)(htm)/i" => "-Gujarati.htm",
    "/(Gujarati)(.*)(pdf)/i" => "-Gujarati.pdf",
    
    //For Hindi
    "/(hindi)(.*)(chart)/i" => "-MurliChart.htm",
    "/(hindi)(.*)(murli)(.*)(htm)/i" => "-H.htm",
    "/(hindi)(.*)(pdf)/i" => "-h.pdf",
    "/(hindi)(.*)(murli)(.*)(mp3)/i" => "-H.mp3",
    "/(hindi)(.*)(chintan)/i" => "-Murli Chintan.mp3",
    "/(Murli Vardan-2- jpg)/i" => "-Var-Hand.jpg",
    "/(Murli Vardan - jpg)/i" => "-Var.jpg",
    "/(hindi)(.*)(purushrath)/i" => "-AKP.mp3",
    "/(hindi)(.*)(moti)/i" => "-Moti.jpg",
    "/(hindi)(.*)(swaman)/i" => "-Swa.jpg",
    "/(hindi)(.*)(adalat)/i" => "-Dharmraj.jpg",
    
    //For Hungarian
    "/(Hungarian)(.*)(htm)/i" => "-Hungarian.htm",
    "/(Hungarian)(.*)(pdf)/i" => "-Hungarian.pdf",
    
    //For Italian
    "/(Italian)(.*)(htm)/i" => "-Italian.htm",
    "/(Italian)(.*)(pdf)/i" => "-Italian.pdf",
    
    //For Kananda
    "/(kannada)(.*)(htm)/i" => "-K.htm",
    "/(kannada)(.*)(pdf)/i" => "-K.pdf",
    
    //For Korean
    "/(Korean)(.*)(htm)/i" => "-Korean.htm",
    "/(Korean)(.*)(pdf)/i" => "-Korean.pdf",
    
    //For Malayalam
    "/(malayalam)(.*)(htm)/i" => "-Mal.htm",
    "/(malayalam)(.*)(pdf)/i" => "-Mal.pdf",
    
    //For Marathi
    "/(Marathi)(.*)(htm)/i" => "-Marathi.htm",
    "/(Marathi)(.*)(pdf)/i" => "-Marathi.pdf",
    
    //For Nepali
    "/(Nepali)(.*)(htm)/i" => "-N.htm",
    "/(Nepali)(.*)(pdf)/i" => "-N.pdf",
    
    //For Odiya
    "/(Odiya)(.*)(htm)/i" => "-Odia.htm",
    "/(Odiya)(.*)(pdf)/i" => "-Odia.pdf",
    
    //For Punjabi
    "/(Punjabi)(.*)(htm)/i" => "-Pun.htm",
    "/(Punjabi)(.*)(pdf)/i" => "-Pun.pdf",
    
    //For Polish
    "/(Polish)(.*)(htm)/i" => "-Polish.htm",
    "/(Polish)(.*)(pdf)/i" => "-Polish.pdf",
    
    //For Portuguese
    "/(Portuguese)(.*)(htm)/i" => "-Port.htm",
    "/(Portuguese)(.*)(pdf)/i" => "-Port.pdf",
    
    //For Sindhi
    "/(Sindhi)(.*)(pdf)/i" => "-Sindhi.pdf",
    
    //For Sinhala
    "/(Sinhala)(.*)(htm)/i" => "-Sinhala.htm",
    "/(Sinhala)(.*)(pdf)/i" => "-Sinhala.pdf",
    
    //For Spanish
    "/(Spanish)(.*)(htm)/i" => "-Spanish.htm",
    "/(Spanish)(.*)(pdf)/i" => "-Spanish.pdf",
    
    //For Tamil
    "/(Tamil Htm)/i" => "-Tamil.htm",
    "/(Tamil Pdf)/i" => "-Tamil.pdf",
    
    //For Tamil-Lanka
    "/(Lanka)(.*)(htm)/i" => "-TamilLanka.htm",
    "/(Lanka)(.*)(pdf)/i" => "-TamilLanka.pdf",
    
    //For Telugu
    "/(telugu)(.*)(htm)/i" => "-Telugu.htm",
    "/(telugu)(.*)(pdf)/i" => "-Telugu.pdf",
    
    //For Thai
    "/(Thai)(.*)(htm)/i" => "-Thai.htm",
    "/(Thai)(.*)(pdf)/i" => "-Thai.pdf",
    
  );
  
  function getPostFix($string) {
    global $match_pattern_for_postfix;
    if(empty($string)) return false;
    foreach($match_pattern_for_postfix as $pattern => $val) {
      if(preg_match($pattern, $string)) {
        return $val;
      }
    }
    return false;
  }
  
  //Below is the temporary function. It can be used as and when required
  //You can modify it as per your need
  //When you call this function, make sure you die() after that
  function renameFilesTemp($source_folder) {
    global $parent_folder_to_search;
    $msg = "Did not find any filename to rename!!";
    $sub_folders = glob("$parent_folder_to_search/$source_folder/*");
    foreach($sub_folders as $folder) {
      $year_folders = glob("$folder/*");
      foreach($year_folders as $year_folder) {
        $month_folders = glob("$year_folder/*");
        foreach($month_folders as $month_folder) {
          $files = glob("$month_folder/*");
          foreach($files as $file) {
            if(strpos($file, "-Nep.") !== false) {
              rename($file, str_replace("-Nep.", "-N.", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, ".2014-N.") !== false) {
              rename($file, str_replace(".2014-N.", ".14-N.", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "MurliNepali") !== false) {   //e.g. SakarMurliNepali-2014-03-14.pdf OR SundayAvyaktMurliNepali-2014-03-16.pdf
              $onlyD = onlyDir($file);
              $onlyEx = onlyExt($file);
              $onlyF = onlyFileName($file);
              $tmp = explode("-", $onlyF);
              if(count($tmp) == 4) {
                if(strlen($tmp[1]) == 4) {   //i.e. 4 digit year
                  $tmp[1] = intval($tmp[1]) - 2000;
                }
                $newFileName = "{$tmp[3]}.{$tmp[2]}.{$tmp[1]}-N.$onlyEx";
                rename("$file", "$onlyD/$newFileName");
                $msg = "Renamed some files!!";
              }
            } else if(strpos($file, "2014-Eng-Viz") !== false) {
              rename($file, str_replace("2014-Eng-Viz", "14-Eng-Viz", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "2015-Eng-Viz") !== false) {
              rename($file, str_replace("2015-Eng-Viz", "15-Eng-Viz", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "2016-Eng-Viz") !== false) {
              rename($file, str_replace("2016-Eng-Viz", "16-Eng-Viz", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-h.htm") !== false) {
              rename($file, str_replace("-h.htm", "-H.htm", $file));
              $msg = "Renamed some files!!";
            } else if(strpos(onlyFileName($file), "_") !== false) {
              rename($file, onlyDir($file) . "/" . str_replace("_", ".", fileWithExt($file)));
              $msg = "Renamed some files!!";
            } else if(strpos(onlyFileName($file), "Hindi Murli-") !== false) {   //e.g. Hindi Murli-05-02-2005.pdf
              $newFileNm = str_replace("Hindi Murli-", "", fileWithExt($file));
              $newFileNm = str_replace("-", ".", $newFileNm);
              $newFileNm = str_replace("200", "0", $newFileNm);
              rename($file, onlyDir($file) . "/$newFileNm-h.pdf");
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-h.mp3") !== false) {
              rename($file, str_replace("-h.mp3", "-H.mp3", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, " . Suraj Bhai.mp3.mp3") !== false) {
              rename($file, str_replace(" . Suraj Bhai.mp3.mp3", "-Murli Chintan.mp3", $file));
              $msg = "Renamed some files!!";
            } else if(strpos(onlyFileName($file), "Aaj Ka Swaman -") !== false) {   //e.g. Aaj Ka Swaman - 06.10.13.jpg
              $newFileNm = str_replace("Aaj Ka Swaman -", "", fileWithExt($file));
              $newFileNm = str_replace(".jpg", "-Swa.jpg", $newFileNm);
              rename($file, onlyDir($file) . "/$newFileNm");
              $msg = "Renamed some files!!";
            } else if(strpos(onlyFileName($file), "-13-Telugu") !== false) {   //e.g. 01-11-13-Telugu.pdf
              $newFileNm = str_replace("-", ".", fileWithExt($file));
              $newFileNm = str_replace(".Telugu", "-Telugu", $newFileNm);
              rename($file, onlyDir($file) . "/$newFileNm");
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-k.pdf") !== false) {
              rename($file, str_replace("-k.pdf", "-K.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-M.htm") !== false) {
              rename($file, str_replace("-M.htm", "-Mal.htm", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Asm.pdf") !== false) {
              rename($file, str_replace("-Asm.pdf", "-Assame.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-O.htm") !== false) {
              rename($file, str_replace("-O.htm", "-Odia.htm", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-O.pdf") !== false) {
              rename($file, str_replace("-O.pdf", "-Odia.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "2018-Marathi.pdf") !== false) {
              rename($file, str_replace("2018-Marathi.pdf", "18-Marathi.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos(onlyFileName($file), "MurliPolish") !== false) {   //e.g. SakarMurliDeutsch-2014-03-08.pdf OR SundayAvyaktMurliDeutsch-2014-03-09.pdf
              $tmp = explode("-", onlyFileName($file));
              $tmp[1] = intval($tmp[1]) - 2000;
              $newFileNm = "{$tmp[3]}.{$tmp[2]}.{$tmp[1]}-Polish.pdf";
              rename($file, onlyDir($file) . "/$newFileNm");
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Italiano.htm") !== false) {
              rename($file, str_replace("-Italiano.htm", "-Italian.htm", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Tamil Lanka.htm") !== false) {
              rename($file, str_replace("-Tamil Lanka.htm", "-TamilLanka.htm", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-moti.jpg") !== false) {
              rename($file, str_replace("-moti.jpg", "-Moti.jpg", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Swaman.jpg") !== false) {
              rename($file, str_replace("-Swaman.jpg", "-Swa.jpg", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-e.pdf") !== false) {
              rename($file, str_replace("-e.pdf", "-E.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Italiano.pdf") !== false) {
              rename($file, str_replace("-Italiano.pdf", "-Italian.pdf", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Tamillanka.") !== false) {
              rename($file, str_replace("-Tamillanka.", "-TamilLanka.", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Hunga.") !== false) {
              rename($file, str_replace("-Hunga.", "-Hungarian.", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Korian.") !== false) {
              rename($file, str_replace("-Korian.", "-Korean.", $file));
              $msg = "Renamed some files!!";
            } else if(strpos($file, "-Shihala.") !== false) {
              rename($file, str_replace("-Shihala.", "-Sinhala.", $file));
              $msg = "Renamed some files!!";
            }
          }
        }
      }
    }
    echo $msg;
    die();
  }
  
  //Below is the temporary function. It can be used as and when required
  //You can modify it as per your need
  //When you call this function, make sure you die() after that
  function renameAKPTmp() {
    global $parent_folder_to_search;
    $sub_folders = glob("$parent_folder_to_search/01. Hindi/24. Hindi - Aaj Ka Purushrath/2016/*");
    for($loop = 1; $loop <= 5; $loop++) {
      $files = glob($sub_folders[$loop-1] . "/*");
      $start_date = "01." . sprintf("%02d", $loop) . ".16";
      foreach($files as $file) {
        $newFileName = onlyDir($file) . "/$start_date-AKP.mp3";
        rename("$file", "$newFileName");
        $start_date = addDaysToDate(1, $start_date, "d.m.y", "d.m.y");
      }
    }
    echo "done";
    die();
  }
  
  //Below is the temporary function. It can be used as and when required
  //You can modify it as per your need
  //When you call this function, make sure you die() after that
  function renameAKPTmpType2() {
    global $parent_folder_to_search;
    $files = glob("$parent_folder_to_search/01. Hindi/24. Hindi - Aaj Ka Purushrath/2015/*");
    $start_date = "01.01.15";
    foreach($files as $file) {
      $newFileName = onlyDir($file) . "/$start_date-AKP.mp3";
      rename("$file", "$newFileName");
      $start_date = addDaysToDate(1, $start_date, "d.m.y", "d.m.y");
    }
    echo "done";
    die();
  }
  
  //Below is the temporary function. It can be used as and when required
  //You can modify it as per your need
  //When you call this function, make sure you die() after that
  function renameTmp() {
    global $parent_folder_to_search;
    $files = glob("$parent_folder_to_search/01. Hindi/27. Murli Swaman - jpg/2013/10-October/*");
    foreach($files as $file) {
      $newFileName = onlyDir($file) . "/" . trim(fileWithExt("$file"));
      rename("$file", "$newFileName");
    }
    echo "done";
    die();
  }
  
  function writeToSpreadSheet($pipeSeparatedRowData, $rownum, &$sheet) {   //$sheet is call by ref
    $cols = explode("|", $pipeSeparatedRowData);
    $col_alpha = "A";
    foreach($cols as $val) {
      $sheet->getStyle("$col_alpha$rownum")->getNumberFormat()
            ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
      if($rownum === 1) {   //first row is supposed to be the column names
        $sheet->getStyle('A1:C1')->getFill()->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $sheet->getStyle('A1:C1')->getFont()->applyFromArray(['bold'=>TRUE, 'italic'=>FALSE, 'color'=>['rgb'=>'008000']]);
      }
      $sheet->setCellValue("$col_alpha$rownum", trim($val));
      $col_alpha++;
    }
  }
  
  function create_processing_folder_inside_missing_folder($process_fldr) {
    global $missing_folder;
    if(!is_dir("$missing_folder/$process_fldr")) {
      if(!mkdir("$missing_folder/$process_fldr")) {
        return false;
      }
    }
    return true;
  }
  
  if(!is_dir("$missing_folder")) {
    $message = $message . "<li><span class='bg-danger text-white'>Please note that the folder <kbd>$missing_folder</kbd> is missing!!</span></li>";
  }
  
  $processing_folder = "01. Hindi";
  $processing_min_year = $default_min_year[$processing_folder];
  $processing_max_year = '2020';
  if(isset($_POST['find'])) {   //"Find Missing Files" button clicked
    //Printing all $_POST keys and values
    //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
    $processing_folder = $_POST['folder'];
    //$processing_min_year = intval($_POST['min_year']);
    //$processing_max_year = intval($_POST['max_year']);
    $processing_min_year = $default_min_year[$processing_folder];
    $processing_max_year = '2020';
    
    //You can open below call if you want to rename some file
    //renameFilesTemp($processing_folder);
    
    //You can open below call if you want to rename some file
    //renameAKPTmp();
    //renameAKPTmpType2();
    //renameTmp();
    
    $message = "Below Results:<ul>";
    if(!is_dir("$missing_folder")) {
      $message = $message . "<li><span class='bg-danger text-white'>$missing_folder Folder missing!!</span></li>";
    }
    else if($processing_min_year > $processing_max_year) {
      $message = $message . "<li><span class='bg-danger text-white'>min year can not be greater than max year!!</span></li>";
    } else {
      create_processing_folder_inside_missing_folder($processing_folder);
      $sub_dirs = glob("$parent_folder_to_search/$processing_folder/*" , GLOB_ONLYDIR);   //gives all subdirs inside parent dir
      if($sub_dirs && count($sub_dirs) > 0) {
        foreach($sub_dirs as $sub_dir) {
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          $sheet->freezePane("A2");   //freeze top row
          $running_row = 1;
          writeToSpreadSheet("Column 1|Column 2|Column 3", $running_row, $sheet);
          $running_row++;
          $sub_dir_short_name = str_replace("$parent_folder_to_search/", "", $sub_dir);
          $tmp = explode("/", $sub_dir);
          $ending_dir = end($tmp);   //e.g. "01. Hindi Murli - Htm", "02. Hindi Murli - Pdf" etc...
          for($i = $processing_min_year; $i <= $processing_max_year; $i++) {
            if(!is_dir("$sub_dir/$i")) {
              $msg = "$sub_dir_short_name|$i|Full Year Missing";
              writeToSpreadSheet($msg, $running_row, $sheet);
              $running_row++;
            } else {
              $curr_month_number = intval(date('n'));   //get month number from 1 to 12
              $curr_year = intval(date('Y'));
              $loop = 1;
              foreach($month_folders_array as $month_folder) {
                if($i === $curr_year && ($loop + 1) > $curr_month_number) break;   //i.e. no need to process if it is a future month
                if(!is_dir("$sub_dir/$i/$month_folder")) {
                  $msg = "$sub_dir_short_name/$i|$month_folder|Full Month Missing";
                  writeToSpreadSheet($msg, $running_row, $sheet);
                  $running_row++;
                } else {
                  $ts = strtotime($i . "-" . sprintf("%02d", $loop) . "-05");   //e.g.2020-02-05
                  //commenting below as I have rename english viz files to 2 digit year format
                  //if(preg_match("/(english)(.*)(visual)/i", "$sub_dir/$i/$month_folder")) {$y = 'Y';}
                  //else {$y = 'y';}   //year format. In some case it may be four digit. e.g. English Viz!
                  $y = 'y';
                  $first_day_this_month = date("01.m.$y", $ts);
                  $last_day_this_month  = date("t.m.$y", $ts);
                  $postfix = getPostFix("$sub_dir/$i/$month_folder");
                  $postfix = $postfix ? $postfix : 'ERROR_PF';
                  for($lp = 1; $lp <= 31; $lp++) {
                    if(!file_exists("$sub_dir/$i/$month_folder/$first_day_this_month$postfix")) {   //i.e. couldnt find the file
                      $tmp = explode(".", $first_day_this_month);
                      $closest_matching = "*{$tmp[0]}*{$tmp[1]}*{$tmp[2]}*";   //i.e.*dd*mm*yy*
                      $closer_matching = "*{$tmp[2]}*{$tmp[1]}*{$tmp[0]}*";   //i.e.*yy*mm*dd*
                      $closest_matching_array = glob("$sub_dir/$i/$month_folder/$closest_matching$postfix");
                      $closer_matching_array = glob("$sub_dir/$i/$month_folder/$closer_matching$postfix");
                      if($closest_matching_array) {
                        $matched_file = fileWithExt(end($closest_matching_array));
                        $msg = "$sub_dir_short_name/$i/$month_folder|$first_day_this_month$postfix|Missing this file! But found one closest match [$matched_file]";
                      } else if($closer_matching_array) {
                        $matched_file = fileWithExt(end($closer_matching_array));
                        $msg = "$sub_dir_short_name/$i/$month_folder|$first_day_this_month$postfix|Missing this file! But found one closer match [$matched_file]";
                      } else {
                        $msg = "$sub_dir_short_name/$i/$month_folder|$first_day_this_month$postfix|Missing this file!";
                      }
                      writeToSpreadSheet($msg, $running_row, $sheet);
                      $running_row++;
                    } else if(filesize("$sub_dir/$i/$month_folder/$first_day_this_month$postfix") < 2) {
                      $msg = "$sub_dir_short_name/$i/$month_folder|$first_day_this_month$postfix|ZERO Byte File!!";
                      writeToSpreadSheet($msg, $running_row, $sheet);
                      $running_row++;
                    }
                    if($first_day_this_month === $last_day_this_month) break;
                    $first_day_this_month = addDaysToDate(1, $first_day_this_month, "d.m.$y", "d.m.$y");
                  }
                }
                $loop++;
              }
            }
          }
          $sheet->getColumnDimension('A')->setAutoSize(true);
          $sheet->getColumnDimension('B')->setAutoSize(true);
          $sheet->getColumnDimension('C')->setAutoSize(true);
          //$writer = new Xlsx($spreadsheet);
          //$writer->save("$missing_folder/$processing_folder.xlsx");
          if(is_dir("$missing_folder/$processing_folder")) {
            $writer = new Xls($spreadsheet);
            $writer->save("$missing_folder/$processing_folder/$ending_dir.xls");
            $message = $message . "<li>Processing Done for <kbd>$ending_dir!!</kbd></li>";
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>$processing_folder is missing inside $missing_folder!!</span></li>";
          }
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>$processing_folder has NO sub folder!!</span></li>";
      }
    }

    $message = "$message</ul>";
  }

?>
<html lang="en">
  <head>
    <title>Missing Files Finder</title>
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
      $brand_name = 'Missing Files Finder';
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return preProcess(this);">
        <div class="form-group row" style="margin-top:10px;">
          <label for="folder" class="col-sm-4 col-form-label">Choose Folder: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="folder" name="folder" style="width:100%;">
              <?php 
                foreach($folders as $folder) {
                  $selected = $folder === $processing_folder ? 'selected' : '';
                  echo "<option value='$folder' $selected>$folder</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;display:none">
          <label for="min_year" class="col-sm-4 col-form-label">Minimum Year: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="min_year" name="min_year" style="width:100%;">
              <?php 
                for($i = 2005; $i <= 2020; $i++) {
                  $selected = $i == $processing_min_year ? 'selected' : '';
                  echo "<option value='$i' $selected>$i</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row" style="margin-top:10px;display:none">
          <label for="min_year" class="col-sm-4 col-form-label">Maximum Year: </label>
          <div class="col-sm-8 form-inline">
            <select class="form-control" id="max_year" name="max_year" style="width:100%;">
              <?php 
                for($i = 2005; $i <= 2020; $i++) {
                  $selected = $i == $processing_max_year ? 'selected' : '';
                  echo "<option value='$i' $selected>$i</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <button type="submit" id="find" name="find" class="btn btn-danger" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;">&nbsp;</span>
          Find Missing Files
        </button>
      </form>
      <div class="card bg-info text-white mt-3">
        <div class="card-body" id="id_card">
          <?php if(strlen($message) > 2) echo $message; else echo 'Output here...' ?>
        </div>
      </div>
    </div>
    <script language="javascript">
      
      function preProcess(form) {
        disableFormControls();
        return true;
      }
      
      function disableFormControls() {
        document.getElementById("loading_spinner").style.display = "inline-block";
        $('#find').addClass('disabled');
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
        document.getElementById("folder").focus();
      }
      
      window.onload=enableFormControls();
    
    </script>
  </body>
</html>