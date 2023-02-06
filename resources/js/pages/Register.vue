<template>
  <v-app>
    <v-main
      class="rapid-panel"
    >
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            class="d-flex flex-column align-center"
          >
            <app-logo
              :width="150"
            />

            <app-card
              :width="290"
              class="ma-4"
            >
              <app-text
                size="sm"
                color="text_inverse"
                class="mb-4"
              >
                Sign up to your Rapid Account
              </app-text>

              <v-form
                ref="form"
                v-model="valid"
                lazy-validation
              >
                <app-input-text
                  v-model="auth.firstname"
                  :rules="validate('First name')"
                  label="First name"
                  type="text"
                  @submit="onRegister"
                />

                <app-input-text
                  v-model="auth.lastname"
                  :rules="validate('Last name')"
                  label="Last name"
                  type="text"
                  @submit="onRegister"
                />

                <app-input-text
                  v-model="auth.email"
                  :rules="validate('Email')"
                  label="Email"
                  type="email"
                  @submit="onRegister"
                />

                <app-input-text
                  v-model="auth.password"
                  :rules="validate('Password')"
                  label="Password"
                  type="password"
                  @submit="onRegister"
                />

                <app-checkbox
                  v-model="auth.confirm"
                  :rules="validate('Confirm checkbox')"
                  class="mb-4"
                >
                  <app-link
                    href="/panel/terms"
                  >
                    Agree the terms and policy
                  </app-link>
                </app-checkbox>
              </v-form>

              <template slot="actions">
                <app-button
                  :loading="isLoading"
                  label="Sign Up"
                  block
                  color="primary"
                  @click="onRegister"
                />
              </template>
            </app-card>

            <app-text>
              Already have an account?

              <app-link
                href="/panel"
              >
                Sign in
              </app-link>
            </app-text>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import AppLogo from '../components/AppLogo'
  import AppCard from '../components/AppCard'
  import AppText from '../components/AppText'
  import AppButton from '../components/AppButton'
  import AppInputText from '../components/AppInputText'
  import AppCheckbox from '../components/AppCheckbox'
  import AppLink from '../components/AppLink'
  import rules from '../validation/rules'

  export default {
    name: 'Register',

    components: {
      AppLogo,
      AppCard,
      AppText,
      AppButton,
      AppInputText,
      AppCheckbox,
      AppLink,
    },

    data() {
      return {
        valid: true,

        auth: {
          firstname: '',
          lastname: '',
          email: '',
          password: '',
          confirm: false,
          link: '',
        },

        isLoading: false,
      }
    },

    computed: {
      ...mapState({
        token: state => state.auth.token,
      }),

      isLoggedIn() {
        return !! this.token
      },
    },

    watch: {
      isLoggedIn(val) {
        if (val) {
          this.$router.replace('/panel')
        }
      },
    },

    created() {
      if (this.isLoggedIn) {
        this.$router.replace('/panel')
      }
    },

    methods: {
      ...mapActions([
        'authRegister',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First name': r = ['require']; break
          case 'Last name': r = ['require']; break
          case 'Email': r = ['require', 'email']; break
          case 'Password': r = ['require', 'min']; o = { min: 8 }; break
          case 'Confirm checkbox': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onRegister() {
        if (this.$refs.form.validate()) {
          this.isLoading = true
          this.auth.link = `${window.location.protocol}//${window.location.host}/panel/activate/{hash}`
          this.authRegister(this.auth)
        }
      },
    },
  }
</script>

<style lang="scss">
  @import '../../sass/_variables.scss';
</style>