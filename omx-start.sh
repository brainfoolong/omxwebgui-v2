#!/bin/bash
## script to start omxplayer

# check if player is already running
ps cax | grep "omxplayer" > /dev/null
if [ $? -eq 0 ]; then
    sudo killall omxplayer && sudo killall omxplayer.bin
fi
# delete mkfifo file if exist
if [ -e $1 ]
then
    rm $1
fi
mkfifo $1

# if subtitle folder isset
params=""
if [ "$6" != "-" ]; then
    basename=$(basename $2)
    subtitle_path="$6/${basename%.*}.srt"
    if [ -e "$subtitle_path" ]; then
      params="$params --subtitles $subtitle_path "
    fi
fi
# check if display parameter isset
if [ "$7" != "-" ]; then
  params="$params --display $7"
fi

omxplayer -b "$2" -o $4 --vol $5 $params < $1 &
echo -n "." > $1 &

# fix for double play speed at start
if [ "$3" = "1" ]; then
    echo -n "1" > $1 &
fi
