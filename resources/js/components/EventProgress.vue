<template>
  <div
    :class="{ visible: eStep > 1 }"
    class="book-progress"
  >
    <div class="book-progress__title">
      Summary
    </div>

    <div
      v-if="user && eStep > 1"
      class="book-progress__option"
    >
      &bull; {{ name() }}<br />
      &nbsp;&nbsp;&nbsp;&nbsp; {{ user.email }}
    </div>

    <div
      v-if="eOptions.terms && eStep > 2"
      class="book-progress__option"
    >
      &bull; Agreed to T&Cs<br />
      &nbsp;&nbsp;&nbsp;&nbsp; and Rider Declaration
    </div>

    <div
      v-if="eOptions.card > 0 && eStep > 3"
      class="book-progress__option"
    >
      &bull; {{ card(eOptions.card) }}
    </div>

    <!-- <div
      v-if="eStep > 4"
      class="book-progress__option"
    >
      &bull; {{ plan(eOptions.plan, eOptions.instalment) }}
    </div> -->

    <book-button
      v-if="eStep == 5"
      :loading="isBookLoading"
      label="Book Now"
      color="red"
      @click="onEvent"
    />
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'EventProgress',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        currentEvent: state => state.events.current,
      }),
    },

    methods: {
      name() {
        if (this.user) {
          return this.user.firstname + ' ' + this.user.lastname
        }

        return 'Unknown user'
      },

      card(card_id) {
        const card = this.cardById(card_id)
        if (card.id) {
          return 'Card ending ' + card.end
        }

        return 'Unknown Card'
      },

      plan(plan, instalment) {
        let price = this.currentEvent.price
        
        if (plan == 0) {
          return 'One-off payment £' + price
        }

        if (plan == 1) {
          return instalment + ' instalments of £' + Math.round(price / instalment)
        }

        return 'Unknown Payment Plan'
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