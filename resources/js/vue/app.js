const { max } = require('lodash');

require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('price-range', require('./components/PriceRange.vue').default);
Vue.component('add-to-cart', require('./components/AddToCart.vue').default);
Vue.component('mini-cart', require('./components/MiniCart.vue').default);
Vue.component('cart-icon', require('./components/CartIcon.vue').default);
Vue.component('cart-page', require('./components/CartPage.vue').default);
Vue.component('shipping-and-payment', require('./components/ShippingAndPayment.vue').default);
Vue.component('v-navigation', require('./components/VNavigation.vue').default);

Vue.prototype.$loadingTime = 0;

const app = new Vue({
    el: '#app',
});

// Load more product list logic
(() => {
    const $loadMoreButton = document.querySelector('#load-more');
    if (!$loadMoreButton) return;

    const $loadMoreIcon = $loadMoreButton.querySelector('svg');
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

// Accordion logic
(() => {
    const $accordionTriggers = document.querySelectorAll('[data-accordion-content-id]');

    $accordionTriggers.forEach($trigger => {
        $trigger.addEventListener('click', event => {
            event.preventDefault();
            $trigger.classList.toggle('active');
            const $accordionContent = document.querySelector(`#${$trigger.getAttribute('data-accordion-content-id')}`);
            const $accordionContentInner = $accordionContent.querySelector('.accordion-content__inner');
            $accordionContent.style.height = $accordionContent.clientHeight === 0 ? `${$accordionContentInner.clientHeight}px` : '0px';
        });
    });
})();

// Newsletter logic
(() => {
    const $form = document.querySelector('.section--newsletter form');
    if (!$form) return;

    $form.addEventListener("submit", event => {
        event.preventDefault();

        const $button = $form.querySelector("button");
        const $loadingIcon = $button.querySelector('svg');
        const email = $form.querySelector("input[name='email']");

        $button.disabled = true;
        $loadingIcon.classList.remove('hidden');
    });
})();

// Category slider logic
(() => {
    const $slider = document.querySelector('.category-list--container');
    const $arrowLeft = document.querySelector('.category-list--arrow-left');
    const $arrowRight = document.querySelector('.category-list--arrow-right');
    if (!$slider) return;

    const updateArrows = () => {
        const visibleWidth = $slider.scrollWidth > window.innerWidth
            ? $slider.scrollWidth + (window.innerWidth - $slider.scrollWidth) / 2
            : $slider.scrollWidth;
        const allPages = Math.ceil($slider.scrollWidth / visibleWidth);
        const currentPage = Number($slider.getAttribute('data-page') || 0);
        const isLastPage = currentPage === allPages - 1;
        isLastPage ? $slider.setAttribute('data-last-page', true) : $slider.removeAttribute('data-last-page');
    }

    const scroll = (left = 0) => {
        const maxScroll = $slider.clientWidth - $slider.scrollWidth;
        const currentPage = Number($slider.getAttribute('data-page') || 0);
        const newPage = left ? Math.max(0, currentPage - 1) : currentPage + 1;

        $slider.setAttribute('data-page', newPage);
        $slider.style.transform = `translateX(max(-${newPage * 100}%, ${maxScroll}px))`;
        updateArrows();
    }

    updateArrows();
    $arrowLeft.addEventListener('click', () => scroll(1));
    $arrowRight.addEventListener('click', () => scroll());
})();