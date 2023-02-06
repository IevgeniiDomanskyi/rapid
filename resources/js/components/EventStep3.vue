<template>
  <div>
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

          <!-- <div class="mt-4">
            <i>* No payment will be taken at this stage.</i>
          </div> -->
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
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookInput from './BookInput'
  import BookRadioGroup from './BookRadioGroup'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'
  import rules from '../validation/rules'

  export default {
    name: 'EventStep3',

    mixins: [RapidBook],

    components: {
      BookInput,
      BookRadioGroup,
      BookButton,
    },

    data: (vm) => ({
      isExpireModalVisible: false,

      valid: true,

      selectedCard: null,
      voucher: '',

      min: new Date().toISOString().substr(0, 10),

      form: {
        card: '',
        expire: new Date().toISOString().substr(0, 10),
        name: '',
      },

      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),

      isLoading: false,
      isCardLoading: false,
    }),

    computed: {
      ...mapState({
        cards: state => state.users.cards,
        options: state => state.events.options,
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
      this.eventsStep({
        step: 5,
      })

      return
    },

    mounted() {
      const eventId = this.$route.params.eventId
      this.selectedCard = this.options.card

      this.usersCardDetails({ userId: this.customer.id }).then(data => {
        if (data) {
          const response = JSON.parse(data)
          const responseEndpoint = `${window.location.protocol}//${window.location.host}/event/${eventId}`
          if (process.env.MIX_GP_MODE == 'dev') {
            RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay")
          } else {
            RealexHpp.setHppUrl("https://pay.realexpayments.com/pay")
          }
          RealexHpp.lightbox.init("addCardLight", responseEndpoint, response)
        }
      })

      this.usersCardAll({ userId: this.customer.id }).then(data => {
        if (data && data.length && ! this.selectedCard) {
          this.selectedCard = data[0].id
        }
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'eventsStep',
        'usersCardDetails',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Credit Card Number': r = ['require', 'max']; o = {max: 16}; break
          case 'Expiry Date': r = ['require']; break
          case 'Cardholder Name': r = ['require']; break
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

      onNextStep() {
        this.isLoading = true

        if (this.selectedCard > 0) {
          this.saveResult()
        } else {
          this.messagesSet({
            text: 'Please add your card',
            type: 'error',
          })

          this.isLoading = false
        }
      },

      saveResult() {
        this.eventsStep({
          step: 5,
          options: {
            card: this.selectedCard,
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