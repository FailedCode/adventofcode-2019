/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

function addFlashMessage(label, message) {
    $container = $('#messageContainer');
    if ($container) {
        $flash = $(`<div class="flash flash-${ label }"><div class="flash-message">${ message }</div><div class="flash-close">X</div></div>`);
        $container.append($flash);
        bindFlashClose();
    }
}

function bindFlashClose() {
    $('.flash-close').on("click", function (event) {
        let closer = $(event.target);
        closer.parent().remove();
    });
}

$(document).ready(function () {

    bindFlashClose();

    $(".solve-action").on("click", function (event) {
        let button = $(event.target);
        let day = button.data('day');
        let part = button.data('part');
        let puzzle = button.data('puzzle');

        $.ajax({
            url: '/solve/day/' + day,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                day: day,
                part: part,
                puzzle: puzzle,
            },
            success: function (data, status) {
                if (data['error']) {
                    addFlashMessage('warn', `Error:<br>${data['message']}`);
                } else {
                    let result = data['part'+part];
                    if (result) {
                        $('#part'+part).val(result);
                        addFlashMessage('success', `Success:<br>${result}`);
                    } else {
                        addFlashMessage('warn', 'Empty result!');
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                addFlashMessage('warn', `Ajax request failed.<br>${errorThrown}`);
            }
        });
    });


    $(".save-action").on("click", function (event) {
        let button = $(event.target);
        let puzzle = button.data('puzzle');
        let part = button.data('part');
        let textBox = $('#part' + part);
        let value = textBox.val();

        $.ajax({
            url: '/save/solution',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                puzzle: puzzle,
                part: part,
                value: value
            },
            success: function (data, status) {
                textBox.removeClass('updated');
                if (data['error']) {
                    addFlashMessage('warn', 'error:<br>' . data['message']);
                } else {
                    addFlashMessage('success', 'saved');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                addFlashMessage('warn', `Ajax request failed.<br>${errorThrown}`);
            }
        });
    });

    $(".test-action").on("click", function (event) {
        let button = $(event.target);
        let day = button.data('day');
        let part = button.data('part');
        let puzzle = button.data('puzzle');

        $.ajax({
            url: '/test/day/' + day,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                day: day,
                part: part,
                puzzle: puzzle,
            },
            success: function (data, status) {
                let result = data['part'+part];
                if (result['equal'] === true) {
                    addFlashMessage('success', `OK!`);
                } else {
                    addFlashMessage('warn', `Result doesn't match<br>${result['value']}`);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                addFlashMessage('warn', `Ajax request failed.<br>${errorThrown}`);
            }
        });
    });

});
