<template>
    <div class="flex">
        <div class="text-white mr-2 hidden lg:block">{{ price_formatted }}</div>
        <div class="navigation--cart-count bg-gray-50 text-default rounded-full w-[20px] h-[20px] flex items-center justify-center">{{ count }}</div>
    </div>
</template>

<script>
import cart from "../cart";

export default {
    name: "CartIcon",
    props: {
        symbol: String
    },
    data: () => {
        return {
            cart: cart,
        };
    },
    computed: {
        count() {
            return this.cart.getCount();
        },
        price_formatted() {
            return this.cart.getPriceFormatted();
        }
    },
    created() {
        cart.update();
    },
}
</script>

<style scoped>

</style>
