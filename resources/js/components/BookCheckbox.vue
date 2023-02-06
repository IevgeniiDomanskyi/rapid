<template>
  <v-checkbox
    v-model="checkbox"
    :label="label"
    :rules="rules"
    color="success"
    class="book-checkbox"
  >
    <template slot="label">
      <slot />
    </template>
  </v-checkbox>
</template>

<script>
  export default {
    name: 'BookCheckbox',

    props: {
      value: {
        type: [String, Boolean],
        default: false,
      },

      label: {
        type: String,
        default: null,
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
  .v-input--selection-controls {
    .v-input__slot {
      &>.v-label {
        display: inline;
      }
    }
  }
</style>