/**
 * @date: 25.03.13
 * @time: 15:20
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file:
 */

function evalCode(editor, controller) {
    $.ajax({
        url    : '/' + controller,
        type   : 'post',
        data   : {code: editor.getValue()},
        success: function (response) {
            $('.alert').remove();
            if (response.output) {
                $('#result').html(response.output);
            } else {
                showAlert(response.message, false)
            }
        }
    });
}

$(document).ready(function () {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    if (allowModules.indexOf(controller) !== -1) {
        editor.getSession().setMode("ace/mode/" + controller);
    }

    $('#eval-btn').on('click', function () {
        evalCode(editor, controller);
        return false;
    });

    $('#reset-btn').on('click', function () {
        document.location.reload();
        return false;
    });
});