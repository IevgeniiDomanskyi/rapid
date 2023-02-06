<template>
  <v-app
    v-if="loaded"
  >
    <router-view
      v-if="isLoggedIn"
    />
    
    <v-main
      v-else
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
                Sign in with your Rapid Account
              </app-text>

              <v-form
                ref="form"
                v-model="valid"
                lazy-validation
              >
                <app-input-text
                  v-model="auth.email"
                  :rules="validate('Email')"
                  label="Email"
                  type="email"
                  @submit="onLogin"
                />

                <app-input-text
                  v-model="auth.password"
                  :rules="validate('Password')"
                  label="Password"
                  type="password"
                  @submit="onLogin"
                />

                <app-checkbox
                  v-model="auth.remember"
                  label="Keep me signed in"
                  class="mb-4"
                />
              </v-form>

              <template slot="actions">
                <app-button
                  :loading="isLoading"
                  label="Sign In"
                  block
                  color="primary"
                  @click="onLogin"
                />
              </template>
            </app-card>

            <div class="mb-4">
              <app-link
                href="/panel/recovery"
              >
                Forgot password?
              </app-link>
            </div>

            <app-text>
              Do not have an account?

              <app-link
                href="/panel/register"
              >
                Sign up
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
    name: 'Login',

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
          email: '',
          password: '',
          remember: false,
        },

        loaded: false,
        isLoading: false,
      }
    },

    computed: {
      ...mapState({
        me: state => state.auth.me,
        token: state => state.auth.token,
      }),

      isLoggedIn() {
        return !! this.token
      },
    },

    mounted() {
      if (this.isLoggedIn) {
        this.authMe().then(data => {
          this.loaded = true
        })
      } else {
        this.loaded = true
      }
    },

    methods: {
      ...mapActions([
        'authMe',
        'authLogin',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Email': r = ['require', 'email']; break
          case 'Password': r = ['require', 'min']; o = { min: 8 }; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onLogin() {
        this.isLoading = true
        if (this.$refs.form.validate()) {
          this.authLogin(this.auth).then(data => {
            this.isLoading = false
          })
        }
      },
    },
  }
</script>