require('./bootstrap');

window.Vue = require('vue');

import VueCharts from 'vue-charts'
Vue.use(VueCharts);

import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyDqH1WEVHKxASl7gzKDzGjfgP5I5IJw7F8',
    libraries: 'places, GmapCluster'
  }
})

import CompteurChart from './components/CompteurChart.vue';
import CompteurMap from './components/CompteurMap.vue';
import JqueryDatepicker from './components/JqueryDatepicker.vue';

var app = new Vue({
    el: '#app',
    data: {
        startDate: '01-01-2018',
        endDate: '31-12-2018',
        //startingDate: '02-01-2018'
    },
    methods: {
        updateDates(startDate, endDate) {
            this.startDate = startDate;
            this.endDate = endDate;
        }
    },
    components: { CompteurChart, CompteurMap, JqueryDatepicker }
});
