<template>
    <div>
        <div class="product-detail--swatches">
            <div v-for="([variantName, variantTree]) in Object.entries(variants_tree)" :key="variantName">
                <div class="product-detail--swatch">
                    <div class="text-subheading-m">
                        {{ variantName }}
                    </div>
                    <div class="flex gap-4">
                        <div v-for="([variantValueName, variant]) in Object.entries(variantTree).reverse()"
                            :key="variantValueName">
                            <div v-if="variantName === 'Farba'"
                                :data-tippy-content="`${variantName}: ${variantValueName}`"
                                :class="{ disabled: isVariantDisabled(variantName, variantValueName) }"
                                class="border rounded-full h-16 w-16 cursor-pointer" :style="{
                                    backgroundColor: getHexColorByColorName(variantValueName),
                                    opacity: (variantSelection[variantName] === variantValueName) ? 1 : 0.5
                                }" @click="setVariant(variantName, variantValueName)"></div>
                            <div v-else :data-tippy-content="`${variantName}: ${variantValueName}`"
                                class="border opacity-50 hover:opacity-100 py-3 px-6 rounded-lg cursor-pointer"
                                :class="{ disabled: isVariantDisabled(variantName, variantValueName) }" :style="{
                                    opacity: (variantSelection[variantName] === variantValueName) ? 1 : 0.5
                                }" @click="setVariant(variantName, variantValueName)">
                                {{ variantValueName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-if="item.order_availability && item.order_availability !== ''">
            <div class="mb-4 mt-8">
                {{ item.order_availability }}
            </div>
        </template>
        <div class="flex items-center mb-4 mt-8">
            <button type="button"
                class="button border border-primary aspect-square !w-16 !h-16 !p-0 rounded-lg text-primary !text-xl"
                @click="removeQuantity">
                -
            </button>
            <input type="text" class="form--input !w-16 !h-16 p-0 border-none text-center" v-model="quantity">
            <button type="button"
                class="button border border-primary aspect-square !w-16 !h-16 !p-0 rounded-lg text-primary !text-xl"
                @click="addQuantity">
                +
            </button>
            <button class="button button--primary rounded-xl ml-16 !w-auto !px-16 flex-grow md:flex-grow-0"
                :class="{ disabled: isAddToCartDisabled }" @click="addToCart()">
                <template v-if="!loading && !error">
                    {{ translations['Do košíka'] }}
                </template>
                <template v-if="loading">
                    <img src="/images/icons/sync_white_24dp.svg" class="animate-spin mx-auto"
                        :alt="translations['Do košíka']">
                </template>
                <template v-if="error">
                    <img src="/images/icons/warning_white_24dp.svg" class="mx-auto"
                        :alt="translations['Na sklade nie je dostatočný počet kusov']">
                </template>
            </button>
        </div>
        <p v-if="error" class="text-small"> {{ translations['Na sklade nie je dostatočný počet kusov'] }} </p>
    </div>
</template>

<script>
import cart from "../cart";

export default {
    name: "AddToCart",
    props: {
        variants_tree: Object,
        translations: Object,
        variants: Array,
        item: Object,
    },
    data: () => {
        return {
            loading: false,
            error: false,
            variantSelection: {},
            quantity: 1
        }
    },
    created() {
        Object.keys(this.variants_tree).forEach(key => {
            this.variantSelection[key] = null;
        });
    },
    computed: {
        _uuid() {
            const isSelectedVariant = Object.values(this.variantSelection).some(value => value !== null);

            if (!isSelectedVariant) {
                return this.item.uuid;
            }

            let variantTemp = { ...this.variants_tree };
            Object.entries(this.variantSelection).forEach(([variantName, variantValueName]) => {
                if (variantValueName) {
                    variantTemp = variantTemp[variantName][variantValueName];
                }
            });

            return variantTemp.uuid || this.uuid;
        },
        selectedProduct() {
            const uuid = this._uuid ?? this.item.uuid;
            const product = [this.item, ...this.variants].find(product => product.uuid === uuid);
            return product;
        },
        isAddToCartDisabled() {
            const numberOfSelectedVariants = Object.values(this.variantSelection).filter(value => value !== null).length;
            return (this.item.count == 0 && this.item.is_available_for_order == 0)
                || (numberOfSelectedVariants > 0 && numberOfSelectedVariants !== Object.keys(this.variants_tree).length);
        }
    },
    methods: {
        isVariantDisabled(variantName, variantValueName) {

            // If this is a variant that is already selected, it is not disabled (so it can be deselected)
            if (this.variantSelection[variantName] === variantValueName) {
                return false;
            }

            // If all variants are selected, it is disabled
            if (Object.values(this.variantSelection).every(value => value !== null)) {
                return true;
            }

            let variantTemp = { ...this.variants_tree };
            Object.entries(this.variantSelection)
                .filter(([_, tempVariantValueName]) => tempVariantValueName !== null)
                .forEach(([tempVariantName, tempVariantValueName]) => {
                    if (tempVariantValueName) {
                        variantTemp = variantTemp[tempVariantName][tempVariantValueName];
                    }
                });

            const uuid = variantTemp.uuid ?? (variantTemp[variantName] ? variantTemp[variantName][variantValueName].uuid : null);

            if (uuid) {
                const product = this.variants.find(variant => variant.uuid === uuid);
                return !this.isProductAvailable(product);
            }

            return false;
        },
        isProductAvailable(product) {
            return product.count > 0 || product.is_available_for_order == 1;
        },
        cartesianProduct(...a) {
            return a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
        },
        addQuantity() {
            this.quantity++;
        },
        removeQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        async addToCart() {
            if (this.isAddToCartDisabled) {
                return;
            }
            if (!this.loading) {
                this.loading = true;
                this.error = false;
                try {
                    await cart.addItem(this._uuid, this.quantity);
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
        },
        getHexColorByColorName(colorName) {
            const map = {
                "Oranžová": "#F6A609",
                "Červená": "#FB4E4E",
                "Zelená": "#2AC769",
                "Biela": "#FFFFFF",
                "Čierna": "#000000",
                "Žltá": "#F9E600",
                "Lime Green": "#00FF00",
                "Ružová": "#FF00FF",
                "Sky Blue": "#87CEEB",
            }

            return map[colorName] ?? null;
        },
        setVariant(variantName, variantValueName) {
            if (this.isVariantDisabled(variantName, variantValueName)) {
                return;
            }

            let newVariantSelection = { ...this.variantSelection };

            newVariantSelection[variantName] = this.variantSelection[variantName] === variantValueName
                ? null
                : newVariantSelection[variantName] = variantValueName;

            this.variantSelection = newVariantSelection;
        }
    },
    watch: {
        selectedProduct() {
            const $price = document.querySelector('.product-detail--price');
            if ($price) {
                const oldPrice = this.selectedProduct.retail_price_formatted;
                const newPrice = this.selectedProduct.retail_price_discounted_formatted;
                if (oldPrice !== newPrice) {
                    $price.innerHTML = `${newPrice} <span class="product-detail--price-old">${oldPrice}</span>`;
                } else {
                    $price.innerHTML = newPrice;
                }
            }
        }
    }
}
</script>

<style scoped>
.disabled {
    opacity: 0.1 !important;
    cursor: not-allowed;
}
</style>