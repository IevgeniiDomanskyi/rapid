<template>
  <v-text-field
    v-model="input"
    :label="label"
    :placeholder="placeholder"
    :type="type"
    :rules="rules"
    :color="$colors.text_inverse"
    :counter="maxlength"
    :maxlength="maxlength"
    v-bind="$attrs"
    v-on="$listeners"
    outlined
    dense
    full-width
    autocomplete="off"
    class="book-input"
    @keydown.enter="onSubmit"
  />
</template>

<script>
  export default {
    name: 'BookInput',

    props: {
      value: {
        type: String,
        default: '',
      },

      label: {
        type: String,
        default: null,
      },

      placeholder: {
        type: String,
        default: null,
      },

      type: {
        type: String,
        default: 'text',
      },

      rules: {
        type: Array,
        default: () => [],
      },

      maxlength: {
        type: [String, Number],
        default: null,
      },
    },

    computed: {
      input: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    methods: {
      onSubmit() {
        this.$emit('submit')
      },
    },
  }
</script>

<style lang="scss">
  .book-input {
    .v-label {
      font-size: 14px;
    }
  }
</style>