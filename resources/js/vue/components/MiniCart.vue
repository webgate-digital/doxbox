<template>
    <div id="mini-cart-wrapper" class="" @click.self="close">
        <div id="mini-cart">
            <img src="/images/icons/sync_black_24dp.svg" v-if="loading" class="animate-spin mx-auto" :alt="translations['Do košíka']">
            <template class="" v-else>
                <div class="flex items-center justify-between mb-8">
                    <p class="h3 !mb-0">{{ translations['Váš košík'] }}</p>
                    <a href="javascript:;" @click="close" class="flex items-center"><small>{{
                            translations['Zatvoriť']
                        }}</small> <img
                        src="/images/icons/close_black_24dp.svg" width="15" class="ml-4"
                        :alt="translations['Zatvoriť']"></a>
                </div>
                <div class="flex flex-wrap flex-col mb-8" v-for="item in items">
                    <div class="flex flex-row mb-4">
                        <a :href="productUrl.replace('CATEGORY', item.meta.category_slug).replace('SLUG', item.meta.slug)"
                        >
                            <img :src="item.meta.image" width="50" class="mr-8" :alt="item.meta.name">
                        </a>
                        <div class="">
                            <a class="text-small"
                               :href="productUrl.replace('CATEGORY', item.meta.category_slug).replace('SLUG', item.meta.slug)"
                            >{{
                                    item.meta.name
                                }}</a>
                            <div v-if="item.multipack">
                                <small v-for="mp in item.multipack">
                                    {{ mp.count }} x {{ mp.name }}<br>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center justify-between">
                        <div v-if="item.availableCount" class="w-1/2">
                            <div class="flex">
                                <button type="button" v-if="!item.meta.is_multipack" class="button button--ghost button--sm"
                                        @click="removeFromCart(item)">-
                                </button>
                                <input type="text" disabled readonly class="form--input text-center"
                                       :value="item.availableCount" name="quantity">
                                <button type="button" v-if="!item.meta.is_multipack" class="button button--ghost button--sm"
                                        @click="addToCart(item)">+
                                </button>
                            </div>
                            <p class="text-small" v-if="countError === item.meta.uuid">
                                {{ translations['Na sklade nie je dostatočný počet kusov.'] }}</p>
                        </div>
                        <div v-else class="w-1/2">
                            <p
                                class="text-small">{{
                                    translations['Na sklade nie je dostatočný počet kusov.']
                                }}</p>
                        </div>
                        <div class="flex flex-col">
                            <small>{{ item.meta.price_formatted }} / {{
                                    translations['ks']
                                }}</small>
                            <div class="flex items-center">
                                <b>{{
                                        item.price_formatted
                                    }}</b>
                                <a href="javascript:;" @click="removeProduct(item)" class="">
                                    <img src="/images/icons/close_black_24dp.svg" width="15" class="ml-4"
                                         :alt="translations['Zatvoriť']"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h4 flex items-center justify-between border-t border-grey pt-4 mt-4">
                    <span>{{ translations['Celkom'] }}:</span>
                    {{ price_formatted }}
                </div>
                <div>
                    <a :href="proceedToCartUrl"
                       class="button button--primary mt-4 proceed-to-cart-button">{{ translations['Zobraziť košík'] }}</a>
                </div>
                <div>
                    <a href="javascript:;" @click="close"
                       class="button button--ghost mt-4">{{ translations['Pokračovať v nákupe'] }}</a>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import cart from "../cart";

export default {
    name: "MiniCart",
    props: {
        proceedToCartUrl: String,
        productUrl: String,
        translations: Object
    },
    data: () => {
        return {
            cart: cart,
            countError: null,
            activeMultipackAccordion: null,
            loading: false
        };
    },
    computed: {
        items() {
            return this.cart.getItems();
        },
        price() {
            return this.cart.getPrice();
        },
        price_formatted() {
            return this.cart.getPriceFormatted();
        },
        count() {
            return this.cart.getCount();
        }
    },
    methods: {
        async addToCart(item) {
            this.countError = null;
            this.loading = true;

            try {
                await this.cart.addItem(item.meta.uuid, 1);
            } catch (e) {
                this.countError = item.meta.uuid
            }

            if (!this.countError) {
                await this.cart.update();
            }

            this.loading = false;
        },
        async removeFromCart(item) {
            this.loading = true;
            this.countError = null;
            if (item.meta.hasOwnProperty('is_variant') && item.meta.is_variant) {
                await this.cart.removeVariantItem(item.meta.uuid);
            } else {
                await this.cart.removeItem(item.meta.uuid);
            }
            await this.cart.update();
            this.loading = false;
        },
        async removeProduct(item) {
            this.loading = true;
            this.countError = null;
            if (item.meta.hasOwnProperty('is_variant') && item.meta.is_variant) {
                await this.cart.deleteVariantItem(item.meta.uuid);
            } else {
                await this.cart.deleteItem(item.meta.uuid);
            }
            await this.cart.update();
            this.loading = false;
        },
        close() {
            document.getElementById('mini-cart-wrapper').classList.remove('is-open');
        }
    }
}
</script>

<style scoped>

</style>
