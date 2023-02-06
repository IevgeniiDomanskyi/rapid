<template>
  <div
    :class="{ visible: tStep > 1 }"
    class="book-progress"
  >
    <div class="book-progress__title">
      Summary
    </div>

    <div
      v-if="user && tStep > 1"
      class="book-progress__option"
    >
      &bull; {{ name() }}<br />
      &nbsp;&nbsp;&nbsp;&nbsp; {{ user.email }}
    </div>

    <div
      v-if="tOptions.terms && tStep > 2"
      class="book-progress__option"
    >
      &bull; Agreed to T&Cs<br />
      &nbsp;&nbsp;&nbsp;&nbsp; and Rider Declaration
    </div>

    <div
      v-if="tOptions.card > 0 && tStep > 3"
      class="book-progress__option"
    >
      &bull; {{ card(tOptions.card) }}
    </div>

    <!-- <div
      v-if="eStep > 4"
      class="book-progress__option"
    >
      &bull; {{ plan(eOptions.plan, eOptions.instalment) }}
    </div> -->

    <book-button
      v-if="tStep == 5"
      :loading="isBookLoading"
      label="Book Now"
      color="red"
      @click="onTrackDay"
    />
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'TrackDayProgress',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        currentTrackDay: state => state.trackDays.current,
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
        let price = this.currentTrackDay.price
        
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