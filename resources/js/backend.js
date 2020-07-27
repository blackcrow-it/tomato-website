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
