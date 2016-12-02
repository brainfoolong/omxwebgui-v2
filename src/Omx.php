<?php
namespace Nullix\Omxwebgui;

/**
 * OMX Player stuff
 */
class Omx
{

    /**
     * The path to the fifo file
     * Will be set bellow this class
     *
     * @var string
     */
    public static $fifoFile;

    /**
     * All hotkeys
     *
     * @var mixed
     */
    public static $hotkeys = array(
        "q" => array("key" => "81"),
        "p" => array("key" => "80"),
        "-" => array("key" => "189,109"),
        "+" => array("key" => "187,107"),
        "left" => array("key" => "37", "shortcut" => "\x1b\x5b\x44"),
        "right" => array("key" => "39", "shortcut" => "\x1b\x5b\x43"),
        "down" => array("key" => "40", "shortcut" => "\x1b\x5b\x42"),
        "up" => array("key" => "38", "shortcut" => "\x1b\x5b\x41"),
        "z" => array("key" => "90"),
        "1" => array("key" => "50,98"),
        "2" => array("key" => "49,57"),
        "j" => array("key" => "74"),
        "k" => array("key" => "75"),
        "i" => array("key" => "73"),
        "o" => array("key" => "79"),
        "n" => array("key" => "78"),
        "m" => array("key" => "77"),
        "s" => array("key" => "83"),
        "d" => array("key" => "68"),
        "f" => array("key" => "70")
    );

    /**
     * Send commands to omxplayer
     *
     * @param mixed $omxcmd
     * @param mixed $method
     */
    public static function sendCommand($omxcmd, $method)
    {
        $script = dirname(__DIR__) . "/omx-{$method}.sh";
        $cmd = "timeout 5 sh " . escapeshellarg($script) . " "
            . escapeshellarg(self::$fifoFile) . " " . $omxcmd . " > /dev/null 2>&1";
        $output = $return = "";
        var_dump($cmd);
        exec($cmd, $output, $return);
    }
}

Omx::$fifoFile = dirname(__DIR__) . "/tmp/fifo";
