#!/bin/bash

#set -x

OMXPLAYER_DBUS_ADDR="/tmp/omxplayerdbus.root"
OMXPLAYER_DBUS_PID="/tmp/omxplayerdbus.root.pid"
export DBUS_SESSION_BUS_ADDRESS=`cat $OMXPLAYER_DBUS_ADDR`
export DBUS_SESSION_BUS_PID=`cat $OMXPLAYER_DBUS_PID`

[ -z "$DBUS_SESSION_BUS_ADDRESS" ] && { echo "Must have DBUS_SESSION_BUS_ADDRESS" >&2; exit 1; }

case $1 in
getduration)
  duration=`dbus-send --print-reply=literal --session --reply-timeout=500 --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.freedesktop.DBus.Properties.Duration`
  [ $? -ne 0 ] && exit 1
  duration="$(awk '{print $2}' <<< "$duration")"
  echo $duration
  ;;

getposition)
  position=`dbus-send --print-reply=literal --session --reply-timeout=500 --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.freedesktop.DBus.Properties.Position`
  [ $? -ne 0 ] && exit 1
  position="$(awk '{print $2}' <<< "$position")"
  echo $position
  ;;

getplaystatus)
  playstatus=`dbus-send --print-reply=literal --session --reply-timeout=500 --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.freedesktop.DBus.Properties.PlaybackStatus`
  [ $? -ne 0 ] && exit 1
  playstatus="$(sed 's/^ *//;s/ *$//;' <<< "$playstatus")"
  echo $playstatus
  ;;

getvolume)
  volume=`dbus-send --print-reply=double --session --reply-timeout=500 --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.freedesktop.DBus.Properties.Volume`
  [ $? -ne 0 ] && exit 1
  volume="$(awk '{print $2}' <<< "$volume")"
  echo $volume
  ;;

setposition)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.SetPosition objpath:/not/used int64:$2 >/dev/null
  ;;

seek)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Seek int64:$2 >/dev/null
  ;;

toggleplay)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:16 >/dev/null
  ;;

stop)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:15 >/dev/null
  ;;

quit)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Quit
  ;;

setvolume)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.freedesktop.DBus.Properties.Volume double:$2 >/dev/null
  ;;

volumeup)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:18 >/dev/null
  ;;

volumedown)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:17 >/dev/null
  ;;

togglesubtitles)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:12 >/dev/null
  ;;

hidesubtitles)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:30 >/dev/null
  ;;

showsubtitles)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:31 >/dev/null
  ;;

hidevideo)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:28 >/dev/null
  ;;

unhidevideo)
  dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.Action int32:29 >/dev/null
  ;;

setalpha)
	dbus-send --print-reply=literal --session --dest=org.mpris.MediaPlayer2.omxplayer /org/mpris/MediaPlayer2 org.mpris.MediaPlayer2.Player.SetAlpha objpath:/not/used int64:$2 >/dev/null
	;;

*)
  echo "error"
  exit 1
  ;;

esac
