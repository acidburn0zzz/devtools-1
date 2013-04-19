var url, controller = getController(), allowModules = ['php', 'javascript', 'sql'];

function getController(url) {
    return ((url || document.location.href).match(/#(\w+)/i) || {1: 'about'})[1];
}

function getConfig() {
    var key, value, config = {};
    $('.config input,.config select,.config textarea').each(function () {
        key = $(this).attr('id');
        value = $(this).val();
        config[key] = value;
    });
    return config;
}

function setConfig(config) {
    var key, value;
    for (key in config) {
        value = config[key];
        $('#' + key).val(value);
    }
}

$(document).on('click', '.config-dropdown input,.config-dropdown select,.config-dropdown textarea', function () {
    return false;
});
$(document).on('click', '.config-save', function () {
    $.ajax({
        url    : '/config',
        type   : 'post',
        data   : getConfig(),
        success: function (response) {
            setConfig(response.config);
        }
    });
});

$(document).on('click', 'a.navigation', function () {
    controller = getController($(this).attr('href'));
    $('a.navigation').parents('li').removeClass('active');
    $('a.navigation[href="/#' + controller + '"]').parents('li').addClass('active');
    url = (allowModules.indexOf(controller) === -1) ? '' : 'code/' + controller;
    $('#content').load('/' + url);
});

$(document).ready(function () {
    $.ajax({
        url    : '/config',
        type   : 'get',
        success: function (response) {
            setConfig(response.config);
        }
    });
    $('a.navigation[href="/#' + controller + '"]').click();
});