#!/usr/bin/env bash

echo "Welcome to the guided OMXWEBGUI installation script"
echo "The script requires 'sudo' at one point, to install php5-cli"
echo "Anyway, you can install all manually by following the steps on"
echo "https://github.com/brainfoolong/omxwebgui-v2"
echo "It will install the latest stable release for you"
echo "If you want the latest master branch, you have do that manually"
echo "Are you want to continue? [y/n]"

read p

if [ "$p" != "y" ] && [ "$p" != "Y" ] ; then
    echo "Aborted"
    exit 1
fi

echo "OMXWEBGUI runs with php5 command line interface"
echo "No separate webserver required and recommended"
echo "Maybe you will some errors because of non existing packages, ignore it"
echo "Are you want to install php5-cli now (sudo)? [y/n]"

read p

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    sudo apt-get install -qq -y php-cli
    sudo apt-get install -qq -y php5-cli
fi

echo "OMXWEBGUI runs as a php-cli server which requires a port to be defined"
echo "Default port is 4321"
echo "Leave it empty if you stick with the default or change it to a number you want"

read p

port=4321
if [ "$p" != "" ] ; then
    port=$p
fi

echo "Downloading and unpacking OMXWEBGUI to disk"
echo "Choose directory path, default: ~/omxwebgui"
echo "Leave it empty if you stick with the default"
echo "Given directory should be empty or non existing"

read p

path="C:\www\private\omxwebgui"
if [ "$p" != "" ] ; then
    path=$p
fi

if [ ! -d "$path" ] ; then
    mkdir $path
    exit
fi

mkdir "$path/tmp"

wget https://raw.githubusercontent.com/brainfoolong/omxwebgui-v2/master/updater.php
php -f updater.php

echo "Enable autostart for the OMXWEBGUI server"
echo "This will add a new entry to crontab"
echo "Are you want to enable autostart now? [y/n]"

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    (crontab -l 2>/dev/null; echo "@reboot php -S 0.0.0.0:$port -t $path > /dev/null 2>&1 &") | crontab -
fi

echo "Start OMXWEBGUI server now? [y/n]"

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    `php -S 0.0.0.0:$port -t $path > /dev/null 2>&1 &`
fi


echo "All files have been installed!"
echo "Open http://iptoyourpi:$port in your browser"
echo "Have fun with this application. You are awesome."
