<template>
    <div class="text-center my-4" v-if="loading">
        <img src="/images/icons/sync_black_24dp.svg" class="animate-spin mx-auto">
    </div>
    <div class="flex flex-wrap -mx-4" v-else>
        <div class="w-full lg:w-1/2 px-4">
            <!--            Shipping country-->
            <h2 class="h2">{{ translations['cart.shipping_country_title'] }}
            </h2>
            <div class="list-item cursor-pointer" v-for="item in shippingCountries"
                 @click="changeShippingCountry(item.uuid)">
                <input :id="item.uuid + '_default_shipping_country'" type="radio" class="list-item--radio"
                       v-model="shippingCountries[shippingCountry].uuid" :value="item.uuid">
                <label :for="item.uuid + '_default_shipping_country'">
                    <b>{{ item.name }}</b>
                </label>
            </div>
            <!--            Shipping country END-->
            <!--            Shipping-->
            <h2 class="h2 mt-8">{{ translations['cart.choose_shipping_title'] }}
            </h2>
            <div class="list-item cursor-pointer" v-for="item in shippingTypes" @click="selectShipping(item.uuid)">
                <input type="radio" v-model="shippingType" :value="item.uuid" :id="item.uuid + '_shipping'"
                       class="list-item--radio">
                <label :for="item.uuid + '_shipping'"
                       class="list-item--label">
                                    <span class="list-item--label---column">
                                        <b>
                                            {{ item.name }}
                                        </b>
                                            <small v-if="item.info"><br>{{ item.info }}</small>
                                            <template
                                                v-if="shippingType && shippingType.includes('PACKETA') && item.uuid.includes('PACKETA') && packetaSelectorBranchName">
                                                <br>
                                            <small>{{ packetaSelectorBranchName }}</small>
                                                </template>
                                            <template
                                                v-if="shippingType && shippingType.includes('ULOZENKA') && item.uuid.includes('ULOZENKA') && ulozenkaBranch">
                                                <br>
                                            <small>{{ ulozenkaBranch }}</small>
                                                </template>
                                            <template
                                                v-if="item.estimated_delivery_time_from && item.estimated_delivery_time_to">
                                                <br>
                                                <small>{{
                                                        translations['cart.estimated_delivery_time_between']
                                                    }} {{ item.estimated_delivery_time_from }} - {{
                                                        item.estimated_delivery_time_to
                                                    }}</small>
                                            </template>

                                    </span>
                    <b class="list-item--label---column">
                        <template
                            v-if="item.price && (cart.getFreeShipping() >= 0 || !cart.getFreeShippingAllowed())">{{
                                item.price_formatted
                            }}
                        </template>
                        <template v-else>{{ translations['cart.free_price'] }}</template>
                    </b>
                </label>
                <!--                <template v-if="item.uuid.includes('ULOZENKA')">-->
                <!--                    <div class="text-center" id="ulozenka-branch-loader"-->
                <!--                         style="display: none; margin-bottom: .5rem;">-->
                <!--                        <i class="fas fa-spinner rotating"></i>-->
                <!--                    </div>-->
                <!--                    <small style="display: block; margin-bottom: .5rem"-->
                <!--                           v-if="ulozenkaBranches.length">{{ translations['cart.cta_choose'] }}</small>-->
                <!--                    <select style="margin-bottom: .5rem" v-model="ulozenkaBranch" v-if="ulozenkaBranches.length">-->
                <!--                        <option v-for="ulozenkaBranchItem in ulozenkaBranches" :value="ulozenkaBranchItem[0]">-->
                <!--                            {{ ulozenkaBranchItem[1] }}-->
                <!--                        </option>-->
                <!--                    </select>-->
                <!--                </template>-->
            </div>
            <!--            Shipping END-->
        </div>
        <div class="w-full lg:w-1/2 px-4">
            <h2 class="h2 mt-8 lg:mt-0" v-if="!shippingType">
                {{ translations['cart.choose_payment_title_first'] }}</h2>
            <h2 class="h2 mt-8 lg:mt-0" v-else>
                {{ translations['cart.choose_payment_title'] }}</h2>
            <div class="text-center my-4" v-if="paymentLoading">
                <img src="/images/icons/sync_black_24dp.svg" class="animate-spin mx-auto">
            </div>
            <template v-else>
                <div class="list-item cursor-pointer" v-for="item in paymentTypes" @click="selectPayment(item.uuid)">
                    <input type="radio" v-if="shippingType" v-model="paymentType" :value="item.uuid" class="list-item--radio"
                           :id="item.uuid + '_payment'"
                           >
                    <label :for="item.uuid + '_payment'"
                           :class="{'opacity-50': !shippingType}"
                           class="flex justify-between w-full -mx-2 items-center">
                                    <span class="list-item--label---column">
                                    <span v-html="item.name"></span>
                                    <template v-if="item.info">
                                        <br>
                                        <small>{{ item.info }}</small>
                                    </template>
                                        </span>
                        <b class="list-item--label---column">
                                    <template
                                        v-if="item.price">{{
                                            item.price_formatted
                                        }}</template>
                                    <template v-else>{{ translations['cart.free_price'] }}</template>
                                </b>
                    </label>
                </div>
            </template>
            <div class="text-center my-4" v-if="tocLoading">
                <img src="/images/icons/sync_black_24dp.svg" class="animate-spin mx-auto">
            </div>
            <template v-else>
                <div class="flex gap-1" v-if="shippingType && paymentType && !paymentLoading">
                    <input class="flex-shrink-0 !w-8 !h-8 ml-4 mr-2" v-model="toc" type="checkbox" id="toc">
                    <label class="" :class="{'text-danger underline': tocError}" for="toc">
                        <small class="block">
                            {{ translations['cart.gdpr_accept_start'] }}
                            <a :href="tocLink" target="_blank"
                               class="text-secondary"><b>{{ translations['cart.gdpr_accept_toc'] }}</b></a>
                            {{ translations['cart.gdpr_accept_middle'] }}
                            <a :href="gdprLink" target="_blank"
                               class="text-secondary"><b>{{ translations['cart.gdpr_accept_self'] }}</b></a>.
                        </small>
                    </label>
                </div>
                <div class="text-right" v-if="shippingType && paymentType">
                    <button type="button" @click="checkout"
                            class="button button--primary button--inline mt-4">
                        <span>{{ translations['cart.cta_continue_to_checkout'] }}</span>
                    </button>
                </div>
            </template>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import cart from "../cart";

