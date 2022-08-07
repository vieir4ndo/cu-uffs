require('./bootstrap');

require('alpinejs');

import 'flowbite';

///////////////////////
// DATEPICKER SCRIPT //
///////////////////////

import Datepicker from '@themesberg/tailwind-datepicker/js/Datepicker.js';

const datepickerEl = document.getElementById('date');
new Datepicker(datepickerEl, {
    format: 'dd/mm/yyyy',
});
