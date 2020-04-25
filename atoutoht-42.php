<?php

  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');
  include './util.php';
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $htm_destination_dir = "$rootdir/000-Ravi-DontDelete/misshtms";   //generated htm will be placed here
  
  $final = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>"
          . "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0'>"
          . "<title>Brahma Kumaris</title></head>\r\n";
  $final = "$final<body link='#800000' vlink='#800000' bgcolor='#FFEBCC' topmargin='0' leftmargin='0'>\r\n";
  $final = "$final<table cellspacing='2' cellpadding='0' width='980' border='0' style='letter-spacing: normal; orphans: auto; text-indent: 0px; text-transform: none; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;'>\r\n";
  $final = "$final<tr><td valign='top' width='980'><table width='980'><colgroup><col width='325' style='width: 244pt;'><col width='97' style='width: 73pt;'></colgroup>\r\n";
  $final = "$final<tr><td class='xl26' valign='middle' align='left' width='980' style='border: 0px solid rgb(192, 192, 192);'>\r\n";
  $final = "$final<blockquote>\r\n";
  $final = "$final<span style='font-size:16pt; color:#006600; lang=HI; font-family:Mangal; text-align:justify; line-height:normal'>\r\n";
  $post = "</span></p>";
  //Printing all $_POST keys and values
  //echo '<blockquote><table>'; foreach ($_POST as $key => $value) {echo "<tr><td>$key</td><td>&nbsp;&nbsp;$value</td></tr>";} echo '</table></blockquote>';
  $murli_file_name_only = '';
  $final_murli = '';
  
  function isMurliOK($converted_text, $is_date_sunday) {
    global $final_murli;
    if(!$is_date_sunday) {   //it its not a sunday murli
      if(strpos($converted_text, "प्रश्न:-") === false) {
        $final_murli = "<span class='bg-danger text-white'>This Murli is mssing प्रश्न:-";
        return false;
      }
      if(strpos($converted_text, "उत्तर:-") === false) {
        $final_murli = "<span class='bg-danger text-white'>This Murli is missing उत्तर:-";
        return false;
      }
      if(strpos($converted_text, "मीठे-मीठे सिकीलधे बच्चों प्रति") === false) {
        $final_murli = "<span class='bg-danger text-white'>This Murli is missing मीठे-मीठे सिकीलधे बच्चों प्रति";
        return false;
      }
      if(strpos($converted_text, "धारणा के लिए मुख्य सार:-") === false) {
        $final_murli = "<span class='bg-danger text-white'>This Murli is missing धारणा के लिए मुख्य सार:-";
        return false;
      }
    }
    if(strpos($converted_text, "वरदान:-") === false) {
      $final_murli = "<span class='bg-danger text-white'>This Murli is missing वरदान:-";
      return false;
    }
    if(strpos($converted_text, "स्लोगन:-") === false) {
      $final_murli = "<span class='bg-danger text-white'>This Murli is missing स्लोगन:-";
      return false;
    }
    return true;
  }
  
  if(isset($_POST['generate'])) {   //"It Looks OK, Now Generate Hindi Htm!" button is clicked
    if(isset($_POST['ConvertedText']) && !empty($_POST['ConvertedText'])) {
      $converted_text = preg_replace("/[[:blank:]]+/"," ", $_POST['ConvertedText']);
      $str_pos_madhuban = strpos($converted_text, "मधुबन") + strlen("मधुबन");   //e.g. 18-10-16 प्रात:मुरली ओम् शान्ति ''बापदादा'' मधुबन
      $murli_header_plain = substr($converted_text, 0, $str_pos_madhuban);
      
      $tmp = explode(" ", $murli_header_plain);   //e.g. 18-10-16 प्रात:मुरली ओम् शान्ति ''बापदादा'' मधुबन
      $raw_date = str_replace("-", ".", trim($tmp[0]));
      $murli_file_name_only = $raw_date . "-H.htm";   //converting 18-10-16 to 18.10.16-H.htm
      $is_date_sunday = date("w", strtotime(getAnyFormatDate($raw_date, 'd.m.y', 'Y-m-d'))) == 0;   //if date is sunday
      if($is_date_sunday) {
        $converted_text = "<center><p style='color:blue;'>" . trim(substr($converted_text, $str_pos_madhuban));   //entire murli excluding heading
        $converted_text = preg_replace('/\n/', "</center><p style='color:navy;'>", $converted_text, 1);   //replace first occurance of \n to <p>. because before \n it will be the Murli title
      } else {
        $converted_text = "<p style='color:blue;'>" . trim(substr($converted_text, $str_pos_madhuban));   //entire murli excluding heading
      }
      $converted_text = str_replace($murli_header_plain, "", $converted_text);
      if(isMurliOK($converted_text, $is_date_sunday)) {   //Proceed only if this is valid Murli
        $converted_text = str_replace("प्रश्न:-", "<p style='color:#7030A0;'>प्रश्न:-", $converted_text);
        $converted_text = str_replace("उत्तर:-", "<p style='color:#C00000;'>उत्तर:-", $converted_text);
        $converted_text = str_replace("गीत:-", "<p style='color:#FF0000;'>गीत:-", $converted_text);
        $converted_text = str_replace("ओम् शान्ति।", "<p style='color:navy;'><b><span style='color:red;'>ओम् शान्ति।</span></b><br>", $converted_text);
        $converted_text = str_replace("ओम शांति।", "<p style='color:navy;'><b><span style='color:red;'>ओम् शान्ति।</span></b><br>", $converted_text);
        $converted_text = str_replace("मीठे-मीठे सिकीलधे बच्चों प्रति", "<p style='color:navy;'>मीठे-मीठे सिकीलधे बच्चों प्रति", $converted_text);
        $converted_text = str_replace("धारणा के लिए मुख्य सार:-", "<p><b><span style='color:red;'>धारणा के लिए मुख्य सार:-</span></b></p>", $converted_text);
        
        $dharna_position = strpos($converted_text, "धारणा के लिए मुख्य सार:-") + strlen("धारणा के लिए मुख्य सार:-");
        if($dharna_position !== false) {   //i.e. its not the sunday murli
          $murli_upto_dharna_heading = substr($converted_text, 0, $dharna_position);
          $murli_after_dharna_heading = substr($converted_text, $dharna_position);
          $murli_after_dharna_heading = str_replace("1)", "<p style='color:#008000;'>1)", $murli_after_dharna_heading); 
          $murli_after_dharna_heading = str_replace("2)", "<p style='color:#008000;'>2)", $murli_after_dharna_heading); 
          $murli_after_dharna_heading = str_replace("1.", "<p style='color:#008000;'>1)", $murli_after_dharna_heading); 
          $murli_after_dharna_heading = str_replace("2.", "<p style='color:#008000;'>2)", $murli_after_dharna_heading); 
          $converted_text = $murli_upto_dharna_heading . $murli_after_dharna_heading;
        }
        
        $converted_text = str_replace("वरदान:-", "</p><p style='color:fuchsia;'>वरदान:-", $converted_text);
        $converted_text = str_replace("स्लोगन:-", "<p style='color:blue;'>स्लोगन:-", $converted_text);
        $position_vardan_bhav = strpos($converted_text, "भव", strpos($converted_text, "वरदान:-")) + strlen("भव");
        $murli_upto_vardan_bhav = substr($converted_text, 0, $position_vardan_bhav);
        $murli_after_bhav = substr($converted_text, $position_vardan_bhav);
        $cooked_murli = "$murli_upto_vardan_bhav<p style='color:navy;'>$murli_after_bhav";
        
        $murli_header = "<p align=center><span style='color:red;'>" 
            . "$murli_header_plain$post"
            . "\n<hr size='1' width='100%' noshade style='color:maroon' align='center'>";
        
        $final_murli = "$final$murli_header\r\n<p>$cooked_murli</p>\r\n";
        $final_murli = "$final_murli</span></span></blockquote></td></tr></table></td></tr></table></body></html>";
        if(!file_put_contents("$htm_destination_dir/$murli_file_name_only", $final_murli)) {
          $murli_file_name_only = "<span class='bg-danger text-white'>ERROR!!</span> Creating file $murli_file_name_only";
        } else {
          $murli_file_name_only = "$murli_file_name_only created <span class='bg-success text-white'>successfully !</span>";
        }
      } else{
        $murli_file_name_only = "INVALID MURLI !! Please see below for more..";   //a way to show message
      }
    }
  }
  
