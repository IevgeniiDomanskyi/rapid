<template>
  <div>
    <div class="step7">
      <v-form
        v-model="valid"
        ref="form"
        lazy-validation
      >
        <v-select
          v-model="selectedRegion"
          :items="regionsList"
          label="Pick a region"
          outlined
          dense
          class="step7-select"
        />
      </v-form>

      <a
        href="https://www.rapidtraining.co.uk/where-we-ride"
        target="_blank"
        class="d-flex align-center"
        style="color: #d53e29;"
      >
        <v-icon color="#d53e29">mdi-map-marker</v-icon>
        WHERE WE RIDE
      </a>
    </div>

    <div class="step7-bottom">
      <book-button
        :loading="isLoading"
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
    name: 'RapidStep7',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    data: (vm) => ({
      valid: true,

      isLoading: false,
    }),

    computed: {
      ...mapState({
        region: state => state.book.options.region,
      }),

      selectedRegion: {
        get() {
          return this.region
        },

        set(val) {
          this.bookStepSet({
            options: {
              region: val,
            },
          })
        },
      },

      regionsList() {
        let result = []
        for (const region of this.regions) {
          result.push({
            value: region.id,
            text: region.title,
          })
        }

        return result
      },
    },

    mounted() {
      if ( ! this.region && this.user.regions && this.user.regions[0]) {
        this.bookStepSet({
          options: {
            region: this.user.regions[0].id,
          },
        })
      }
    },

    methods: {
      ...mapMutations([
        'messagesSet',
        'bookStepSet',
      ]),

      ...mapActions([
        'bookStep',
      ]),

      onNextStep() {
        if (this.region > 0) {
          this.isLoading = true

          this.bookStep({
            step: 8,
            options: {
              region: this.region,
            },
          }).then(data => {
            this.isLoading = false
          })
        } else {
          this.messagesSet({
            text: 'Please pick a region',
            type: 'warning',
          })
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/_variables.scss";
  @import "../../sass/book.scss";

  .step7 {
    &-select {
      margin-top: 5px;
      width: 300px;
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step7 {
      &-select {
        width: 100%;
      }

      &-bottom {
        text-align: center;
        padding-top: 0;
      }
    }
  }
</style>