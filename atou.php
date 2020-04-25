<?php

  set_time_limit(3000);   //time limit (in second) this script is allowed to run. Will error out if takes more time than this
  ini_set("error_log", "./error_log");
  date_default_timezone_set('Asia/Calcutta');

?>
<html>
  <head>
  <meta charset="utf-8">
  <title>Ravi - Akruti Dev Priya-to-Unicode</title>
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
        modified_substring = modified_substring.replace(/(\r?\n|\r)/g, ' ');
        modified_substring = modified_substring.replace(/\n/g, ' ');
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
  /*replace 
  g-global, 
  gi-case-sensitive global, 
  i-case-sensitive
  m-multiline
  */
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
      $brand_name = "Ravi's <mark>Font <i class='fa fa-font' aria-hidden='true'></i></mark> Converter";
      include('nav_header.php');
    ?>
    <div class="container" style="margin-bottom: 45px;">
      <div style="height: 10px;"></div>
      <form name="form1" style="position:relative;">
        <div class="form-group row" style="margin-top:10px;">
          <label for="legacy_text" class="col-sm-4 col-form-label">
            Akruti Font Text <span style="margin-left:10px;color:crimson;" onclick="legacy_textClicked()"><i class="fa fa-window-close-o" aria-hidden="true"></i></span>
          </label>
          <div class="col-sm-8 form-inline">
            <textarea class="form-control" style="width:100%;resize: none;" name="TextToConvert" placeholder="Paste here your text in any Akruti Devanagari font" id="legacy_text" rows="4" autofocus></textarea>
          </div>
        </div>
        <input type="button" name="converter" id="converter" class="btn btn-danger" style="width:100%;" value=" Convert to Unicode >> " onClick="convert_to_unicode();" accesskey="c">  
        <div class="form-group row" style="margin-top:10px;">
          <label for="unicode_text" class="col-sm-4 col-form-label">
            Unicode Devanagari Text <span style="margin-left:10px;color:crimson;" onclick="unicode_textClicked()"><i class="fa fa-window-close-o" aria-hidden="true"></i></span>
          </label>
          <div class="col-sm-8 form-inline">
            <textarea class="form-control" style="width:100%;resize: none;" name="ConvertedText" id="unicode_text" cols="92" rows="14" placeholder="Conversion out put will be shown here"></textarea>
          </div>
        </div>
      </form>
      <a href="i.php" class="btn btn-success" style="position: fixed;bottom: 3px;right: 3px;">
      Go Home
      </a>
    </div>
  </body>
</html>
