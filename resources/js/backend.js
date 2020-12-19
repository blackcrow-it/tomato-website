import './bootstrap';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

import 'bootstrap';

import IMask from 'imask';
window.IMask = IMask;

import Vue from 'vue';
window.Vue = Vue;

require('admin-lte'); // không dùng import được với thằng này, chả hiểu @@

import bsCustomFileInput from 'bs-custom-file-input'
window.bsCustomFileInput = bsCustomFileInput;

import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);

import moment from 'moment';
window.moment = moment;

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
Vue.component('datetimepicker', VueCtkDateTimePicker);

import VueCurrencyInput from 'vue-currency-input'
Vue.use(VueCurrencyInput, {
    globalOptions: {
        currency: null,
        distractionFree: false,
        precision: 0,
        allowNegative: false
    }
});
