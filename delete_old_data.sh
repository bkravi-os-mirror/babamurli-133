#!/bin/bash

########################################################################################################################################
########################################################################################################################################
## ========================================================================================================================
## 192.157.241.133 / root / Xoa3n8jt
##
## Step1) Change Date in this file - What date do u want to many??
## Step2) Log in to putty using above credentials
## Step3) Execute below 2 commands 
## ========================================================================================================================
# cd /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete
# ./delete_old_data.sh
########################################################################################################################################
# NOTE below useful command to generate the rm commands:
#	cd "01. Hindi"
#	ls -d "$PWD"/*|awk -F ~ '{print "rm -f \"" $1 "/\"*.03.20*" }'
########################################################################################################################################
########################################################################################################################################
# NOTE: Also I have created below php to generate these rm comamnds
#   [So just execute the above php and copy its output below]
#	/BABAMURLI/000-Ravi-DontDelete/genremsh.php
#	  Just update the date filter in above php and run in browser. It will print all rm commands
#   It takes all current subfolders from all the folders
########################################################################################################################################

#unalias cp
#unset -f cp

#### 01. Hindi
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/01. Hindi Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/02. Hindi Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/03. Hindi Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/04. Hindi Murli - MP3 - 2/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/05. Hindi Murli - Saar - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/06. Hindi Murli - Saar - MP3 - 2/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/07. Hindi Murli - Saar - SMS/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/08. Hindi Murli - OSB - MP4/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/09. Hindi Murli - OSB - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/10. Hindi Murli - Mumbai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/12. Murli Preeti Bahen/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/13. Murli Chintan - Suraj Bhai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/21. Murli Vardan - jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/22. Murli Chart - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/23. Murli Chart - pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/24. Hindi - Aaj Ka Purushrath/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/25. Today Calendar/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/26. Today Moti/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/27. Murli Swaman - jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/30. Murli Vardan-2- jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/36. Mobile Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/37. Mobile SMS/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/39. Mobile Murli Chart Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/44. Todays Commentary - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/45. Dharmraj Ki Adalat/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/46. Avyakt Palna/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/01. Hindi/Aaj Ki Murli Saar Kavita By BK Satish Bhaiji_Madhuvan/"*.03.20*

#### 02. English
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/01. Eng Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/02. Eng Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/04. Eng Murli - MP3 - 2/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/04. Eng Murli - MP3 - UK/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/05. Eng Murli - Ess - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/06. Eng Murli - Ess - MP3 - UK/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/07. Eng Murli - Ess - SMS/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/11. Eng Murli Hindi Words - Amola/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/25. Today Calendar/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/27. Eng Murli Swaman - jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/28. Eng Murli Vardan - jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/29. Murli Vardan Hand - jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/30. Todays Thought/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/36. Mobile Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/02. English/37. Mobile SMS/"*.03.20*

#### 03. Tamil
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/01. Tamil Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/02. Tamil Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/03. Tamil Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/04. Tamil Murli - Ess - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/05. Tamil Murli - Vizual - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/36. Mobile Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/37. Mobile SMS/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/38. Suraj Bhai Class/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/39. Tamil Thoughts/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/40. Mamma Classes/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/41. Sachin Bhai Classes/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/03. Tamil/45. Suraj Bhai class 1/"*.03.20*

#### 04. Telugu
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/01. Telugu - Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/02. Telugu - Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/03. Telugu - Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/04. Telugu - Murli - Ess - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/05. Telugu - Murli - Viz - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/07. Telugu - Murli - Ess - Jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/08. Telugu - Murli - Vardan - Jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/09. Telugu - Murli - Slogan - Jpg/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/10. Murli Chintan - Suraj Bhai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/24. Telugu - Aaj Ka Purusharth/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/25. Today Calendar/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/04. Telugu/36. Mobile Htm/"*.03.20*

#### 05. Kannada
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/01. Kannada Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/02. Kannada Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/03. Kannada Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/03. Kannada Murli - V2 - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/04. Kannada Murli - Ess - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/05. Kannada - AKP/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/06. Kannada Murli - Mobile -  Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/05. Kannada/07. Hindi To Kannada Murli - Mp3/"*.03.20*

#### 06. Malayalam
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/06. Malayalam/01. Malayalam Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/06. Malayalam/02. Malayalam Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/06. Malayalam/03. Malayalam Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/06. Malayalam/04. Malayalam Murli -Mobile/"*.03.20*

#### 07. Bengali
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/07. Bengali/01. Bengali Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/07. Bengali/02. Bengali Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/07. Bengali/05. Bengali Murli - Mobile Htm/"*.03.20*

