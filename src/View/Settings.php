<?php
namespace Nullix\Omxwebgui\View;

use Nullix\Omxwebgui\Data;
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
     * Get content for the page
     */
    public function getContent()
    {
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
        ?>
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
                <strong><?= t("settings.language.title") ?></strong>
                <small><?= t("settings.language.desc") ?></small>
            </div>
            <div class="spacer">
                <select class="selectpicker" name="setting[language]">
                    <option value="en">English</option>
                    <option value="de">Deutsch</option>
                </select>
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
            owg.folders = <?=json_encode(Data::get("folders"))?>;
            owg.settings = <?=json_encode(Data::get("settings"))?>;
        </script>
        <?php
    }
}
