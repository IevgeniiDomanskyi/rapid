<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Bookings
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <div
              class="d-flex justify-space-between align-center mb-4"
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

              <!-- <rapid-book-modal
                v-model="bookModal"
                @close="onBookModalClose"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-button
                    label="Add Booking"
                    color="primary"
                    icon="mdi-plus"
                    small
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
              </rapid-book-modal> -->
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="booksModified"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.courseField`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.courseField }}
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

              <template v-slot:[`item.statusField`]="{ item }">
                <app-chip
                  v-if="item.status == 2 && (item.course.id != 1 || (item.course.id == 1 && item.level.level == 1))"
                  color="#009fe3"
                >
                  New Order
                </app-chip>

                <app-chip
                  v-else
                  :color="item.statusColor"
                >
                  {{ item.statusText }}

                  <span
                    v-if="item.status == 6"
                  >
                    &nbsp;{{ item.road }} Signed
                  </span>
                </app-chip>
              </template>

              <template v-slot:[`item.regionField`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.region.title }}
                </span>
              </template>

              <template v-slot:[`item.coachField`]="{ item }">
                <div
                  v-if=" ! item.coach"
                  class="text-center"
                >
                  -
                </div>

                <div
                  v-else
                >
                  {{ item.coach.firstname }} {{ item.coach.lastname }}
                </div>
              </template>

              <template v-slot:[`item.date`]="{ item }">
                <div
                  v-if="item.status == 0"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status == 1 && ! item.track_date"
                  @click="onDefineTrackDate(item.id, item.postcode.id)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status > 1 && item.track_date"
                >
                  {{ item.track_date }}
                </div>
              </template>

              <template v-slot:[`item.road_date_1`]="{ item }">
                <div
                  v-if="item.status < 3 || item.level.road_dates < 1"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 1) && item.level.road_dates >= 1"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 1, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 1) && item.level.road_dates >= 1"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 1, roadDate(item.road_dates, 1), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 1) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 1) }}
                </div>
              </template>

              <template v-slot:[`item.road_date_2`]="{ item }">
                <div
                  v-if="item.status < 3 || (item.status >= 3 && ! roadDateIsset(item.road_dates, 1)) || item.level.road_dates < 2"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 2) && roadDateIsset(item.road_dates, 1) && item.level.road_dates >= 2"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 2, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 1) && roadDateIsset(item.road_dates, 2) && item.level.road_dates >= 2"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 2, roadDate(item.road_dates, 2), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 2) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 2) }}
                </div>
              </template>

              <template v-slot:[`item.road_date_3`]="{ item }">
                <div
                  v-if="item.status < 3 || (item.status >= 3 && ! roadDateIsset(item.road_dates, 2)) || item.level.road_dates < 3"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 3) && roadDateIsset(item.road_dates, 2) && item.level.road_dates >= 3"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 3, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 2) && roadDateIsset(item.road_dates, 3) && item.level.road_dates >= 2"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 3, roadDate(item.road_dates, 3), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 3) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 3) }}
                </div>
              </template>

              <template v-slot:[`item.road_date_4`]="{ item }">
                <div
                  v-if="item.status < 3 || (item.status >= 3 && ! roadDateIsset(item.road_dates, 3)) || item.level.road_dates < 4"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 4) && roadDateIsset(item.road_dates, 3) && item.level.road_dates >= 4"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 4, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 3) && roadDateIsset(item.road_dates, 4) && item.level.road_dates >= 4"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 4, roadDate(item.road_dates, 4), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 4) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 4) }}
                </div>
              </template>

              <template v-slot:[`item.road_date_5`]="{ item }">
                <div
                  v-if="item.status < 3 || (item.status >= 3 && ! roadDateIsset(item.road_dates, 4)) || item.level.road_dates < 5"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 5) && roadDateIsset(item.road_dates, 4) && item.level.road_dates >= 5"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 5, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 4) && roadDateIsset(item.road_dates, 5) && item.level.road_dates >= 5"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 5, roadDate(item.road_dates, 5), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 5) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 5) }}
                </div>
              </template>

              <template v-slot:[`item.road_date_6`]="{ item }">
                <div
                  v-if="item.status < 3 || (item.status >= 3 && ! roadDateIsset(item.road_dates, 5)) || item.level.road_dates < 6"
                  class="text-center"
                >
                  -
                </div>

                <v-icon
                  v-if="item.status >= 3 && ! roadDateIsset(item.road_dates, 6) && roadDateIsset(item.road_dates, 5) && item.level.road_dates >= 6"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 6, {}, item.status)"
                >
                  mdi-calendar-month
                </v-icon>

                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 5) && roadDateIsset(item.road_dates, 6) && item.level.road_dates >= 6"
                  class="pointer"
                  @click="onDefineRoadDate(item.id, item.coach.id, 6, roadDate(item.road_dates, 6), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 6) }}
                  <br />
                  {{ roadDateSlot(item.road_dates, 6) }}
                </div>
              </template>

              <template v-slot:[`item.action`]="{ item }">
                <app-button
                  v-if="item.status == 0 && ! item.track_date"
                  label="Choose Track Date"
                  color="brown"
                  small
                  @click="onDefineTrackDate(item.id, item.postcode.id)"
                />

                <app-button
                  v-if="item.status == 1"
                  label="Confirm Track"
                  color="red"
                  small
                  @click="onPayTrack(item.id)"
                />

                <app-button
                  v-if="item.status == 2 && ! item.coach"
                  label="Assign Coach"
                  color="grey"
                  small
                  @click="onAssignCoach(item.id, item.postcode.id)"
                />

                <app-button
                  v-if="item.status == 3"
                  label="Agree Road Dates"
                  color="green"
                  small
                  @click="onAgreeRoadDates(item.id)"
                />

                <app-button
                  v-if="item.status == 4"
                  label="Request Payment"
                  color="red"
                  small
                  @click="onRequestPayment(item.id)"
                />

                <app-chip
                  v-if="item.status == 5 || (item.status == 6 && item.road < item.road_dates.length)"
                  color="brown"
                >
                  Rider Declaration {{ item.road + 1 }}
                </app-chip>

                <app-button
                  v-if="item.status == 6 && item.road == item.road_dates.length"
                  :color="isRoadDatesPassed(item) ? 'blue' : 'grey'"
                  label="Reports/Certificates"
                  small
                  @click="onCertificates(item)"
                />

                <app-chip
                  v-if="item.status == 7"
                  color="#015322"
                >
                  Completed
                </app-chip>

                <app-chip
                  v-if="item.status == 10"
                  color="#d53e29"
                >
                  Canceled
                </app-chip>
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <rapid-define-track-date-modal
      v-model="isDefineTrackDateModalVisible"
      :book="book"
      @close="onDefineTrackDateModalClose"
    />

    <rapid-pay-track-modal
      v-model="isPayTrackModalVisible"
      :book="book"
      @close="onPayTrackModalClose"
    />

    <rapid-define-road-date-modal
      v-model="isDefineRoadDateModalVisible"
      :book="book"
      :road="road"
      :selectedDate="date"
      @close="onDefineRoadDateModalClose"
    />

    <rapid-agree-road-dates-modal
      v-model="isAgreeRoadDatesModalVisible"
      :book="book"
      @close="onAgreeRoadDatesModalClose"
    />

    <rapid-certificates-modal
      v-model="isCertificatesModalVisible"
      :book="bookObject"
      @close="onCertificatesModalClose"
    />
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import moment from 'moment'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppButton from '../components/AppButton'
  import AppText from '../components/AppText'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import AppInputText from '../components/AppInputText'
  import AppLink from '../components/AppLink'
  import RapidDefineTrackDateModal from '../components/RapidDefineTrackDateModal'
  import RapidPayTrackModal from '../components/RapidPayTrackModal'
  import RapidDefineRoadDateModal from '../components/RapidDefineRoadDateModal'
  import RapidAgreeRoadDatesModal from '../components/RapidAgreeRoadDatesModal'
  import RapidBookModal from '../components/RapidBookModal'
  import RapidCertificatesModal from '../components/RapidCertificatesModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'BookingsCoach',

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
      AppLink,
      RapidDefineTrackDateModal,
      RapidPayTrackModal,
      RapidDefineRoadDateModal,
      RapidAgreeRoadDatesModal,
      RapidBookModal,
      RapidCertificatesModal,
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'Order ID', value: 'order_id' },
        { text: 'Member Number', value: 'user.id', },
        { text: 'Full Name', value: 'full_name', },
        { text: 'Phone', value: 'phone', },
        { text: 'Date', value: 'created_at', },
        { text: 'Course', value: 'course.title', },
        { text: 'Order Status', value: 'statusField', },
        { text: 'Region', value: 'regionField', },
        { text: 'Coach', value: 'coachField', },
        { text: 'Track Date', value: 'date', align: 'center', },
        { text: 'Road Date 1', value: 'road_date_1', align: 'center', },
        { text: 'Road Date 2', value: 'road_date_2', align: 'center', },
        { text: 'Road Date 3', value: 'road_date_3', align: 'center', },
        { text: 'Road Date 4', value: 'road_date_4', align: 'center', },
        { text: 'Road Date 5', value: 'road_date_5', align: 'center', },
        { text: 'Road Date 6', value: 'road_date_6', align: 'center', },
        { text: 'Action required', value: 'action', },
      ],

      bookObject: {},
      book: 0,
      road: 0,
      date: {},
      bookModal: false,
      isDefineTrackDateModalVisible: false,
      isPayTrackModalVisible: false,
      isDefineRoadDateModalVisible: false,
      isAgreeRoadDatesModalVisible: false,
      isCertificatesModalVisible: false,

      selected: [],
      exportOptions: {
        file: 'bookings',
        type: 'book',
        roles: ['coach'],
      },
    }),

    computed: {
      ...mapState({
        books: state => state.coaches.books,
      }),

      booksModified() {
        let result = []
        for (const key in this.books) {
          let item = { ...this.books[key] }

          item.full_name = item.user.firstname + ' ' + item.user.lastname
          item.phone = item.user.phone

          item.courseField = item.course.title + (item.course.id != 3 ? (' ' + item.level.level) : (item.level.crm_id == 'Level_b_fd' ? ' FD' : ''))

          if (item.status == 2 && (item.course.id != 1 || (item.course.id == 1 && item.level.level == 1))) {
            item.statusField = 'New Order'
          } else {
            item.statusField = item.statusText

            if (item.status == 6) {
              item.statusField += (' ' + item.road + ' Signed')
            }
          }

          item.regionField = (item.region ? item.region.title : '')
          item.coachField = item.coach ? (item.coach.firstname + ' ' + item.coach.lastname) : ''
          item.date = item.status > 0 && item.track_date ? (item.track_date + ' ' + item.track_course) : ''

          item.road_date_1 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 1) && item.level.road_dates >= 1) {
            item.road_date_1 = this.roadDateFormat(item.road_dates, 1)
          }

          item.road_date_2 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 1) && this.roadDateIsset(item.road_dates, 2) && item.level.road_dates >= 2) {
            item.road_date_2 = this.roadDateFormat(item.road_dates, 2)
          }

          item.road_date_3 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 2) && this.roadDateIsset(item.road_dates, 3) && item.level.road_dates >= 3) {
            item.road_date_3 = this.roadDateFormat(item.road_dates, 3)
          }

          item.road_date_4 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 3) && this.roadDateIsset(item.road_dates, 4) && item.level.road_dates >= 4) {
            item.road_date_4 = this.roadDateFormat(item.road_dates, 4)
          }

          item.road_date_5 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 4) && this.roadDateIsset(item.road_dates, 5) && item.level.road_dates >= 5) {
            item.road_date_5 = this.roadDateFormat(item.road_dates, 5)
          }

          item.road_date_6 = ''
          if (item.status >= 3 && this.roadDateIsset(item.road_dates, 5) && this.roadDateIsset(item.road_dates, 6) && item.level.road_dates >= 6) {
            item.road_date_6 = this.roadDateFormat(item.road_dates, 6)
          }

          result.push(item)
        }

        return result
      },

      isRoadDatesPassed() {
        return (item) => {
          let check = 1
          for (let i = 1; i <= 6; i++) {
            if (item['road_date_' + i]) {
              const temp = item['road_date_' + i].split('/')
              check *= moment('20' + temp[2] + '-' + temp[1] + '-' + temp[0]).isBefore(new Date)
            }
          }

          return check
        }
      },
    },

    created() {
      if (this.me.role != 1) {
        return this.$router.replace('/panel')
      }

      this.coachesBooks()
    },

    methods: {
      ...mapActions([
        'coachesBooks',
        'tracksDatesByPostcode',
        'datesAll',
        'bookRequestPayment',
      ]),

      onDefineTrackDate(book, postcode) {
        this.tracksDatesByPostcode({
          postcode,
        }).then(data => {
          this.book = book
          this.isDefineTrackDateModalVisible = true
        })
      },

      onDefineTrackDateModalClose() {
        this.book = 0
        this.isDefineTrackDateModalVisible = false
        this.coachesBooks()
      },

      onPayTrack(book) {
        this.book = book
        this.isPayTrackModalVisible = true
      },

      onPayTrackModalClose() {
        this.book = 0
        this.isPayTrackModalVisible = false
        this.coachesBooks()
      },

      roadDate(dates, road) {
        for (const date of dates) {
          if (date.road == road) {
            return date
          }
        }

        return null
      },

      roadDateFormat(dates, road) {
        const date = this.roadDate(dates, road)
        if (date) {
          return date.date_human
        }

        return ''
      },

      roadDateSlot(dates, road) {
        const date = this.roadDate(dates, road)
        if (date) {
          if (date.slots[road]) {
            if (date.slots[road] == 3) {
              return 'Full Day'
            }

            if (date.slots[road] == 1) {
              return 'AM'
            }

            if (date.slots[road] == 2) {
              return 'PM'
            }
          }
        }

        return ''
      },

      roadDateIsset(dates, road) {
        const date = this.roadDate(dates, road)
        if (date) {
          return true
        }

        return false
      },

      onDefineRoadDate(book, coach, road, date, status) {
        this.datesAll({
          userId: coach,
        }).then(data => {
          this.book = book
          this.road = road
          this.date = date
          this.isDefineRoadDateModalVisible = true
        })
      },

      onDefineRoadDateModalClose() {
        this.book = 0
        this.road = 0
        this.date = {}
        this.isDefineRoadDateModalVisible = false
        this.coachesBooks()
      },

      onAgreeRoadDates(book) {
        this.book = book
        this.isAgreeRoadDatesModalVisible = true
      },

      onAgreeRoadDatesModalClose() {
        this.book = 0
        this.isAgreeRoadDatesModalVisible = false
        this.coachesBooks()
      },

      onRequestPayment(book) {
        this.bookRequestPayment({
          book,
        })
      },

      onBookModalClose() {
        this.bookModal = false
      },

      onCertificates(book) {
        if (this.isRoadDatesPassed(book)) {
          this.bookObject = book
          this.isCertificatesModalVisible = true
        } else {
          this.messagesSet({
            text: 'Wait until last road date will pass',
            type: 'warning',
          })
        }
      },

      onCertificatesModalClose() {
        this.isCertificatesModalVisible = false
        this.coachesBooks().then(data => {
          this.bookObject = {}
        })
      },
    },
  }
</script>