#### 08. Assame
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/08. Assame/01. Assame Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/08. Assame/02. Assam3 - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/08. Assame/03. Assam3 - Mobile Htm/"*.03.20*

#### 09. Gujarati
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/09. Gujarati/01. Gujarati Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/09. Gujarati/02. Gujarati Mobile - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/09. Gujarati/03. Gujarati Murli - PDF/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/09. Gujarati/04. Gujarati Murli - Mp3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/09. Gujarati/05. Gujarati - Avyakt Murli/"*.03.20*

#### 10. Odiya
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/10. Odiya/01. Odiya Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/10. Odiya/02. Odiya Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/10. Odiya/03. Odiya Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/10. Odiya/04. Odiya Murli - Mobile Htm/"*.03.20*

#### 11. Punjabi
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/11. Punjabi/01. Punjabi Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/11. Punjabi/02. Punjabi Murli - PDF/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/11. Punjabi/03. Punjabi Murli - Mobile Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/11. Punjabi/04. Punjabi Murli - MP3/"*.03.20*

#### 12. Marathi
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/12. Marathi/01. Marathi Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/12. Marathi/02. Marathi Mobile - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/12. Marathi/03. Marathi Murli - PDF/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/12. Marathi/04. Marathi Murli - Mp3/"*.03.20*

#### 30. Nepali
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/30. Nepali/01. Nepali Murli - MP3/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/30. Nepali/02. Nepali Murli - Pdf/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/30. Nepali/03. Nepali Murli - Htm/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/30. Nepali/03. Nepali Murli - Mobile Htm/"*.03.20*

#### 31. Deutsch
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/31. Deutsch/Htm-Deutsch/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/31. Deutsch/Mobile Htm-Deutsch/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/31. Deutsch/Mp3-Deutsch/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/31. Deutsch/PDF-Deutsch/"*.03.20*

#### 32. Spanish
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/32. Spanish/Htm-Spanish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/32. Spanish/Mobile Htm-Spanish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/32. Spanish/Mp3-Spanish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/32. Spanish/PDF-Spanish/"*.03.20*

#### 33. Italian
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/33. Italian/Htm-Italiano/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/33. Italian/Mobile Htm-Italiano/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/33. Italian/Mp3-Italiano/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/33. Italian/PDF-Italiano/"*.03.20*

#### 34. Chinese
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/34. Chinese/MP3-Chinese/"*.03.20*

#### 35. Tamil-Lanka
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/35. Tamil-Lanka/Htm-Tamil-Lanka/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/35. Tamil-Lanka/MP3-Tamil-Lanka/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/35. Tamil-Lanka/Mobile Htm-Tamil-Lanka/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/35. Tamil-Lanka/PDF-Tamil-Lanka/"*.03.20*

#### 36. French
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/36. French/Htm-French/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/36. French/Mobile Htm-French/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/36. French/Mp3-French/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/36. French/PDF-French/"*.03.20*

#### 37. Greek
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/37. Greek/Htm-Greek/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/37. Greek/Mobile Htm-Greek/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/37. Greek/PDF-Greek/"*.03.20*

#### 38. Hungarian
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/38. Hungarian/Htm-Hungarian/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/38. Hungarian/Mobile Htm-Hungarian/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/38. Hungarian/PDF-Hungarian/"*.03.20*

#### 39. Korean
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/39. Korean/Htm-Korian/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/39. Korean/Mobile Htm-Korian/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/39. Korean/PDF-Korian/"*.03.20*

#### 40. Polish
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/40. Polish/Htm-Polish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/40. Polish/MP3-Polish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/40. Polish/Mobile Htm-Polish/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/40. Polish/PDF-Polish/"*.03.20*

#### 41. Portuguese
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/41. Portuguese/Htm-Portuguese/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/41. Portuguese/MP3-Portuguese/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/41. Portuguese/Mobile Htm-Portuguese/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/41. Portuguese/PDF-Portuguese/"*.03.20*

#### 42. Sindhi
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/42. Sindhi/Pdf-Sindhi/"*.03.20*

#### 43. Thai
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/43. Thai/Htm-Thai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/43. Thai/Mobile Htm-Thai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/43. Thai/Mp3-Thai/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/43. Thai/PDF-Thai/"*.03.20*

#### 44. Sinhala
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/44. Sinhala/Htm-Sinhala/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/44. Sinhala/Mobile Htm-Sinhala/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/44. Sinhala/Mp3-Sinhala/"*.03.20*
rm -f "/var/www/vhosts/babamurli.com/BABAMURLI/01. Daily Murli/44. Sinhala/PDF-Sinhala/"*.03.20*

echo "Done!!!"

