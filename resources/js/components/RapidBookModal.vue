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
        Add Booking
      </template>
      
      <v-form
        v-model="valid"
        ref="form"
        lazy-validation
      >
        <v-row>
          <v-col
            :cols="12"
            :sm="8"
            class="py-0"
          >
            <app-autocomplete
              v-model="book.customer"
              :items="customersList"
              :rules="validate('Customer')"
              :multiple="false"
              :chips="false"
              item-value="value"
              item-text="text"
              label="Select Customer"
              @input="onCustomerSelect"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-text
              v-if="isAdmin"
              align="center"
              size="xs"
            >
              Not on the list?
            </app-text>
            
            <app-button
              v-if="isAdmin"
              label="Add Customer"
              color="primary"
              outlined
              small
              block
              @click="onCustomerModal"
            />
          </v-col>
        </v-row>

        <v-row
          v-if="book.customer"
        >
          <v-col
            v-if="cards.length"
            :cols="12"
            :sm="8"
            class="py-0"
          >
            <app-select
              v-model="book.card"
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
              v-if="isAdmin"
              align="center"
              size="xs"
            >
              {{ customerById(book.customer).payment_request ? "You already sent request to customer" : "Customer hasn't any cards" }}
            </app-text>
            
            <app-button
              v-if="isAdmin"
              :loading="isRequestLoading"
              :label="customerById(book.customer).payment_request ? 'Request Payment Details Again' : 'Request Payment Details'"
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
              v-if="isAdmin"
              align="center"
              size="xs"
            >
              &nbsp;
            </app-text>
            
            <app-button
              v-if="isAdmin"
              id="addCardLight"
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
              v-if="isAdmin"
              align="center"
              size="xs"
            >
              For adding cards
            </app-text>
            
            <app-button
              v-if="isAdmin"
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

        <v-row>
          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-select
              v-model="book.region"
              :items="regionList"
              :rules="validate('Region')"
              label="Region"
              @submit="onSave"
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-select
              v-model="book.course"
              :items="coursesList"
              :rules="validate('Course')"
              label="Course"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-select
              v-model="book.level"
              :items="levelsList"
              :rules="validate('Level')"
              label="Level"
              @submit="onSave"
            />
          </v-col>
        </v-row>

        <v-radio-group
          v-model="book.plan"
          row
          class="book-radio-group"
        >
          <v-row>
            <v-col
              v-for="item in plans"
              :key="item.value"
              :cols="6"
              class="py-0"
            >
              <v-radio
                :value="item.value"
                :label="item.label"
                :color="$colors.primary"
              />

              <app-input-text
                v-if="item.value == 0"
                v-model="book.voucher"
                placeholder="Add voucher code"
              />

              <app-select
                v-if="item.value == 1 && book.plan == 1"
                v-model="book.instalment"
                :items="instalments"
                outlined
                dense
              />
            </v-col>
          </v-row>

          <!-- <v-row>
            <v-col
              :cols="12"
              class="py-0"
            >
              <app-checkbox
                v-model="book.request"
              >
                Request payment detaiils
              </app-checkbox>
            </v-col>
          </v-row> -->
        </v-radio-group>
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
          label="Save Booking"
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
  import AppCheckbox from './AppCheckbox'
  import AppAutocomplete from './AppAutocomplete'
  import RapidCustomerModal from './RapidCustomerModal'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'RapidBookModal',

    mixins: [Rapid],

    components: {
      AppCard,
      AppInputText,
      AppText,
      AppButton,
      AppSelect,
      AppCheckbox,
      AppAutocomplete,
      RapidCustomerModal,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },
    },

    data: () => ({
      customerModal: false,
      valid: true,
      existCustomer: {},

      book: {
        customer: '',
        card: '',
        region: '',
        course: '',
        level: '',
        plan: 0,
        voucher: '',
        instalment: 2,
        request: false,
      },

      isLoading: false,
      isRequestLoading: false,
    }),

    computed: {
      ...mapState({
        customers: state => state.users.customers,
        regions: state => state.regions.list,
        courses: state => state.courses.list,
        cards: state => state.users.cards,
      }),

      ...mapGetters([
        'courseById',
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
        const user = this.customerById(this.book.customer)
        return user.address && user.phone != '' && user.phone.length == 11 && user.email != '' && user.firstname != '' && user.lastname != ''
      },

      customersList() {
        let result = []
        for (const customer of this.customers) {
          result.push({
            value: customer.id,
            text: (customer.firstname + ' ' + customer.lastname),
          })
        }
        return result
      },

      regionList() {
        let result = []
        for (const region of this.regions) {
          result.push({
            value: region.id,
            text: region.title,
          })
        }
        return result
      },

      postcodesList() {
        let result = []
        for (const postcode of this.postcodes) {
          result.push({
            value: postcode.id,
            text: (postcode.code + ' (' + postcode.area + ')'),
          })
        }
        return result
      },

      coursesList() {
        let result = []
        for (const course of this.courses) {
          result.push({
            value: course.id,
            text: course.title,
          })
        }
        return result
      },

      levelsList() {
        let result = []
        if (this.book.course) {
          for (const course of this.courses) {
            if (this.book.course == course.id) {
              for (const level of course.levels) {
                result.push({
                  value: level.id,
                  text: (level.level > 0 ? ('Level ' + level.level + ' - ') : '') + level.title,
                })
              }
            }
          }
        }
        return result
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

      plans() {
        return [
          { value: 0, label: 'One-off payment' },
          { value: 1, label: 'Pay By Instalments' },
        ]
      },

      instalments() {
        let price = 0
        const course = this.courseById(this.book.course)
        if (course && course.id) {
          if (course.levels) {
            for (const level of course.levels) {
              if (level.id == this.book.level) {
                price = level.price
              }
            }
          }

          return [
            { value: 2, text: ('2 instalments of £' + (Math.round(price / 2))) },
            { value: 3, text: ('3 instalments of £' + (Math.round(price / 3))) },
            { value: 4, text: ('4 instalments of £' + (Math.round(price / 4))) },
          ]
        }

        return []
      },
    },

    created() {
      this.usersCustomers()
      this.regionsAll()
      this.coursesAll()
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'regionsAll',
        'coursesAll',
        'usersCustomers',
        'usersCardAll',
        'usersCardDetails',
        'usersRequestPaymentDetails',
        'bookCreate',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Customer': r = ['require']; break
          // case 'Card': r = ['require']; break
          case 'Region': r = ['require']; break
          case 'Course': r = ['require']; break
          case 'Level': r = ['require']; break
          default: r = []; o = {}
        }

        return rules.assign(field, r, o)
      },

      onCustomerSelect() {
        this.book.card = ''

        this.usersCardAll({
          userId: this.book.customer,
        }).then(() => {
          this.requestCardDetails()
        })
      },

      requestCardDetails() {
        if (this.hasPersonalDetails) {
          this.usersCardDetails({ userId: this.book.customer }).then(data => {
            if (data) {
              const response = JSON.parse(data)
              const responseEndpoint = `${window.location.protocol}//${window.location.host}${window.location.pathname}`
              if (process.env.MIX_GP_MODE == 'dev') {
                RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay")
              } else {
                RealexHpp.setHppUrl("https://pay.realexpayments.com/pay")
              }
              RealexHpp.lightbox.init("addCardLight", responseEndpoint, response)
            }
          })
        }
      },

      onEditCustomer() {
        this.existCustomer = this.customerById(this.book.customer)
        this.customerModal = true
      },

      onRequestPayment() {
        this.isRequestLoading = true
        this.usersRequestPaymentDetails({ userId: this.book.customer }).then(() => {
          this.usersCustomers().then(() => {
            this.isRequestLoading = false
            this.requestCardDetails()
          })
        })
      },

      onCustomerModal() {
        this.customerModal = true
      },

      onCustomerModalClose() {
        this.customerModal = false
        this.existCustomer = {}
        this.usersCustomers().then(() => {
          this.requestCardDetails()
        })
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          /* if (this.book.card != '' && this.book.card > 0) { */
            this.bookCreate(this.book).then(data => {
              this.isLoading = false
              if (data) {
                this.onClose()

                this.book = {
                  customer: '',
                  card: '',
                  region: '',
                  course: '',
                  level: '',
                  plan: 0,
                  voucher: '',
                  instalment: 2,
                  request: false,
                }
              }
            })
          /* } else {
            this.isLoading = false

            this.messagesSet({
              type: 'error',
              text: 'You should select a card to make a booking',
            })
          } */
        }
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>