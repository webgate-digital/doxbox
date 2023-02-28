<template>
    <div>
        <div class="product-detail--swatches">
            <div v-for="([variantName, variantTree]) in Object.entries(item.variants_tree)" :key="variantName">
                <div class="product-detail--swatch">
                    <div class="text-subheading-m">
                        {{ variantName }}
                    </div>
                    <div class="flex gap-4">
                        <div v-for="([variantValueName]) in Object.entries(variantTree)"
                            :key="variantValueName">
                            <div v-if="variantName === 'Farba'"
                                :data-tippy-content="`${variantName}: ${variantValueName}`" :class="{
                                    variant: true,
                                    disabled: isVariantDisabled(variantName, variantValueName),
                                    selected: variantSelection[variantName] === variantValueName
                                }"
                                class="select-none rounded-full h-16 w-16 cursor-pointer circle"
                                :style="{
                                    backgroundColor: getHexColorByColorName(variantValueName),
                                }" @click="setVariant(variantName, variantValueName)">
                            </div>
                            <div v-else :data-tippy-content="`${variantName}: ${variantValueName}`"
                                class="select-none py-3 px-6 rounded-lg cursor-pointer rectangle" :class="{
                                    variant: true,
                                    disabled: isVariantDisabled(variantName, variantValueName),
                                    selected: variantSelection[variantName] === variantValueName
                                }" @click="setVariant(variantName, variantValueName)">
                                {{ variantValueName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="mt-4" :style="{ visibility: isCleared ? 'hidden' : 'visible' }">
            <span class="text-primary cursor-pointer" @click="resetVariants">
                {{ translations['Resetovať'] }}
            </span>
        </div> -->
        <template v-if="!selectedProduct.count && selectedProduct.is_available_for_order && selectedProduct.order_availability && selectedProduct.order_availability !== ''">
            <div class="mt-8">
                {{ selectedProduct.order_availability }}
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
            <button class="button button--primary rounded-xl ml-16 !w-auto !px-16 flex-grow md:flex-grow-0 add-to-cart-button"
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
        translations: Object,
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
        this.resetVariants();

        // If this is a variable product, set the cheapest available variant as the default selection
        if (this.isVariableProduct) {
            const combinations = this.variantValueCombinations;
            const cheapestAvailableVariation = combinations.reduce((accumulator, current) => {
                const accumulatorProduct = this.getProductByCombination(accumulator);
                const currentProduct = this.getProductByCombination(current);

                const isCurrentProductAvailable = currentProduct && this.isProductAvailable(currentProduct);
                if (!isCurrentProductAvailable) {
                    return accumulator;
                }

                const isCurrentProductCheaper = Number(currentProduct.retail_price_discounted) < Number(accumulatorProduct.retail_price_discounted);
                return isCurrentProductCheaper ? current : accumulator;
            }, combinations[0]);

            this.variantKeys.forEach((variantKey, index) => Vue.set(this.variantSelection, variantKey, cheapestAvailableVariation[index]));
        }
    },
    mounted() {
        this.updatePrice();
    },
    computed: {
        selectedProduct() {
            const selectedCombination = Object.values(this.variantSelection);
            return this.isVariableProduct && selectedCombination.every(value => value) && selectedCombination.length === this.variantKeys.length
                ? this.getProductByCombination(selectedCombination)
                : this.item;
        },
        isAddToCartDisabled() {
            return this.loading || this.error || !this.isProductAvailable(this.selectedProduct);
        },
        products() {
            return [this.item, ...this.item.variants];
        },
        variantKeys() {
            return Object.keys(this.item.variants_tree);
        },
        variantValueCombinations() {
            return this.cartesianProduct(...this.variantKeys.map(variantKey => Object.keys(this.item.variants_tree[variantKey])));
        },
        isVariableProduct() {
            return this.variantKeys.length > 0;
        },
        isCleared() {
            return Object.values(this.variantSelection).every(value => value === null);
        },
        hasSelectedAllVariants() {
            return Object.values(this.variantSelection).every(value => value !== null);
        },
    },
    methods: {
        isVariantDisabled(variantName, variantValueName) {

            // If this is a variant that is already selected, it is not disabled (so it can be deselected)
            if (this.variantSelection[variantName] === variantValueName) {
                return false;
            }

            // Check if after selecting this variant, there is still a product available
            const selection = {
                ...this.variantSelection,
                [variantName]: variantValueName
            };

            const product = this.hasSelectedAllVariants
                ? this.getProductByCombination(Object.values(selection))
                : this.item;
            return product ? !this.isProductAvailable(product) : true;
        },
        isProductAvailable(product) {
            return product.count > 0 || product.is_available_for_order == 1;
        },
        cartesianProduct(...a) {
            const result = a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())))
            return typeof result[0] === 'string' ? result.map(item => [item]) : result;
        },
        addQuantity() {
            this.quantity++;
        },
        removeQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        isProductAVariant(product) {
            return product.uuid !== this.item.uuid;
        },
        async addToCart() {
            if (this.isAddToCartDisabled) {
                return;
            }
            if (!this.loading) {
                this.loading = true;
                this.error = false;
                try {
                    const method = this.isVariableProduct ? 'addVariant' : 'addItem';
                    await cart[method](this.selectedProduct.uuid, this.quantity);
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
                'Modrá': '#0000FF',
            }

            return map[colorName] ?? null;
        },
        setVariant(variantName, variantValueName) {
            if (this.isVariantDisabled(variantName, variantValueName)) {
                return;
            }

            let newVariantSelection = {
                ...this.variantSelection,
                [variantName]: variantValueName
            };

            this.variantSelection = newVariantSelection;
        },
        getProductByCombination(combination) {
            let item = this.item.variants_tree;
            this.variantKeys.forEach((variantKey, index) => item = item[variantKey][combination[index]]);
            return this.products.find(product => product.uuid === item.uuid);
        },
        updatePrice() {
            const $price = document.querySelector('.product-detail--price');
            if ($price && this.selectedProduct) {
                const {
                    retail_price_formatted: oldPrice,
                    retail_price_discounted_formatted: newPrice
                } = this.selectedProduct;
                $price.innerHTML = oldPrice === newPrice
                    ? $price.innerHTML = newPrice
                    : `<span class="text-success">${newPrice}</span>
                       <span class="product-detail--price-old">${oldPrice}</span>`;
            }
        },
        resetVariants() {
            this.variantKeys.forEach(variantKey => Vue.set(this.variantSelection, variantKey, null));
        }
    },
    watch: {
        selectedProduct() {
            this.updatePrice();
        }
    }
}
</script>

<style scoped>
.variant {
    position: relative;
}

.variant.circle {
    width: 44px;
    height: 44px;
}

.variant.circle::before {
    content: "";
    position: absolute;
    inset: 0;
    border: solid 1px #131416;
    border-radius: inherit;
    background-color: white;
    z-index: 1;
}

.variant.circle::after {
    content: "";
    position: absolute;
    inset: 3px;
    z-index: 2;
    background: inherit;
    border-radius: inherit;
}

.variant.circle.selected::before {
    border-width: 3px;
}

.variant.circle:not(.disabled, .selected):hover::before {
    border-width: 2px;
}

.variant.rectangle.selected {
    color: white;
    background-color: #131416;
}

.variant.selected:not(.rounded-full) {
    margin: -1px;
}

.variant.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.variant.rectangle {
    text-align: center;
    border: solid 1px #131416;
    padding: 8px;
    height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.variant.rectangle.disabled {
    opacity: 1;
    color: #D3D4D5;
    border-color: #D3D4D5;
}

.variant.rectangle:not(.disabled):hover {
    border-width: 2px;
    padding: 7px;
}
</style>