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
                color="text_inverse"
                class="mb-4"
              >
                Forgot your password?
              </app-text>

              <app-text
                size="sm"
                color="text_inverse"
                class="mb-4"
              >
                Enter your email address below and we will send you instructions on how to change your password.
              </app-text>

              <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                @submit.prevent="onRecovery"
              >
                <app-input-text
                  v-model="auth.email"
                  :rules="validate('Email')"
                  label="Your Email"
                  type="email"
                  class="mb-4"
                  @submit="onRecovery"
                />
              </v-form>

              <template slot="actions">
                <app-button
                  label="Send"
                  block
                  color="primary"
                  @click="onRecovery"
                />
              </template>
            </app-card>

            <app-text
              color="text"
            >
              Return to

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
  import AppLink from '../components/AppLink'
  import rules from '../validation/rules'

  export default {
    name: 'Recovery',

    components: {
      AppLogo,
      AppCard,
      AppText,
      AppButton,
      AppInputText,
      AppLink,
    },

    data() {
      return {
        valid: true,

        auth: {
          email: '',
        },
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
        'authRecovery',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Email': r = ['require', 'email']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onRecovery() {
        if (this.$refs.form.validate()) {
          this.authRecovery(this.auth)
        }
      },
    },
  }
</script>

<style lang="scss">
  @import '../../sass/_variables.scss';
</style>