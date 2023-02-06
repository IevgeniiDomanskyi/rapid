<template>
  <div>
    <div class="step4">
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <book-textarea
          v-model="messageValue"
          label="Your message"
          :rules="validate('Your message')"
        />
      </v-form>
    </div>

    <div class="step4-bottom">
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
  import { mapMutations, mapActions } from 'vuex'
  import BookTextarea from './BookTextarea'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'
  import rules from '../validation/rules'

  export default {
    name: 'RapidStep4Enquiry',

    mixins: [RapidBook],

    components: {
      BookTextarea,
      BookButton,
    },

    data: () => ({
      valid: true,

      message: '',

      isLoading: false,
    }),

    computed: {
      messageValue: {
        get() {
          return this.message != '' ? this.message : this.options.message
        },

        set(val) {
          this.message = val
        },
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'bookStep',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Your message': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onNextStep() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.bookStep({
            step: 5,
            options: {
              message: this.message,
            }
          }).then(data => {
            this.isLoading = false
          })
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/_variables.scss";
  @import "../../sass/book.scss";

  .step4 {
    &-terms {
      border: solid $panel 1px;
      border-radius: 10px;
      overflow: hidden;

      &__inner {
        max-height: 500px;
        overflow: auto;
        padding: 15px;
      }
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step4 {
      &-bottom {
        text-align: center;
        padding-top: 15px;
      }
    }
  }
</style>