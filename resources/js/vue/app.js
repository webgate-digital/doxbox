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
Vue.component('v-mobile-navigation', require('./components/VMobileNavigation.vue').default);

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

    document.addEventListener('scroll', () => {
        const $loadMoreButtonRect = $loadMoreButton.getBoundingClientRect();
        if ($loadMoreButtonRect.top < window.innerHeight && $productContainer.dataset.loading !== 'true' && $productContainer.dataset.lastPage !== 'true') {
            $loadMoreButton.click();
        }
    });

    $loadMoreButton.addEventListener("click", () => {
        $loadMoreIcon.classList.remove('hidden');
        $productContainer.dataset.loading = true;
        const $currentPage = $loadMoreButton.getAttribute('data-page');
        const currentUrl = window.location.href;

        const url = new URL(currentUrl);
        url.searchParams.set('page', parseInt($currentPage) + 1);

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = () => {
            const response = JSON.parse(xhr.response);
            $productContainer.innerHTML += response.html
            $loadMoreButton.setAttribute('data-page', parseInt($currentPage) + 1);
            !response.hasMoreProducts && $loadMoreButton.remove();
            $loadMoreIcon.classList.add('hidden');
            $productContainer.dataset.loading = false;
            $productContainer.dataset.lastPage = !response.hasMoreProducts;
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
        const $message = $form.querySelector('.message');
        const $email = $form.querySelector("input[name='email']");
        const $csrf = $form.querySelector("input[name='_token']");

        $button.disabled = true;
        $email.disabled = true;
        $loadingIcon.classList.remove('hidden');

        fetch($form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $csrf.value
            },
            body: JSON.stringify({
                email: $email.value
            })
        })
            .then(response => response.json())
            .then(data => {
                const status = data.status;

                status !== 'success' && ($button.disabled = $email.disabled = false);
                $loadingIcon.classList.add('hidden');
                $message.innerHTML = data.message;
            });
    });
})();

// Category slider logic
/* (() => {
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
})(); */

// Search bar logic
(() => {
    document.addEventListener('DOMContentLoaded', () => {
        const $openSearch = document.querySelectorAll('.open-search');
        const $wrapper = document.querySelector('#search-wrapper');
        $openSearch.forEach($element => {
            $element.addEventListener('click', () => {
                $wrapper.classList.add('opened');
                $wrapper.querySelector('input').focus();
            });
        });
    });
})();

// Attribute block accordion logic
(() => {
    document.addEventListener('DOMContentLoaded', () => {
        const $attributeBlocks = document.querySelectorAll('.attribute-block');

        $attributeBlocks.forEach($block => {
            const $valuesBlock = $block.querySelector('.attribute-block__values');
            const $showMore = $block.querySelector('.filter-bar--show-more');
            const $title = $block.querySelector('.attribute-block__title');
            const maxHeight = $valuesBlock.clientHeight + 20;

            const updateHeight = () => {
                $valuesBlock.style.height = $block.classList.contains('opened') ? `${maxHeight}px` : `0`;
            };

            $title.addEventListener('click', () => {
                $block.classList.toggle('opened');
                updateHeight();
            });

            $showMore?.addEventListener('click', () => {
                $showMore.classList.contains('active')
                    ? $valuesBlock.style.height = `auto`
                    : $valuesBlock.style.height = `${maxHeight}px`;
            });

            updateHeight();
        });
    });
})();

// Bread crumbs logic
document.addEventListener('DOMContentLoaded', () => {
    const isMobile = window.matchMedia("only screen and (max-width: 768px)").matches;
    if (isMobile) {
        const navigationBar = document.querySelector('#navigationBar');
        const breadcrumbs = navigationBar.querySelectorAll('.breadcrumbs--item');
        if (breadcrumbs.length > 7) {
            document.querySelector('#ellipsis').style.display = "block";
            document.querySelector('#ellipsisSeparator').style.display = "block";
            for (let i = 1; i < breadcrumbs.length - 6; ++i) {
                breadcrumbs[i].style.display = 'none';
            }
        }
    }
});