export default {
    name: "ShippingAndPayment",
    props: {
        translations: Object,
        initUrl: String,
        shippingCountryUrl: String,
        shippingUrl: String,
        paymentUrl: String,
        continueToCheckoutUrl: String,
        locale: String,
        tocLink: String,
        gdprLink: String
    },
    watch: {
        ulozenkaBranch: function (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.changeShipping(this.shippingType)
            }
        }
    },
    data: function () {
        return {
            loading: true,
            paymentLoading: false,
            tocLoading: false,
            cart: cart,
            shippingCountries: {},
            shippingCountry: {},
            accordion: false,
            shippingTypes: {},
            shippingType: null,
            packetaSelectorBranchName: null,
            packetaSelectorBranchId: null,
            ulozenkaBranch: null,
            ulozenkaBranches: [],
            paymentTypes: {},
            paymentType: null,
            toc: false,
            tocError: false,
            packetaApiKey: null,
        }
    },
    async mounted() {
        await this.init();
        this.loading = false;
    },
    methods: {
        async init() {
            await axios.get(this.initUrl).then(response => {
                this.shippingCountries = response.data.shippingCountries;
                this.shippingCountry = response.data.shippingCountry;
                this.shippingTypes = response.data.shippingTypes;
                this.shippingType = response.data.shippingType;
                this.packetaSelectorBranchName = response.data.packetaSelectorBranchName;
                this.packetaSelectorBranchId = response.data.packetaSelectorBranchId;
                this.ulozenkaBranch = response.data.ulozenkaBranch;
                this.paymentTypes = response.data.paymentTypes;
                this.paymentType = response.data.paymentType;
                this.packetaApiKey = response.data.packetaApiKey;
            }).catch(error => {

            })
        },
        async changeShippingCountry(uuid) {
            this.loading = true;

            await axios.post(this.shippingCountryUrl, {
                default_shipping_country: uuid
            }).then(async response => {
                await this.init();
                this.loading = false;
            }).catch(error => {

            })
        },
        async selectShipping(uuid) {
            if (!uuid.includes('PACKETA') && !uuid.includes('ULOZENKA')) {
                await this.changeShipping(uuid);
            } else {
                if (uuid.includes('PACKETA')) {
                    Packeta.Widget.pick(this.packetaApiKey, this.showSelectedPickupPoint, {
                        country: this.shippingCountry.toLowerCase(),
                        language: this.locale
                    })
                }

                if (uuid.includes('ULOZENKA')) {
                    this.showUlozenka()
                }
            }
        },
        async changeShipping(uuid) {
            this.paymentLoading = true;

            await axios.post(this.shippingUrl, {
                shipping_type: uuid,
                'packeta-selector-branch-name': this.packetaSelectorBranchName,
                'packeta-selector-branch-id': this.packetaSelectorBranchId,
                'ulozenka-branch': this.ulozenkaBranch
            }).then(async response => {
                await this.init();
                this.paymentLoading = false;
            }).catch(error => {

            })
        },
        async showSelectedPickupPoint(point) {
            if (point) {
                this.packetaSelectorBranchName = point.name;
                this.packetaSelectorBranchId = point.id;
                await this.changeShipping(this.shippingType);
            } else {
                this.loading = true;
                await this.init();
                this.loading = false;
            }
        },
        async selectPayment(uuid) {
            if (this.shippingType) {
                this.tocLoading = true;

                await axios.post(this.paymentUrl, {
                    payment_type: uuid,
                }).then(async response => {
                    await this.init();
                    this.tocLoading = false;
                }).catch(error => {

                })
            }
        },
        async showUlozenka() {
            var response = "";
            var request = new XMLHttpRequest();
            document.getElementById('ulozenka-branch-loader').style.display = 'block';
            request.open("GET", "https://api.ulozenka.cz/v3/transportservices/1/branches?destinationCountry=CZE&shopId=26656", true);
            request.setRequestHeader('Accept', 'application/json')
            request.onreadystatechange = () => {
                if (request.readyState == 4) {
                    if (request.status == 200 || request.status == 0) {
                        response = JSON.parse(request.responseText);
                        var sortable = [];
                        for (var i = 0; i < response.data.destination.length; i++) {
                            sortable.push([response.data.destination[i].id + "-" + response.data.destination[i].name, "" + response.data.destination[i].name + ""])
                        }
                        sortable.sort(function (a, b) {
                            return a[1].localeCompare(b[1]);
                        })

                        this.ulozenkaBranches = sortable;

                    }

                    document.getElementById('ulozenka-branch-loader').style.display = 'none';
                }
            }
            request.send();
        },
        checkout() {
            this.tocError = false;
            if (!this.toc) {
                this.tocError = true;
            } else {
                document.getElementById('order-loading-wrapper').style.display = 'flex';
                axios.post(this.continueToCheckoutUrl, {
                    toc: this.toc
                }).then(response => {
                    window.location.href = response.data;
                }).catch(error => {

                })
            }
        }
    }
}
</script>

<style scoped>

.text-underline {
    text-decoration: underline;
}

</style>
