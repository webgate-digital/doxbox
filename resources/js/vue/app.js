require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('price-range', require('./components/PriceRange.vue').default);
Vue.component('add-to-cart', require('./components/AddToCart.vue').default);
Vue.component('mini-cart', require('./components/MiniCart.vue').default);
Vue.component('cart-icon', require('./components/CartIcon.vue').default);
Vue.component('cart-page', require('./components/CartPage.vue').default);
Vue.component('shipping-and-payment', require('./components/ShippingAndPayment.vue').default);

Vue.prototype.$loadingTime = 0;

const app = new Vue({
    el: '#app',
});
