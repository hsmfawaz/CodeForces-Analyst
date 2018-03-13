require('./bootstrap');
window.Vue = require('vue');
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    data: {
        Pagename: Window.pagename
    },
    components: {
        home: require('./components/home.vue'),
        navbar: require('./components/layout/navbar.vue'),
        report: require('./components/report.vue'),
        latest: require('./components/latest.vue'),
        pagefooter: require('./components/layout/footer.vue')
    }
});
