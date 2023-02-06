<template>
  <v-avatar
    :color="$colors.primary"
    size="48"
    @click="onClick"
  >
    <img
      v-if="image"
      src="https://cdn.vuetifyjs.com/images/john.jpg"
      alt="John"
    >

    <span
      v-if="name"
      class="white--text headline"
    >
      {{ initials }}
    </span>
  </v-avatar>
</template>

<script>
  export default {
    name: 'AppAvatar',

    props: {
      name: {
        type: String,
        default: null,
      },

      image: {
        type: String,
        default: null,
      },
    },

    computed: {
      initials() {
        if (this.name) {
          const part = this.name.split(' ')
          return (part[0] ? part[0].slice(0, 1).toUpperCase() : '') + (part[1] ? part[1].slice(0, 1).toUpperCase() : '')
        }

        return ''
      },
    },

    methods: {
      onClick() {
        this.$emit('click')
      },
    },
  }
</script>