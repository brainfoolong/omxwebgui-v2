<?php
namespace Nullix\Omxwebgui\View;

use Nullix\Omxwebgui\Data;
use Nullix\Omxwebgui\View;

/**
 * Class Settings
 * @package Nullix\Omxwebgui\View
 */
class Settings extends View
{
    /**
     * Default file formats
     * @var string
     */
    public static $defaultFileFormats = "mp4|mkv|mpg|avi|mpeg|mp3|ogg";

    /**
     * Get content for the page
     */
    public function getContent()
    {
        if (post("save")) {
            $settings = Data::get("settings");
            $postSettings = post("setting");
            if (is_array($postSettings)) {
                foreach ($postSettings as $key => $value) {
                    $settings[$key] = $value;
                }
                Data::set("settings", $settings);
            }
            $folders = [];
            $postFolders = post("folder");
            $postRecursive = post("folder_recursive");
            if (is_array($postFolders)) {
                $c = 0;
                foreach ($postFolders as $key => $value) {
                    if (!$value) {
                        continue;
                    }
                    $folders[$c++] = [
                        "folder" => $value,
                        "recursive" => (int)$postRecursive[$key]
                    ];
                }
                Data::set("folders", $folders);
            }
            echo '<div class="btn btn-success note">Settings saved</div>';
        }
        ?>
        <h1>Settings</h1>
        <form name="settings" method="post" action="">
            <div class="title">
                <strong>Media folders and files</strong>
                <small>Point to folders with your media files or directly to a single media file. You can also add URL's
                    to direct-streams or online files (rtmp://, rtstp://, http://).
                </small>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    &nbsp;
                </div>
                <div class="col-xs-4">
                    Recursive
                </div>
                <div class="col-xs-2">
                    &nbsp;
                </div>
            </div>
            <div class="folders spacer">
                <div class="row hidden">
                    <div class="col-xs-6">
                        <input type="text" placeholder="Absolute Path" name="folder[]" class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <select class="selectpicker" name="folder_recursive[]">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <span class="btn btn-default btn-danger delete-folder">X</span>
                    </div>
                </div>
            </div>
            <div class="spacer">
                <span class="btn btn-default btn-xs add-folder">Add another folder</span>
            </div>

            <div class="title spacer">
                <strong>File formats</strong>
                <small>You can modify the file formats that will be available for the playlist.</small>
            </div>
            <div class="spacer">
                <input type="text" placeholder="Default: <?= self::$defaultFileFormats ?>" name="setting[file_formats]"
                       class="form-control">
            </div>

            <div class="title spacer">
                <strong>Double speed and no audio fix</strong>
                <small>Activate this if you have troubles with videos starting at double speed and without
                    audio
                </small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[speedfix]">
                    <option value="0">Disabled</option>
                    <option value="1">Enabled</option>
                </select>
            </div>

            <div class="title spacer">
                <strong>Audio out device</strong>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[audioout]">
                    <option value="hdmi">HDMI</option>
                    <option value="local">Local</option>
                </select>
            </div>

            <div class="title spacer">
                <strong>Initial Volume</strong>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[initvol]">
                    <?php
                    for ($i = 0; $i >= -30; $i -= 3) {
                        echo '<option value="' . $i . '">' . $i . ' dB</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="title spacer">
                <strong>Language</strong>
                <small>Change language for the interface</small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[language]">
                    <option value="en">English</option>
                    <option value="de">Deutsch</option>
                </select>
            </div>
            <input type="submit" value="Save" name="save" class="btn btn-default btn-info">
        </form>
        <?php
    }
}
