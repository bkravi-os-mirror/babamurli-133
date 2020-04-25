<?php
  
  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  include './util.php';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  
  //Assign below 3 values
  $working_year = '2016';
  $target_file_postfix = "-E.pdf";
  $main_dir = "$rootdir/0000-Old Daily/02. English/02. Eng Murli - Pdf/2016/10-October";
  
  $outputDir = "$main_dir/split";
  $pdf_source_dir = "$main_dir";
  $not_initiated_splitting = "$main_dir/not_initiated_splitting.txt";
  
  function getPDFPagesCount($sourceFileFullName) {
    $exec_command_to_get_total_pages = "gs -q -dNODISPLAY -c \"($sourceFileFullName) "
                                      . "(r) file runpdfbegin pdfpagecount = quit\"";
    exec($exec_command_to_get_total_pages, $command_output, $return_val);
    if(!$return_val) {
      return (int)implode("", $command_output);
    } else {
      return 0;
    }
  }
  
  //Below is the temporary function to show all pdfs not having 24 pages
  //Make sure to die at the end of this function
  function showPDFNotHaving24Pages_TEMP() {
    global $pdf_source_dir;
    $msg = "Calling <b>showPDFNotHaving24Pages_TEMP</b> and <u>I will die at end!</u><br><br>Output Below:<ol>";
    $pdf_list = glob("$pdf_source_dir/*.pdf");
    if($pdf_list && count($pdf_list) > 0) {
      foreach($pdf_list as $pdf) {
        $onlyFileWithExt = fileWithExt($pdf);
        $pdf_pg = getPDFPagesCount($pdf);
        if($pdf_pg !== 28) {
          $msg = "$msg<li>$onlyFileWithExt [$pdf_pg Pages]</li>";
        }
      }
    }
    $msg = "$msg</ol>";
    echo $msg;
    die();
  }
  
  //showPDFNotHaving24Pages_TEMP();
  
  function splitAndMovePDF($sourceFileFullName, $split_freq) {   //e.g. ../../T2k9_03_16_22.pdf
    global $pdf_source_dir, $target_file_postfix, $working_year, $outputDir;
    $sam_msg_array = array();
    $pdf_in_process = onlyFileName($sourceFileFullName);
    $tmp = explode("_", $pdf_in_process);   //e.g. T2k9_03_16_22
    if(count($tmp) === 4) {
      $m = intval($tmp[1]);
      if($m > 0) {
        $m = sprintf("%02d", $m);
        $st_dt = intval($tmp[2]);
        if($st_dt > 0) {
          $st_dt = sprintf("%02d", $st_dt);
          $y = strlen($working_year) > 2 ? intval(substr($working_year, 2)) : intval($working_year);
          if($y > 0) {
            $y = sprintf("%02d", $y);
            $start_date = "$st_dt.$m.$y";
            $st_pg = 1;
            foreach($split_freq as $split) {
              $splt_val = intval($split);
              if($splt_val > 0) {
                $lst_pg = $st_pg + $splt_val - 1;
                $exec_command_to_split = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=\"$outputDir\"/$start_date$target_file_postfix "
                                . "-dFirstPage=$st_pg -dLastPage=$lst_pg -dPDFSettings=/prepress -dAutoRotatePages=/None "
                                . "-f \"$sourceFileFullName\"";
                exec($exec_command_to_split, $command_output, $return_val);
                if($return_val === 0) {   //command success
                } else {
                  $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> "
                    . "Split failed for [$pdf_in_process, FirstPage=$st_pg, LastPage=$lst_pg]"
                    . "</li>";
                }
                $start_date = addDaysToDate(1, $start_date, 'd.m.y', 'd.m.y');
                $st_pg = $lst_pg + 1;
              } else {
                $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> invalid split_freq number $split for $pdf_in_process!</li>";
              }
            }
            $sam_msg_array[] = "<li>Done!!</li>";
          } else {
            $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> invalid year $working_year</li>";
          }
        } else {
          $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> invalid start-date in PDF name $pdf_in_process</li>";
        }
      } else {
        $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> invalid month in PDF name $pdf_in_process</li>";
      }
    } else {
      $sam_msg_array[] = "<li><span class='bg-danger text-white'>ERROR!!</span> invalid PDF name $pdf_in_process</li>";
    }
    return $sam_msg_array;
  }
  
  //Use below map to assign split frequency to a particular PDF based on total number of pages
  $pdf_splitfreq_map = array(
    //Oct-2016 English
    "English_10_03_09.pdf" => array(3,3,3,3,3,3,4),
    "English_10_10_16.pdf" => array(3,3,3,3,3,3,3),
    "English_10_17_23.pdf" => array(3,3,3,3,3,3,3),
    "English_10_24_30.pdf" => array(3,3,3,3,2,2,3),
    "English_09_26_02.pdf" => array(3,3,3,3,3,3,4),
    "English_10_31_06.pdf" => array(3,3,3,3,3,3,4),
    
    //2009
    "T2k9_03_23_29.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_04_06_12.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_04_13_19.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_05_02_02.pdf" => array(4),   //It has only single date i.e. 02nd-05 date murli
    "T2k9_05_04_10.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_05_18_24.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_05_25_31.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_06_01_07.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_06_08_14.pdf" => array(4,4,4,4,4,3,4),
    "T2k9_06_15_21.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_06_29_05.pdf" => array(4,4,4,4,4,4,7),
    "T2k9_07_13_19.pdf" => array(4,4,4,4,4,4,5),
    "T2k9_07_27_02.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_08_03_09.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_08_17_23.pdf" => array(4,4,4,5,4,4,4),
    "T2k9_08_24_30.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_11_02_08.pdf" => array(4,4,4,4,4,5,4),
    "T2k9_11_09_15.pdf" => array(4,4,4,4,5,4,5),
    "T2k9_11_23_29.pdf" => array(4,4,4,4,4,4,5),
    "T2k9_12_01_06.pdf" => array(4,4,4,4,4,4,3),
    "T2k9_12_07_13.pdf" => array(4,4,5,5,5,4,4),
    "T2k9_12_21_27.pdf" => array(4,4,4,4,4,4,5),
    "T2k9_12_28_03.pdf" => array(4,4,4,4,4,4,3),

    //2010
    "T2k10_01_11_17.pdf" => array(4,4,4,5,4,4,4),
    "T2k10_01_18_24.pdf" => array(4,4,4,4,4,4,3),
    "T2k10_01_25_31.pdf" => array(4,4,4,4,4,5,5),
    "T2k10_02_01_07.pdf" => array(5,4,4,4,4,4,4),
    "T2k10_02_08_14.pdf" => array(4,4,4,4,4,5,4),
    "T2k10_02_15_21.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_02_22_28.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_03_01_07.pdf" => array(4,4,4,4,5,4,5),
    "T2k10_03_15_21.pdf" => array(4,4,4,4,5,4,4),
    "T2k10_03_22_28.pdf" => array(4,4,4,4,4,5,5),
    "T2k10_04_12_18.pdf" => array(4,4,4,4,4,5,4),
    "T2k10_04_19_25.pdf" => array(4,4,4,5,5,3,5),
    "T2k10_04_26_02.pdf" => array(4,4,4,5,4,5,5),
    "T2k10_05_10_16.pdf" => array(4,4,4,4,4,4,3),
    "T2k10_05_17_23.pdf" => array(4,4,4,4,4,5,5),
    "T2k10_06_07_13.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_08_09_15.pdf" => array(4,5,4,4,4,4,4),
    "T2k10_08_23_30.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_09_01_05.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_09_06_12.pdf" => array(3,4,4,4,4,4,4),
    "T2k10_09_13_19.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_09_27_03.pdf" => array(4,4,4,4,5,4,4),
    "T2k10_10_04_10.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_10_11_17.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_10_18_24.pdf" => array(4,4,4,4,5,5,5),
    "T2k10_11_01_07.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_11_22_28.pdf" => array(4,4,4,4,4,4,5),
    "T2k10_12_01_05.pdf" => array(4,4,4,4,4,4,3),
    "T2k10_12_06_12.pdf" => array(4,4,4,4,4,4,5),

    //2011
    "T2k11_01_03_09.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_01_10_16.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_02_01_06.pdf" => array(5,4,4,4,4,4,4),
    "T2k11_02_14_20.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_02_21_27.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_03_21_27.pdf" => array(4,4,4,4,3,4,4),
    "T2k11_03_28_03.pdf" => array(4,4,4,3,4,4,4),
    "T2k11_05_23_29.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_06_20_26.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_06_27_03.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_07_18_24.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_08_15_21.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_08_22_28.pdf" => array(4,4,4,5,4,4,4),
    "T2k11_09_05_11.pdf" => array(4,4,4,4,4,5,4),
    "T2k11_09_12_18.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_10_03_09.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_10_10_16.pdf" => array(4,4,4,3,3,4,5),
    "T2k11_10_24_30.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_11_07_13.pdf" => array(4,4,4,4,4,4),   //13th-07 is missing
    "T2k11_11_14_20.pdf" => array(4,4,4,4,4,4,7),   //Last page i.e. 31st page is blank
    "T2k11_11_21_27.pdf" => array(4,4,4,4,4,4),   //27th-11 is missing
    "T2k11_12_05_11.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_12_12_18.pdf" => array(4,4,4,4,4,4,5),
    "T2k11_12_19_25.pdf" => array(4,4,4,4,4,4,3),
    "T2k11_12_26_31.pdf" => array(4,4,4,4,4,4,5),

    //2012
    "T2k12_01_02_08.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_01_09_15.pdf" => array(4,4,4,4,4,4,3),
    "T2k12_01_23_29.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_02_13_19.pdf" => array(4,4,4,4,3,3,5),
    "T2k12_02_20_26.pdf" => array(3,4,4,4,4,4,4),
    "T2k12_02_27_04.pdf" => array(4,4,5,5,4,4,4),
    "T2k12_03_19_25.pdf" => array(4,4,4,4,4,3,4),
    "T2k12_04_09_15.pdf" => array(4,4,4,4,4,4,3),
    "T2k12_04_23_29.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_04_30_06.pdf" => array(4,4,4,3,4,4,4),
    "T2k12_05_07_13.pdf" => array(4,4,4,4,4,4,3),
    "T2k12_05_21_27.pdf" => array(4,4,4,4,4,3,4),
    "T2k12_05_28_03.pdf" => array(4,4,4,4,4,5,4),
    "T2k12_06_18_24.pdf" => array(4,5,4,5,4,4,4),
    "T2k12_07_23_29.pdf" => array(4,4,4,5,4,4,4),
    "T2k12_07_30_05.pdf" => array(4,4,4,4,4,5,4),
    "T2k12_08_06_12.pdf" => array(4,4,4,4,4,5,5),
    "T2k12_08_13_19.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_08_20_26.pdf" => array(4,4,4,4,4,4,3),
    "T2k12_09_03_09.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_10_08_14.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_10_15_21.pdf" => array(4,4,4,4,4,5,5),
    "T2k12_10_22_28.pdf" => array(3,5,4,4,4,4,10),   //Last page i.e. 28th-10 Murli has 6 blank pages!
    "T2k12_10_29_04.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_11_05_11.pdf" => array(4,5,4,4,4,4,4),
    "T2k12_11_26_02.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_12_10_16.pdf" => array(4,4,4,4,4,4,5),
    "T2k12_12_24_30.pdf" => array(4,4,4,4,4,4,5),

    //2013
    "T2k13_01_01_06.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_01_07_13.pdf" => array(4,5,4,4,4,4,4),
    "T2k13_01_14_20.pdf" => array(4,4,5,4,4,4,4),
    "T2k13_01_21_27.pdf" => array(4,5,5,4,4,4,4),
    "T2k13_01_28_03.pdf" => array(4,4,4,5,4,4,5),
    "T2k13_02_11_17.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_02_18_24.pdf" => array(3,4,4,4,4,4,4),
    "T2k13_03_18_24.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_03_25_31.pdf" => array(4,4,4,4,4,4,3),
    "T2k13_04_08_14.pdf" => array(4,5,4,4,4,4,4),
    "T2k13_04_15_21.pdf" => array(4,4,4,4,4,5,4),
    "T2k13_04_22_28.pdf" => array(4,4,4,4,4,5,5),
    "T2k13_04_29_05.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_05_13_19.pdf" => array(4,5,4,4,4,4,4),
    "T2k13_05_20_26.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_05_27_31.pdf" => array(4,4,4,4,4,5,4),
    "T2k13_06_03_09.pdf" => array(4,4,4,5,4,4,4),
    "T2k13_07_22_28.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_08_26_31.pdf" => array(4,4,4,4,4,4,3),
    "T2k13_10_01_06.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_10_21_27.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_11_04_10.pdf" => array(4,4,4,4,4,4,3),
    "T2k13_11_11_17.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_12_09_15.pdf" => array(4,4,4,4,4,4,5),
    "T2k13_12_16_22.pdf" => array(4,4,4,3,4,4,4),
    "T2k13_12_23_29.pdf" => array(4,4,4,4,4,4,5),
  );
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  if(isset($_POST['initiater'])) {   //"Split Murli PDF" button is clicked
    $message = "Below Results:<ul>";
    if(is_dir("$pdf_source_dir")) {
      if(!is_dir("$outputDir")) {
        if(!mkdir("$outputDir")) {
          $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> creating directory $outputDir!!</li>";
        }
      }
      if(is_dir("$outputDir")) {   //All OK now. Go ahead...
        $pdf_list = glob("$pdf_source_dir/*.pdf");
        $skipped = '';
        @unlink("$not_initiated_splitting");
        //Uncomment below in case of overwrite
        //$pdf_list = array("$pdf_source_dir/T2k9_03_16_22.pdf");
        $total_processed = 0; $total_not_processed = 0;
        foreach($pdf_list as $pdf) {
          $onlyFileWithExt = fileWithExt($pdf);
          $pdf_pg = getPDFPagesCount($pdf);
          $split_freq = array();
          if($pdf_pg === 28) {$split_freq = array(4, 4, 4, 4, 4, 4, 4);}
          else if(array_key_exists($onlyFileWithExt, $pdf_splitfreq_map)) {$split_freq = $pdf_splitfreq_map[$onlyFileWithExt];}
          //Uncomment below in case of overwrite
          //$split_freq = array(4, 4, 4, 4, 4, 4, 4);
          $sum_split_freq_array = array_sum($split_freq);
          if($sum_split_freq_array === $pdf_pg) {
            $message = $message . "<li><span style='color:#FFFF00;'>For $onlyFileWithExt having pages $pdf_pg:</span></li>";
            $result_array = implode("", splitAndMovePDF($pdf, $split_freq));
            $message = $message . "<ul>$result_array</ul>";
            $total_processed++;
          } else {
            $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> Mismatch for $onlyFileWithExt [Pages#$pdf_pg, Split Freq#$sum_split_freq_array]</li>";
            $skipped = "$skipped$onlyFileWithExt [$pdf_pg Pages]\n";
            $total_not_processed++;
          }
        }
        $message = $message . "<li><kbd>Total Processed: $total_processed, Total Not Processed: $total_not_processed</kbd></li>";
        if(strlen($skipped) > 2) {   //logging the files which were not processed
          file_put_contents("$not_initiated_splitting", $skipped);
          $message = $message . "<li>Please check <kbd>" . fileWithExt("$not_initiated_splitting") . "</kbd> for more...</li>";
        }
      } else {
        $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> can't proceed as $outputDir is not a directory any more!!</li>";
      }
    } else {
      $message = $message . "<li><span class='bg-danger text-white'>ERROR!!</span> $pdf_source_dir is no more a directory!!</li>";
    }
    $message = "$message</ul>";
  } else {
    $message = "Output Here...";
  }
  
?>
<html lang="en">
  <head>
    <title>PDF Splitter</title>
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
      $brand_name = "PDF Murli Splitter <i class='fa fa-gavel' aria-hidden='true'></i>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" enctype="multipart/form-data">
        <kbd>PDF Split Dir: <?php echo "$pdf_source_dir"; ?></kbd>
        <button type="submit" id="initiater" name="initiater" class="btn btn-danger mt-4" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          Split Murli PDF
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