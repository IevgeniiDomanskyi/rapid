<template>
  <div>
    <div
      class="step3"
    >
      <div
        class="rapid-step__text-title"
      >
        Please enter your details
      </div>

      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <p class="step3-p">
          First Name
        </p>

        <book-input
          v-model="form.firstname"
          :rules="validate('First Name')"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Last Name
        </p>

        <book-input
          v-model="form.lastname"
          :rules="validate('Last Name')"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Phone Number
        </p>

        <book-input
          v-model="form.phone"
          :rules="validate('Phone Number')"
          type="number"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Email Address
        </p>

        <book-input
          v-model="form.email"
          :rules="validate('Email Address')"
          type="email"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Postcode
        </p>

        <book-input
          v-model="form.postcode"
          :rules="validate('Postcode')"
          @submit="onNextStep"
        />

        <!-- <p
          v-if=" ! customer.id"
          class="step3-p"
        >
          Pick Your Password (or use your actual password if you already have an account). <a href="/panel/recovery" target="_blank">Forgot password?</a>
        </p>

        <book-input
          v-if=" ! customer.id"
          v-model="form.password"
          :rules="validate('Password')"
          type="password"
          @submit="onNextStep"
        /> -->

        <div
          v-if=" ! token || token && user.role > 0"
        >
          <!-- <book-checkbox
            v-model="form.confirm"
            :rules="validate('Confirm checkbox')"
          >
            I have read and agreed to the&nbsp;
            <a href="https://www.rapidtraining.co.uk/privacy-policy" target="_blank" @click.stop>website privacy policy</a>
          </book-checkbox> -->

          <book-checkbox
            v-model="form.gdpr"
            :rules="validate('Receive emails confirmation')"
          >
            Join the Rapid Community for free access to all our performance riding resources plus exclusive member only events, offers and news straight to your inbox
          </book-checkbox>

          <p>
            Rapid take your privacy seriously. You can find out more about how we use your personal data on our <a href="https://www.rapidtraining.co.uk/privacy-policy" target="_blank" @click.stop>privacy policy</a>.
          </p>
        </div>

        <!-- <a
          v-else
          href="/panel"
          target="_blank"
        >
          Edit Personal Details
        </a> -->
      </v-form>
    </div>

    <div class="step3-bottom">
      <!-- <book-button
        v-if="token"
        :loading="isLogoutLoading"
        label="Log out"
        color="red"
        style="float: left;"
        @click="onLogout"
      /> -->

      <!-- <book-button
        :loading="isLoading"
        label="Next"
        color="red"
        @click="onNextStep"
      /> -->

      <book-button
        :loading="isLoading"
        label="Send Enquiry"
        color="red"
        @click="onNextStep"
      />
    </div>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookInput from './BookInput'
  import BookCheckbox from './BookCheckbox'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'
  import rules from '../validation/rules'

  export default {
    name: 'EventStep1Enquiry',

    mixins: [RapidBook],

    components: {
      BookInput,
      BookCheckbox,
      BookButton,
    },

    data: () => ({
      valid: true,

      form: {
        firstname: '',
        lastname: '',
        phone: '',
        email: '',
        postcode: '',
        gdpr: false,
      },

      isLoading: false,
      isLogoutLoading: false,
    }),

    computed: {
      ...mapState({
        eForm: state => state.events.options.eForm,
      }),
    },

    created() {
      if (this.token && ! this.customer.id) {
        this.authMe().then(data => {
          if (data) {
            this.initForm()
          }
        })
      } else {
        this.initForm()
      }
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'eventsStep',
        'authLogout',
      ]),

      initForm() {
        this.eventsStep({
          step: 1,
          options: {
            event: this.$route.params.eventId,
          },
        })

        if (this.customer.id) {
          this.form.firstname = this.customer.firstname
          this.form.lastname = this.customer.lastname
          this.form.phone = this.customer.phone
          this.form.email = this.customer.email
          this.form.postcode = this.customer.postcode
          this.form.gdpr = this.customer.gdpr
        } else {
          this.form.firstname = this.eForm.firstname
          this.form.lastname = this.eForm.lastname
          this.form.phone = this.eForm.phone
          this.form.email = this.eForm.email
          this.form.postcode = this.eForm.postcode
          this.form.gdpr = this.eForm.gdpr
        }
      },

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First Name': r = ['require']; break
          case 'Last Name': r = ['require']; break
          case 'Phone Number': r = ['require', 'phone']; break
          case 'Email Address': r = ['require', 'email']; break
          // case 'Password': r = ['require', 'min']; o = { min: 8 }; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onNextStep() {
        if (this.$refs.form.validate()) {
          this.isLoading = true
          this.eventsStep({
            step: 1,
            options: {
              eForm: this.form,
            },
          }).then(() => {
            this.onEventEnquiry().then(() => {
              this.isLoading = false
            })
          })
        }
      },

      onLogout() {
        this.isLogoutLoading = true
        this.authLogout()

        this.form.firstname = ''
        this.form.lastname = ''
        this.form.phone = ''
        this.form.email = ''
        this.form.postcode = ''
        this.form.gdpr = false
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';
  @import '../../sass/book.scss';

  .step3 {
    &-p {
      margin-bottom: 5px;
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }

    .rapid-step__text-title {
      margin-top: -30px;
      font-size: 16px;
      text-transform: uppercase;
      margin-bottom: 30px;
      font-family: FuturaPTHeavy, sans-serif;
    }
  }

  @media (max-width: 768px) {
    .step3 {
      &-bottom {
        text-align: center;
        padding-top: 15px;
      }

      .rapid-step__text-title {
        margin-top: 0;
      }
    }
  }
</style>