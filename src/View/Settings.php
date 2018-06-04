<?php

namespace Nullix\Omxwebgui\View;

use Nullix\Omxwebgui\Core;
use Nullix\Omxwebgui\Data;
use Nullix\Omxwebgui\Github;
use Nullix\Omxwebgui\View;

/**
 * Class Settings
 *
 * @package Nullix\Omxwebgui\View
 */
class Settings extends View
{
    /**
     * Default file formats
     *
     * @var string
     */
    public static $defaultFileFormats = "mp4|mkv|mpg|avi|mpeg|mp3|ogg";

    /**
     * Load
     */
    public function load()
    {
        if (post("do-update")) {
            exec("php -f " . escapeshellarg(dirname(dirname(__DIR__))
                    . "/updater.php"));
            header("Location: " . View::link("settings") . "?update-done=1");
            die();
        }

        // check if new version exists
        if (get("check-update")) {
            $lastRelease = Data::getKey("updater", "github-last-release");
            if (Data::getKey("settings", "check_update") !== "0") {
                // do update checks only each hour
                $lastUpdate = Data::getKey("updater", "last-check");
                if (get("force") || !$lastUpdate
                    || $lastUpdate < time() - 3600
                ) {
                    Data::setKey("updater", "last-check", time());
                    $apiData
                        = Github::apiRequest("https://api.github.com/repos/brainfoolong/omxwebgui-v2/releases");
                    if ($apiData) {
                        if (isset($apiData[0]["tag_name"])) {
                            $lastRelease = [
                                "version" => $apiData[0]["tag_name"],
                                "published_at" => $apiData[0]["published_at"],
                                "zipball_url" => $apiData[0]["zipball_url"]
                            ];
                            Data::setKey(
                                "updater",
                                "github-last-release",
                                $lastRelease
                            );
                        }
                    }
                }
            }
            echo json_encode($lastRelease);
            return;
        }
        parent::load();
    }

    /**
     * Get content for the page
     */
    public function getContent()
    {
        if (get("update-done")) {
            echo '<div class="btn btn-success note">'
                . t("settings.updates.success") . '</div>';
        }

        if (post("delete-seen")) {
            Data::set("filesseen", null);
            echo '<div class="btn btn-success note">'
                . t("settings.seen.reseted") . '</div>';
        }

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
            echo '<div class="btn btn-success note">' . t("saved") . '</div>';
        }

        if (Data::getKey("settings", "check_update") !== "0") {
            ?>
            <h1><?= t("settings.updates") ?></h1>
            <form name="updater" method="post" action="">
                <p>
                    <?php
                    $lastRelease = Data::getKey("updater", "github-last-release");
                    if ($lastRelease
                        && $lastRelease["version"] != Core::$version
                    ) {
                        echo t(
                            "settings.updates.available",
                            [
                                "versionA" => Core::$version,
                                "versionB" => $lastRelease["version"]
                            ]
                        );
                        echo '<div class="spacer"></div>';
                        echo '<input type="submit" name="do-update" value="'
                            . t("settings.updates.doupdate")
                            . '" class="btn btn-danger">';
                    } else {
                        echo t("settings.updates.up2date");
                    }
                    ?>
                </p>
            </form>
            <?php
        } ?>
        <div class="spacer"></div>
        <h1><?= t("settings") ?></h1>
        <form name="settings" method="post" action="">
            <div class="title">
                <strong><?= t("settings.folders.title") ?></strong>
                <small><?= t("settings.folders.desc") ?></small>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    &nbsp;
                </div>
                <div class="col-xs-4">
                    <?= t("recursive") ?>
                </div>
                <div class="col-xs-2">
                    &nbsp;
                </div>
            </div>
            <div class="folders spacer">
                <div class="row hidden">
                    <div class="col-xs-6">
                        <input type="text"
                               placeholder="<?= t("settings.folders.path") ?>"
                               name="folder[]"
                               class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <select class="selectpicker" name="folder_recursive[]">
                            <option value="0"><?= t("no") ?></option>
                            <option value="1"><?= t("yes") ?></option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <span class="btn btn-default btn-danger delete-folder">X</span>
                    </div>
                </div>
            </div>
            <div class="spacer">
                <span class="btn btn-default btn-xs add-folder"><?= t("settings.folders.add") ?></span>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.fileformats.title") ?></strong>
                <small><?= t("settings.fileformats.desc") ?></small>
            </div>
            <div class="spacer">
                <input type="text"
                       placeholder="<?= self::$defaultFileFormats ?>"
                       name="setting[file_formats]"
                       class="form-control">
            </div>

            <div class="title spacer">
                <strong><?= t("settings.subtitlesfolder.title") ?></strong>
                <small><?= t("settings.subtitlesfolder.desc") ?></small>
            </div>
            <div class="spacer">
                <input type="text"
                       placeholder="<?= t("settings.subtitlesfolder.title") ?>"
                       name="setting[subtitles_folder]"
                       class="form-control">
            </div>

            <div class="title spacer">
                <strong><?= t("settings.speedfix.title") ?></strong>
                <small><?= t("settings.speedfix.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[speedfix]">
                    <option value="0"><?= t("disabled") ?></option>
                    <option value="1"><?= t("enabled") ?></option>
                </select>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.hidefolder.title") ?></strong>
                <small><?= t("settings.hidefolder.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[hidefolder]">
                    <option value="0"><?= t("disabled") ?></option>
                    <option value="1"><?= t("enabled") ?></option>
                </select>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.audioout.title") ?></strong>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[audioout]">
                    <option value="hdmi">HDMI</option>
                    <option value="local">Local</option>
                    <option value="both">Both</option>
                    <option value="alsa">ALSA</option>
                </select>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.initvol.title") ?></strong>
                <small><?= t("settings.initvol.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[initvol]">
                    <?php
                    for ($i = 0; $i >= -30; $i -= 3) {
                        echo '<option value="' . $i . '">' . $i
                            . ' dB</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.display.title") ?></strong>
                <small><?= t("settings.display.desc") ?></small>
            </div>
            <div class="spacer">
                <input type="text"
                       name="setting[display]"
                       class="form-control">
            </div>

            <div class="title spacer">
                <strong><?= t("settings.check_update.title") ?></strong>
                <small><?= t("settings.check_update.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[check_update]">
                    <option value="1"><?= t("enabled") ?></option>
                    <option value="0"><?= t("disabled") ?></option>
                </select>
            </div>

            <div class="title spacer">
                <strong><?= t("settings.language.title") ?></strong>
                <small><?= t("settings.language.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[language]">
                    <option value="en">English</option>
                    <option value="de">Deutsch</option>
                    <option value="fr">Français</option>
                </select>
            </div>
            <input type="submit" value="<?= t("save") ?>" name="save"
                   class="btn btn-default btn-info">
        </form>

        <div class="spacer"></div>
        <br/>
        <div class="spacer"></div>

        <h1><?= t("settings.resetflags.title") ?></h1>
        <p><?= t("settings.resetflags.desc") ?></p>
        <form name="delete-seen" method="post" action="">
            <input type="submit" value="<?= t("delete") ?>" name="delete-seen"
                   class="btn btn-danger">
        </form>
        <script type="text/javascript">
          owg.folders = <?=json_encode(Data::get("folders"))?>
            owg.settings = <?=json_encode(Data::get("settings"))?>
        </script>
        <?php
    }
}
