<template>
  <div ref="navigation" class="navigation">
    <div class="container">
      <ul class="main-nav--menu">
        <li :key="item.uuid" v-for="(item, index) in rootItems">
          <a :href="getCategoryUrl(item.slug)" @mouseenter="onItemMouseEnter($event, index)">
            {{ item.name }}
          </a>
        </li>
      </ul>
    </div>
    <template v-if="activeItem && activeItem.children && activeItem.children.length > 0">
      <div ref="flyout" class="main-nav__flyout" :class="{ 'main-nav__flyout--big': activeItemHasNestedChildren }"
        :style="{
          left: activeItemHasNestedChildren
            ? '0'
            : `${activeNavigationItemLeftPosition}px`,
        }">
        <div class="grid gap-6" :class="{
          container: activeItemHasNestedChildren,
          'grid-cols-4': activeItemHasNestedChildren,
          'grid-flow-row': !activeItemHasNestedChildren,
          'gap-y-16': activeItemHasNestedChildren,
        }">
          <template v-for="item in activeItem.children">
            <div :key="item.uuid" class="main-nav__flyout--item">
              <a :href="getCategoryUrl(item.slug)" class="main-nav__flyout--link" :class="{
                'text-subheading-m': activeItemHasNestedChildren,
                'text-body-m': !activeItemHasNestedChildren,
              }">
                {{ item.name }}
              </a>
              <template v-if="item.children && item.children.length > 0">
                <ul class="main-nav__flyout--menu">
                  <li v-for="child in item.children" :key="child.uuid">
                    <a :href="getCategoryUrl(child.slug)" class="text-body-m main-nav__flyout--link">
                      {{ child.name }}
                    </a>
                  </li>
                </ul>
              </template>
            </div>
          </template>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: "VNavigation",
  props: {
    items: {
      type: Array,
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
      return this.activeItem?.children.some((item) => item.children.length);
    },
  },
  methods: {
    onItemMouseEnter(event, index) {
      this.activeItemIndex = index;
      this.activeNavigationItemLeftPosition = event.target.parentNode.offsetLeft;
      document.addEventListener("mousemove", this.onMouseMove);
    },
    onMouseMove(event) {
      const flyoutRef = this.$refs.flyout;
      if (flyoutRef) {
        const flyoutRect = flyoutRef.getBoundingClientRect();
        const isMouseOverFlyout =
          event.clientX >= flyoutRect.left &&
          event.clientX <= flyoutRect.right &&
          event.clientY >= flyoutRect.top &&
          event.clientY <= flyoutRect.bottom;

        const navigationRect = document.querySelector('.main-nav').getBoundingClientRect();
        const isMouseOverNavigation =
          event.clientX >= navigationRect.left &&
          event.clientX <= navigationRect.right &&
          event.clientY >= navigationRect.top &&
          event.clientY <= navigationRect.bottom;

        if (!isMouseOverFlyout && !isMouseOverNavigation) {
          this.activeItemIndex = null;
          document.removeEventListener("mousemove", this.onMouseMove);
        }
      }
    },
    getCategoryUrl(slug) {
      return this.items.find((item) => item.slug === slug).url;
    }
  }
};
</script>
