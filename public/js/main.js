/**
 * @date: 25.03.13
 * @time: 15:20
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file:
 */
function getController(){
    return (document.location.href.match(/#(\w+)/i) || {1:'about'})[1];
}


$(document).ready(function () {
    $('ul.nav a').click(function(){
        $('ul.nav li').removeClass('active');
        $(this).parents('li').addClass('active');
    });
    $('ul.nav a[href="#'+getController()+'"]').click();



    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setMode("ace/mode/php");
    editor.setValue($('#editor').data('code').code);
    editor.clearSelection();

    $('#eval-btn').on('click', function () {
        $.ajax({
            url    : '/',
            type : 'post',
            data   : {code: editor.getValue()},
            success: function(response){
                if(response.output) {
                    $('#result').html(response.output);
                }
            }
        });
        return false;
    });

    $('#reset-btn').on('click', function () {
        $('#code').val('');
        $('#result').html('');
        return false;
    });

    $('.navbar-inner ul.nav li a[href=\\/\\/'+document.domain.replace(/(\.)/,'\\$1')+']').parent().attr('class', 'active');
});