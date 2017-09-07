"use strict";
$(function () {
    var status = $(".player-status");

    var getStatus = function () {
        $.post(window.location.href, {
            "action": "status"
        }, function (data) {
            data = JSON.parse(data);
            switch (data.status) {
                case "playing":
                    status.html(t("playing") + ' <span>' + data.path + '</span>');
                    $('#videoComponent').show();
                    $('#videoProgressScrubber').prop('max',data.duration);
                    $('#videoProgressScrubber').val(data.position);
                    break;
                case "stopped":
                    status.html(t("stopped"));
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
});