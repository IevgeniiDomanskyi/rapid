<template>
  <div
    v-if="isAddress"
  >
    <div class="step5">
      <v-row>
        <v-col
          :cols="12"
          :md="6"
        >
          <book-radio-group
            v-model="selectedCard"
            :list="cardList"
          />

          <book-button
            :loading="isCardLoading"
            id="addCardLight"
            label="Add Card"
            color="red"
          />

          <div class="mt-4">
            <i>* No payment will be taken at this stage.</i>
          </div>
        </v-col>

        <v-col
          v-if="selectedCard"
          :cols="12"
          :md="6"
          :lg="4"
        >
          <div
            v-if="user.address"
          >
            <p class="step5-p">
              <b>Billing Address</b>&nbsp;&nbsp;&nbsp;<a href="javascript:;" @click="onEditAddress">Edit</a><br />
              {{ user.address.line1 }}<br />
              {{ user.address.line2 }}<br />
              {{ user.address.town }}, {{ user.address.county }}<br />
              {{ user.address.country }}, {{ user.address.postcode }}<br />
            </p>

            <br />
          </div>

          <p class="step5-p">
            Add voucher code
          </p>

          <book-input
            v-model="voucher"
            @keypress.enter="onNextStep"
          />
        </v-col>
      </v-row>
    </div>

    <div class="step5-bottom">
      <book-button
        :loading="isLoading"
        label="Next"
        color="red"
        @click="onNextStep"
      />
    </div>
  </div>

  <div
    v-else
  >
    <div class="step5">
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <p class="step5-p">
          Address Line 1
        </p>

        <book-input
          v-model="form.line1"
          :rules="validate('Address Line 1')"
          @submit="onSaveAddress"
        />

        <p class="step5-p">
          Address Line 2
        </p>

        <book-input
          v-model="form.line2"
          :rules="validate('Address Line 2')"
          @submit="onSaveAddress"
        />

        <p class="step5-p">
          Town/City
        </p>

        <book-input
          v-model="form.town"
          :rules="validate('Town/City')"
          @submit="onSaveAddress"
        />

        <p class="step5-p">
          County
        </p>

        <book-input
          v-model="form.county"
          :rules="validate('County')"
          @submit="onSaveAddress"
        />

        <p class="step5-p">
          Country
        </p>

        <v-select
          v-model="form.country"
          :items="countryList"
          outlined
          dense
          :rules="validate('Country')"
          @submit="onSaveAddress"
        />

        <p class="step5-p">
          Postcode
        </p>

        <book-input
          v-model="form.postcode"
          :rules="validate('Postcode')"
          @submit="onSaveAddress"
        />
      </v-form>
    </div>

    <div class="step5-bottom">
      <book-button
        :loading="isLoading"
        label="Save Billing Address"
        color="red"
        @click="onSaveAddress"
      />
    </div>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookInput from './BookInput'
  import BookRadioGroup from './BookRadioGroup'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'
  import rules from '../validation/rules'

  export default {
    name: 'RapidStep5',

    mixins: [RapidBook],

    components: {
      BookInput,
      BookRadioGroup,
      BookButton,
    },

    data: (vm) => ({
      isExpireModalVisible: false,

      valid: true,
      editAddress: false,

      selectedCard: null,
      voucher: '',

      min: new Date().toISOString().substr(0, 10),

      form: {
        line1: '',
        line2: '',
        town: '',
        county: '',
        country: '',
        postcode: '',
      },

      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),

      countryList: [
        { value: 'England', text: 'England' },
        { value: 'Scotland', text: 'Scotland' },
        { value: 'Wales', text: 'Wales' },
      ],

      isLoading: false,
      isCardLoading: false,
    }),

    computed: {
      ...mapState({
        cards: state => state.users.cards,
        options: state => state.book.options,
      }),

      isAddress() {
        return this.user.address && ! this.editAddress
      },

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

    /* created() {
      this.bookStep({
        step: 7,
      })

      return
    }, */

    mounted() {
      this.selectedCard = this.options.card
      this.voucher = this.options.voucher

      if (this.isAddress) {
        this.initPayment()
      }

      this.usersCardAll({ userId: this.customer.id }).then(data => {
        if (data && data.length && ! this.selectedCard) {
          this.selectedCard = data[0].id
        }
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
        'vouchersItemSet',
      ]),

      ...mapActions([
        'bookStep',
        'usersCardDetails',
        'vouchersCheck',
        'usersAddressSave',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Credit Card Number': r = ['require', 'max']; o = {max: 16}; break
          case 'Expiry Date': r = ['require']; break
          case 'Cardholder Name': r = ['require']; break

          case 'Address Line 1': r = ['require']; break
          case 'Town/City': r = ['require']; break
          // case 'County': r = ['require']; break
          case 'Country': r = ['require']; break
          case 'Postcode': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onExpireSelected(date) {
        this.$refs.dialog.save(date)
        this.isExpireModalVisible = false
        this.dateFormatted = this.formatDate(date)
      },

      formatDate (date) {
        if (!date) return null

        const [year, month] = date.split('-')
        return `${month}/${year}`
      },

      parseDate (date) {
        if (!date) return null

        const [month, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-01`
      },

      onEditAddress() {
        this.form = this.user.address
        this.editAddress = true
      },

      onSaveAddress() {
        if (this.$refs.form.validate()) {
          this.isLoading = true
          this.form.userId = this.user.id
          this.usersAddressSave(this.form).then(data => {
            this.isLoading = false
            this.editAddress = false
            this.authMe().then(() => {
              this.initPayment()
            })
          })
        }
      },

      initPayment() {
        this.usersCardDetails({ userId: this.customer.id }).then(data => {
          if (data) {
            const response = JSON.parse(data)
            const responseEndpoint = `${window.location.protocol}//${window.location.host}/`
            if (process.env.MIX_GP_MODE == 'dev') {
              RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay")
            } else {
              RealexHpp.setHppUrl("https://pay.realexpayments.com/pay")
            }
            RealexHpp.lightbox.init("addCardLight", responseEndpoint, response)
          }
        })
      },

      onNextStep() {
        this.isLoading = true

        if (this.selectedCard > 0) {
          if (!! this.voucher) {
            this.vouchersCheck({
              code: this.voucher,
            }).then(data => {
              if (data) {
                this.saveResult()
              } else {
                this.isLoading = false
              }
            })
          } else {
            this.vouchersItemSet({})
            this.saveResult()
          }
        } else {
          this.messagesSet({
            text: 'Please add your card',
            type: 'error',
          })

          this.isLoading = false
        }
      },

      saveResult() {
        this.bookStep({
          //step: 7,
          step: 6,
          options: {
            card: this.selectedCard,
            voucher: this.voucher,
          }
        }).then(data => {
          this.isLoading = false
        })
      },
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/_variables.scss";
  @import "../../sass/book.scss";

  .step5 {
    &-p {
      margin-bottom: 5px;
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step5 {
      &-bottom {
        text-align: center;
        padding-top: 15px;
      }
    }
  }
</style>