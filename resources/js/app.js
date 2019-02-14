/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Devices from "./components/Devices.vue";
import Tickets from "./components/Tickets.vue";
import PapercutStatuses from "./components/PapercutStatuses.vue";
import Printers from "./components/Printers.vue";
import Resolutions from "./components/Resolutions.vue";

Vue.component("devices", Devices);
Vue.component("tickets", Tickets);
Vue.component("papercut-statuses", PapercutStatuses);
Vue.component("printers", Printers);
Vue.component("resolutions", Resolutions);

const app = new Vue({
    el: "#app"
});

// Master Control Event for mass reloading of clients
Echo.channel("MasterControlEvent").listen(".Reload", e => {
    if (
        e.key == process.env.MIX_MASTER_CONTROL_KEY &&
        process.env.MIX_MASTER_CONTROL_KEY != null
    ) {
        location.reload(true);
    }
});

// Automatically refresh page after 5 hours
let refreshTimeInHours = 5;
setTimeout(function() {
    location.reload(true);
}, 1000 * 60 * 60 * refreshTimeInHours);
