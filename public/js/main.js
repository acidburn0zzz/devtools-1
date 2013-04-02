/**
 * @date: 25.03.13
 * @time: 15:20
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file:
 */
function log(text) {
    $('#result').html(text);
}

$(document).ready(function () {
    $('#eval-btn').on('click', function () {
        $.ajax({
            url    : '/',
            method : 'post',
            data   : {code: $('#code').val()},
            success: $('#result').data('success')
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