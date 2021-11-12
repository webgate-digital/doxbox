<template>
    <div>
        <vue-slider :tooltip="'none'" :min="0" :max="maxPrice" v-model="sliderValue"></vue-slider>
        <input type="hidden" name="min_price" :value="sliderValue[0]">
        <input type="hidden" name="max_price" :value="sliderValue[1]">
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
    props: {
        maxPrice: Number,
        price: Array,
        currency: Object
    },
    data: function () {
        return {
            sliderValue: this.price
        }
    },
    components: {
        VueSlider
    },
    watch: {
        sliderValue: function (val) {
            let decimals = '';
            for(let i = 0; i < this.currency.decimals; i++) {
                decimals = decimals + '0';
            }
            let price = ',' + decimals + ' ' + this.currency.symbol;
            document.getElementById('filter_sidebar_max_price').innerText = val[1].toString() + price;
            document.getElementById('filter_sidebar_min_price').innerText = val[0].toString() + price;
        }
    }
}
</script>

<style>

</style>
