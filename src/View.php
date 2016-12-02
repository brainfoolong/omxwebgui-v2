<?php
namespace Nullix\Omxwebgui;

/**
 * Class View
 * @package Nullix\Omxwebgui
 */
abstract class View
{
    /**
     * The root url of this application
     * @var string
     */
    public static $rootUrl;

    /**
     * Generate a link to the given view
     * @param string $view
     * @return string
     */
    public static function link($view)
    {
        return View::$rootUrl . "/index.php/" . strtolower($view);
    }

    /**
     * Just load the layout
     */
    final public function load()
    {
        header("Content-Type: text/html; charset=UTF-8");
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="format-detection" content="telephone=no">
            <meta name="msapplication-tap-highlight" content="no">
            <meta name="viewport"
                  content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
            <link rel="stylesheet" type="text/css" href="<?= View::$rootUrl ?>/stylesheets/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?= View::$rootUrl ?>/stylesheets/bootstrap-select.min.css">
            <link rel="stylesheet" type="text/css" href="<?= View::$rootUrl ?>/stylesheets/page.css">
            <link rel="shortcut icon" href="<?= View::$rootUrl ?>/images/favicon.ico" type="image/icon"/>
            <script type="text/javascript" src="<?= View::$rootUrl ?>/scripts/jquery-3.1.1.min.js"></script>
            <title>Omx Web Gui by BrainFooLong</title>
        </head>
        <body>

        <div id="wrapper">
            <div class="overlay"></div>
            <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
                <ul class="nav sidebar-nav">
                    <li class="sidebar-brand">
                        <div><img src="<?= View::$rootUrl ?>/images/logo.png"></div>
                    </li>
                    <li>
                        <a href="<?=View::link("index")?>">Playlist</a>
                    </li>
                    <li>
                        <a href="<?=View::link("settings")?>">Settings</a>
                    </li>
                    <li>
                        <a href="https://github.com/brainfoolong/omxwebgui" target="_blank">Github</a>
                    </li>
                    <li>
                        <a href="https://github.com/brainfoolong/omxwebgui/issues" target="_blank">Issues</a>
                    </li>
                </ul>
            </nav>
            <div id="page-content-wrapper">
                <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                    <span class="hamb-top"></span>
                    <span class="hamb-middle"></span>
                    <span class="hamb-bottom"></span>
                </button>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                            <div class="page-content">
                                <?= $this->getContent() ?>
                            </div>
                            <script type="text/javascript">
                                $(".page-content").addClass("hidden");
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="<?= View::$rootUrl ?>/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= View::$rootUrl ?>/scripts/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="<?= View::$rootUrl ?>/scripts/page.js"></script>
        </body>
        </html>
        <?php
    }

    /**
     * Get content for the page
     */
    abstract public function getContent();
}
