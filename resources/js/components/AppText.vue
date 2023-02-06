<template>
  <div
    :class="className"
    :style="'color: ' + colorName"
  >
    <slot />
  </div>
</template>

<script>
  export default {
    name: 'AppText',

    props: {
      size: {
        type: String,
        default: null,
      },

      color: {
        type: String,
        default: 'text',
      },

      nowrap: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      className() {
        let result = ['app-text']

        if (this.size) {
          result.push(this.size)
        }

        if (this.nowrap) {
          result.push('nowrap')
          result.push('text-truncate')
        }

        return result.join(' ')
      },

      colorName() {
        return this.$colors[this.color]
      },
    },
  }
</script>

<style lang="scss" scoped>
  .app-text {
    font-size: 14px;
    line-height: 1.5;


    &.xs {
      font-size: 11px;
    }

    &.sm {
      font-size: 12px;
    }

    &.md {
      font-size: 18px;
    }

    &.lg {
      font-size: 24px;
    }

    &.nowrap {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      width: 100%;
    }
  }
</style>
