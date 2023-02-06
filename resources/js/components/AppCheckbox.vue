<template>
  <v-checkbox
    v-model="checkbox"
    :label="label"
    :color="$colors.primary"
    :rules="rules"
    :hide-details="hideDetails"
    :dark="dark"
    class="app-checkbox"
  >
    <template slot="label">
      <slot />
    </template>
  </v-checkbox>
</template>

<script>
  export default {
    name: 'AppCheckbox',

    props: {
      value: {
        type: [String, Boolean, Number],
        default: false,
      },

      label: {
        type: String,
        default: null,
      },

      hideDetails: {
        type: Boolean,
        default: false,
      },

      dark: {
        type: Boolean,
        default: false,
      },

      rules: {
        type: Array,
      },
    },

    computed: {
      checkbox: {
        get() {
          return this.value
        },
        set(val) {
          this.$emit('input', val)
        },
      },
    },
  }
</script>

<style lang="scss">
  .app-checkbox {
    .v-label {
      font-size: 14px;
    }
  }
</style>