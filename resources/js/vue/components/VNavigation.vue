<template>
    <div class="main-nav">
        <div class="container">
            <ul class="main-nav--menu">
                <li v-for="item in rootItems" :key="item.uuid">
                    <a :href="item.url">
                        {{ item.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {
    name: 'VNavigation',
    props: {
        items: {
            type: Array,
            required: true
        }
    },
    data: () => {
        return {
            active: false
        }
    },
    computed: {
        rootItems() {
            return this.items.filter(item => !item.has_parent)
        }
    },
    mounted() {
        console.log(this.items)
    }
}
</script>
