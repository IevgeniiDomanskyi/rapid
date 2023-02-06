<template>
  <div
    class="rapid-title"
  >
    <slot />
  </div>
</template>

<script>
  export default {
    name: 'RapidTitle',
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .rapid-title {
    padding: 10px 16px;
    min-height: 80px;
    background: $panel;
    border-top: solid hsla(0,0%,100%,.12) 1px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);

    display: flex;
    align-items: center;
  }
</style>