<?php
   `gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 -sOutputFile=hin_1.pdf -c .setpdfwrite -f hin.pdf`;
   `gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=ServeSimple448 -sOutputFile=eng_1.pdf -c .setpdfwrite -f eng.pdf`;

?>