<template>
  <div
    :style="'width: ' + width + (isNumeric(width) ? 'px' : '')"
    class="app-logo"
  >
    <img src="../assets/img/logo-white.png" />
  </div>
</template>

<script>
  export default {
    name: 'AppLogo',

    props: {
      width: {
        type: [Number, String],
        default: '100%',
      },
    },

    methods: {
      isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n)
      },
    },
  }
</script>

<style lang="scss" scoped>
  .app-logo {
    text-align: center;

    img {
      max-width: 100%;
    }
  }
</style>