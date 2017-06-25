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

Vue.component('create-workout', require('./components/CreateWorkout.vue'));
Vue.component('list-workouts', require('./components/ListWorkouts.vue'));
Vue.component('show-workout', require('./components/ShowWorkout.vue'));

if (typeof window.locale == 'undefined') window.locale = 'sv';
window.lang = require('../../lang/' + window.locale + '.json');

const app = window.app = new Vue({
    el: '#app',

    methods: {


       /* mapInit() {
            let map;
            let infowindow;
            let service;


            google.maps.event.addDomListener(window, 'load', function () {

            });
        }*/
    }
});
