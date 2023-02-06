<template>
  <div>
    <div
      class="step3"
    >
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
          :readonly="!! customer.id"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Last Name
        </p>

        <book-input
          v-model="form.lastname"
          :rules="validate('Last Name')"
          :readonly="!! customer.id"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Phone Number
        </p>

        <book-input
          v-model="form.phone"
          :rules="validate('Phone Number')"
          :readonly="!! customer.id"
          type="number"
          @submit="onNextStep"
        />

        <p class="step3-p">
          Email Address
        </p>

        <book-input
          v-model="form.email"
          :rules="validate('Email Address')"
          :readonly="!! customer.id"
          type="email"
          @submit="onNextStep"
        />

        <p
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
        />

        <div
          v-if=" ! token || token && user.role > 0"
        >
          <book-checkbox
            v-model="form.confirm"
            :rules="validate('Confirm checkbox')"
          >
            I have read and agreed to the&nbsp;
            <a href="https://www.rapidtraining.co.uk/privacy-policy" target="_blank" @click.stop>website privacy policy</a>
          </book-checkbox>

          <book-checkbox
            v-model="form.gdpr"
            :rules="validate('Receive emails confirmation')"
          >
            I'd like to receive emails from Rapid Training to stay up to date and to enhance my course and user experience
          </book-checkbox>
        </div>

        <a
          v-else
          href="/panel"
          target="_blank"
        >
          Edit Personal Details
        </a>
      </v-form>
    </div>

    <div class="step3-bottom">
      <book-button
        v-if="token"
        :loading="isLogoutLoading"
        label="Log out"
        color="red"
        style="float: left;"
        @click="onLogout"
      />

      <book-button
        :loading="isLoading"
        label="Next"
        color="red"
        @click="onNextStep"
      />
    </div>

    <rapid-terms-modal
      v-model="isTermsModalVisible"
      @close="onTermsModalClose"
    />

    <rapid-policy-modal
      v-model="isPolicyModalVisible"
      @close="onPolicyModalClose"
    />
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookInput from './BookInput'
  import BookCheckbox from './BookCheckbox'
  import BookButton from './BookButton'
  import RapidTermsModal from './RapidTermsModal'
  import RapidPolicyModal from './RapidPolicyModal'
  import RapidBook from '../mixins/rapid-book'
  import rules from '../validation/rules'

  export default {
    name: 'RapidStep3',

    mixins: [RapidBook],

    components: {
      BookInput,
      BookCheckbox,
      BookButton,
      RapidTermsModal,
      RapidPolicyModal,
    },

    data: () => ({
      valid: true,

      form: {
        firstname: '',
        lastname: '',
        phone: '',
        email: '',
        password: '',
        confirm: false,
        gdpr: false,
        link: '',
      },

      isLoading: false,
      isLogoutLoading: false,

      isTermsModalVisible: false,
      isPolicyModalVisible: false,
    }),

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
        'bookStepSet',
      ]),

      ...mapActions([
        'bookStep',
        'authLogout',
      ]),

      initForm() {
        this.form.firstname = this.customer.firstname
        this.form.lastname = this.customer.lastname
        this.form.phone = this.customer.phone
        this.form.email = this.customer.email
        this.form.gdpr = this.customer.gdpr
      },

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First Name': r = ['require']; break
          case 'Last Name': r = ['require']; break
          case 'Phone Number': r = ['require', 'phone']; break
          case 'Email Address': r = ['require', 'email']; break
          case 'Password': r = ['require', 'min']; o = { min: 8 }; break
          case 'Confirm checkbox': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onTermsModal() {
        this.isTermsModalVisible = true
      },

      onTermsModalClose() {
        this.isTermsModalVisible = false
      },

      onPolicyModal() {
        this.isPolicyModalVisible = true
      },

      onPolicyModalClose() {
        this.isPolicyModalVisible = false
      },

      onNextStep() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          if (this.customer.id) {
            this.bookStep({
              step: 4,
            })
          } else {
            this.form.link = `${window.location.protocol}//${window.location.host}/panel/activate/{hash}`
            this.authBook(this.form).then(me => {
              if (me) {
                this.usersCardAll({
                  userId: me.id,
                })

                this.bookStep({
                  step: 4,
                })
              }

              this.isLoading = false
            })
          }
        }
      },

      onLogout() {
        this.isLogoutLoading = true
        this.authLogout()

        this.form.firstname = ''
        this.form.lastname = ''
        this.form.phone = ''
        this.form.email = ''
        this.form.confirm = false
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
  }

  @media (max-width: 768px) {
    .step3 {
      &-bottom {
        text-align: center;
        padding-top: 15px;
      }
    }
  }
</style>