#!/bin/bash

## Usage
#    nohup /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete/ml.sh -d /var/www/vhosts/babamurli.com/BABAMURLI/000-Ravi-DontDelete/mfm > /dev/null 2>&1 &

set -e

helpFunction() {
  echo ""
  echo "Usage: $0 -d dir"
  echo -e "\t-d Directory to read files for e-mailing"
  exit 1 # Exit script after printing help
}


while getopts "d:" opt
do
  case "$opt" in
    d ) dir="$OPTARG" ;;
    ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
  esac
done

if [ -z "$dir" ]
then
  echo "Parameters are empty or incorrect";
  helpFunction
fi

`php mmrl-shell.php "$dir"`
#res=`php mmrl-p1.php "$dir"`
#echo "$res"