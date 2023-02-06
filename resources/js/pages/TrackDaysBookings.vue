<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Track Days Bookings
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <div
              class="d-flex flex-column flex-sm-row justify-space-between align-center mb-4"
            >
              <div
                class="rapid-search-field"
              >
                <app-input-text
                  v-model="search"
                  inverse
                  label="Search..."
                />
              </div>
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="bookingsModified"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.date`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.date }}
                </span>
              </template>

              <template v-slot:[`item.track`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.track }}
                </span>
              </template>

              <template v-slot:[`item.phone`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.user.phone }}
                </span>

                <app-link
                  :href="'/panel/customers/' + item.user.id"
                  class="text-no-wrap"
                >
                  Details
                </app-link>
              </template>

              <template v-slot:[`item.regionField`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.regionField }}
                </span>
              </template>

              <template v-slot:[`item.coachField`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.coachField }}
                </span>
              </template>

              <template v-slot:[`item.payment`]="{ item }">
                <div
                  v-if="! item.is_paid"
                  class="d-flex"
                >
                  <app-button
                    v-if="item.card"
                    label="Get Paid"
                    color="primary"
                    small
                    @click="onGetPay(item)"
                  />

                  <app-button
                    v-else
                    label="Add Card"
                    color="primary"
                    outlined
                    small
                    @click="onAddCard(item)"
                  />

                  <app-button
                    label="Bank"
                    color="primary"
                    outlined
                    small
                    class="ml-2"
                    @click="onBankTransfer(item)"
                  />
                </div>

                <app-chip
                  v-if="item.is_paid"
                  color="green"
                >
                  Paid&nbsp;<span v-if="item.is_bank">(Bank)</span>
                </app-chip>
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <rapid-add-card-modal
      v-if="bookPayObject.id"
      v-model="isAddCardModalVisible"
      :item="bookPayObject"
      type="event"
      @refresh="onEventRefresh"
      @close="onAddCardModalClose"
    />

    <app-confirm-modal
      v-model="getPayConfirmModal"
      title="Confirm Payment"
      @confirm="onConfirmGetPay"
      @close="getPayConfirmModal = false"
    >
      Do you really want get payment from this client?
      <br /><br />
    </app-confirm-modal>

    <app-confirm-modal
      v-model="bankTransferConfirmModal"
      title="Confirm Bank Transfer"
      @confirm="onConfirmBankTransfer"
      @close="bankTransferConfirmModal = false"
    >
      Do you really want to mark this booking as paid via bank transfer?
      <br /><br />
    </app-confirm-modal>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import moment from 'moment'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppButton from '../components/AppButton'
  import AppText from '../components/AppText'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import AppInputText from '../components/AppInputText'
  import AppConfirmModal from '../components/AppConfirmModal'
  import AppLink from '../components/AppLink'
  import RapidAddCardModal from '../components/RapidAddCardModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'TrackDayBookings',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppButton,
      AppText,
      AppTable,
      AppChip,
      AppInputText,
      AppConfirmModal,
      AppLink,
      RapidAddCardModal,
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'Order ID', value: 'id' },
        { text: 'Date', value: 'date', },
        { text: 'Track', value: 'track', },
        { text: 'Member Number', value: 'user.id', },
        { text: 'Full Name', value: 'full_name', },
        { text: 'Phone', value: 'phone', },
        { text: 'Region', value: 'regionField', },
        { text: 'Coach', value: 'coachField', },
        { text: 'Book Date', value: 'booked_at', },
        { text: 'Payment', value: 'payment', },
      ],

      bookPayObject: {},
      isAddCardModalVisible: false,

      getPayConfirmModal: false,
      bankTransferConfirmModal: false,

      selected: [],
      exportOptions: {
        file: 'track-day-booking',
        type: 'track-day-booking',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        bookings: state => state.trackDays.bookings,
      }),

      bookingsModified() {
        let result = []
        for (const key in this.bookings) {
          let item = { ...this.bookings[key] }

          item.full_name = item.user.firstname + ' ' + item.user.lastname
          item.phone = item.user.phone

          item.track = item.trackDay.track_date.track.name
          item.date = item.trackDay.track_date.date

          item.regionField = (item.trackDay.track_date.track.region ? item.trackDay.track_date.track.region.title : '')
          item.coachField = item.trackDay.coach ? (item.trackDay.coach.firstname + ' ' + item.trackDay.coach.lastname) : ''

          result.push(item)
        }

        return result
      },
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.trackDaysBookings()
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'trackDaysBookings',
        'trackDaysPay',
      ]),

      onTrackDayRefresh() {
        this.trackDaysBookings()
      },

      onGetPay(book) {
        this.bookPayObject = book
        this.getPayConfirmModal = true
      },

      onAddCard(book) {
        this.bookPayObject = book
        this.isAddCardModalVisible = true
      },

      onAddCardModalClose() {
        this.isAddCardModalVisible = false
        this.bookPayObject = {}
      },

      onBankTransfer(book) {
        this.bookPayObject = book
        this.bankTransferConfirmModal = true
      },

      onConfirmGetPay() {
        this.getPayConfirmModal = false
        this.trackDaysPay({
          id: this.bookPayObject.id,
          bank: false,
        }).then(() => {
          this.bookPayObject = {}
        })
      },

      onConfirmBankTransfer() {
        this.bankTransferConfirmModal = false
        this.trackDaysPay({
          id: this.bookPayObject.id,
          bank: true,
        }).then(() => {
          this.bookPayObject = {}
        })
      },
    },
  }
</script>