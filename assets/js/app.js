/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

$(document).ready(function () {
    $(".solve").on("click", function (event) {
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
                let partId = '#part'+part;
                let textBox = $(partId);
                let result = '';
                if (data['error']) {
                    result = data['message'];
                } else {
                    result = data['part'+part];
                }
                textBox.val(result);
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                alert('Ajax request failed.');
            }
        });
    });
});