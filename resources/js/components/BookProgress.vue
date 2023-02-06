<template>
  <div
    :class="{ visible: step > 1 }"
    class="book-progress"
  >
    <div class="book-progress__title">
      Summary
    </div>

    <div
      v-if="options.course > 0 && step > 1"
      class="book-progress__option"
    >
      &bull; {{ course(options.course) }}
    </div>

    <div
      v-if="options.level > 0 && step > 2"
      class="book-progress__option"
    >
      &bull; {{ level(options.level) }}
    </div>

    <div
      v-if="user && step > 3"
      class="book-progress__option"
    >
      &bull; {{ name() }}<br />
      &nbsp;&nbsp;&nbsp;&nbsp; {{ email() }}
    </div>

    <div
      v-if="options.terms && step > 4"
      class="book-progress__option"
    >
      &bull; Agreed to T&Cs<br />
      &nbsp;&nbsp;&nbsp;&nbsp; and Rider Declaration
    </div>

    <!-- <div
      v-if="enquiry && options.message && step > 4"
      class="book-progress__option"
    >
      &bull; Message:<br />
      {{ options.message }}
    </div> -->

    <div
      v-if="options.card > 0 && step > 5"
      class="book-progress__option"
    >
      &bull; {{ card(options.card) }}

      <div
        v-if="options.voucher && options.voucher != ''"
      >
        &nbsp;&nbsp;&nbsp;&nbsp; Voucher code: {{ options.voucher }}
      </div>
    </div>

    <div
      v-if="step > 6"
      class="book-progress__option"
    >
      &bull; {{ plan(options.course, options.plan, options.instalment) }}
    </div>

    <div
      v-if="step > 7"
      class="book-progress__option"
    >
      &bull; {{ region(options.region) }}
    </div>

    <book-button
      v-if="! enquiry && step == 8"
      :loading="isBookLoading"
      label="Book Now"
      color="red"
      @click="onBook"
    />

    <book-button
      v-if="enquiry && step == 5"
      :loading="isBookLoading"
      label="Send Enquiry"
      color="red"
      @click="onEnquiry"
    />
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'BookProgress',

    mixins: [RapidBook],

    props: {
      enquiry: {
        type: Boolean,
        default: false,
      },
    },

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        voucher: state => state.vouchers.item,
      }),
    },

    methods: {
      course(course_id) {
        const course = this.courseById(course_id)
        if (course.id) {
          return course.title
        }

        return 'Unknown Course'
      },

      level(level_id) {
        const course = this.courseById(this.options.course)
        if (course.levels) {
          for (const level of course.levels) {
            if (level.id == level_id) {
              return 'Level ' + level.level
            }
          }
        }

        return 'Unknown Level'
      },

      name() {
        if (this.user && this.user.id && this.user.role == 0) {
          return this.user.firstname + ' ' + this.user.lastname
        } else {
          if (this.options.eForm && this.options.eForm.firstname) {
            return this.options.eForm.firstname + ' ' + this.options.eForm.lastname
          }
        }

        return 'Unknown user'
      },

      email() {
        if (this.user && this.user.id && this.user.role == 0) {
          return this.user.email
        } else {
          if (this.options.eForm && this.options.eForm.email) {
            return this.options.eForm.email
          }
        }

        return ''
      },

      card(card_id) {
        const card = this.cardById(card_id)
        if (card.id) {
          return 'Card ending ' + card.end
        }

        return 'Unknown Card'
      },

      plan(course_id, plan, instalment) {
        let price = 0
        const course = this.courseById(course_id)
        if (course.levels) {
          for (const level of course.levels) {
            if (level.id == this.options.level) {
              price = level.price
            }
          }
        }

        if (this.voucher.id) {
          if (this.voucher.type == 1) {
            price = Math.round(price * this.voucher.amount / 100)
          } else {
            price = Math.round(price - this.voucher.amount)
          }
        }

        if (price < 0) {
          price = 0
        }

        if (plan == 0) {
          return 'One-off payment £' + price
        }

        if (plan == 1) {
          return instalment + ' instalments of £' + Math.round(price / instalment)
        }

        return 'Unknown Payment Plan'
      },

      region(region_id) {
        const region = this.regionById(region_id, true)
        if (region) {
          return region.title
        }

        return 'Unknown Region'
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .book-progress {
    width: 300px;
    border: solid $border 1px;
    border-radius: 10px;
    padding: 15px;
    opacity: 0;
    min-height: 380px;

    &.visible {
      opacity: 1;
    }

    &__title {
      font-family: FuturaPTHeavy, sans-serif;
      font-size: 16px;
      text-transform: uppercase;
      border-bottom: solid 1px;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    &__option {
      margin-bottom: 10px;
    }
  }

  @media (max-width: 768px) {
    .book-progress {
      width: 100%;
      min-height: 0;
    }
  }
</style>