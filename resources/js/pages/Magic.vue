<template>
  <div />
</template>

<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'Magic',

    created() {
      const { token, segment, id } = this.$router.history.current.params
      if (token) {
        this.authMagic({ token }).then(data => {
          if (data) {
            this.$router.replace('/panel/' + segment + (id ? ('/' + id) : ''))
          }
        })
      }
    },

    methods: {
      ...mapActions([
        'authMagic',
      ]),
    },
  }
</script>