#!/usr/bin/env bash

echo ""
echo "==========================================="
echo "Welcome to the guided OMXWEBGUI installation script"
echo "The script requires 'sudo' at one point, to install php5-cli"
echo "Anyway, you can install all manually by following the steps on"
echo "https://github.com/brainfoolong/omxwebgui-v2"
echo "It will install the latest stable release for you"
echo "If you want the latest master branch, you have do that manually"
echo "Are you want to continue? [y/n]"

read p
echo ""

if [ "$p" != "y" ] && [ "$p" != "Y" ] ; then
    echo "Aborted"
    exit 1
fi


echo ""
echo "==========================================="
echo "OMXWEBGUI runs with php command line interface"
echo "No separate webserver required and recommended"
echo "Maybe you will some errors because of non existing packages, ignore it"
echo "Are you want to install php-cli now (sudo)? [y/n]"

read p
echo ""

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    sudo apt-get install -qq -y php-cli
    sudo apt-get install -qq -y php-mbstring
    sudo apt-get install -qq -y php5-cli
    sudo apt-get install -qq -y php5-mbstring
fi


echo ""
echo "==========================================="
echo "OMXWEBGUI runs as a php-cli server which requires a port to be defined"
echo "Default port is 4321"
echo "Leave it empty if you stick with the default or change it to a number you want"

read p
echo ""

port=4321
if [ "$p" != "" ] ; then
    port=$p
fi

echo ""
echo "==========================================="
echo "Downloading and unpacking OMXWEBGUI to disk"
echo "Choose directory path, default: ~/omxwebgui"
echo "Leave it empty if you stick with the default"
echo "Given directory should be empty or non existing"

read p
echo ""

path=~/omxwebgui
if [ "$p" != "" ] ; then
    path=$p
fi

if [ ! -d "$path" ] ; then
    mkdir $path
fi

mkdir "$path/tmp"

cd $path
wget https://raw.githubusercontent.com/brainfoolong/omxwebgui-v2/master/updater.php
php -f updater.php
chmod +x *.sh

echo ""
echo "==========================================="
echo "Enable autostart for the OMXWEBGUI server"
echo "This will add a new entry to crontab"
echo "Are you want to enable autostart now? [y/n]"

read p
echo ""

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    (crontab -l 2>/dev/null; echo "@reboot php -S 0.0.0.0:$port -t $path > /dev/null 2>&1 &") | crontab -
fi

echo ""
echo "==========================================="
echo "Start OMXWEBGUI server now? [y/n]"

read p
echo ""

if [ "$p" = "y" ] || [ "$p" = "Y" ] ; then
    `php -S 0.0.0.0:$port -t $path > /dev/null 2>&1 &`
fi

echo ""
echo "==========================================="
echo "All files have been installed!"
echo "Open http://iptoyourpi:$port in your browser"
echo "Have fun with this application. You are awesome."
