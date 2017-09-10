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
    public static $hotkeys
        = [
            "q" => ["key" => "81"],
            "p" => ["key" => "80"],
            "-" => ["key" => "189,109"],
            "+" => ["key" => "187,107"],
            "left" => ["key" => "37", "shortcut" => "\x1b\x5b\x44"],
            "right" => ["key" => "39", "shortcut" => "\x1b\x5b\x43"],
            "down" => ["key" => "40", "shortcut" => "\x1b\x5b\x42"],
            "up" => ["key" => "38", "shortcut" => "\x1b\x5b\x41"],
            "z" => ["key" => "90"],
            "1" => ["key" => "50,98"],
            "2" => ["key" => "49,57"],
            "j" => ["key" => "74"],
            "k" => ["key" => "75"],
            "i" => ["key" => "73"],
            "o" => ["key" => "79"],
            "n" => ["key" => "78"],
            "m" => ["key" => "77"],
            "s" => ["key" => "83"],
            "d" => ["key" => "68"],
            "f" => ["key" => "70"]
        ];

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
            . escapeshellarg(self::$fifoFile) . " " . $omxcmd
            . " > /dev/null 2>&1";
        $output = $return = "";
        exec($cmd, $output, $return);
    }
}

Omx::$fifoFile = dirname(__DIR__) . "/tmp/fifo";
