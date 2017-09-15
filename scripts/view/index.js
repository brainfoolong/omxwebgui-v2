'use strict'
$(function () {
  var source = $('.player-source')
  var controls = $('.player-controls')
  var controlBtns = controls.find('.controls .control')
  var lastStatusData = null
  var statusTo = null
  var lastCmd = null

  var dbusCmd = function (command, parameter, callback) {
    clearTimeout(statusTo)
    if (lastCmd) {
      lastCmd.abort()
      lastCmd = null
    }
    lastCmd = $.post(window.location.href, {
      'action': 'dbus',
      'command': command,
      'parameter': parameter
    }, function (data) {
      data = JSON.parse(data)
      lastStatusData = data.status
      parseStatus(data.status)
      if (callback) callback(data)
      statusTo = setTimeout(function () {
        dbusCmd('status')
      }, 1000)
    })
  }

  var getStatus = function () {
    dbusCmd('status')
  }

  var parseStatus = function (statusData) {
    if (!statusData || statusData === 0 || statusData.status === 'stopped') {
      source.html(t('stopped'))
      controls.hide()
    } else {
      var status = statusData.status.toLowerCase()
      source.html(t(status) + ' <span>' + statusData.source + '</span>')
      controls.find('.time').html(numberToTime(statusData.position) + ' / ' + numberToTime(statusData.duration))
      controls.show()
      controlBtns.addClass('hidden')
      if (status === 'paused') {
        controlBtns.filter('.play').removeClass('hidden')
      } else if (status === 'playing') {
        controlBtns.filter('.pause').removeClass('hidden')
      }
      var left = 100 / statusData.duration * statusData.position
      controls.find('.point').css('left', left + '%')
    }
  }

  var numberToTimePad = function (number) {
    return number < 10 ? '0' + Math.floor(number) : Math.floor(number)
  }

  var numberToTime = function (number) {
    number = parseInt(number)
    var totalSeconds = number / 1000 / 1000
    var hours = Math.floor(totalSeconds / 3600)
    var minutes = (totalSeconds / 60) % 60
    var seconds = totalSeconds % 60
    var str = []
    if (hours > 0) {
      str.push(numberToTimePad(hours))
    }
    str.push(numberToTimePad(minutes))
    str.push(numberToTimePad(seconds))
    return str.join(':')
  }

  getStatus()

  var keys = $('.keymap .btn')
  var clickStart = null

  // bind controls
  controls.on('click', '.play, .pause', function () {
    keys.filter('[data-shortcut=\'p\']').trigger('click')
  })
  controls.on('mousedown', '.bar', function (ev) {
    clickStart = ev.pageX
    var b = controls.find('.bar')
    var x = ev.pageX - b.offset().left
    var w = b.width()
    var offset = 1 / w * x
    if (offset < 0) offset = 0
    if (offset > 1) offset = 1
    if (lastStatusData) {
      lastStatusData.position = lastStatusData.duration * offset
      parseStatus(lastStatusData)
      dbusCmd('setposition', lastStatusData.position)
    }
  })

  // bind keymap
  $(document).on('keyup', function (ev) {
    if (!$(ev.target).is('body')) return
    var k = ev.keyCode.toString()
    keys.each(function () {
      var s = $(this).attr('data-key').split(',')
      if ($.inArray(k, s) !== -1) {
        $(this).trigger('click')
        return false
      }
    })
  }).on('click', '.keymap .btn', function (ev) {
    $.post(window.location.href, {
      'action': 'shortcut',
      'shortcut': $(this).attr('data-shortcut')
    }, getStatus)
    ev.stopPropagation()
  }).on('click', '.filelist .file', function () {
    $(this).removeClass('seen-false').addClass('seen-true')
    $.post(window.location.href, {
      'action': 'shortcut',
      'shortcut': 'start',
      'path': $(this).attr('data-path')
    }, getStatus)
  }).on('click', '.filelist .file img', function (ev) {
    ev.stopPropagation()
    var file = $(this).closest('.file')
    file.toggleClass('seen-false seen-true')
    $.post(window.location.href, {
      'action': 'seen',
      'path': file.attr('data-path')
    })
  })

  var fl = $('.filelist')
  var files = fl.children('.file')
  var visImg = owg.rootUrl + '/images/icons/ic_visibility_white_24dp_2x.png'
  spinner('.filelist')

  // get filelist
  $.post(window.location.href, {'action': 'filelist'}, function (data) {
    fl.html('')
    data = JSON.parse(data)
    if (data) {
      for (var i in data) {
        var file = data[i]
        var fileEl = $('<div class="file">')
        if (owg.settings.hidefolder === "1") fileEl.addClass('hidefolder')
        fileEl.addClass('seen-' + (file.seen ? 'true' : 'false'))
        fileEl.attr('data-path', file.path)
        fileEl.attr('data-dir', file.dir)
        fileEl.attr('data-filename', file.filename)
        fileEl.append('<img src="' + visImg + '">')
        fileEl.append('<div class="dir">' + file.dir + '</div>')
        fileEl.append('<div class="filename">' + file.filename + '</div>')
        fl.append(fileEl)
      }
      files = fl.children('.file')
    }
  })

  // do file search
  $('.search').on('input', function () {
    if (this.value.length <= 1) {
      files.removeClass('hidden').each(function () {
        $(this).find('.dir').html($(this).attr('data-dir'))
        $(this).find('.filename').html($(this).attr('data-filename'))
      })
    } else {
      var s = this.value.trim().split(' ')
      var sRegex = s
      for (var i in sRegex) {
        sRegex[i] = {
          'regex': new RegExp(sRegex[i].replace(/[^0-9a-z\/\*]/ig, '\\$&').replace(/\*/ig, '.*?'), 'ig'),
          'val': s[i]
        }
      }
      files.addClass('hidden').each(function () {
        var f = $(this)
        var p = f.attr('data-path')
        var elements = [
          {'el': f.find('.dir'), 'value': f.attr('data-dir')},
          {'el': f.find('.filename'), 'value': f.attr('data-filename')}
        ]
        for (var i in elements) {
          var data = elements[i]
          var html = data.value
          var matches = []
          var fail = false
          sRegex.forEach(function (val) {
            var m = p.match(val.regex)
            if (!p.match(val.regex)) {
              fail = true
              return true
            } else {
              matches.push(m[0])
              html = html.replace(val.regex, '_' + (matches.length - 1) + '_')
            }
          })
          if (!fail) f.removeClass('hidden')
          for (var i in matches) {
            html = html.replace(new RegExp('_' + i + '_', 'ig'), '<span class="match">' + matches[i] + '</span>')
          }
          data.el.html(html)
        }
      })
    }
  })
})