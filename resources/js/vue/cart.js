import axios from "axios";

export default {
    price: 0.00,
    price_formatted: "",
    count: 0,
    items: {},
    free_shipping_allowed: false,
    free_shipping_formatted: '',
    free_shipping: 0,

    getItems() {
        return this.items;
    },

    getCount() {
        return this.count;
    },

    getPrice() {
        return this.price;
    },

    getPriceFormatted() {
        return this.price_formatted;
    },

    async addItem(uuid, quantity) {
        await axios.post('/cart/add', {uuid: uuid, quantity: quantity})
            .then(response => {

            })
            .catch((err) => {
                throw err;
            });
    },
    async removeItem(uuid) {
        await axios.delete('/cart/remove/', {data: {uuid: uuid}})
            .then(response => {
            })
            .catch((err) => {
                throw err;
            });
    },
    async deleteItem(uuid) {
        await axios.delete('/cart/delete/', {data: {uuid: uuid}})
            .then(response => {
            })
            .catch((err) => {
                throw err;
            });
    },
    async update() {
        await axios.get('/cart/update')
            .then(response => {
                this.count = response.data.count;
                this.items = response.data.items;
                this.price = response.data.price;
                this.price_formatted = response.data.price_formatted;
                this.free_shipping_allowed = response.data.free_shipping_allowed;
                this.free_shipping_formatted = response.data.free_shipping_formatted;
                this.free_shipping = response.data.free_shipping;

                let el = document.getElementById('total-price-cart-formatted');
                let el2 = document.getElementById('total-price-cart-formatted-2');
                let free_shipping_bar = document.getElementById('free-shipping-bar');
                let free_shipping_bar_price = document.getElementById('free-shipping-bar-price');

                if (el) {
                    el.innerText = this.price_formatted;
                }

                if (el2) {
                    el2.innerText = this.price_formatted;
                }

                if (free_shipping_bar_price) {
                    free_shipping_bar_price.innerText = this.free_shipping_formatted;
                }

                if (!this.free_shipping_allowed || this.free_shipping < 0) {
                    if (free_shipping_bar) {
                        free_shipping_bar.style.display = 'none';
                    }
                }

                if (this.free_shipping_allowed && this.free_shipping >= 0) {
                    if (free_shipping_bar) {
                        free_shipping_bar.style.display = 'block';
                    }
                }

            })
            .catch((err) => {
                throw err;
            });
    }
}
