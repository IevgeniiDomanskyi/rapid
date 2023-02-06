<template>
  <v-dialog
    v-model="dialog"
    width="500"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Add Card
      </template>
      
      <v-form
        v-model="valid"
        ref="form"
        lazy-validation
      >
        <v-row>
          <v-col
            v-if="cards.length"
            :cols="12"
            :sm="8"
            class="py-0"
          >
            <app-select
              v-model="card"
              :items="cardsList"
              :rules="validate('Card')"
              label="Select Card"
              @submit="onSave"
            />
          </v-col>

          <v-col
            v-else
            :cols="12"
            :sm="8"
            class="py-0"
          >
            <app-text
              v-if="hasPaymentRequest"
              align="center"
              size="xs"
            >
              You already sent request to customer
            </app-text>
            
            <app-button
              v-if="hasPaymentRequest"
              :loading="isRequestLoading"
              label="Request Payment Details Again"
              color="primary"
              outlined
              small
              block
              @click="onRequestPayment"
            />

            <app-text
              v-if=" ! hasPaymentRequest"
              align="center"
              size="xs"
            >
              Customer hasn't any cards
            </app-text>
            
            <app-button
              v-if=" ! hasPaymentRequest"
              :loading="isRequestLoading"
              label="Request Payment Details"
              color="primary"
              outlined
              small
              block
              @click="onRequestPayment"
            />
          </v-col>

          <v-col
            v-if="hasPersonalDetails"
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-text
              align="center"
              size="xs"
            >
              &nbsp;
            </app-text>
            
            <app-button
              id="addCardModal"
              label="Add Card"
              color="primary"
              outlined
              small
              block
            />

            <br />
          </v-col>

          <v-col
            v-else
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-text
              align="center"
              size="xs"
            >
              For adding cards
            </app-text>
            
            <app-button
              label="Fill User Profile"
              color="primary"
              outlined
              small
              block
              @click="onEditCustomer"
            />

            <br />
          </v-col>
        </v-row>
      </v-form>

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          :loading="isLoading"
          label="Save"
          color="primary"
          @click="onSave"
        />
      </template>

      <rapid-customer-modal
        v-model="customerModal"
        :exist-customer="existCustomer"
        @close="onCustomerModalClose"
      />
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapGetters, mapMutations, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppInputText from './AppInputText'
  import AppText from './AppText'
  import AppButton from './AppButton'
  import AppSelect from './AppSelect'
  import RapidCustomerModal from './RapidCustomerModal'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'RapidAddCardModal',

    mixins: [Rapid],

    components: {
      AppCard,
      AppInputText,
      AppText,
      AppButton,
      AppSelect,
      RapidCustomerModal,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      item: {
        type: Object,
        default: () => {},
      },

      type: {
        type: String,
        default: null,
      },
    },

    data: () => ({
      customerModal: false,
      valid: true,
      existCustomer: {},

      card: '',

      isLoading: false,
      isRequestLoading: false,
      isRequestSent: false,
    }),

    computed: {
      ...mapState({
        cards: state => state.users.cards,
      }),

      ...mapGetters([
        'customerById',
      ]),

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      hasPersonalDetails() {
        if (this.item.user) {
          const user = this.customerById(this.item.user.id)
          return user.address && user.phone != '' && user.phone.length == 11 && user.email != '' && user.firstname != '' && user.lastname != ''
        }

        return false
      },

      hasPaymentRequest() {
        if (this.item.user) {
          const user = this.customerById(this.item.user.id)
          return user.payment_request || this.isRequestSent
        }

        return false
      },

      cardsList() {
        let result = []
        for (const card of this.cards) {
          result.push({
            value: card.id,
            text: 'XXXX-XXXX-XXXX-' + card.end,
          })
        }
        return result
      },
    },

    watch: {
      hasPersonalDetails() {
        this.requestCardDetails()
      },
    },

    created() {
      if (this.item.user) {
        this.usersCardAll({
          userId: this.item.user.id,
        }).then(() => {
          this.requestCardDetails()
        })
      }
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'usersCardAll',
        'usersCardDetails',
        'usersRequestPaymentDetails',
        'usersCardConnect',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Card': r = ['require']; break
          default: r = []; o = {}
        }

        return rules.assign(field, r, o)
      },

      requestCardDetails() {
        if (this.hasPersonalDetails) {
          this.usersCardDetails({ userId: this.item.user.id }).then(data => {
            if (data) {
              const response = JSON.parse(data)
              const responseEndpoint = `${window.location.protocol}//${window.location.host}${window.location.pathname}`
              if (process.env.MIX_GP_MODE == 'dev') {
                RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay")
              } else {
                RealexHpp.setHppUrl("https://pay.realexpayments.com/pay")
              }
              RealexHpp.lightbox.init("addCardModal", responseEndpoint, response)
            }
          })
        }
      },

      onEditCustomer() {
        this.existCustomer = this.item.user
        this.customerModal = true
      },

      onRequestPayment() {
        this.isRequestLoading = true
        this.usersRequestPaymentDetails({ userId: this.item.user.id }).then(data => {
          this.isRequestLoading = false
          this.$emit('refresh')

          if (data) {
            this.isRequestSent = true
          }
        })
      },

      onCustomerModal() {
        this.customerModal = true
      },

      onCustomerModalClose() {
        this.customerModal = false
        this.existCustomer = {}
        this.$emit('refresh')
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          if (this.card != '' && this.card > 0) {
            this.usersCardConnect({
              id: this.item.id,
              type: this.type,
              card: this.card,
            }).then(data => {
              this.isLoading = false
              if (data) {
                this.$emit('refresh')
                this.onClose()

                this.card = ''
              }
            })
          } else {
            this.isLoading = false

            this.messagesSet({
              type: 'error',
              text: 'You should select a card',
            })
          }
        }
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>