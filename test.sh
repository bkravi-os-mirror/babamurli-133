#!/bin/bash
set -e

dirname=/var/www/abc.mp4
result=`echo "$dirname" | awk -F "/" '{print $NF}'`
echo "$result"
arrIN=(${result//./ })
fn=${arrIN[0]}
echo "$fn"
