<template>
    <div>
        <template v-if="!loading">
            <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8" v-for="item in items">
                <div class="w-full lg:w-1/3 flex flex-row items-center my-8">
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
                <div v-if="item.availableCount" class="w-full lg:w-1/3">
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
                <div v-else class="w-full lg:w-1/3">
                    <p
                        class="text-small">{{
                            translations['Na sklade nie je dostatočný počet kusov.']
                        }}</p>
                </div>
                <div class="w-full lg:w-1/3 flex flex-col items-end mt-4 lg:mt-0">
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
        </template>
        <img src="/images/icons/sync_black_24dp.svg" v-else="loading" class="animate-spin mx-auto my-16">
    </div>
</template>

<script>
import cart from "../cart";

export default {
    name: "CartPage",
    props: {
        productUrl: String,
        translations: Object
    },
    data: () => {
        return {
            cart: cart,
            countError: null,
            activeMultipackAccordion: null,
            loading: true
        };
    },
    watch: {
        items() {
            if (this.count <= 0) {
                location.reload();
            }

            this.loading = false;
        }
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
            await this.cart.removeItem(item.meta.uuid);
            await this.cart.update();
            this.loading = false;
        },
        async removeProduct(item) {
            this.loading = true;
            this.countError = null;
            await this.cart.deleteItem(item.meta.uuid);
            await this.cart.update();
            this.loading = false;
        },
    }
}
</script>

<style scoped>

</style>
