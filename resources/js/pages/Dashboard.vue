<template>
  <div>

    <rapid-content>
      <v-row
        class="mx-0 d-none d-sm-inline-flex"
      >
        <v-col
          cols="3"
        >
          <app-panel flex>
            <v-avatar
              color="#a88add"
              size="58"
              class="mr-4"
            />

            <div
              class="dashboard-panel__info"
            >
              <app-text
                nowrap
                class="mb-1"
              >
                <span class="text-h4">{{ books.length }}</span> Bookings
              </app-text>

              <app-text>
                {{ unconfirmedBooks }} unconfirmed
              </app-text>
            </div>
          </app-panel>
        </v-col>

        <v-col
          cols="3"
        >
          <app-panel flex>
            <v-avatar
              color="#0cc2aa"
              size="58"
              class="mr-4"
            />

            <div
              class="dashboard-panel__info"
            >
              <app-text
                nowrap
                class="mb-1"
              >
                <span class="text-h4">{{ enquiries.length }}</span> Enquiries
              </app-text>

              <app-text>
                {{ openEnquiries }} open
              </app-text>
            </div>
          </app-panel>
        </v-col>

        <v-col
          cols="3"
        >
          <app-panel flex>
            <v-avatar
              color="#fcc100"
              size="58"
              class="mr-4"
            />

            <div
              class="dashboard-panel__info"
            >
              <app-text
                nowrap
                class="mb-1"
              >
                <span class="text-h4">{{ completedCourses() }}</span> Completed Courses
              </app-text>

              <app-text>
                {{ inprogressCourses() }} in progress
              </app-text>
            </div>
          </app-panel>
        </v-col>

        <v-col
          cols="3"
        >
          <app-panel flex>
            <v-avatar
              color="#84CFF6"
              size="58"
              class="mr-4"
            />

            <div
              class="dashboard-panel__info"
            >
              <app-text
                nowrap
                class="mb-1"
              >
                <span class="text-h4">&pound;{{ totalPayment() }}</span> Total revenue
              </app-text>

              <app-text>
                &pound;{{ paidPayment() }} collected
              </app-text>
            </div>
          </app-panel>
        </v-col>
      </v-row>

      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <app-table
              :headers="headers"
              :items="books"
              :search="search"
              show-select
            >
              <template v-slot:[`item.status`]="{ item }">
                <app-chip
                  v-if="item.status < 10"
                  color="green"
                  small
                >
                  Active
                </app-chip>

                <app-chip
                  v-else
                  color="red"
                  small
                >
                  Closed
                </app-chip>
              </template>

              <template v-slot:[`item.region`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.region ? item.region.title : '' }}
                </span>
              </template>

              <template v-slot:[`item.payment`]="{ item }">
                <span
                  v-if="item.status >= 4"
                  class="text-no-wrap"
                >
                  Processed
                </span>
              </template>

              <template v-slot:[`item.coach`]="{ item }">
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
                  v-if="item.status > 1 && item.track_date"
                >
                  {{ item.track_date }}<br />
                  {{ item.track_course }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_1`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 1)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 1, roadDate(item.road_dates, 1), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 1) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_2`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 1) && roadDateIsset(item.road_dates, 2)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 2, roadDate(item.road_dates, 2), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 2) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_3`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 2) && roadDateIsset(item.road_dates, 3)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 3, roadDate(item.road_dates, 3), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 3) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_4`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 3) && roadDateIsset(item.road_dates, 4)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 4, roadDate(item.road_dates, 4), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 4) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_5`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 4) && roadDateIsset(item.road_dates, 5)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 5, roadDate(item.road_dates, 5), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 5) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>

              <template v-slot:[`item.road_date_6`]="{ item }">
                <div
                  v-if="item.status >= 3 && roadDateIsset(item.road_dates, 5) && roadDateIsset(item.road_dates, 6)"
                  @click="onDefineRoadDate(item.id, item.coach.id, 6, roadDate(item.road_dates, 6), item.status)"
                >
                  {{ roadDateFormat(item.road_dates, 6) }}
                </div>

                <div
                  v-else
                  class="text-center"
                >
                  -
                </div>
              </template>
            </app-table>
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
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'

  export default {
    name: 'Dashboard',

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppTable,
      AppChip,
    },

    data () {
      return {
        search: '',
        
        headers: [
          { text: 'Order ID', value: 'order_id' },
          { text: 'First Name', value: 'user.firstname' },
          { text: 'Last Name', value: 'user.lastname' },
          { text: 'Email Address', value: 'user.email' },
          { text: 'Phone Number', value: 'user.phone' },
          { text: 'Region', value: 'region' },
          { text: 'Payment', value: 'payment' },
          { text: 'Coach', value: 'coach' },
          { text: 'Track Date', value: 'date', align: 'center' },
          { text: 'Road Date 1', value: 'road_date_1', align: 'center' },
          { text: 'Road Date 2', value: 'road_date_2', align: 'center' },
          { text: 'Road Date 3', value: 'road_date_3', align: 'center' },
          { text: 'Road Date 4', value: 'road_date_4', align: 'center' },
          { text: 'Road Date 5', value: 'road_date_5', align: 'center' },
          { text: 'Road Date 6', value: 'road_date_6', align: 'center' },
          { text: 'Order Status', value: 'status' },
        ],
      }
    },

    computed: {
      ...mapState({
        me: state => state.auth.me,
        books: state => state.book.list,
        enquiries: state => state.book.enquiries,
        history: state => state.payment.history,
      }),

      unconfirmedBooks() {
        return this.books.filter(item => {
          return item.status == 0
        }).length
      },

      openEnquiries() {
        return this.enquiries.filter(item => {
          return item.status == 0
        }).length
      },
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.bookAll()
      this.enquiryAll()
      this.paymentHistory({
        userId: this.me.id,
      })
    },

    methods: {
      ...mapActions([
        'bookAll',
        'paymentHistory',
        'enquiryAll',
      ]),

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

      roadDateIsset(dates, road) {
        const date = this.roadDate(dates, road)
        if (date) {
          return true
        }

        return false
      },

      totalPayment() {
        let total = 0
        for (const item of this.history) {
          total += item.amount
        }
        return total
      },

      paidPayment() {
        let paid = 0
        const date = Date.now()
        
        for (const item of this.history) {
          const hDate = + new Date(item.paid_at)
          if (hDate <= date) {
            paid += item.amount
          }
        }
        return paid
      },

      completedCourses() {
        let completed = 0
        for (const book of this.books) {
          if (book.status == 7) {
            completed++
          }
        }
        return completed
      },

      inprogressCourses() {
        let inprogress = 0
        for (const book of this.books) {
          if (book.status > 0 && book.status < 7) {
            inprogress++
          }
        }
        return inprogress
      },
    },
  }
</script>

<style lang="scss" scoped>
  .dashboard-panel__info {
    width: calc(100% - 74px);
  }
</style>