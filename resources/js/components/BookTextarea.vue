<template>
  <v-textarea
    v-model="input"
    :label="label"
    :height="height"
    outlined
    class="book-textarea"
  />
</template>

<script>
  export default {
    name: 'BookTextarea',

    props: {
      value: {
        type: String,
        default: '',
      },

      label: {
        type: String,
        default: '',
      },

      height: {
        type: [String, Number],
        default: 200,
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
  }
</script>

<style lang="scss" scoped>
  .book-textarea {
    
  }
</style>