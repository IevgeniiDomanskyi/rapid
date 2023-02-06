<template>
  <div
    v-if=" ! enquiry"
    class="book-sidebar"
  >
    <a
      :class="{ visible: tStep > 1 }"
      class="book-step-arrow book-step-arrow-left"
      href="javascript:;"
      @click="onStepClick(tStep - 1)"
    >
      <v-icon>
        mdi-chevron-left
      </v-icon>
    </a>

    <rapid-step
      v-for="item in range(stepsCount)"
      :key="item"
      :number="item"
      :title="bookStepTitle(item)"
      :is-current="tStep == item"
      :hide-line="item == stepsCount"
      @click="onStepClick(item)"
    />

    <a
      :class="{ visible: tStep < stepsCount }"
      href="javascript:;"
      class="book-step-arrow book-step-arrow-right"
      @click="onStepClick(tStep + 1)"
    >
      <v-icon>
        mdi-chevron-right
      </v-icon>
    </a>
  </div>
</template>

<script>
  import { mapGetters, mapMutations, mapActions } from 'vuex'
  import RapidStep from './RapidStep'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'TrackDaySidebar',

    mixins: [RapidBook],

    components: {
      RapidStep,
    },

    props: {
      enquiry: {
        type: Boolean,
        default: false,
      },
    },

    data: () => ({
      stepsCount: 5,
    }),

    computed: {
      ...mapGetters([
        'trackDaysStepTitle',
      ]),
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'trackDaysStep',
      ]),

      range(max) {
        let result = []
        for (let i = 1; i <= max; i++) {
          result.push(i)
        }
        return result
      },

      bookStepTitle(step) {
        return this.trackDaysStepTitle(step)
      },

      onStepClick(step) {
        if (step <= this.tMax) {
          if (step == 3 || step == 4) {
            step = 5
          }
          this.trackDaysStep({step})
        } else {
          this.messagesSet({
            text: 'Please fill in all required fields and previous steps',
            type: 'warning'
          })
        }
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .book-sidebar {
    width: 300px;
    border: solid $border 1px;
    border-radius: 10px;
    padding: 15px;
  }

  .book-step-arrow {
    display: none;
    width: 10%;
  }

  @media (max-width: 768px) {
    .book-sidebar {
      display: flex;
      width: 100%;
      align-items: center;
    }

    .book-step-arrow {
      display: block;
      opacity: 0;
      padding: 15px 0;
      width: 10%;
      text-align: left;

      &-right {
        text-align: right;
      }

      &.visible {
        opacity: 1;
      }
    }
  }
</style>