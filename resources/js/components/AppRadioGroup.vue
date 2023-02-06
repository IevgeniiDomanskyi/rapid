<template>
  <v-radio-group
    v-model="radio"
    :row="row"
    :rules="rules"
    :dark=" ! light"
    :hide-details="hideDetails"
    class="app-radio-group"
  >
    <v-radio
      v-for="item in list"
      :key="item.value"
      :value="item.value"
      :disabled="item.disabled"
      :color="$colors.primary"
    >
      <template v-slot:label>
        <div v-html="item.label" />
      </template>
    </v-radio>
  </v-radio-group>
</template>

<script>
  export default {
    name: 'AppRadioGroup',

    props: {
      value: {
        type: [String, Number],
        default: null,
      },

      list: {
        type: Array,
        default: () => []
      },

      row: {
        type: Boolean,
        default: false,
      },

      rules: {
        type: Array,
        default: () => [],
      },

      light: {
        type: Boolean,
        default: false,
      },

      hideDetails: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      radio: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      }
    },
  }
</script>

<style lang="scss">
  .app-radio-group {
    &.v-input--selection-controls {
      margin-top: 0;

      .v-label {
        font-size: 14px;
      }
    }
  }
</style>