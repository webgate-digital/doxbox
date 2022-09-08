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

// Add load more product list logic
(() => {
    const $loadMoreButton = document.querySelector('#load-more');
    const $loadMoreIcon = $loadMoreButton.querySelector('svg');
    const $loadMoreText = $loadMoreButton.querySelector('span');
    const $productContainer = document.querySelector('.product-container');
    $loadMoreButton.addEventListener("click", () => {
        $loadMoreIcon.classList.remove('hidden');
        const $currentPage = $loadMoreButton.getAttribute('data-page');
        const currentUrl = window.location.href;

        const url = new URL(currentUrl);
        url.searchParams.set('page', parseInt($currentPage) + 1);

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = () => {
            $productContainer.innerHTML += xhr.response;
            $loadMoreButton.setAttribute('data-page', parseInt($currentPage) + 1);
            xhr.response.length === 0 && $loadMoreButton.remove();
            $loadMoreIcon.classList.add('hidden');
        };

        xhr.send();
    });
})();