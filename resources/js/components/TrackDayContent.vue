<template>
  <div
    class="book-content"
  >
    <rapid-step
      v-if=" ! enquiry"
      :number="step"
      :title="trackDaysStepTitle(step)"
      is-current
      hide-line
      class="book-content-step"
    />

    <component
      :is="'TrackDayStep' + step + (enquiry && (step == 1) ? 'Enquiry' : '')"
      class="book-content__component"
    />
  </div>
</template>

<script>
  import { mapState, mapGetters } from 'vuex'
  import RapidStep from './RapidStep'
  import TrackDayStep1 from './TrackDayStep1'
  import TrackDayStep1Enquiry from './TrackDayStep1Enquiry'
  import TrackDayStep2 from './TrackDayStep2'
  import TrackDayStep3 from './TrackDayStep3'
  import TrackDayStep4 from './TrackDayStep4'
  import TrackDayStep5 from './TrackDayStep5'

  export default {
    name: 'TrackDayContent',

    components: {
      RapidStep,
      TrackDayStep1,
      TrackDayStep1Enquiry,
      TrackDayStep2,
      TrackDayStep3,
      TrackDayStep4,
      TrackDayStep5,
    },

    props: {
      enquiry: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      ...mapState({
        step: state => state.trackDays.step,
      }),

      ...mapGetters([
        'trackDaysStepTitle',
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