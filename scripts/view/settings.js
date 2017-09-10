'use strict'
$(function () {
  var baseRow = $('.folders .row.hidden')
  var createFolderRow = function (values) {
    baseRow.find('select').selectpicker('destroy')
    var clone = baseRow.clone()
    clone.removeClass('hidden')
    if (values) {
      clone.find('[name=\'folder[]\']').val(values.folder)
      clone.find('[name=\'folder_recursive[]\']').val(values.recursive)
    }
    $('.folders').append(clone)
    clone.find('select').selectpicker()
  };

  // set all folders
  (function () {
    if (owg.folders && owg.folders.length) {
      for (var i in owg.folders) {
        createFolderRow(owg.folders[i])
      }
    } else {
      createFolderRow()
    }
  })();
  // set all setting values
  (function () {
    if (owg.settings) {
      for (var i in owg.settings) {
        var f = $('form').find('[name=\'setting[' + i + ']\']')
        if (f.hasClass('selectpicker')) {
          f.selectpicker('val', owg.settings[i])
        } else {
          f.val(owg.settings[i])
        }
      }
    }
  })()
  // bind add folder and delete folder clicks
  $('form').on('click', '.add-folder', function () {
    createFolderRow()
  }).on('click', '.delete-folder', function () {
    $(this).closest('.row').remove()
    if ($('form .folders .row').not('.hidden').length == 0) {
      createFolderRow()
    }
  })
})