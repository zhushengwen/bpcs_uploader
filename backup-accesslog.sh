#!/bin/bash

rm -f /usr/local/nginx/logs/*\"*
rm -f /usr/local/nginx/logs/*\**
rm -f /usr/local/nginx/logs/*\'*
rm -f /usr/local/nginx/logs/*=*
rm -f /usr/local/nginx/logs/*\$*

DATE=`date "+%Y-%m-%d" -d @$(($(date +%s)-86400))`

for i in $(seq -f "%02g" 1 7)
do
  DATE=`date "+%Y-%m-%d" -d @$(($(date +%s)-86400*$i))`
  echo $DATE To Start!
  for f in $(ls /usr/local/nginx/logs/hack/*${DATE}_sec.log); do
    echo "File -> $f"
    DEST="accesslog/$DATE/hack/`basename \"$f\"`"
    echo DEST:$DEST
    INFO=$(uploader info $DEST | grep error)
    echo INFO:$INFO
    if [ -n "$INFO" ];then
    echo uploader upload $f $DEST
    uploader upload $f $DEST
    else
    echo exists
    fi
  done
done

for i in $(seq -f "%02g" 1 7)
do
  DATE=`date "+%Y-%m-%d" -d @$(($(date +%s)-86400*$i))`
  echo $DATE To Start!
  for f in $(ls /usr/local/nginx/logs/*$DATE-access.log); do
    echo "File -> $f"
    DEST="accesslog/$DATE/`basename \"$f\"`"
    echo DEST:$DEST
    INFO=$(uploader info $DEST | grep error)
    echo INFO:$INFO
    if [ -n "$INFO" ];then
    echo uploader upload $f $DEST
    uploader upload $f $DEST
    else
    echo exists
    fi
  done
done


cd /usr/local/nginx/logs && find . -mtime +7 -name "*.log" -exec rm -f {} \;
