import './bootstrap';

import Popper from 'popper.js';
window.Popper = Popper.default;

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'bootstrap';

import Vue from 'vue';
window.Vue = Vue;

import moment from 'moment';
window.moment = moment;

import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);

import Draggable from 'vuedraggable';
Vue.component('draggable', Draggable);

import bootbox from 'bootbox';
window.bootbox = bootbox;
