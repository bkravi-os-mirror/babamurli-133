#!/bin/bash

## Usage
#    nohup /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete/ytd.sh -v fq6utUvQijI -d /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete -o temp -f 18 -t 2 > /dev/null 2>&1 &
## youtube-dl -x -k --audio-format mp3 --audio-quality 32K -f 18 https://www.youtube.com/watch?v=fq6utUvQijI --output temp.mp4 --force-ipv4 --newline
##   1) will download MP4 of formatcode -f(i.e. 18 here) and store at --output location(i.e. ./temp.mp4 here) then
##   2) convert temp.mp4 to temp.mp3 with bit rate 32K as given above
##   NOTE: if you remove above -k flag, it will delete the --output file(i.e. temp.mp4 here) and final only temp.mp3 will be remaining!


set -e

helpFunction() {
  echo ""
  echo "Usage: $0 -v videoID -f formatCode -o outputFileNameOnly -d outputDir -t whatType"
  echo -e "\t-v Video ID e.g. fq6utUvQijI"
  echo -e "\t-f Video Format Code e.g. 140, 18"
  echo -e "\t-o Output Filename without ext!"
  echo -e "\t-d Output directory"
  echo -e "\t-t [1-MP3, 2-MP4, 3-BOTH]"
  exit 1 # Exit script after printing help
}

while getopts "v:f:o:d:t:" opt
do
  case "$opt" in
    v ) videoid="$OPTARG" ;;
    f ) formatcode="$OPTARG" ;;
    o ) outputfile="$OPTARG" ;;
    d ) outputdir="$OPTARG" ;;
    t ) whattype="$OPTARG" ;;
    ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
  esac
done

if [[ "$whattype" =~ ^[0-9]+$ ]] && [ "$whattype" -ge 1 ] && [ "$whattype" -le 3 ]   #$whattype must be 1, 2 or 3
then
  # Print helpFunction in case parameters are empty
  if [ -z "$videoid" ] || [ -z "$formatcode" ] || [ -z "$outputfile" ] || [ -z "$outputdir" ]
  then
    echo "Some or all of the parameters are empty or incorrect";
    helpFunction
  fi
else
  echo "-t option must be 1, 2 or 3 i.e. [1-MP3, 2-MP4, 3-BOTH]";
  helpFunction
fi

# Below begining of the script ######################################################

log="ytd.log"

starttime=`date`
echo "#STARTED $starttime" > "$log"

if [ -e "$outputdir/$outputfile.mp4" ]
then
  echo "File $outputdir/$outputfile.mp4 already exists! Deleting it by running: rm -rf $outputdir/$outputfile.mp4" >> "$log"
  `rm -rf "$outputdir"/"$outputfile.mp4"`
fi

echo "Running Youtube command: youtube-dl -x -k --audio-format mp3 --audio-quality 32K -f $formatcode https://www.youtube.com/watch?v=$videoid --output $outputdir/$outputfile.mp4 --force-ipv4 --newline" >> "$log"
`/usr/local/bin/youtube-dl -x -k --audio-format mp3 --audio-quality 32K -f "$formatcode" https://www.youtube.com/watch?v="$videoid" --output "$outputdir"/"$outputfile.mp4" --force-ipv4 --newline >> "$log"`

if [ $whattype -eq 1 ]   #[1-MP3, 2-MP4, 3-BOTH]
then
  echo "Deleting $outputdir/$outputfile.mp4 by running: rm -rf $outputdir/$outputfile.mp4" >> "$log"
  `rm -rf "$outputdir"/"$outputfile.mp4"`
elif [ $whattype -eq 2 ]
then
  echo "Deleting $outputdir/$outputfile.mp3 by running: rm -rf $outputdir/$outputfile.mp3" >> "$log"
  `rm -rf "$outputdir"/"$outputfile.mp3"`
elif [ $whattype -eq 3 ]
then
  echo "Both files $outputfile.mp4 & $outputfile.mp3 preserved" >> "$log"
fi

endtime=`date`
printf "#FINISHED $endtime" >> "$log"   #this will avoid printing \n at end
