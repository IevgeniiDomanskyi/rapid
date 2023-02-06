<template>
  <v-btn
    :block="block"
    :color="colorName"
    :loading="loading"
    :small="small"
    :dark=" ! light"
    :icon="isIcon"
    class="app-button"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <v-icon
      v-if="icon"
      dark
    >
      {{ icon }}
    </v-icon>

    {{ label }}

    <slot />
  </v-btn>
</template>

<script>
  export default {
    name: 'AppButton',

    props: {
      label: {
        type: String,
        default: null,
      },

      icon: {
        type: String,
        default: null,
      },

      isIcon: {
        type: Boolean,
        default: false,
      },

      block: {
        type: Boolean,
        default: false,
      },

      light: {
        type: Boolean,
        default: false,
      },

      small: {
        type: Boolean,
        default: false,
      },

      color: {
        type: String,
        default: null,
      },

      loading: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      colorName() {
        return this.$colors[this.color]
      },
    },

    methods: {
      onClick() {
        this.$emit('click')
      },
    },
  }
</script>

<style lang="scss">
  .app-button {
    position: relative;
    z-index: 0;
    overflow: hidden;

    &.v-btn {
      text-transform: none;
      letter-spacing: 0;

      &.v-size--default,
      &.v-size--large {
        font-size: 16px;
        font-weight: 400;
        height: 40px;
      }

      &.v-size--small {
        font-size: 14px;
        font-weight: 400;
        height: 32px;
      }
    }

    input[type=file] {
      position: absolute;
      z-index: 1;
      font-size: 150px;
      top: -10px;
      right: -20px;
      opacity: 0;
      cursor: pointer;
    }
  }
</style>