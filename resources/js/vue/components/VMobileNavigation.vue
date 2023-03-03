<template>
    <div class="mobile-navigation">
        <div class="mobile-navigation__header"
            onclick="document.getElementById('main-nav--mobile').classList.remove('is-open');">
            <svg width="24" height="24" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M26.7758 24.66C26.9177 24.8008 26.9976 24.9925 26.9976 25.1925C26.9976 25.3925 26.9177 25.5842 26.7758 25.725L25.7258 26.775C25.5849 26.917 25.3932 26.9968 25.1933 26.9968C24.9933 26.9968 24.8016 26.917 24.6608 26.775L18.0008 20.115L11.3408 26.775C11.1999 26.917 11.0082 26.9968 10.8083 26.9968C10.6083 26.9968 10.4166 26.917 10.2758 26.775L9.22575 25.725C9.08377 25.5842 9.00391 25.3925 9.00391 25.1925C9.00391 24.9925 9.08377 24.8008 9.22575 24.66L15.8858 18L9.22575 11.34C9.08377 11.1992 9.00391 11.0075 9.00391 10.8075C9.00391 10.6075 9.08377 10.4158 9.22575 10.275L10.2758 9.22499C10.4166 9.08301 10.6083 9.00314 10.8083 9.00314C11.0082 9.00314 11.1999 9.08301 11.3408 9.22499L18.0008 15.885L24.6608 9.22499C24.8016 9.08301 24.9933 9.00314 25.1933 9.00314C25.3932 9.00314 25.5849 9.08301 25.7258 9.22499L26.7758 10.275C26.9177 10.4158 26.9976 10.6075 26.9976 10.8075C26.9976 11.0075 26.9177 11.1992 26.7758 11.34L20.1158 18L26.7758 24.66Z"
                    fill="white" />
            </svg>
            <span>Zavrieť</span>
        </div>
        <div class="mobile-navigation__body">
            <div>
                <div class="mobile-navigation__item">
                    <div class="mobile-navigation__item__header">
                        <a href="/">{{ translations["breadcrumbs.home"].text }}</a>
                    </div>
                </div>
            </div>
            <template v-for="(item, index) in rootItems">
                <div class="mobile-navigation__item" :class="{active: activeItemIndex === index}">
                    <div class="mobile-navigation__item__header" @click="toggleItem(index)">
                        <template v-if="item.children && item.children.length > 0">
                            <span>{{ item.name }}</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.7199 15.78C12.5793 15.9207 12.3887 15.9998 12.1899 16H11.8099C11.6114 15.9977 11.4216 15.9189 11.2799 15.78L6.14985 10.64C6.0552 10.5461 6.00195 10.4183 6.00195 10.285C6.00195 10.1517 6.0552 10.0239 6.14985 9.93001L6.85985 9.22001C6.95202 9.12595 7.07816 9.07294 7.20985 9.07294C7.34154 9.07294 7.46769 9.12595 7.55985 9.22001L11.9999 13.67L16.4399 9.22001C16.5337 9.12536 16.6615 9.07211 16.7949 9.07211C16.9282 9.07211 17.056 9.12536 17.1499 9.22001L17.8499 9.93001C17.9445 10.0239 17.9978 10.1517 17.9978 10.285C17.9978 10.4183 17.9445 10.5461 17.8499 10.64L12.7199 15.78Z"
                                    fill="#131416" />
                            </svg>
                        </template>
                        <template v-else>
                            <a :href="getCategoryUrl(item.slug)">{{ item.name }}</a>
                        </template>
                    </div>
                    <div class="mobile-navigation__item__content" :ref="'item-' + index"
                        :style="{height: index === activeItemIndex ? $refs['item-' + index][0].scrollHeight + 'px' : '0px'}">
                        <template v-if="item.children">
                            <template v-for="item in getChildren(item)">
                                <div class="mobile-navigation__item__content__item" :key="item.uuid">
                                    <a :href="getCategoryUrl(item.slug)"
                                        class="mobile-navigation__item__content__item__link">
                                        {{ item.name }}
                                    </a>
                                    <template v-if="item.children && item.children.length > 0">
                                        <ul class="mobile-navigation__item__content__item__sublist">
                                            <li v-for="child in getChildren(item)" :key="child.uuid">
                                                <a :href="getCategoryUrl(child.slug)"
                                                    class="mobile-navigation__item__content__item__sublist__item">
                                                    {{ child.name }}
                                                </a>
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div class="mobile-navigation__footer">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: "VMobileNavigation",
    props: {
        items: {
            type: Array,
            required: true,
        },
        translations: {
            type: Object,
            required: true,
        },
    },
    data: () => {
        return {
            activeItemIndex: null,
            activeNavigationItemLeftPosition: 0,
        };
    },
    computed: {
        rootItems() {
            return this.items.filter((item) => !item.has_parent && item.score >= 100);
        },
        activeItem() {
            return this.items[this.activeItemIndex];
        },
        activeItemHasNestedChildren() {
            const childrenCategories = this.getChildren(this.activeItem);
            return childrenCategories.some((item) => this.getChildren(item).length > 0);
        },
    },
    methods: {
        getCategoryUrl(slug) {
            return this.items.find((item) => item.slug === slug)?.url;
        },
        toggleItem(index) {
            this.activeItemIndex = this.activeItemIndex === index ? null : index;
        },
        resetActiveItem() {
            this.activeItemIndex = null;
        },
        getChildren(category){
            const childrenIDs = category.children.map(item => item.uuid);
            const childrenCategories = this.items.filter(item => childrenIDs.includes(item.uuid));
            return childrenCategories
                .sort((a, b) => b.score - a.score)
                .filter(item => item.score >= 100);
        }
    },
};
</script>