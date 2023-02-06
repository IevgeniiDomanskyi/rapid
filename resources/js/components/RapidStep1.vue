<template>
  <div>
    <div class="step1">
      <div
        v-for="item in courses"
        :key="item.id"
        :class="{ active: course == item.id }"
        class="step1-option"
        @click="onCourseClick(item.id)"
      >
        <img :src="'/images/course-' + item.id + '.jpg'" class="step1-option__image" />

        <p class="step1-option__title">
          {{ item.title }}
        </p>

        <p class="step1-option__text">
          {{ item.description }}
        </p>
      </div>
    </div>

    <div class="step1-bottom">
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
    name: 'RapidStep1',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        course: state => state.book.options.course,
      }),
    },

    methods: {
      ...mapMutations([
        'messagesSet',
        'bookStepSet',
      ]),

      ...mapActions([
        'bookStep',
      ]),

      onCourseClick(course) {
        this.bookStepSet({
          options: {
            course,
          }
        })
      },

      onNextStep() {
        if (this.course > 0) {
          this.bookStep({
            step: 2,
            options: {
              course: this.course,
            },
          })
        } else {
          this.messagesSet({
            text: 'Please, pick your course',
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

  .step1 {
    display: flex;
    justify-content: space-between;

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

        .step1-option__title,
        .step1-option__text {
          color: $step-text;
        }
      }
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step1 {
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