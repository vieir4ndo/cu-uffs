require('./bootstrap');

require('alpinejs');

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
