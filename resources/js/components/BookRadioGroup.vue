<template>
  <v-radio-group
    v-model="radio"
    :row="row"
    class="book-radio-group"
  >
    <v-radio
      v-for="item in list"
      :key="item.value"
      :value="item.value"
      :label="item.label"
      :color="$colors.text_inverse"
    />
  </v-radio-group>
</template>

<script>
  export default {
    name: 'BookRadioGroup',

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
  @import '../../sass/_variables.scss';

  .book-radio-group {
    &.v-input--selection-controls {
      margin-top: 0;
    }
  }

  @media (max-width: 768px) {
    .book-radio-group {
      .v-radio {
        margin-bottom: 15px;
      }
    }
  }
</style>