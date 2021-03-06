
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


window.Vue = require('vue');

Vue.component('newsletter-form', require('./components/NewsletterForm.vue'));
Vue.component('purchase-job-form', require('./components/PurchaseJobForm.vue'));
Vue.component('type-select-input', require('./components/TypeSelectInput.vue'));
Vue.component('logo-file-input', require('./components/LogoFileInput.vue'));
Vue.component('image-upload', require('./components/ImageUpload.vue'));

const app = new Vue({
    el: '#app'
});
