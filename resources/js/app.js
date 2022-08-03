require('./bootstrap');

require('alpinejs');

import Datepicker from '@themesberg/tailwind-datepicker/js/Datepicker.js';

const datepickerEl = document.getElementById('datepickerId');
debugger
new Datepicker(datepickerEl, {
    // options
});