<template>
  <div>
    <div
      :class="{ single: levels.length == 1, pair: levels.length == 2 }"
      class="step2"
    >
      <div
        v-for="item in levels"
        :key="item.id"
        :class="{ active: (level == item.id || levels.length == 1) }"
        class="step2-option"
        @click="onLevelClick(item.id)"
      >
        <img :src="'/images/course-' + course + '-' + item.level + '.jpg'" class="step2-option__image" />

        <p
          v-if="item.level > 0"
          class="step2-option__level"
        >
          Level {{ item.level }}
        </p>

        <p class="step2-option__title">
          {{ item.title }}
        </p>
      </div>
    </div>

    <div class="step2-bottom">
      <book-button
        label="Next"
        color="red"
        @click="onNextStep"
      />
    </div>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'RapidStep2',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        course: state => state.book.options.course,
        level: state => state.book.options.level,
      }),

      levels() {
        const levels = this.courseById(this.course).levels || []
        if (levels.length == 1) {
          this.onLevelClick(levels[0].id)
        }
        return levels
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
        'bookStepSet',
      ]),

      ...mapActions([
        'bookStep',
      ]),

      onLevelClick(level) {
        this.bookStepSet({
          options: {
            level,
          }
        })
      },

      onNextStep() {
        if (this.level > 0) {
          this.bookStep({
            step: 3,
            options: {
              level: this.level,
            },
          })
        } else {
          this.messagesSet({
            text: 'Please, choose your level',
            type: 'warning'
          })
        }
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';
  @import '../../sass/book.scss';

  .step2 {
    display: flex;
    justify-content: space-between;

    &.single {
      justify-content: center;

      .step2-option { 
        flex: 1;
      }
    }

    &-option {
      border: solid $border-light 1px;
      border-radius: 8px;
      padding: 10px;
      width: 30%;
      cursor: pointer;

      &__image {
        max-width: 100%;
        border-radius: 4px;
        margin-bottom: 10px;
      }

      &__level {
        font-size: 18px;
        text-align: center;
        margin-bottom: 10px;
        font-family: FuturaPTBook, sans-serif;
      }

      &__title {
        font-size: 16px;
        font-weight: 900;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 10px;
        font-family: FuturaPTBook, sans-serif;
      }

      &__text {
        font-size: 15px;
        line-height: 1.3;
        text-align: center;
        font-family: FuturaPTBook, sans-serif;
      }

      &.active {
        border-color: $step-box;
        background-color: $step-box;

        .step2-option__level,
        .step2-option__title,
        .step2-option__text {
          color: $step-text;
        }
      }
    }

    &.pair {
      .step2-option { 
        width: 48%;
      }
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step2 {
      flex-direction: column;

      &-option {
        width: 100%;
        margin-bottom: 15px;
      }

      &-bottom {
        text-align: center;
        padding-top: 15px;
      }
    }
  }
</style>