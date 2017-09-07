<?php
namespace Nullix\Omxwebgui\View;

use Nullix\Omxwebgui\Data;
use Nullix\Omxwebgui\Omx;
use Nullix\Omxwebgui\View;

/**
 * Class Index
 *
 * @package Nullix\Omxwebgui\View
 */
class Index extends View
{

    /**
     * Tmp saved file formats
     *
     * @var string
     */
    private $fileFormats;

    /**
     * Load
     */
    public function load()
    {

        if (post("action") == "seen") {
            $path = md5(post("path"));
            $flag = Data::getKey("filesseen", $path);
            Data::setKey("filesseen", $path, !$flag);
            return;
        }

        // get player status, is running or not
        if (post("action") == "status") {
            $data = array("status" => "stopped");
            $output = $return = "";
            exec('sh ' . escapeshellcmd(dirname(dirname(__DIR__))
                    . "/omx-status.sh"), $output, $return);
            if ($return) {
                $data["status"] = "playing";
                $activeFile = Data::get("active-file");
                if ($activeFile) {
                    $data["path"] = $activeFile;
                }
            }

           $data["duration"] = $this->exec_dbus("getduration","");
            $data["position"] = $this->exec_dbus("getposition","");
            echo json_encode($data);
            return;
        }

        // trigger a keyboard shortcut
        if (post("action") == "shortcut") {
            $shortcut = post("shortcut");
            $path = post("path");
            if (!$path) {
                $path = Data::get("active-file");
            }
            $settings = Data::get("settings");
            $params = [
                isset($settings["speedfix"]) && $settings["speedfix"] ? "1" : "0",
                isset($settings["audioout"]) ? $settings["audioout"] : "hdmi",
                isset($settings["initvol"]) ? $settings["initvol"] * 100 : "0",
                isset($settings["subtitles_folder"]) && $settings["subtitles_folder"] != "" ? $settings["subtitles_folder"] : "-",
                isset($settings["display"]) && $settings["display"] != "" ? $settings["display"] : "-"
            ];
            foreach ($params as $key => $value) {
                $params[$key] = escapeshellarg($value);
            }
            $startCmd = escapeshellarg($path) . " " . implode(" ", $params);
            
            switch (post("shortcut")) {
                case "start":
                    Data::setKey("filesseen", md5($path), true);
                    Data::set("active-file", $path);
                    Omx::sendCommand($startCmd, "start");
                    break;
                case "p":
                    if (!file_exists(Omx::$fifoFile)) {
                        Data::setKey("filesseen", md5($path), true);
                        Omx::sendCommand($startCmd, "start");
                    } else {
                        Omx::sendCommand(escapeshellarg("p"), "pipe");
                    }
                    break;
                default:
                    $key = Omx::$hotkeys[$shortcut];
                    $shortcut = isset($key["shortcut"]) ? $key["shortcut"] : $shortcut;
                    Omx::sendCommand(escapeshellarg($shortcut), "pipe");
            }
            return;
        }

        // get filelist
        if (post("action") == "filelist") {
            $folders = Data::get("folders");
            $this->fileFormats = Data::getKey("settings", "file_formats");
            if (!$this->fileFormats) {
                $this->fileFormats = Settings::$defaultFileFormats;
            }
            $files = [];
            if (is_array($folders)) {
                foreach ($folders as $folderData) {
                    $files = array_merge(
                        $files,
                        $this->getFilesRecursive($folderData["folder"],
                            (bool)$folderData["recursive"])
                    );
                }
            }
            $filesseen = Data::get("filesseen");
            $json = [];
            foreach ($files as $file) {
                $json[] = [
                    "path" => $file,
                    "dir" => dirname($file),
                    "filename" => basename($file),
                    "seen" => isset($filesseen[md5($file)])
                        && $filesseen[md5($file)]
                ];
            }
            echo json_encode($json);
            return;
        }
        parent::load();
    }

