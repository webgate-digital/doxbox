<template>
    <div>
        <div class="flex items-center -mx-4 mb-4">
            <div class="w-full lg:w-1/2 px-4">
                <div class="flex">
                    <button type="button" class="button button--ghost button--sm" @click="removeQuantity">-</button>
                    <input type="text" class="form--input text-center" v-model="quantity">
                    <button type="button" class="button button--ghost button--sm" @click="addQuantity">+</button>
                </div>
            </div>
            <div class="w-full lg:w-1/2 px-4">
                <button class="button button--primary" @click="addToCart()">
                    <template v-if="!loading && !error">
                        {{ translations['Do košíka'] }}
                    </template>
                    <template v-if="loading">
                        <img src="/images/icons/sync_white_24dp.svg" class="animate-spin mx-auto" :alt="translations['Do košíka']">
                    </template>
                    <template v-if="error">
                        <img src="/images/icons/warning_white_24dp.svg" class="mx-auto"
                             :alt="translations['Na sklade nie je dostatočný počet kusov']">
                    </template>
                </button>
            </div>
        </div>
        <p v-if="error" class="text-small"> {{ translations['Na sklade nie je dostatočný počet kusov'] }} </p>
    </div>
</template>

<script>
import cart from "../cart";

export default {
    name: "AddToCart",
    props: {
        uuid: String,
        translations: Object
    },
    data: () => {
        return {
            loading: false,
            error: false,
            quantity: 1
        }
    },
    methods: {
        addQuantity() {
            this.quantity++;
        },

        removeQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        async addToCart() {
            if (!this.loading) {
                this.loading = true;
                this.error = false;
                try {
                    await cart.addItem(this.uuid, this.quantity);
                    this.error = false;
                } catch (e) {
                    this.error = true;
                }

                if (!this.error) {
                    await cart.update();
                }

                setTimeout(() => {
                    this.loading = false;
                    if (!this.error) {
                        document.getElementById('mini-cart-wrapper').classList.add('is-open');
                    }
                }, this.$loadingTime)
            }
        }
    }
}
</script>
