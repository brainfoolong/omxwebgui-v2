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

# check if subtitle file exists in subtitle folder
basename=$(basename $2)
subtitle_path="$6/${basename%.*}.srt"
if [ -e "$subtitle_path" ]; then
  subtitle_argument="--subtitles $subtitle_path"
  else
  subtitle_argument=""
fi

omxplayer -b "$2" -o $4 --vol $5 $subtitle_argument < $1 &
echo -n "." > $1 &

# fix for double play speed at start
if [ "$3" = "1" ]; then
    echo -n "1" > $1 &
fi
