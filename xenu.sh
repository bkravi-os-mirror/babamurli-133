#!/bin/bash

## curl -Is "http://www.babamurli.com/00.%20Htm/../01.%20Daily%20Murli/37.%20Greek/PDF-Greek/29.02.20-Greek.pdf" | head -n 1
##   HTTP/1.1 404 Not Found
##   HTTP/1.1 200 OK
##   HTTP/1.1 301 Moved Permanently

set -e

in="${1:-xenu_links.txt}"   #input file here
out="/var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete/curl.out"   #output file with json data
outone="/var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete/curl_broken_only.out"   #another output file with links only

[ ! -f "$in" ] && { exit 1; }   #if input file doesn't exist, exit 1

echo "#STARTED" > "$out"   #add start flag
echo "#STARTED" > "$outone"   #add start flag

starttime=`date`
result="{"
while IFS= read -r file_row
do
  ## avoid commented lines ##
  [[ $file_row = \#* ]] && continue
  tmp=`curl -Is "$file_row" | head -n 1 | sed 's/[\n\r]//g'`
  if [[ ( "$tmp" != *"HTTP/1.1 200 OK"* ) && ( "$tmp" != *"HTTP/1.1 301"* ) ]]; then
    result="$result\"$file_row\":\"$tmp\","
    echo "$file_row" >> "$outone"
  fi
done < "${in}"
endtime=`date`
result="$result\"start\":\"$starttime\", \"end\":\"$endtime\"}"

echo "$result" >> "$out"   #append final result

echo "#FINISHED" >> "$out"   #append end flag
echo "#FINISHED" >> "$outone"   #append end flag
