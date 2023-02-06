<template>
  <v-select
    v-model="select"
    :items="items"
    :label="label"
    :rules="rules"
    :dark="inverse"
    :placeholder="placeholder"
    :disabled="disabled"
    class="app-select"
    @keydown.enter="onSubmit"
  />
</template>

<script>
  export default {
    name: 'AppSelect',

    props: {
      value: {
        type: [Number, String],
        default: null,
      },

      items: {
        type: Array,
        default: () => [],
      },

      rules: {
        type: Array,
        default: () => [],
      },

      label: {
        type: String,
        default: '',
      },

      placeholder: {
        type: String,
        default: '',
      },

      inverse: {
        type: Boolean,
        default: false,
      },

      disabled: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      select: {
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
  .app-select {
    .v-label {
      font-size: 14px;
    }
  }
</style>
