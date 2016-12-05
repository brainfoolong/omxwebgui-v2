<?php
namespace Nullix\Omxwebgui;

/**
 * Class Translation
 *
 * @package Nullix\Omxwebgui
 */
class Translation
{

    /**
     * All translation values
     *
     * @var array
     */
    public static $values
        = [
            "en" => [
                "shortcut-q" => "Stop\nPlayer",
                "shortcut-p" => "Pause\nResume",
                "shortcut--" => "Volume Down",
                "shortcut-+" => "Volume Up",
                "shortcut-left" => "Slow Backward",
                "shortcut-right" => "Slow Forward",
                "shortcut-down" => "Fast Backward",
                "shortcut-up" => "Fast Forward",
                "shortcut-z" => "Show Info",
                "shortcut-1" => "Speed\nDown",
                "shortcut-2" => "Speed\nUp",
                "shortcut-j" => "Prev Audio Stream",
                "shortcut-k" => "Next Audio Stream",
                "shortcut-i" => "Previous Chapter",
                "shortcut-o" => "Next Chapter",
                "shortcut-n" => "Prev Subtitle Stream",
                "shortcut-m" => "Next Subtitle Stream",
                "shortcut-s" => "Toggle subtitles",
                "shortcut-d" => "Decrease Subtitle delay",
                "shortcut-f" => "Increase Subtitle delay",
                "yes" => "Yes",
                "no" => "No",
                "enabled" => "Enabled",
                "disabled" => "Disabled",
                "save" => "Save",
                "saved" => "Saved",
                "delete" => "Delete",
                "stopped" => "Player stopped",
                "playing" => "Playing",
                "playlist" => "Playlist",
                "keymap.btn" => "Keymap/Controls",
                "keymap.desc" => "Most Omxplayer keyboard bindings are available directly on this webpage. 
            You can press the corresponding key on your keyboard or you can click the button. 
            Make sure your focus is out of the search input field.",
                "search.placeholder" => "Search for folder and files...",
                "settings" => "Settings",
                "settings.seen.reseted" => "Seen flags reseted",
                "settings.folders.add" => "Add another folder",
                "settings.folders.title" => "Media folders and files",
                "settings.folders.desc" => "Point to folders with your media files or directly to a single media file. 
            You can also add URL's to direct-streams or online files (rtmp://, rtstp://, http://).",
                "recursive" => "Recursive",
                "settings.folders.path" => "Absolute Path",
                "settings.fileformats.title" => "File extensions",
                "settings.fileformats.desc" => "You can modify the file formats that will be available for the playlist.",
                "settings.speedfix.title" => "Double speed and no audio fix",
                "settings.speedfix.desc" => "Activate this if you have troubles with videos starting 
            at double speed and without audio",
                "settings.audioout.title" => "Audio out device",
                "settings.initvol.title" => "Initial volume",
                "settings.initvol.desc" => "Choose the volume for the player to start with",
                "settings.language.title" => "Language",
                "settings.language.desc" => "Select the language for the web interface",
                "settings.resetflags.title" => "Reset seen flags",
                "settings.resetflags.desc" => "Delete all seen flags from all videos with just one click",
                "settings.hidefolder.title" => "Hide folder names in playlist",
                "settings.hidefolder.desc" => "Enable a more compact view for the playlist by hiding the folder names",
                "settings.subtitlesfolder.title" => "Subtitles folder",
                "settings.subtitlesfolder.desc" => "If you would like to keep your subtitle files in a separate place from your media files, you can provide the folder here.",
                "settings.check_update.title" => "Automatic updater",
                "settings.check_update.desc" => "Check for updates of OMXWEBGUI periodically and notify you when a new update is available. If disabled all update information will be hidden, including the updater.",
                "settings.updates" => "Updater",
                "settings.updates.available" => "A new version is available. You've installed {versionA} and the latest version is {versionB}. You can do an automatic update. Manually backup the full omxwebgui folder if you feel more comfortable than. The updates will be loaded directly from github over the https protocol. The updater don't delete any files, it just override or add new files, so your data folder and settings are save. On the other hand, you can do the update manually as always. Just override all files with the new ones.",
                "settings.updates.up2date" => "Your version is up 2 date. Awesome.",
                "settings.updates.doupdate" => "Do automatic update now",
                "settings.updates.success" => "Update successfull"
            ],
            "de" => [
                "shortcut-q" => "Stoppe\nPlayer",
                "shortcut-p" => "Pausieren\nFortfahren",
                "shortcut--" => "Lautstärke runter",
                "shortcut-+" => "Lautstärke rauf",
                "shortcut-left" => "Langsam zurück",
                "shortcut-right" => "Langsam vorwärts",
                "shortcut-down" => "Schnell zurück",
                "shortcut-up" => "Schnell vorwärts",
                "shortcut-z" => "Zeige Info",
                "shortcut-1" => "Video Langsamer",
                "shortcut-2" => "Video Schneller",
                "shortcut-j" => "Vorheriger Audio Stream",
                "shortcut-k" => "Nächster Audio Stream",
                "shortcut-i" => "Letztes Kapitel",
                "shortcut-o" => "Nächstes Kapitel",
                "shortcut-n" => "Vorheriger Untertitel Stream",
                "shortcut-m" => "Nächster Untertitel Stream",
                "shortcut-s" => "Untertitel an/aus",
                "shortcut-d" => "Weniger Untertitel Verzögerung",
                "shortcut-f" => "Mehr Untertitel Verzögerung",
                "yes" => "Ja",
                "no" => "Nein",
                "enabled" => "Aktiviert",
                "disabled" => "Deaktiviert",
                "save" => "Speichern",
                "saved" => "Gespeichert",
                "delete" => "Löschen",
                "stopped" => "Wiedergabe gestoppt",
                "playing" => "Spielt",
                "playlist" => "Wiedergabeliste",
                "keymap.btn" => "Keymap/Steuerung",
                "keymap.desc" => "Die meisten Omxplayer Tastatur Hotkeys werden direkt in diesem Webinterface unterstützt. 
            Drücke den entsprechende Taste am Keyboard oder klicke auf den entsprechenden Button-
            Stelle aber sicher das du nicht mit dem Cursor im Suchfeld stehst.",
                "search.placeholder" => "Suche nach Ordner und Dateien...",
                "settings" => "Einstellungen",
                "settings.seen.reseted" => "Gesehen Flags gelöscht",
                "settings.folders.add" => "Weiteren Ordner hinzufügen",
                "settings.folders.title" => "Medienordner und Dateien",
                "settings.folders.desc" => "Zeige auf einen Ordner oder direkt auf eine Mediendatei. 
            Du kannst auch einfach Links zu Streams oder Online Dateien hinzufügen (rtmp://, rtstp://, http://).",
                "recursive" => "Rekursiv",
                "settings.folders.path" => "Absoluter Pfad",
                "settings.fileformats.title" => "Dateiendungen",
                "settings.fileformats.desc" => "Du kannst die Dateiendungen definieren 
            die für die Anzeige der Wiedergabeliste benutzt werden.",
                "settings.speedfix.title" => "Fix für doppelte Geschwindigkeit und kein Audio",
                "settings.speedfix.desc" => "Aktiviere diese Einstellung wenn du damit Probleme hast",
                "settings.audioout.title" => "Audio Out Gerät",
                "settings.initvol.title" => "Startlautstärke",
                "settings.initvol.desc" => "Wähle wie laut der Player starten soll",
                "settings.language.title" => "Sprache",
                "settings.language.desc" => "Wähle eine Sprache für das Webinterface",
                "settings.resetflags.title" => "Lösche alle gesehen Flags",
                "settings.resetflags.desc" => "Lösche alle gesehen Flags mit einem Klick",
                "settings.hidefolder.title" => "Verstecke Ordnername in Wiedergabeliste",
                "settings.hidefolder.desc" => "Aktiviere eine kompaktere Ansicht mit dieser Option",
                "settings.subtitlesfolder.title" => "Untertitel Ordner",
                "settings.subtitlesfolder.desc" => "Wenn Sie Ihre Untertitel-Dateien an einem anderen Ort aus Ihren Mediendateien speichern möchten, können Sie den Ordner hier angeben.",
                "settings.check_update.title" => "Automatische Aktualisierungen",
                "settings.check_update.desc" => "Überprüft regelmäßig auf neue Updates für OMXWEBGUI und benachrichtigt dich wenn es eine neue Version gibt. Wenn du das deaktivierst werden diese Checks und der Updater deaktiviert.",
                "settings.updates" => "Updater",
                "settings.updates.available" => "Eine neue Version ist verfügbar. Installiert ist {versionA} und die letzte ist {versionB}. Du kannst jetzt die automatische Aktualisierung durchführen. Mach ein Backup deiner OMXWEBGUI Installation wenn du dich dann besser fühlst. Die Updates werden direkt von Github über eine https Verbindung geladen. Der Updater löscht keine Dateien, es werden nur neue hinzugefügt und vorhandene überschrieben. Deine Einstellungen sind also sicher. Auf der anderen Seite kannst du natürlich auch ein manuelles Update machen indem du einfach alle Dateien mit den neuen überschreibst.",
                "settings.updates.up2date" => "Deine Version ist aktuell. Leiwand.",
                "settings.updates.doupdate" => "Jetzt automatisches Update durchführen",
                "settings.updates.success" => "Update erfolgreich"
            ]
        ];
}
