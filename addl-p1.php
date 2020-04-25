<?php
  
  //Here all variables are decalred for addl.php file
  
  $rootdir = $_SERVER['DOCUMENT_ROOT'];
  $weburl_babamurli = "http://babamurli.com";
  $weburl_bkdrluhar = "http://bkdrluhar.com";
  
  $languages = array(
    "Hindi", "Tamil", "English", "Kannada", "Assame", "Bengali", "Malayalam", "Marathi", "Punjabi", 
    "Odiya", "Deutsch", "Nepali", "Gujarati", "Italiano", "Spanish", "Chinese", "French", "Tamil-Lanka", 
    "Greek", "Hungarian", "Polish", "Korean", "Sindhi", "Portuguese", "Sinhala", "Thai", "Telugu", 
    "Separate Series in Hindi",
  );
  sort($languages);
  
  $file_source_file_array = array(
    
    //Assame
    "Assame_Murli_Pdf" => "$rootdir/00. Htm/08. Assame.html",
    "Assame_Murli_Htm" => "$rootdir/00. Htm/08. Assame.html",

    //Bengali
    "Bengali_Murli_Pdf" => "$rootdir/00. Htm/07. Bengali.html",
    "Bengali_Murli_Htm" => "$rootdir/00. Htm/07. Bengali.html",
    
    //Chinese
    "MP3_Chinese" => "$rootdir/00. Htm/34. Chinese.html",

    //Deutsch
    "PDF_Deutsch" => "$rootdir/00. Htm/31. Deutsch.html",
    "Mp3_Deutsch" => "$rootdir/00. Htm/31. Deutsch.html",
    "Htm_Deutsch" => "$rootdir/00. Htm/31. Deutsch.html",
    
    //English
    "Eng_Murli_Vardan_jpg" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Vardan2_jpg" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Ess_MP3" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_MP3_UK" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Htm" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Hindi_Words_Amola" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Ess_MP3_UK" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Swaman_jpg" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Pdf" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_MP3_2" => "$rootdir/00. Htm/02. English.html",
    "Eng_Murli_Ess_SMS" => "$rootdir/00. Htm/02. English.html",
    "Eng_Today_Calendar" => "$rootdir/00. Htm/02. English.html",
    "Eng_Todays_Thought" => "$rootdir/00. Htm/02. English.html",
    
    //French
    "PDF_French" => "$rootdir/00. Htm/36. French.html",
    "Mp3_French" => "$rootdir/00. Htm/36. French.html",
    "Htm_French" => "$rootdir/00. Htm/36. French.html",
    
    //Greek
    "PDF_Greek" => "$rootdir/00. Htm/37. Greek.html",
    "Htm_Greek" => "$rootdir/00. Htm/37. Greek.html",
    
    //Gujarati
    "Gujarati_Murli_PDF" => "$rootdir/00. Htm/13. Gujarati.html",
    "Gujarati_Murli_Htm" => "$rootdir/00. Htm/13. Gujarati.html",
    "Gujarati_Murli_Mp3" => "$rootdir/00. Htm/13. Gujarati.html",
    
    //Hindi
    "Murli_Preeti_Bahen" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Mumbai" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Saar_SMS" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Saar_MP3" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Chart_Htm" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_OSB_MP4" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Vardan_jpg" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Saar_MP3_2" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Vardan_2jpg" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_MP3_2" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Swaman_jpg" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_MP3" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Chintan_Suraj_Bhai_H" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Pdf" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_OSB_MP3" => "$rootdir/00. Htm/01. Hindi.html",
    "Murli_Chart_pdf" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Murli_Htm" => "$rootdir/00. Htm/01. Hindi.html",
    
    //Hungarian
    "PDF_Hungarian" => "$rootdir/00. Htm/38. Hungarian.html",
    "Htm_Hungarian" => "$rootdir/00. Htm/38. Hungarian.html",
    
    //Italiano
    "PDF_Italiano" => "$rootdir/00. Htm/33. Italian.html",
    "Mp3_Italiano" => "$rootdir/00. Htm/33. Italian.html",
    "Htm_Italiano" => "$rootdir/00. Htm/33. Italian.html",
    
    //Kannada
    "Kannada_Murli_Pdf" => "$rootdir/00. Htm/05. Kannada.html",
    "Hindi_To_Kannada_Murli_Mp3" => "$rootdir/00. Htm/05. Kannada.html",
    "Kannada_Murli_V2_MP3" => "$rootdir/00. Htm/05. Kannada.html",
    "Kannada_Murli_Ess_MP3" => "$rootdir/00. Htm/05. Kannada.html",
    "Kannada_Murli_Htm" => "$rootdir/00. Htm/05. Kannada.html",
    "Kannada_Murli_MP3" => "$rootdir/00. Htm/05. Kannada.html",
    "Kannada_AKP" => "$rootdir/00. Htm/05. Kannada.html",
    
    //Korian
    "Htm_Korian" => "$rootdir/00. Htm/39. Korean.html",
    "PDF_Korian" => "$rootdir/00. Htm/39. Korean.html",
    
    //Malayalam
    "Malayalam_Murli_Pdf" => "$rootdir/00. Htm/06. Malayalam.html",
    "Malayalam_Murli_MP3" => "$rootdir/00. Htm/06. Malayalam.html",
    "Malayalam_Murli_Htm" => "$rootdir/00. Htm/06. Malayalam.html",
    
    //Marathi
    "MarathiMurli-Htm" => "$rootdir/00. Htm/12. Marathi.html",
    "MarathiMurli-PDF" => "$rootdir/00. Htm/12. Marathi.html",
    "MarathiMurli-Mp3" => "$rootdir/00. Htm/12. Marathi.html",

    //Nepali
    "Nepali_Murli_Htm" => "$rootdir/00. Htm/30. Nepali.html",
    "Nepali_Murli_MP3" => "$rootdir/00. Htm/30. Nepali.html",
    "Nepali_Murli_Pdf" => "$rootdir/00. Htm/30. Nepali.html",
    
    //Odiya
    "Odiya_Murli_MP3" => "$rootdir/00. Htm/10. Odiya.html",
    "Odiya_Murli_Htm" => "$rootdir/00. Htm/10. Odiya.html",
    "Odiya_Murli_Pdf" => "$rootdir/00. Htm/10. Odiya.html",
    
    //Polish
    "Htm-Polish" => "$rootdir/00. Htm/40. Polish.html",
    "PDF-Polish" => "$rootdir/00. Htm/40. Polish.html",
    "MP3-Polish" => "$rootdir/00. Htm/40. Polish.html",
    
    //Portuguese
    "Htm_Portuguese" => "$rootdir/00. Htm/41. Portuguese.html",
    "PDF_Portuguese" => "$rootdir/00. Htm/41. Portuguese.html",
    "MP3_Portuguese" => "$rootdir/00. Htm/41. Portuguese.html",
    
    //Punjabi
    "Punjabi_Murli_MP3" => "$rootdir/00. Htm/11. Punjabi.html",
    "Punjabi_Murli_Htm" => "$rootdir/00. Htm/11. Punjabi.html",
    "Punjabi_Murli_PDF" => "$rootdir/00. Htm/11. Punjabi.html",
    
    //Separate Series in Hindi
    "Today_Moti" => "$rootdir/00. Htm/01. Hindi.html",
    "Today_Calendar_H" => "$rootdir/00. Htm/01. Hindi.html",
    "Todays_Commentary_MP3" => "$rootdir/00. Htm/01. Hindi.html",
    "Avyakt_Palna" => "$rootdir/00. Htm/01. Hindi.html",
    "Hindi_Aaj_Ka_Purushrath" => "$rootdir/00. Htm/01. Hindi.html",
    
    //Sindhi
    "Pdf-Sindhi" => "$rootdir/00. Htm/42. Sindhi.html",
    
    //Sinhala
    "PDF_Sinhala" => "$rootdir/00. Htm/44. Sinhala.html",
    "Htm_Sinhala" => "$rootdir/00. Htm/44. Sinhala.html",
    "Mp3_Sinhala" => "$rootdir/00. Htm/44. Sinhala.html",
    
    //Spanish
    "Htm_Spanish" => "$rootdir/00. Htm/32. Spanish.html",
    "Mp3_Spanish" => "$rootdir/00. Htm/32. Spanish.html",
    "PDF_Spanish" => "$rootdir/00. Htm/32. Spanish.html",
    
    //Tamil
    "TamilMurli-Htm" => "$rootdir/00. Htm/03. Tamil.html",
    "TamilMurli-Pdf" => "$rootdir/00. Htm/03. Tamil.html",
    "TamilMurli-MP3" => "$rootdir/00. Htm/03. Tamil.html",
    "TamilMurli-Ess-MP3" => "$rootdir/00. Htm/03. Tamil.html",
    "TamilMurli-Vizual-Pdf" => "$rootdir/00. Htm/03. Tamil.html",
    "TamilThoughts" => "$rootdir/00. Htm/03. Tamil.html",
    
    //Tamil Lanka
    "MP3_Tamil_Lanka" => "$rootdir/00. Htm/35. Tamil-Lanka.html",
    "PDF_Tamil_Lanka" => "$rootdir/00. Htm/35. Tamil-Lanka.html",
    "Htm_Tamil_Lanka" => "$rootdir/00. Htm/35. Tamil-Lanka.html",
    
    //Telugu
    "Telugu_Murli_Slogan_Jpg" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_MP3" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Aaj_Ka_Purusharth" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Pdf" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Vardan_Jpg" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Viz_Pdf" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Ess_Jpg" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Htm" => "$rootdir/00. Htm/04. Telugu.html",
    "Murli_Chintan_Suraj_Bhai_T" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Murli_Ess_MP3" => "$rootdir/00. Htm/04. Telugu.html",
    "Telugu_Today_Calendar" => "$rootdir/00. Htm/04. Telugu.html",
    
    //Thai
    "Htm-Thai" => "$rootdir/00. Htm/43. Thai.html",
    "PDF-Thai" => "$rootdir/00. Htm/43. Thai.html",
    "Mp3-Thai" => "$rootdir/00. Htm/43. Thai.html",
    
  );
  
  $file_postfix_array = array(
    
    //Assame
    "Assame_Murli_Pdf" => "-Assame.pdf",
    "Assame_Murli_Htm" => "-Assame.htm",

    //Bengali
    "Bengali_Murli_Pdf" => "-Bengali.pdf",
    "Bengali_Murli_Htm" => "-Bengali.htm",
    
    //Chinese
    "MP3_Chinese" => "-Chinese.mp3",

    //Deutsch
    "PDF_Deutsch" => "-Deutsch.pdf",
    "Mp3_Deutsch" => "-Deutsch.mp3",
    "Htm_Deutsch" => "-Deutsch.htm",
    
    //English
    "Eng_Murli_Vardan_jpg" => "-Var.jpg",
    "Eng_Murli_Vardan2_jpg" => "-E-Var-Hand.jpg",
    "Eng_Murli_Ess_MP3" => "-Eng-Ess.mp3",
    "Eng_Murli_MP3_UK" => "-E-UK.mp3",
    "Eng_Murli_Htm" => "-E.htm",
    "Eng_Murli_Hindi_Words_Amola" => "-E-H.mp3",
    "Eng_Murli_Ess_MP3_UK" => "-E-Ess-Mob-UK.mp3",
    "Eng_Murli_Swaman_jpg" => "-Swa-Eng.jpg",
    "Eng_Murli_Pdf" => "-E.pdf",
    "Eng_Murli_MP3_2" => "-Eng-Full.mp3",
    "Eng_Murli_Ess_SMS" => "-Eng-SMS.htm",
    "Eng_Today_Calendar" => "-e.jpg",
    "Eng_Todays_Thought" => "-Thought.pdf",
    
    //French
    "PDF_French" => "-French.pdf",
    "Mp3_French" => "-French.mp3",
    "Htm_French" => "-French.htm",
    
    //Greek
    "PDF_Greek" => "-Greek.pdf",
    "Htm_Greek" => "-Greek.htm",
    
    //Gujarati
    "Gujarati_Murli_PDF" => "-Gujarati.pdf",
    "Gujarati_Murli_Htm" => "-Gujarati.htm",
    "Gujarati_Murli_Mp3" => "-Gujarati.mp3",
    
    //Hindi
    "Hindi_Murli_Mumbai" => ".mp3",
    "Murli_Preeti_Bahen" => "-PREETI.mp3",
    "Hindi_Murli_Saar_SMS" => "-Hin-SMS.htm",
    "Hindi_Murli_Saar_MP3" => "-H-ess.mp3",
    "Murli_Chart_Htm" => "-MurliChart.htm",
    "Hindi_Murli_OSB_MP4" => "-OSB.mp4",
    "Murli_Vardan_jpg" => "-Var.jpg",
    "Hindi_Murli_Saar_MP3_2" => "-Hindi-Ess.mp3",
    "Murli_Vardan_2jpg" => "-Var-Hand.jpg",
    "Hindi_Murli_MP3_2" => "-Hindi-Full.mp3",
    "Murli_Swaman_jpg" => "-Swa.jpg",
    "Hindi_Murli_MP3" => "-H.mp3",
    "Murli_Chintan_Suraj_Bhai_H" => "-Murli Chintan.mp3",
    "Hindi_Murli_Pdf" => "-h.pdf",
    "Hindi_Murli_OSB_MP3" => "-OSB.mp3",
    "Murli_Chart_pdf" => "-MurliChart.pdf",
    "Hindi_Murli_Htm" => "-H.htm",
    
    //Hungarian
    "PDF_Hungarian" => "-Hungarian.pdf",
    "Htm_Hungarian" => "-Hungarian.htm",
    
    //Italiano
    "PDF_Italiano" => "-Italian.pdf",
    "Mp3_Italiano" => "-Italian.mp3",
    "Htm_Italiano" => "-Italian.htm",
    
    //Kannada
    "Kannada_Murli_Pdf" => "-K.pdf",
    "Hindi_To_Kannada_Murli_Mp3" => "-Murli hindi to kannada.mp3",
    "Kannada_Murli_V2_MP3" => "-V2-K.mp3",
    "Kannada_Murli_Ess_MP3" => "-Kan-Ess.mp3",
    "Kannada_Murli_Htm" => "-K.htm",
    "Kannada_Murli_MP3" => "-K.mp3",
    "Kannada_AKP" => "-AKP-K.mp3",
    
    //Korian
    "Htm_Korian" => "-Korean.htm",
    "PDF_Korian" => "-Korean.pdf",
    
    //Malayalam
    "Malayalam_Murli_Pdf" => "-Mal.pdf",
    "Malayalam_Murli_MP3" => "-Mal.mp3",
    "Malayalam_Murli_Htm" => "-Mal.htm",
    
    //Marathi
    "MarathiMurli-Htm" => "-Marathi.htm",
    "MarathiMurli-PDF" => "-Marathi.pdf",
    "MarathiMurli-Mp3" => "-Marathi.mp3",

    //Nepali
    "Nepali_Murli_Htm" => "-Nep.htm",
    "Nepali_Murli_MP3" => "-Nep.mp3",
    "Nepali_Murli_Pdf" => "-Nep.pdf",
    
    //Odiya
    "Odiya_Murli_MP3" => "-Odia.mp3",
    "Odiya_Murli_Htm" => "-Odia.htm",
    "Odiya_Murli_Pdf" => "-Odia.pdf",
    
    //Polish
    "Htm-Polish" => "-Polish.htm",
    "PDF-Polish" => "-Polish.pdf",
    "MP3-Polish" => "-Polish.mp3",
    
    //Portuguese
    "Htm_Portuguese" => "-Port.htm",
    "PDF_Portuguese" => "-Port.pdf",
    "MP3_Portuguese" => "-Port.mp3",
    
    //Punjabi
    "Punjabi_Murli_MP3" => "-Pun.mp3",
    "Punjabi_Murli_Htm" => "-Pun.htm",
    "Punjabi_Murli_PDF" => "-Pun.pdf",
    
    //Separate Series in Hindi
    "Today_Moti" => "-Moti.jpg",
    "Today_Calendar_H" => "-h.jpg",
    "Avyakt_Palna" => "-AvyaktPaalna.mp3",
    "Todays_Commentary_MP3" => "-AvyaktVaaniCommentary.mp3",
    "Hindi_Aaj_Ka_Purushrath" => "-AKP.mp3",
    
    //Sindhi
    "Pdf-Sindhi" => "-Sindhi.pdf",
    
    //Sinhala
    "PDF_Sinhala" => "-Sinhala.pdf",
    "Htm_Sinhala" => "-Sinhala.htm",
    "Mp3_Sinhala" => "-Sinhala.mp3",
    
    //Spanish
    "Htm_Spanish" => "-Spanish.htm",
    "Mp3_Spanish" => "-Spanish.mp3",
    "PDF_Spanish" => "-Spanish.pdf",
    
    //Tamil
    "TamilMurli-Htm" => "-Tamil.htm",
    "TamilMurli-Pdf" => "-Tamil.pdf",
    "TamilMurli-MP3" => "-Tamil.mp3",
    "TamilMurli-Ess-MP3" => "-Tamil-Ess.mp3",
    "TamilMurli-Vizual-Pdf" => "-Tamil-Viz.pdf",
    "TamilThoughts" => ".htm",
    
    //Tamil Lanka
    "MP3_Tamil_Lanka" => "-TamilLanka.mp3",
    "PDF_Tamil_Lanka" => "-TamilLanka.pdf",
    "Htm_Tamil_Lanka" => "-TamilLanka.htm",
    
    //Telugu
    "Telugu_Murli_Slogan_Jpg" => "-Telugu-Slogan.jpg",
    "Telugu_Murli_MP3" => "-Telugu.mp3",
    "Telugu_Aaj_Ka_Purusharth" => "-AKP-T.mp3",
    "Telugu_Murli_Pdf" => "-Telugu.pdf",
    "Telugu_Murli_Vardan_Jpg" => "-Telugu-Vardan.jpg",
    "Telugu_Murli_Viz_Pdf" => "-Telugu-Viz.pdf",
    "Telugu_Murli_Ess_Jpg" => "-Tel-Ess.jpg",
    "Telugu_Murli_Htm" => "-Telugu.htm",
    "Murli_Chintan_Suraj_Bhai_T" => "-Murli-Chintan-(Telugu)-Suraj Bhaiji.mp3",
    "Telugu_Murli_Ess_MP3" => "-Telugu-Murli-Saar.mp3",
    "Telugu_Today_Calendar" => "-t.jpg",
    
    //Thai
    "Htm-Thai" => "-Thai.htm",
    "PDF-Thai" => "-Thai.pdf",
    "Mp3-Thai" => "-Thai.mp3",
    
  );
  
  $file_dir_array = array(
    
    //Assame
    "Assame_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/08. Assame/01. Assame Murli - Pdf",
    "Assame_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/08. Assame/02. Assam3 - Htm",
    
    //Bengali
    "Bengali_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/07. Bengali/02. Bengali Murli - Pdf",
    "Bengali_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/07. Bengali/01. Bengali Murli - Htm",
    
    //Chinese
    "MP3_Chinese" => "$weburl_babamurli/01. Daily Murli/34. Chinese/MP3-Chinese",

    //Deutsch
    "PDF_Deutsch" => "$weburl_babamurli/01. Daily Murli/31. Deutsch/PDF-Deutsch",
    "Mp3_Deutsch" => "$weburl_babamurli/01. Daily Murli/31. Deutsch/Mp3-Deutsch",
    "Htm_Deutsch" => "$weburl_babamurli/01. Daily Murli/31. Deutsch/Htm-Deutsch",
    
    //English
    "Eng_Murli_Vardan_jpg" => "$weburl_babamurli/01. Daily Murli/02. English/28. Eng Murli Vardan - jpg",
    "Eng_Murli_Vardan2_jpg" => "$weburl_babamurli/01. Daily Murli/02. English/29. Murli Vardan Hand - jpg",
    "Eng_Murli_Ess_MP3" => "$weburl_babamurli/01. Daily Murli/02. English/05. Eng Murli - Ess - MP3",
    "Eng_Murli_MP3_UK" => "$weburl_babamurli/01. Daily Murli/02. English/04. Eng Murli - MP3 - UK",
    "Eng_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/02. English/01. Eng Murli - Htm",
    "Eng_Murli_Hindi_Words_Amola" => "$weburl_babamurli/01. Daily Murli/02. English/11. Eng Murli Hindi Words - Amola",
    "Eng_Murli_Ess_MP3_UK" => "$weburl_babamurli/01. Daily Murli/02. English/06. Eng Murli - Ess - MP3 - UK",
    "Eng_Murli_Swaman_jpg" => "$weburl_babamurli/01. Daily Murli/02. English/27. Eng Murli Swaman - jpg",
    "Eng_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/02. English/02. Eng Murli - Pdf",
    "Eng_Murli_MP3_2" => "$weburl_babamurli/01. Daily Murli/02. English/04. Eng Murli - MP3 - 2",
    "Eng_Murli_Ess_SMS" => "$weburl_babamurli/01. Daily Murli/02. English/07. Eng Murli - Ess - SMS",
    "Eng_Today_Calendar" => "$weburl_babamurli/01. Daily Murli/02. English/25. Today Calendar",
    "Eng_Todays_Thought" => "$weburl_babamurli/01. Daily Murli/02. English/30. Todays Thought",
    
    //French
    "PDF_French" => "$weburl_babamurli/01. Daily Murli/36. French/PDF-French",
    "Mp3_French" => "$weburl_babamurli/01. Daily Murli/36. French/Mp3-French",
    "Htm_French" => "$weburl_babamurli/01. Daily Murli/36. French/Htm-French",
    
    //Greek
    "PDF_Greek" => "$weburl_babamurli/01. Daily Murli/37. Greek/PDF-Greek",
    "Htm_Greek" => "$weburl_babamurli/01. Daily Murli/37. Greek/Htm-Greek",
    
    //Gujarati
    "Gujarati_Murli_PDF" => "$weburl_babamurli/01. Daily Murli/09. Gujarati/03. Gujarati Murli - PDF",
    "Gujarati_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/09. Gujarati/01. Gujarati Murli - Htm",
    "Gujarati_Murli_Mp3" => "$weburl_babamurli/01. Daily Murli/09. Gujarati/04. Gujarati Murli - Mp3",
    
    //Hindi
    "Hindi_Murli_Mumbai" => "$weburl_babamurli/01. Daily Murli/01. Hindi/10. Hindi Murli - Mumbai",
    "Murli_Preeti_Bahen" => "$weburl_babamurli/01. Daily Murli/01. Hindi/12. Murli Preeti Bahen",
    "Hindi_Murli_Saar_SMS" => "$weburl_babamurli/01. Daily Murli/01. Hindi/07. Hindi Murli - Saar - SMS",
    "Hindi_Murli_Saar_MP3" => "$weburl_babamurli/01. Daily Murli/01. Hindi/05. Hindi Murli - Saar - MP3",
    "Murli_Chart_Htm" => "$weburl_babamurli/01. Daily Murli/01. Hindi/22. Murli Chart - Htm",
    "Hindi_Murli_OSB_MP4" => "$weburl_babamurli/01. Daily Murli/01. Hindi/08. Hindi Murli - OSB - MP4",
    "Murli_Vardan_jpg" => "$weburl_babamurli/01. Daily Murli/01. Hindi/21. Murli Vardan - jpg",
    "Hindi_Murli_Saar_MP3_2" => "$weburl_babamurli/01. Daily Murli/01. Hindi/06. Hindi Murli - Saar - MP3 - 2",
    "Murli_Vardan_2jpg" => "$weburl_babamurli/01. Daily Murli/01. Hindi/30. Murli Vardan-2- jpg",
    "Hindi_Murli_MP3_2" => "$weburl_babamurli/01. Daily Murli/01. Hindi/04. Hindi Murli - MP3 - 2",
    "Murli_Swaman_jpg" => "$weburl_babamurli/01. Daily Murli/01. Hindi/27. Murli Swaman - jpg",
    "Hindi_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/01. Hindi/03. Hindi Murli - MP3",
    "Murli_Chintan_Suraj_Bhai_H" => "$weburl_babamurli/01. Daily Murli/01. Hindi/13. Murli Chintan - Suraj Bhai",
    "Hindi_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/01. Hindi/02. Hindi Murli - Pdf",
    "Hindi_Murli_OSB_MP3" => "$weburl_babamurli/01. Daily Murli/01. Hindi/09. Hindi Murli - OSB - MP3",
    "Murli_Chart_pdf" => "$weburl_babamurli/01. Daily Murli/01. Hindi/23. Murli Chart - pdf",
    "Hindi_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm",
    
    //Hungarian
    "PDF_Hungarian" => "$weburl_babamurli/01. Daily Murli/38. Hungarian/PDF-Hungarian",
    "Htm_Hungarian" => "$weburl_babamurli/01. Daily Murli/38. Hungarian/Htm-Hungarian",
    
    //Italiano
    "PDF_Italiano" => "$weburl_babamurli/01. Daily Murli/33. Italian/PDF-Italiano",
    "Mp3_Italiano" => "$weburl_babamurli/01. Daily Murli/33. Italian/Mp3-Italiano",
    "Htm_Italiano" => "$weburl_babamurli/01. Daily Murli/33. Italian/Htm-Italiano",
    
    //Kannada
    "Kannada_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/05. Kannada/02. Kannada Murli - Pdf",
    "Hindi_To_Kannada_Murli_Mp3" => "$weburl_babamurli/01. Daily Murli/05. Kannada/07. Hindi To Kannada Murli - Mp3",
    "Kannada_Murli_V2_MP3" => "$weburl_babamurli/01. Daily Murli/05. Kannada/03. Kannada Murli - V2 - MP3",
    "Kannada_Murli_Ess_MP3" => "$weburl_babamurli/01. Daily Murli/05. Kannada/04. Kannada Murli - Ess - MP3",
    "Kannada_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/05. Kannada/01. Kannada Murli - Htm",
    "Kannada_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/05. Kannada/03. Kannada Murli - MP3",
    "Kannada_AKP" => "$weburl_babamurli/01. Daily Murli/05. Kannada/05. Kannada - AKP",
    
    //Korian
    "Htm_Korian" => "$weburl_babamurli/01. Daily Murli/39. Korean/Htm-Korian",
    "PDF_Korian" => "$weburl_babamurli/01. Daily Murli/39. Korean/PDF-Korian",
    
    //Malayalam
    "Malayalam_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/06. Malayalam/02. Malayalam Murli - Pdf",
    "Malayalam_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/06. Malayalam/03. Malayalam Murli - MP3",
    "Malayalam_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/06. Malayalam/01. Malayalam Murli - Htm",
    
    //Marathi
    "MarathiMurli-Htm" => "$weburl_babamurli/01. Daily Murli/12. Marathi/01. Marathi Murli - Htm",
    "MarathiMurli-PDF" => "$weburl_babamurli/01. Daily Murli/12. Marathi/03. Marathi Murli - PDF",
    "MarathiMurli-Mp3" => "$weburl_babamurli/01. Daily Murli/12. Marathi/04. Marathi Murli - Mp3",

    //Nepali
    "Nepali_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/30. Nepali/03. Nepali Murli - Htm",
    "Nepali_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/30. Nepali/01. Nepali Murli - MP3",
    "Nepali_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/30. Nepali/02. Nepali Murli - Pdf",
    
    //Odiya
    "Odiya_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/10. Odiya/03. Odiya Murli - MP3",
    "Odiya_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/10. Odiya/01. Odiya Murli - Htm",
    "Odiya_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/10. Odiya/02. Odiya Murli - Pdf",
    
    //Polish
    "Htm-Polish" => "$weburl_babamurli/01. Daily Murli/40. Polish/Htm-Polish",
    "PDF-Polish" => "$weburl_babamurli/01. Daily Murli/40. Polish/PDF-Polish",
    "MP3-Polish" => "$weburl_babamurli/01. Daily Murli/40. Polish/MP3-Polish",
    
    //Portuguese
    "Htm_Portuguese" => "$weburl_babamurli/01. Daily Murli/41. Portuguese/Htm-Portuguese",
    "PDF_Portuguese" => "$weburl_babamurli/01. Daily Murli/41. Portuguese/PDF-Portuguese",
    "MP3_Portuguese" => "$weburl_babamurli/01. Daily Murli/41. Portuguese/MP3-Portuguese",
    
    //Punjabi
    "Punjabi_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/11. Punjabi/04. Punjabi Murli - MP3",
    "Punjabi_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/11. Punjabi/01. Punjabi Murli - Htm",
    "Punjabi_Murli_PDF" => "$weburl_babamurli/01. Daily Murli/11. Punjabi/02. Punjabi Murli - PDF",
    
    //Separate Series
    "Today_Moti" => "$weburl_babamurli/01. Daily Murli/01. Hindi/26. Today Moti",
    "Today_Calendar_H" => "$weburl_babamurli/01. Daily Murli/01. Hindi/25. Today Calendar",
    "Todays_Commentary_MP3" => "$weburl_babamurli/01. Daily Murli/01. Hindi/44. Todays Commentary - MP3",
    "Avyakt_Palna" => "$weburl_babamurli/01. Daily Murli/01. Hindi/46. Avyakt Palna",
    "Hindi_Aaj_Ka_Purushrath" => "$weburl_babamurli/01. Daily Murli/01. Hindi/24. Hindi - Aaj Ka Purushrath",
    
    //Sindhi
    "Pdf-Sindhi" => "$weburl_babamurli/01. Daily Murli/42. Sindhi/Pdf-Sindhi",
    
    //Sinhala
    "PDF_Sinhala" => "$weburl_babamurli/01. Daily Murli/44. Sinhala/PDF-Sinhala",
    "Htm_Sinhala" => "$weburl_babamurli/01. Daily Murli/44. Sinhala/Htm-Sinhala",
    "Mp3_Sinhala" => "$weburl_babamurli/01. Daily Murli/44. Sinhala/Mp3-Sinhala",
    
    //Spanish
    "Htm_Spanish" => "$weburl_babamurli/01. Daily Murli/32. Spanish/Htm-Spanish",
    "Mp3_Spanish" => "$weburl_babamurli/01. Daily Murli/32. Spanish/Mp3-Spanish",
    "PDF_Spanish" => "$weburl_babamurli/01. Daily Murli/32. Spanish/PDF-Spanish",
    
    //Tamil
    "TamilMurli-Htm" => "$weburl_babamurli/01. Daily Murli/03. Tamil/01. Tamil Murli - Htm",
    "TamilMurli-Pdf" => "$weburl_babamurli/01. Daily Murli/03. Tamil/02. Tamil Murli - Pdf",
    "TamilMurli-MP3" => "$weburl_babamurli/01. Daily Murli/03. Tamil/03. Tamil Murli - MP3",
    "TamilMurli-Ess-MP3" => "$weburl_babamurli/01. Daily Murli/03. Tamil/04. Tamil Murli - Ess - MP3",
    "TamilMurli-Vizual-Pdf" => "$weburl_babamurli/01. Daily Murli/03. Tamil/05. Tamil Murli - Vizual - Pdf",
    "TamilThoughts" => "$weburl_babamurli/01. Daily Murli/03. Tamil/39. Tamil Thoughts",
    
    //Tamil Lanka
    "MP3_Tamil_Lanka" => "$weburl_babamurli/01. Daily Murli/35. Tamil-Lanka/MP3-Tamil-Lanka",
    "PDF_Tamil_Lanka" => "$weburl_babamurli/01. Daily Murli/35. Tamil-Lanka/PDF-Tamil-Lanka",
    "Htm_Tamil_Lanka" => "$weburl_babamurli/01. Daily Murli/35. Tamil-Lanka/Htm-Tamil-Lanka",
    
    //Telugu
    "Telugu_Murli_Slogan_Jpg" => "$weburl_babamurli/01. Daily Murli/04. Telugu/09. Telugu - Murli - Slogan - Jpg",
    "Telugu_Murli_MP3" => "$weburl_babamurli/01. Daily Murli/04. Telugu/03. Telugu - Murli - MP3",
    "Telugu_Aaj_Ka_Purusharth" => "$weburl_babamurli/01. Daily Murli/04. Telugu/24. Telugu - Aaj Ka Purusharth",
    "Telugu_Murli_Pdf" => "$weburl_babamurli/01. Daily Murli/04. Telugu/02. Telugu - Murli - Pdf",
    "Telugu_Murli_Vardan_Jpg" => "$weburl_babamurli/01. Daily Murli/04. Telugu/08. Telugu - Murli - Vardan - Jpg",
    "Telugu_Murli_Viz_Pdf" => "$weburl_babamurli/01. Daily Murli/04. Telugu/05. Telugu - Murli - Viz - Pdf",
    "Telugu_Murli_Ess_Jpg" => "$weburl_babamurli/01. Daily Murli/04. Telugu/07. Telugu - Murli - Ess - Jpg",
    "Telugu_Murli_Htm" => "$weburl_babamurli/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm",
    "Murli_Chintan_Suraj_Bhai_T" => "$weburl_babamurli/01. Daily Murli/04. Telugu/10. Murli Chintan - Suraj Bhai",
    "Telugu_Murli_Ess_MP3" => "$weburl_babamurli/01. Daily Murli/04. Telugu/04. Telugu - Murli - Ess - MP3",
    "Telugu_Today_Calendar" => "$weburl_babamurli/01. Daily Murli/04. Telugu/25. Today Calendar",
    
    //Thai
    "Htm-Thai" => "$weburl_babamurli/01. Daily Murli/43. Thai/Htm-Thai",
    "PDF-Thai" => "$weburl_babamurli/01. Daily Murli/43. Thai/PDF-Thai",
    "Mp3-Thai" => "$weburl_babamurli/01. Daily Murli/43. Thai/Mp3-Thai",
    
  );
  
  $file_col_array = array(
    
    //Assame
    "Assame_Murli_Htm" => 1,
    "Assame_Murli_Pdf" => 2,
    
    //Bengali
    "Bengali_Murli_Htm" => 1,
    "Bengali_Murli_Pdf" => 2,
    
    //Chinese
    "MP3_Chinese" => 1,

    //Deutsch
    "Htm_Deutsch" => 1,
    "PDF_Deutsch" => 2,
    "Mp3_Deutsch" => 3,
    
    //English
    "Eng_Murli_Htm" => 1,
    "Eng_Murli_Pdf" => 2,
    "Eng_Murli_Ess_SMS" => 3,
    "Eng_Murli_MP3_UK" => 4,
    "Eng_Murli_Ess_MP3_UK" => 5,
    "Eng_Murli_MP3_2" => 6,
    "Eng_Murli_Ess_MP3" => 7,
    "Eng_Murli_Hindi_Words_Amola" => 8,
    "Eng_Murli_Vardan_jpg" => 9, 
    "Eng_Murli_Vardan2_jpg" => 10, 
    "Eng_Murli_Swaman_jpg" => 11,
    "Eng_Todays_Thought" => 12,
    "Eng_Today_Calendar" => 13,
    
    //French
    "Htm_French" => 1,
    "PDF_French" => 2,
    "Mp3_French" => 3,
    
    //Greek
    "Htm_Greek" => 1,
    "PDF_Greek" => 2,
    
    //Gujarati
    "Gujarati_Murli_Htm" => 1,
    "Gujarati_Murli_PDF" => 2,
    "Gujarati_Murli_Mp3" => 3,

    //Hindi
    "Hindi_Murli_Htm" => 1,
    "Hindi_Murli_Pdf" => 2,
    "Hindi_Murli_MP3" => 3,
    "Hindi_Murli_MP3_2" => 4,
    "Hindi_Murli_Saar_MP3" => 5,
    "Hindi_Murli_Saar_MP3_2" => 6,
    "Hindi_Murli_Saar_SMS" => 7,
    "Hindi_Murli_OSB_MP3" => 8,
    "Hindi_Murli_OSB_MP4" => 9, 
    "Hindi_Murli_Mumbai" => 10, 
    "Murli_Preeti_Bahen" => 11,
    "Murli_Chintan_Suraj_Bhai_H" => 12,
    "Murli_Swaman_jpg" => 13,
    "Murli_Vardan_jpg" => 14,
    "Murli_Vardan_2jpg" => 15,
    "Murli_Chart_Htm" => 16,
    "Murli_Chart_pdf" => 17,
    
    //Hungarian
    "Htm_Hungarian" => 1,
    "PDF_Hungarian" => 2,
    
    //Italiano
    "Htm_Italiano" => 1,
    "PDF_Italiano" => 2,
    "Mp3_Italiano" => 3,
    
    //Kannada
    "Kannada_Murli_Htm" => 1,
    "Kannada_Murli_Pdf" => 2,
    "Kannada_Murli_MP3" => 3,
    "Kannada_Murli_V2_MP3" => 4,
    "Kannada_Murli_Ess_MP3" => 5,
    "Hindi_To_Kannada_Murli_Mp3" => 6,
    "Kannada_AKP" => 7,
    
    //Korian
    "Htm_Korian" => 1,
    "PDF_Korian" => 2,
    
    //Malayalam
    "Malayalam_Murli_Htm" => 1,
    "Malayalam_Murli_Pdf" => 2,
    "Malayalam_Murli_MP3" => 3,
    
    //Marathi
    "MarathiMurli-Htm" => 1,
    "MarathiMurli-PDF" => 2,
    "MarathiMurli-Mp3" => 3,
    
    //Nepali
    "Nepali_Murli_Htm" => 1,
    "Nepali_Murli_Pdf" => 2,
    "Nepali_Murli_MP3" => 3,
    
    //Odiya
    "Odiya_Murli_Htm" => 1,
    "Odiya_Murli_Pdf" => 2,
    "Odiya_Murli_MP3" => 3,
    
    //Polish
    "Htm-Polish" => 1,
    "PDF-Polish" => 2,
    "MP3-Polish" => 3,
    
    //Portuguese
    "Htm_Portuguese" => 1,
    "PDF_Portuguese" => 2,
    "MP3_Portuguese" => 3,
    
    //Punjabi
    "Punjabi_Murli_Htm" => 1,
    "Punjabi_Murli_PDF" => 2,
    "Punjabi_Murli_MP3" => 3,
    
    //Separate Series in Hindi
    "Today_Moti" => 1,
    "Today_Calendar_H" => 2,
    "Todays_Commentary_MP3" => 3,
    "Avyakt_Palna" => 4,
    "Hindi_Aaj_Ka_Purushrath" => 5,
    
    //Sindhi
    "Pdf-Sindhi" => 1,
    
    //Sinhala
    "Htm_Sinhala" => 1,
    "PDF_Sinhala" => 2,
    "Mp3_Sinhala" => 3,
    
    //Spanish
    "Htm_Spanish" => 1,
    "PDF_Spanish" => 2,
    "Mp3_Spanish" => 3,
    
    //Tamil
    "TamilMurli-Htm" => 1,
    "TamilMurli-Pdf" => 2,
    "TamilMurli-MP3" => 3,
    "TamilMurli-Ess-MP3" => 4,
    "TamilMurli-Vizual-Pdf" => 5,
    "TamilThoughts" => 6,
    
    //Tamil Lanka
    "Htm_Tamil_Lanka" => 1,
    "MP3_Tamil_Lanka" => 3,
    "PDF_Tamil_Lanka" => 2,
    
    //Telugu
    "Telugu_Murli_Htm" => 1,
    "Telugu_Murli_Pdf" => 2,
    "Telugu_Murli_MP3" => 3,
    "Telugu_Murli_Ess_MP3" => 4,
    "Murli_Chintan_Suraj_Bhai_T" => 5,
    "Telugu_Murli_Viz_Pdf" => 6,
    "Telugu_Murli_Ess_Jpg" => 7,
    "Telugu_Murli_Vardan_Jpg" => 8,
    "Telugu_Murli_Slogan_Jpg" => 9,
    "Telugu_Aaj_Ka_Purusharth" => 10,
    "Telugu_Today_Calendar" => 11,
    
    //Thai
    "Htm-Thai" => 1,
    "PDF-Thai" => 2,
    "Mp3-Thai" => 3,
    
  );


?>