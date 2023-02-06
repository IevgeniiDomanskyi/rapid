<template>
  <v-btn
    :block="block"
    :color="colorName"
    :loading="loading"
    dark
    class="book-button"
    @click="onClick"
  >
    {{ label }}
  </v-btn>
</template>

<script>
  export default {
    name: 'BookButton',

    props: {
      label: {
        type: String,
        default: null,
      },

      block: {
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
  .book-button {
    &.v-btn {
      text-transform: uppercase;

      &.v-size--default,
      &.v-size--large {
        font-size: 14px;
        font-weight: 400;
        height: 40px;
        min-width: 150px;
        border-radius: 8px;
      }
    }
  }
</style>