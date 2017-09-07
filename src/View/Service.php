<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 9/6/2017
 * Time: 11:58 PM
 */

namespace Nullix\Omxwebgui\View;



use Nullix\Omxwebgui\Data;
use Nullix\Omxwebgui\Omx;
use Nullix\Omxwebgui\View;

class Service extends view
{

    public function load()
    {
        //override main index page
        $this->getContent();
    }

    /**
     * Get content for the page
     */
    public function getContent()
    {
        header('Content-Type: application/json');

        if (get("action")) { // get information functions
            switch (get("action")) {
                case "duration":
                    $this->exec_dbus("getduration","");
                    break;
                case "position":
                    $this->exec_dbus("getposition","");
                    break;
                case "playstatus":
                    $this->exec_dbus("getplaystatus","");
                    break;
                case "volume":
                    $this->exec_dbus("getvolume","");
                    break;
                default:
                    return json_encode("unknown method",true);
            }

            if (post("action")) {
                switch (post("action")) {

                    default:
                        return json_encode("unknown method",true);
                }
            }
        }
    }

    private function exec_dbus($command, $value) {
        $output = $return = "";
        $cmd = escapeshellcmd(dirname(dirname(__DIR__)) . "/dbus.sh $command $value");
        exec($cmd, $output, $return);
        print(json_encode($output,true));
    }

}