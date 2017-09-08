"use strict";
$(function () {
    var status = $(".player-status");

    /* video Player variables */

    var videoProgressScrubber = document.getElementById("videoProgressScrubber");
    var videoProgressFill = document.getElementById("videoProgressFill");
    var videoPlayPauseBtn = document.getElementById("playPauseBtn");
    var videoCurrentTime = document.getElementById("videoCurrentTime");
    var videoDurationTime = document.getElementById("videoDurationTime");
    var videoVolumeIcon = document.getElementById("videoVolumeIcon");
    var videoVolumeBtn = document.getElementById("videoVolumeBtn");
    var videoPlayPauseIcon = document.getElementById("videoPlayPauseIcon");
    var videoPauseUI = false;

    /* video Player variables end*/


    var getStatus = function () {


        $.post(window.location.href, {
            "action": "status"
        }, function (data) {
            data = JSON.parse(data);
            switch (data.status) {
                case "playing":
                    if (data.extra.Paused == true) {
                        if ($(videoPlayPauseIcon).hasClass("glyphicon-pause")) {
                            $(videoPlayPauseIcon).removeClass("glyphicon-pause").addClass("glyphicon-play");
                        }
                    } else {
                        if ($(videoPlayPauseIcon).hasClass("glyphicon-play")) {
                            $(videoPlayPauseIcon).removeClass("glyphicon-play").addClass("glyphicon-pause");
                        }
                    }

                    status.html(t("playing") + ' <span>' + data.path + '</span>');
                    updateVideoControlsTime(data.extra.Position, data.extra.Duration, data.extra.Volume);

                    break;
                case "stopped":
                    status.html(t("stopped"));
                    if ($(videoPlayPauseIcon).hasClass()) {
                        $(videoPlayPauseIcon).removeClass("glyphicon-pause").addClass("glyphicon-play");
                    }
                    break;
            }
            setTimeout(getStatus, 1500);
        });
    };

    getStatus();

    // bind keymap
    var keys = $(".keymap .btn");
    $(document).on("keyup", function (ev) {
        if (!$(ev.target).is("body")) return;
        var k = ev.keyCode.toString();
        keys.each(function () {
            var s = $(this).attr("data-key").split(",");
            if ($.inArray(k, s) !== -1) {
                $(this).trigger("click");
                return false;
            }
        });
    }).on("click", ".keymap .btn", function (ev) {
        $.post(window.location.href, {
            "action": "shortcut",
            "shortcut": $(this).attr("data-shortcut")
        });
        ev.stopPropagation();
    }).on("click", ".filelist .file", function () {
        $(this).removeClass("seen-false").addClass("seen-true");
        $.post(window.location.href, {
            "action": "shortcut",
            "shortcut": "start",
            "path": $(this).attr("data-path")
        });
    }).on("click", ".filelist .file img", function (ev) {
        ev.stopPropagation();
        var file = $(this).closest(".file");
        file.toggleClass("seen-false seen-true");
        $.post(window.location.href, {
            "action": "seen",
            "path": file.attr("data-path")
        });
    });


    var fl = $(".filelist");
    var files = fl.children(".file");
    var visImg = owg.rootUrl + '/images/icons/ic_visibility_white_24dp_2x.png';
    spinner(".filelist");

    // get filelist
    $.post(window.location.href, {"action": "filelist"}, function (data) {
        fl.html('');
        data = JSON.parse(data);
        if (data) {
            for (var i in data) {
                var file = data[i];
                var fileEl = $('<div class="file">');
                if (owg.settings.hidefolder) fileEl.addClass("hidefolder");
                fileEl.addClass('seen-' + (file.seen ? 'true' : 'false'));
                fileEl.attr("data-path", file.path);
                fileEl.attr("data-dir", file.dir);
                fileEl.attr("data-filename", file.filename);
                fileEl.append('<img src="' + visImg + '">');
                fileEl.append('<div class="dir">' + file.dir + '</div>');
                fileEl.append('<div class="filename">' + file.filename + '</div>');
                fl.append(fileEl);
            }
            files = fl.children(".file");
        }
    });

    // do file search
    $(".search").on("input", function () {
        if (this.value.length <= 1) {
            files.removeClass("hidden").each(function () {
                $(this).find(".dir").html($(this).attr("data-dir"));
                $(this).find(".filename").html($(this).attr("data-filename"));
            });
        } else {
            var s = this.value.trim().split(" ");
            var sRegex = s;
            for (var i in sRegex) {
                sRegex[i] = {
                    "regex": new RegExp(sRegex[i].replace(/[^0-9a-z\/\*]/ig, "\\$&").replace(/\*/ig, ".*?"), "ig"),
                    "val": s[i]
                };
            }
            files.addClass("hidden").each(function () {
                var f = $(this);
                var p = f.attr("data-path");
                var elements = [
                    {"el": f.find(".dir"), "value": f.attr("data-dir")},
                    {"el": f.find(".filename"), "value": f.attr("data-filename")}
                ];
                for (var i in elements) {
                    var data = elements[i];
                    var html = data.value;
                    var matches = [];
                    var fail = false;
                    sRegex.forEach(function (val) {
                        var m = p.match(val.regex);
                        if (!p.match(val.regex)) {
                            fail = true;
                            return true;
                        } else {
                            matches.push(m[0]);
                            html = html.replace(val.regex, "_" + (matches.length - 1) + "_");
                        }
                    });
                    if (!fail) f.removeClass("hidden");
                    for (var i in matches) {
                        html = html.replace(new RegExp("_" + i + "_", "ig"), '<span class="match">' + matches[i] + '</span>');
                    }
                    data.el.html(html);
                }
            });
        }
    });


    /* video player ui start */


    Number.prototype.pad = function (size) {
        var s = String(this);
        while (s.length < (size || 2)) {
            s = "0" + s;
        }
        return s;
    };

    //convert a microsend time into a text formated hh:mm:ss:
    function formatOMXTime(microSecondTime) {
        var seconds = Math.floor((microSecondTime / 1000000) % 60).pad();
        var minutes = Math.floor((microSecondTime / (1000000 * 60)) % 60).pad();
        var hours = Math.floor((microSecondTime / (1000000 * 60 * 60)) % 24).pad();
        return hours + ':' + minutes + ':' + seconds;
    }

    function updateVideoControlsTime(currentTime, duration, volume) {
        /* so we can drag the ui bar around for seeking we pause the updates while the mouse is down */
        if (videoPauseUI) {
            return;
        }
        $('#videoComponent').show();


        videoProgressFill.style.width = (Math.floor((currentTime / duration) * 100)) + "%";


        if ($(videoProgressScrubber).prop('max') != duration) {
            $(videoProgressScrubber).prop('max', duration);
            $(videoDurationTime).text(formatOMXTime(duration));
        }

        videoProgressScrubber.value = Math.floor(currentTime);
        $(videoCurrentTime).text(formatOMXTime(currentTime));

        if (volume == 0 && $(videoVolumeIcon).hasClass("glyphicon-volume-up")) {
            $(videoVolumeIcon).removeClass("glyphicon-volume-up").addClass("glyphicon-volume-off");
        } else if (volume > 0 && $(videoVolumeIcon).hasClass("glyphicon-volume-off")) {
            $(videoVolumeIcon).removeClass("glyphicon-volume-off").addClass("glyphicon-volume-up");
        }


    }

    videoPlayPauseBtn.addEventListener('click', function (event) {
        togglePlayPause();
    }, false);

    videoVolumeBtn.addEventListener('click', function (event) {
        videoToggleSound();
    }, false);

    var mouseupType = ((document.ontouchstart !== null) ? 'mouseup' : 'touchend');
    var mousedownType = ((document.ontouchstart !== null) ? 'mousedown' : 'touchstart');

    videoProgressScrubber.addEventListener(mouseupType, function (event) {
        console.log(videoProgressScrubber.value);

        $.post(window.location.href, {action: "setposition", value: videoProgressScrubber.value},
            function (returnedData) {
                console.log(returnedData);
            }).fail(function () {
            console.log("error");
        });

        videoPauseUI = false;
        // videoPlayer.currentTime = this.value;
    }, false);


    videoProgressScrubber.addEventListener('input', function (event) {
        videoPauseUI = true;
        $(videoCurrentTime).text(formatOMXTime(this.value));
    }, false);

    videoProgressScrubber.addEventListener(mousedownType, function (event) {
        videoPauseUI = true;
        console.log(this.value);
        //  videoPlayer.currentTime = this.value;
    }, false);


    function togglePlayPause() {
        $.post(window.location.href, {action: "toggleplay"},
            function (returnedData) {
                console.log(returnedData);
            }).fail(function () {
            console.log("error");
        });
    }

    function videoToggleSound() {
        var value = 1;
        if ($(videoVolumeIcon).hasClass("glyphicon-volume-up")) {
            value = 0;
        }
        //flip the value based on the icon
        $.post(window.location.href, {action: "setvolume", value: value},
            function (returnedData) {
                console.log(returnedData);
            }).fail(function () {
            console.log("error");
        });
    }

});