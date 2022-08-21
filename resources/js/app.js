window.$ = window.jQuery = require('jquery');

require('./bootstrap');
require('alpinejs');
require('select2');
import 'flowbite';

///////////////////////
// DATEPICKER SCRIPT //
///////////////////////

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

$('.select2').select2();
$('.select2').on('select2:select', function (e) {
    var selectedEnrollmentId = e.target.value;
    var amount = $(`#${selectedEnrollmentId}`).data('amount');

    var display = $('#show-amount');
    display.show();
    display.find('.amount-data').html(`<span class='amount-number'>${amount}</span><span class='amount-text'> ficha(s)</span>`);
  });