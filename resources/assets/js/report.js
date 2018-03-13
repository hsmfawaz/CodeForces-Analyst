require('./bootstrap');
window.Vue = require('vue');
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    components:{
        report : require('./components/report.vue')
    }
});
