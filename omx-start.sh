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
omxplayer -b "$2" -o $4 --vol $5 < $1 &
echo -n "." > $1 &

# fix for double play speed at start
if [ "$3" = "1" ]; then
    echo -n "1" > $1 &
fi