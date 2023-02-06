<template>
  <v-radio
    v-model="radio"
    :label="label"
    :name="name"
    :color="$colors.text_inverse"
  />
</template>

<script>
  export default {
    name: 'BookRadioGroup',

    props: {
      value: {
        type: [String, Number],
        default: null,
      },

      label: {
        type: String,
        default: '',
      },

      name: {
        type: String,
        default: '',
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
</style>