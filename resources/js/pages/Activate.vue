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
                Email confirmation
              </app-text>

              <app-text
                v-if="isHashConfirmed"
                size="sm"
                color="text_inverse"
                class="mb-4"
              >
                Thank you for confirming your email.
              </app-text>

              <app-text
                v-if="isHashConfirmed == false"
                size="sm"
                color="text_inverse"
                class="mb-4"
              >
                This link is wrong. Try to check confirmation link and try again
              </app-text>

              <template slot="actions">
                <app-button
                  label="Login to my account"
                  block
                  color="primary"
                  @click="onLogin"
                />
              </template>
            </app-card>
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

  export default {
    name: 'Activate',

    components: {
      AppLogo,
      AppCard,
      AppText,
      AppButton,
    },

    data: () => ({
      isHashConfirmed: null,
    }),

    created() {
      if (this.$router.history.current.params.hash) {
        this.authActivate({ hash: this.$router.history.current.params.hash }).then(result => {
          this.isHashConfirmed = result
        })
      } else {
        this.isHashConfirmed = false
      }
    },

    methods: {
      ...mapActions([
        'authActivate',
      ]),

      onLogin() {
        this.$router.replace('/panel')
      },
    },
  }
</script>

<style lang="scss">
  @import '../../sass/_variables.scss';
</style>