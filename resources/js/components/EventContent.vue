<template>
  <div
    class="book-content"
  >
    <rapid-step
      v-if=" ! enquiry"
      :number="step"
      :title="eventsStepTitle(step)"
      is-current
      hide-line
      class="book-content-step"
    />

    <component
      :is="'EventStep' + step + (enquiry && (step == 1) ? 'Enquiry' : '')"
      class="book-content__component"
    />
  </div>
</template>

<script>
  import { mapState, mapGetters } from 'vuex'
  import RapidStep from './RapidStep'
  import EventStep1 from './EventStep1'
  import EventStep1Enquiry from './EventStep1Enquiry'
  import EventStep2 from './EventStep2'
  import EventStep3 from './EventStep3'
  import EventStep4 from './EventStep4'
  import EventStep5 from './EventStep5'

  export default {
    name: 'EventContent',

    components: {
      RapidStep,
      EventStep1,
      EventStep1Enquiry,
      EventStep2,
      EventStep3,
      EventStep4,
      EventStep5,
    },

    props: {
      enquiry: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      ...mapState({
        step: state => state.events.step,
      }),

      ...mapGetters([
        'eventsStepTitle',
      ]),
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .book-content {
    flex: 1;
    border: solid transparent 1px;
    border-radius: 10px;
    padding: 15px 30px;

    &__component {
      margin-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .book-content {
      width: 100%;
      padding: 0 0 30px;

      &-step {
        display: none;
      }

      &__component {
        margin-top: 15px;
      }
    }
  }
</style>