    /**
     * @param $command string dbus command to execute
     * @param $value integer optional param to send
     */
    private function exec_dbus($command, $value) {
        $output = $return = "";
        $cmd = escapeshellcmd(dirname(dirname(__DIR__)) . "/dbus.sh $command $value");
        exec($cmd, $output, $return);
        if (isset($output) && is_array($output)) {
            return $output[0];
        } else {
            return 0;
        }
    }

    /**
     * Get content for the page
     */
    public function getContent()
    {
        ?>
        <div class="spacer">
            <h1 class="pull-left"><?= t("playlist") ?></h1>
            <div class="btn btn-info pull-right" style="margin-top: 20px"
                 onclick="$('.keymap').toggleClass('hidden')">
                <?= t("keymap.btn") ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="keymap hidden">
            <p><?= t("keymap.desc") ?></p>
            <div class="buttons row">
                <?php
                foreach (Omx::$hotkeys as $key => $value) {
                    $keyValue = $key;
                    switch ($key) {
                        case "left":
                            $keyValue = "&#x2190";
                            break;
                        case "right":
                            $keyValue = "&#x2192";
                            break;
                        case "up":
                            $keyValue = "&#x2191";
                            break;
                        case "down":
                            $keyValue = "&#x2193";
                            break;
                    }
                    echo '<div class="col-md-3 col-xs-6 btn btn-success" data-key="'
                        . $value["key"] . '" data-shortcut="' . $key . '">
                        <div class="shortcut">' . $keyValue
                        . '</div><div class="info">' . t("shortcut-$key") . '</div>
                        </div>';
                }
                ?>
                <div class="clear"></div>
            </div>
        </div>
        <div class="note bg-primary"><span class="player-status">-</span></div>
        <!-- Custom Video Controls -->
        <div class="video-component" id="videoComponent">

            <div class="video-controls" id="videoControls" dir="ltr" role="group">
                <div class="icon-spinner anim-spin video-preloader" id="videoPreloader"></div>

                <div class="video-progress-container">
                    <div class="video-progress-fill" id="videoProgressFill"></div>
                    <input type="range" min="0" title="Press the left or right arrow to control the video" value="0" id="videoProgressScrubber" />
                </div>
                <button disabled title="Play/Pause Video" class="icon-pause" id="playPauseBtn"><span class="glyphicon glyphicon-play"></span></button>

                <span class="video-progress-text" id="videoProgressText">00:00 / 00:00</span>
                <div class="pull-right video-utilities">
                    <button  title="Mute the video volume" class="icon-volume-high" id="videoVolumeBtn"><span class="glyphicon glyphicon-volume-up"></span></button>
                </div>
            </div>
        </div>
        <!-- END Custom Video Controls -->
        <div class="input-group spacer">
            <div class="input-group-addon"><img
                        src="<?= View::$rootUrl ?>/images/icons/ic_search_white_24dp_1x.png"
                        width="15"></div>
            <input type="text" class="form-control input-lg search"
                   placeholder="<?= t("search.placeholder") ?>">
        </div>
        <div class="filelist"></div>
        <?php
    }

    /**
     * Display recursive
     *
     * @param string $path
     * @param bool $recursive
     *
     * @return array
     */
    private function getFilesRecursive($path, $recursive)
    {
        // if is url or real file
        if (preg_match("~[a-z]+\:\/\/~i", $path) || is_file($path)) {
            return [$path];
        }
        // check directory
        if (is_dir($path)) {
            $files = [];
            $dirFiles = scandir($path, SCANDIR_SORT_ASCENDING);
            foreach ($dirFiles as $file) {
                if ($file == "." || $file == "..") {
                    continue;
                }
                $filePath = $path . "/" . $file;
                if (is_dir($filePath)) {
                    if ($recursive) {
                        $files = array_merge($files,
                            $this->getFilesRecursive($filePath, $recursive));
                    }
                } elseif (preg_match("~(" . $this->fileFormats . ")$~i",
                    $file)) {
                    $files[] = $filePath;
                }
            }
            return $files;
        }
        return [];
    }
}
