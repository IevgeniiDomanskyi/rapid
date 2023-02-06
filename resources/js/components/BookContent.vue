<template>
  <div
    class="book-content"
  >
    <rapid-step
      v-if=" ! enquiry"
      :number="step"
      :title="bookStepTitle(step)"
      is-current
      hide-line
      class="book-content-step"
    />

    <component
      :is="'RapidStep' + step + (enquiry && (step == 3 || step == 4 || step == 5) ? 'Enquiry' : '')"
      class="book-content__component"
    />
  </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex'
  import RapidStep from './RapidStep'
  import RapidStep1 from './RapidStep1'
  import RapidStep2 from './RapidStep2'
  import RapidStep3 from './RapidStep3'
  import RapidStep3Enquiry from './RapidStep3Enquiry'
  import RapidStep4 from './RapidStep4'
  import RapidStep4Enquiry from './RapidStep4Enquiry'
  import RapidStep5 from './RapidStep5'
  import RapidStep5Enquiry from './RapidStep5Enquiry'
  import RapidStep6 from './RapidStep6'
  import RapidStep7 from './RapidStep7'
  import RapidStep8 from './RapidStep8'

  export default {
    name: 'BookContent',

    components: {
      RapidStep,
      RapidStep1,
      RapidStep2,
      RapidStep3,
      RapidStep3Enquiry,
      RapidStep4,
      RapidStep4Enquiry,
      RapidStep5,
      RapidStep5Enquiry,
      RapidStep6,
      RapidStep7,
      RapidStep8,
    },

    props: {
      enquiry: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      ...mapState({
        step: state => state.book.step,
      }),

      ...mapGetters([
        'stepTitle',
      ]),
    },

    methods: {
      bookStepTitle(step) {
        return this.stepTitle(step, this.enquiry)
      },
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