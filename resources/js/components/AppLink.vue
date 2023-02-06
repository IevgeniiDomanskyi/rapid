<template>
  <router-link
    v-if="href"
    :to="href"
    :style="color ? ('color: ' + color) : ''"
    class="app-link"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <slot />
  </router-link>

  <a
    v-else
    :style="color ? ('color: ' + color) : ''"
    class="app-link"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <slot />
  </a>
</template>

<script>
  export default {
    name: 'AppLink',

    props: {
      href: {
        type: String,
        default: null,
      },

      color: {
        tyle: String,
        default: null,
      },
    },

    methods: {
      onClick() {
        this.$emit('click')
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .app-link {
    color: $primary;
    font-size: 14px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease 0s;
    display: inline-flex;
    align-items: center;

    &:hover,
    &:focus,
    &:active {
      opacity: 0.8;
    }
  }
</style>