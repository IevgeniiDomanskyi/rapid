<template>
  <div
    :class="className"
  >
    <slot />
  </div>
</template>

<script>
  export default {
    name: 'AppPanel',

    props: {
      flex: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      className() {
        let result = ['app-panel']

        if (this.flex) {
          result.push('flex')
        }

        return result.join(' ')
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .app-panel {
    padding: 16px;
    background: $panel;

    &.flex {
      display: flex;
      flex-direction: row;
      align-items: center;
    }
  }
</style>