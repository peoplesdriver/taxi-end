window.Vue = require('vue');

Vue.component('app-display', require('./components/display/index.vue'));

const app = new Vue({
    el: '#app'
});
