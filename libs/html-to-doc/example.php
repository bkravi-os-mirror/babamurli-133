<?php 

  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  $message = '';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  include_once 'class.doc.php';
  
  $input_html_file = "$rootdir/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm/11.03.20-H.htm";
  $output_doc_file = "./test.doc";
  
  $htd = new HTML_TO_DOC();
  $htd->createDoc($input_html_file, $output_doc_file);

?>
