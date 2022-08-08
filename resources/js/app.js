require('./bootstrap');

require('alpinejs');

///////////////////////
// DATEPICKER SCRIPT //
///////////////////////

import Datepicker from '@themesberg/tailwind-datepicker/js/Datepicker.js';

const datepickerEl = document.getElementById('date');
new Datepicker(datepickerEl, {
    format: 'dd/mm/yyyy',
    autohide: true,
});