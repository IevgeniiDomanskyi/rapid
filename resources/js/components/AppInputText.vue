<template>
  <v-text-field
    v-model="input"
    :label="label"
    :placeholder="placeholder"
    :type="type"
    :rules="rules"
    :dark="inverse"
    :color="inverse ? $colors.text : $colors.primary"
    :style="style"
    :role="role"
    :readonly="readonly"
    :disabled="disabled"
    class="app-input-text"
    autocomplete="new-password"
    v-bind="$attrs"
    v-on="$listeners"
    @keydown.enter="onSubmit"
  />
</template>

<script>
  export default {
    name: 'AppInputText',

    props: {
      value: {
        type: [String, Number],
        default: '',
      },

      inverse: {
        type: Boolean,
        default: false,
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

      width: {
        type: Number,
        default: null,
      },

      rules: {
        type: Array,
        default: () => [],
      },

      role: {
        type: String,
        default: null,
      },

      readonly: {
        type: Boolean,
        default: false,
      },

      disabled: {
        type: Boolean,
        default: false,
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

      style() {
        let result = []

        if (this.width) {
          result.push('width: ' + this.width + 'px')
        }

        return result.join(';')
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
  .app-input-text {
    .v-label {
      font-size: 14px;
    }
  }
</style>