
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('devices', require('./components/Devices.vue'));
Vue.component('tickets', require('./components/Tickets.vue'));
Vue.component('papercut-statuses', require('./components/PapercutStatuses.vue'));
Vue.component('printers', require('./components/Printers.vue'));
Vue.component('stats', require('./components/Stats.vue'));

const app = new Vue({
    el: '#app'
});