?>
<html>
  <head>
  <meta charset="utf-8">
  <title>Akruti Dev Priya-to-Htm</title>
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
  <script type="text/javascript">
  function convert_to_unicode() {
    var array_one = new Array(
      "ÒeÍe>>", "प्रश्न",   //ravi fixed
      'e\\ve', "र्निं",   //ravi fixed
      "OeceeX", "धर्मों",   //ravi fixed
      '"', "ठ",   //ravi fixed
      "e|", "र्ि",   //ravi fixed
      "keWÀ","कें",   //ravi fixed e.g. sa'ken'ge
      "1","१",
      "2","२",
      "3","३",
      "4","४",
      "5","५",
      "6","६",
      "7","७",
      "8","८",
      "9","९",
      "0","०",
      "ß","ॐ",//
      " ̒","'",
      " ̓","'",
      "`","'",
      "e̓s","े'",
      "•","ड्ड",
      "ñe","mj",
      "Ô","ह्",
      "ea","ीÃ",
      "›","ड़",
      "!̓","!'",
      "¦","्रु",
      "?̓","?'",
      "õ","–",//
      "C³ex","र्ण्ये",
      "C³eex","र्ण्यो",
      "DeeW ","ओं",
      "eex","eesÃ",
      "ex","esÃ",
      "x","ेÃ",
      "eez","eewÃ",
      "ez","ewÃ",
      "z","ौÃ",
      "z","ौÃ",
      "Ne","र",
      "N","र्",
      "Öe","प्र",
      "Ö","प्र्",
      //"eE","ंि",
      "eE","ß",
      "²","ङ्ग",
      "§","ु",
      "$eÝ","ऋ",
      "^","्र",
      "±","ङ्ख",
      "¨e","द्म",
      "¨","द्म",
      "¿e","ह्य",
      "eÅ","bि",
      "eA","ीÃb",
      "dÀ","Àd",
      "bÀ","Àb",
      "gÀ","Àg",
      "tÀ","Àt",
      "sÀ","Às",
      "wÀ","Àw",
      "BÀ","ÀB",
      "=À","À=",
      "Ü","द्व",
      "eEkeÀ","किं",
      "keÀ","क",   //ravi
      "E","प्",
      "½e","श्च",
      "»","ह्व",
      "×","द्ध",
      "îe","्य",
      "Úe","ह्न",
      "Ú","ह्न",
      "Je","व",
      "J","व्",
      "ÿ","ष्ठ",
      "¢","दृ",
      "³e","य",
      "#e","क्ष",
      "#","क्ष्",
      "¹","ङ्क",
      "¼","ह्ल",
      "¡e","ञ्ज",
      "%e","ज्ञ",
      "%","ज्ञ्",
      "ö","-",
      "Q","ैं",
      //"”","ह्ण",
      "ñ","स्र",
      "Â","द्ब",
      "æ","़",
      "Ó","द्भ",
      "F&","ई",
      "Ss","ऐ",
      "Deew","औ",
      "keÀ","क",
      "Dees","ओ",
      "#e","क्ष",
      "$eÀ","क्र",
      "$e","त्र",
      "ïe","ह्म",
      "¿e","ह्य",
      "er","ी",
      "Ke","ख",
      "K","ख्",
      "ðe","स्त्र",
      "ûe","ग्र",
      "û","ग्",
      "ÊeÀ","क्त",
      "Êe","त्त",
      "Ê","त्त्",
      "&À","À&",
      "Æ","ट्ट",
      "Ve","न्न",
      "V","न्न्",//
      "Òe","प्र",
      "Ò","प्र",
      "Î","द्द",
      "eq","ि",
      "~","।",
      "ie²","ङ्ग",
      "²e","ङ",
      "²","ङ्ग",
      "ª","रू",
      "Í","श्",
      "}","ल",
      "ä","ष्ट",
      "==","ॄ",
      "=","ृ",
      //"Ü","त्र",
      "Þe","श्र",
      //"Þ","श्र",
      "ée","श्व",
      "©","रु",
      "áe","ञ्च",
      "ç","ऽ",
      "HeÀ","फ",
      "$","ledjd",
      "B","ँ",
      "bb","ं",
      "b","ं",
      "eR","ीं",
      "ë","ः",
      "F","इ",
      "G","उ",
      "T","ऊ",
      "keÌ","क्",
      "¬eÀ","क्र",
      "¸","ख्र",
      "ie","ग",
      "i","ग्",
      "Ie","घ",
      "I","घ्",
      "*","ङ",
      "®e","च",
      "®","च्",
      "í","छ",
      "pe","ज",
      "p","ज्",
      "ý","ज्र",
      "Pe","झ",
      "â","झ्र",
      "_e","ञ",
      "ì","ट",
      "þ","ठ",
      "[","ड", //there is clash between these two. Can't be resolved. All [ must be checked finally for probable ड
      "{","ढ",
      "Ce","ण",
      "C","ण्",
      "le","त",
      "Le","थ",
      "L","थ्",
      "o","द",
      "Ðe","द्य",
      "ê","द्र",
      "Oe","ध",
      "O","ध्",
      "ve","न",
      "v","न्",
      "He","प",
      "he","प",
      "H","प्",
      "ÖeÀ","फ्र",
      "ye","ब",
      "Ye","भ",
      "ce","म",
      "y","ब्",
      "Y","भ्",
      "c","म्",
      "j","र",
      "e´","्र",
      "´e","्र",
      "´","्र",
      "ue","ल",
      "u","ल्",
      "Me","श",
      "M","श्",
      "<e","ष",
      "<","ष्",
      "me","स",
      "m","स्",
      "n","ह",
      "Ë","हृ",
      "Ú","ह्न",
      "Û","ह्र",
      "ew","ौ",
      "eW","ों",
      "W","ें",
      "g","ु",
      "t","ू",
      "ess","ो",
      "es","ो",
      "ss","े",
      "s","े",
      "ww","ै",
      "w","ै",
      "dd","्",
      "d","्",
      //"ef","ि",
      "ef","ि",
      "$eÝ","ऋ",
      "S","ए",
      "keÀ","क",
      "Dee","आ",
      "De","अ",
      "ke","व",
      "ees","ो",
      "e","ा",
      "_","ञ्",
      "³","य्",
      "l","त्",
      "h","प्",   //ravi fixed. e.g. 'py'ase
      "ùer","ट्ठी",  //ravi fixed e.g. bha'tthi'
      "]","़",   //ravi fixed e.g. z.ameen
      "@", "ॅ",   //ravi fixed
    )

    //**********************************************
    //variables are set here. array_one_length is the variable and its value is array_one.length
    //The value property sets or returns the value of the value attribute of a text field.
    //The value property contains the default value OR the value a user types in (or a value set by a script).
    var array_one_length = array_one.length ;
    var modified_substring = document.getElementById("legacy_text").value  ;	
    modified_substring = modified_substring.replace(/\n/g, "");
    document.getElementById("unicode_text").value = "Conversion in progress.."  ;  
    //****************************************************
    //  Break the long text into small bunches of chunk_size  characters each.
    //****************************************************
    var text_size = modified_substring.length ;
    var processed_text = ''; //blank
    var sthiti1 = 0;  var sthiti2 = 0;  var chale_chalo = 1;
    var chunk_size = 6000; // this character long text will be processed in one go.
    while (chale_chalo == 1) {
      sthiti1 = sthiti2;
      if (sthiti2 < (text_size - chunk_size) ) { 
        sthiti2 +=  chunk_size;
      }
      else  {
        sthiti2 = text_size;
        chale_chalo = 0
      }
      var modified_substring = document.getElementById("legacy_text").value.substring (sthiti1, sthiti2);
      Replace_Symbols() ;
      var processed_text = processed_text + modified_substring ;
      document.getElementById("unicode_text").value = "Conversion in progress.." + '\n\n' + 'Conversion of ' + sthiti2 + ' charecters out of ' + text_size + ' completed.';
    }
    
    document.getElementById("unicode_text").value = processed_text;
    function Replace_Symbols() {
      //substitute array_two elements in place of corresponding array_one elements
      if(modified_substring != "") {
        for(input_symbol_idx = 0; input_symbol_idx < array_one_length-1; input_symbol_idx = input_symbol_idx + 2) { 
          idx = 0;  //index of the symbol being searched for replacement
          while(idx != -1) {
            modified_substring = modified_substring.replace( array_one[ input_symbol_idx ] , array_one[input_symbol_idx+1] )
            idx = modified_substring.indexOf( array_one[input_symbol_idx] )
          }
        }
        // following statements for adjusting postion of i maatraas.
        //ravi below one line
        modified_substring = modified_substring.replace(   /([ि])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$1" ) ;
        modified_substring = modified_substring.replace( /([ि])([्])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$3$1" ) ;
        modified_substring = modified_substring.replace( /([ि])([्])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$3$1" ) ;
        modified_substring = modified_substring.replace( /f/g ,  "ि" ) ;
        //for anuswar shifting
        modified_substring = modified_substring.replace(   /([ß])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$1" ) ;
        modified_substring = modified_substring.replace( /([ß])([्])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$3$1" ) ;
        modified_substring = modified_substring.replace( /([ß])([्])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$3$1" ) ;
        modified_substring = modified_substring.replace( /ß/g ,  "िं" ) ;
        //following three statement for adjusting position of reph ie, half r
        modified_substring = modified_substring.replace( /([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([ािीुूृेैोौंँ]*)¥/g, "¥$1$2ं") ;
        modified_substring = modified_substring.replace( /([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([्])¥/g, "¥$1$2") ;
        modified_substring = modified_substring.replace(/¥/g , "र्" ) ;
        modified_substring = modified_substring.replace( /([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([ािीुूृेैोौंँ]*)&/g, "&$1$2");
        modified_substring = modified_substring.replace( /([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([्])&/g , "&$1$2" ) ;
        modified_substring = modified_substring.replace(/&/g, "र्") ;
        modified_substring = modified_substring.replace(/([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([ािीुूृेैोौंँ])Ã/g , "x$1$2" ) ;
        modified_substring = modified_substring.replace(/([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([ािीुूृेैोौंँ]*)Ã/g , "x$1$2" ) ;
        modified_substring = modified_substring.replace(/([कखगघचछजझटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])([्])Ã/g, "x$1$2" ) ;
        modified_substring = modified_substring.replace(/x/g, "र्") ;
        modified_substring = modified_substring.replace(/Ã/g, "र्") ;
        modified_substring = modified_substring.replace(/ंसि/g, "सिं") ;
        modified_substring = modified_substring.replace(/Je/g, "व") ;
        modified_substring = modified_substring.replace(/([़])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g, "$2$1") ;
        //ravi did some below replacements
        modified_substring = modified_substring.replace( /([ौ])([ं])([कखगघङचछजझञटठडड़ढढ़णतथदधनपफबभमयरलळवशषसहक्षज्ञ])/g , "$2$3$1" ) ;
        modified_substring = modified_substring.replace( /आॅ/g, "ऑ") ;
        //modified_substring = modified_substring.replace( /(ा)(ॅ)/g, "$2$1");
        modified_substring = modified_substring.replace("पुरुषाा|थयों", "पुरूषार्थियों");
        modified_substring = modified_substring.replace("प्रÀे", "फ्रे");
        modified_substring = modified_substring.replace("ख्ाुद", "खुद");
        modified_substring = modified_substring.replace("ख्ाु", "खु");
        modified_substring = modified_substring.replace("क्य्ाा", "क्या");
        modified_substring = modified_substring.replace("पÌट", "फ्ट");
        modified_substring = modified_substring.replace("k्रÀोध", "क्रोध");
        modified_substring = modified_substring.replace("k्रÀो", "क्रो");
        modified_substring = modified_substring.replace("k्रÀ", "क्र");
        modified_substring = modified_substring.replace("kाÀ", "क");
        modified_substring = modified_substring.replace("अोंं", "ओं");
        //modified_substring = modified_substring.replace(/@/g, "");
        modified_substring = modified_substring.replace("उÀ", "ऊ");
        modified_substring = modified_substring.replace("सा|व", "सर्वि");
        modified_substring = modified_substring.replace("य्ा", "य");
        modified_substring = modified_substring.replace("आ@ख", "आँख");
        modified_substring = modified_substring.replace("िज़", "ज़ि");
        modified_substring = modified_substring.replace("त्ो", "ते");
        modified_substring = modified_substring.replace("माX", "मों");
        modified_substring = modified_substring.replace("प्रÀी", "फ्री");
        modified_substring = modified_substring.replace("प्रÀे", "फ्रे");
        modified_substring = modified_substring.replace("व्ाा", "वा");
        modified_substring = modified_substring.replace("न्ाा", "ना");
        modified_substring = modified_substring.replace("क्ùी", "कट्ठी");
        modified_substring = modified_substring.replace("ùी","ट्ठी");
        modified_substring = modified_substring.replace("निा|व", "निर्वि");
        modified_substring = modified_substring.replace("य्ा", "य");
        modified_substring = modified_substring.replace("र्णर्", "र्ण");
        modified_substring = modified_substring.replace("अों", "ओं");
        modified_substring = modified_substring.replace("भ्ाी", "भी");
        modified_substring = modified_substring.replace("आंे", "ओं");
        modified_substring = modified_substring.replace("पुरÀषार्थ", "पुरुषार्थ");
        modified_substring = modified_substring.replace("पुरÀषर्थ", "पुरुषार्थ");
        modified_substring = modified_substring.replace("सा|वस", "सर्विस");
        modified_substring = modified_substring.replace("त्र+षि", "ऋषि");
        modified_substring = modified_substring.replace("हँू", "हूँ");
        modified_substring = modified_substring.replace("का|ष","कर्षि");
        modified_substring = modified_substring.replace("व्ाार", "वार");
        modified_substring = modified_substring.replace("अों", "ओं");
        modified_substring = modified_substring.replace("ùी", "ट्टी");
        modified_substring = modified_substring.replace("ा़िज", "जि");
        modified_substring = modified_substring.replace("मÏ", "म्र");
        modified_substring = modified_substring.replace("रज़िल्ट", "रिज़ल्ट");
        modified_substring = modified_substring.replace("ः-", ":-");
        modified_substring = modified_substring.replace("वÀो", "को");
        modified_substring = modified_substring.replace(/वÀ/g, "क");
        modified_substring = modified_substring.replace("Dाा", "आ");
        modified_substring = modified_substring.replace("पÌ", "फ्ला");
        modified_substring = modified_substring.replace("सिऱ्फ", "सिर्फ");
        modified_substring = modified_substring.replace("ùा", "ठ्ठा");
        modified_substring = modified_substring.replace("इकùी", "इकट्ठी");
        
        //Below custom ones
        //modified_substring = modified_substring.replace(/(\r?\n|\r)/g, ' ');
        //modified_substring = modified_substring.replace(/\n/g, ' ');
        modified_substring = modified_substring.replace(/१/g, '1');   //Since I need english numbers, so revert back
        modified_substring = modified_substring.replace(/२/g, '2');
        modified_substring = modified_substring.replace(/३/g, '3');
        modified_substring = modified_substring.replace(/४/g, '4');
        modified_substring = modified_substring.replace(/५/g, '5');
        modified_substring = modified_substring.replace(/६/g, '6');
        modified_substring = modified_substring.replace(/७/g, '7');
        modified_substring = modified_substring.replace(/८/g, '8');
        modified_substring = modified_substring.replace(/९/g, '9');
        modified_substring = modified_substring.replace(/०/g, '0');
        modified_substring = modified_substring.replace(/([1-9]\/[1-9])/g, '');   //removing page number
        modified_substring = modified_substring.trim();
        //modified_substring = modified_substring.replace(/(आलस्य-अलबेलापन)(\r?\n|\r)([०-९][०-९])/g,''); //this is custom. different for different docs
        //modified_substring = modified_substring.replace(/(मन्सा सेवा)(\r?\n|\r)([०-९][०-९])/g,'');
        //modified_substring = modified_substring.replace(/\n/, " ");
        //modified_substring = modified_substring.replace(/\(/g, "\n(");
        //modified_substring = modified_substring.replace(/०\) /g, "०)\n");
        //modified_substring = modified_substring.replace(/१\) /g, "१)\n");
        //modified_substring = modified_substring.replace(/२\) /g, "२)\n");
        //modified_substring = modified_substring.replace(/३\) /g, "३)\n");
        //modified_substring = modified_substring.replace(/४\) /g, "४)\n");
        //modified_substring = modified_substring.replace(/५\) /g, "५)\n");
        //modified_substring = modified_substring.replace(/६\) /g, "६)\n");
        //modified_substring = modified_substring.replace(/७\) /g, "७)\n");
        //modified_substring = modified_substring.replace(/८\) /g, "८)\n");
        //modified_substring = modified_substring.replace(/९\) /g, "९)\n");
        
        
      }
    }
  }
  
  function legacy_textClicked() {
    document.getElementById("legacy_text").value = '';
  }
  
  function unicode_textClicked() {
    document.getElementById("unicode_text").value = '';
  }
  
  </script>
  </head>
  <body style="padding-top: 70px;font-family: Ubuntu;background-color: #FFEBCC">
    <?php
      $brand_name = "Akruti Dev Priya To Htm <mark>Font <i class='fa fa-font' aria-hidden='true'></i></mark>";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form method="post" action="" onsubmit="return validateForm0(this);" name="form1" style="position:relative;">
        <?php if(strlen($murli_file_name_only) > 2) {echo "<div style='margin-top:30px;'><kbd>$murli_file_name_only <u>[see below Murli Template]</u></kbd></div>";}?>
        <div class="form-group row" style="margin-top:30px;">
          <label for="legacy_text" class="col-sm-4 col-form-label">
            Copy Hindi Murli PDF Text: <span style="margin-left:10px;color:crimson;" onclick="legacy_textClicked()"><i class="fa fa-window-close-o" aria-hidden="true"></i></span>
          </label>
          <div class="col-sm-8 form-inline">
            <textarea class="form-control" style="width:100%;resize: none;" name="TextToConvert" placeholder="Paste here your text in any Akruti Devanagari font" id="legacy_text" rows="4" autofocus></textarea>
          </div>
        </div>
        <input type="button" name="converter" id="converter" class="btn btn-danger" style="width:100%;" value="Click To Convert Into Readable Devanagari Text" onClick="convert_to_unicode();" accesskey="c">  
        <div class="form-group row" style="margin-top:10px;">
          <label for="unicode_text" class="col-sm-4 col-form-label">
            Converted Readable Devanagari Text: <span style="margin-left:10px;color:crimson;" onclick="unicode_textClicked()"><i class="fa fa-window-close-o" aria-hidden="true"></i></span>
          </label>
          <div class="col-sm-8 form-inline">
            <textarea class="form-control" style="width:100%;resize: none;" name="ConvertedText" id="unicode_text" cols="92" rows="14" placeholder="Conversion out put will be shown here"></textarea>
          </div>
        </div>
        <button type="submit" id="generate" name="generate" class="btn btn-success mt-4" style="width:100%;">
          <span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;">&nbsp;</span>
          It Looks OK, Now Generate Hindi Htm!
        </button>
        <?php if(strlen($final_murli) > 10) { echo "<div style='margin-top:30px;'>$final_murli</div>"; } ?>
      </form>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
      Go Home
      </a>
    </div>
    <script language="javascript">
      
      function validateForm0(form) {
        document.getElementById("generate").innerHTML = '<span id="loading_spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:inline-block;"></span>&nbsp;Generating Hindi Htm...';
        return true;
      }
      
      function enableFormControls() {
        document.getElementById("loading_spinner").style.display = "none";
      }
      
      window.onload=enableFormControls();
    </script>
  </body>
</html>
