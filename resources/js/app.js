window.$ = window.jQuery = require('jquery');

require('./bootstrap');
require('alpinejs');
require('select2');
import 'flowbite';

/* DATEPICKER SCRIPT */
import Datepicker from '@themesberg/tailwind-datepicker/js/Datepicker.js';
import DateRangePicker from '@themesberg/tailwind-datepicker/js/DateRangePicker';

const datepickers = document.querySelectorAll('[datepicker=""]');

[...datepickers].map(n => {
    new Datepicker(n, {
        format: 'dd/mm/yyyy',
        autohide: true,
    });
})

const dateRangePickers = document.querySelectorAll('[date-rangepicker=""]');
[...dateRangePickers].map(n => {
    new DateRangePicker(n, {
        format: 'dd/mm/yyyy',
        autohide: true,
    });
})
/* DATEPICKER SCRIPT */

/* SELECT2 SCRIPT */
$('.select2').select2();
$('.select2').on('select2:select', function (e) {
    var selectedEnrollmentId = e.target.value;
    var amount = $(`#${selectedEnrollmentId}`).data('amount');

    var display = $('#show-amount');
    display.show();
    display.find('.amount-data').html(`<span class='amount-number'>${amount}</span><span class='amount-text'> ficha(s)</span>`);
});
/* SELECT2 SCRIPT */

/* PROFILE PICTURE SCRIPT */
var fileField = $('#profile-photo');
var base64Field = $('#profile-photo-base64');
var imageOutput = $('#profile-image-output');

fileField.on('change', (e) => {
    var file = e.target.files[0];

    var reader = new FileReader();
    reader.readAsBinaryString(file);

    reader.onload = (e) => {
        var convertedImg = `data:image/jpeg;base64,${btoa(reader.result)}`
        base64Field.val(convertedImg);
        imageOutput.attr("src",convertedImg);
    };
})
/* PROFILE PICTURE SCRIPT */