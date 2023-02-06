<template>
  <div
    :class="{ withLine: ! hideLine, current: isCurrent }"
    class="rapid-step"
    @click="onClick"
  >
    <div class="rapid-step__number">
      {{ zero(number) }}

      <div
        v-if=" ! hideLine"
        class="rapid-step__number-line"
      />
    </div>

    <div class="rapid-step__text">
      <div class="rapid-step__text-step">
        Step {{ number }}
      </div>

      <div
        :class="{ current: isCurrent }"
        class="rapid-step__text-title"
      >
        {{ title }}
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'RapidStep',

    props: {
      number: {
        type: [Number, String],
        required: true,
      },

      title: {
        type: String,
        default: '',
      },

      isCurrent: {
        type: Boolean,
        default: false,
      },

      hideLine: {
        type: Boolean,
        default: false,
      },
    },

    methods: {
      zero(number) {
        return (number < 10 ? '0' : '') + number
      },

      onClick() {
        this.$emit('click', this.number)
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/book.scss';
  @import '../../sass/_variables.scss';

  .rapid-step {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    cursor: pointer;

    &.withLine {
      margin-bottom: 80px;
    }

    &__number {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 30px;
      font-family: FuturaPTLight, sans-serif;
      color: $step-text;
      background-color: $step-box;
      width: 50px;
      height: 60px;
      border-radius: 5px;
      margin-right: 15px;
      position: relative;
      z-index: 0;

      &-line {
        position: absolute;
        z-index: 1;
        top: 70px;
        left: 50%;
        width: 1px;
        height: 60px;
        background: $border;
      }
    }

    &__text {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;

      &-step {
        color: $step-red;
        font-size: 14px;
        text-transform: uppercase;
        font-family: FuturaPTBold;
        line-height: 1;
        margin-bottom: 10px;
      }

      &-title {
        color: $border;
        font-size: 16px;
        text-transform: uppercase;
        font-family: FuturaPTBook;
        line-height: 1.1;

        &.current {
          font-family: FuturaPTHeavy;
        }
      }
    }
  }

  @media (max-width: 768px) {
    .rapid-step {
      width: 80%;
      display: none;

      &.current:not(.book-content-step) {
        display: flex;
      }

      &.withLine {
        margin-bottom: 0;
      }

      &__number {
        &-line {
          display: none;
        }
      }
    }
  }
</style>