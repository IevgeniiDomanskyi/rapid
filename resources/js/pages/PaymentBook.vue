<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Payment Process
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        v-if="payment.book"
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <app-text
              size="lg"
            >
              {{ payment.book.course.title }}
            </app-text>

            <app-text
              size="md"
            >
              <span
                v-if="payment.book.level.level > 0"
              >
                Level {{ payment.book.level.level }}: 
              </span>
              {{ payment.book.level.title }}
            </app-text>

            <app-text
              size="lg"
              class="mb-4"
            >
              &pound;{{ payment.amount }}
              <small
                v-if="payment.instalment > 1"
              >
                ({{ payment.instalment }} instalments of &pound;{{ Math.round(payment.amount / payment.instalment) }})
              </small>
            </app-text>

            <div
              v-if="payment.status"
            >
              <app-chip
                :color="$colors.green"
                dark
              >
                <app-text
                  :color="$colors.white"
                >
                  Paid Successfully
                </app-text>
              </app-chip>

              <app-button
                color="default"
                label="Back to Payments"
                small
                text
                @click="onPayments"
              />
            </div>

            <v-row
              v-else
            >
              <v-col
                :xl="2"
                :lg="3"
                :md="4"
                :sm="6"
                :xs="12"
              >
                <v-form
                  ref="form"
                  v-model="valid"
                  lazy-validation
                >
                  <app-text>
                    Select Card:
                  </app-text>

                  <app-radio-group
                    v-model="card"
                    :list="cardList"
                    :rules="validate('Card')"
                  />

                  <app-input-text
                    v-model="cvv"
                    :rules="validate('CVV Code')"
                    inverse
                    label="CVV Code"
                    class="mb-4"
                  />

                  <app-button
                    :loading="isLoading"
                    color="primary"
                    label="Make a Payment"
                    @click="onPay"
                  />
                </v-form>
              </v-col>
            </v-row>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppChip from '../components/AppChip'
  import AppRadioGroup from '../components/AppRadioGroup'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'PaymentBook',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppChip,
      AppRadioGroup,
      AppInputText,
      AppButton,
    },

    data: () => ({
      valid: true,
      card: null,
      cvv: null,
      isLoading: false,
    }),

    computed: {
      ...mapState({
        payment: state => state.payment.current,
        cards: state => state.users.cards,
      }),

      cardList() {
        let result = []
        for (const card of this.cards) {
          result.push({
            value: card.id,
            label: 'XXXX-XXXX-XXXX-' + card.end,
          })

          if (this.cards.length == 1) {
            this.selectedCard = card.id
          }
        }

        return result
      },
    },

    created() {
      if (this.me.role != 0) {
        return this.$router.replace('/panel')
      }

      const { paymentId } = this.$router.history.current.params
      if (paymentId) {
        this.paymentGet({ userId: this.me.id, paymentId }).then(data => {
          if ( ! data.status) {
            this.usersCardAll({ userId: this.me.id })
          }
        })
      }
    },

    methods: {
      ...mapActions([
        'paymentGet',
        'paymentPay',
        'usersCardAll',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Card': r = ['require']; break
          case 'CVV Code': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onPay() {
        if (this.$refs.form.validate()) {
          this.isLoading = true
          this.paymentPay({
            userId: this.me.id,
            paymentId: this.payment.id,
            card: this.card,
            cvv: this.cvv,
          })
        }
      },

      onPayments() {
        this.$router.push('/panel/payment')
      },
    },
  }
</script>