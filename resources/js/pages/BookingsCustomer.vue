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
            </div>

            <app-table
              :headers="headers"
              :items="booksModified"
              :per-page="10"
            >
              <template v-slot:[`item.courseField`]="{ item }">
                <app-text>
                  {{ item.course.title }}
                </app-text>

                <app-text
                  v-if="item.level.level > 0"
                >
                  Level {{ item.level.level }}
                </app-text>

                <app-text
                  v-if="item.level.crm_id == 'Level_b_fd'"
                >
                  Full Day
                </app-text>
              </template>

              <template v-slot:[`item.road_date_1`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 1)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 1) }}
                  {{ roadDateSlot(item.road_dates, 1) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_2`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 2)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 2) }}
                  {{ roadDateSlot(item.road_dates, 2) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_3`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 3)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 3) }}
                  {{ roadDateSlot(item.road_dates, 3) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_4`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 4)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 4) }}
                  {{ roadDateSlot(item.road_dates, 4) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_5`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 5)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 5) }}
                  {{ roadDateSlot(item.road_dates, 5) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_6`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 6)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 6) }}
                  {{ roadDateSlot(item.road_dates, 6) }}
                </app-chip>
              </template>

              <template v-slot:[`item.date`]="{ item }">
                <div
                  v-if="item.track_date"
                >
                  {{ item.track_date }}
                </div>
              </template>

              <template v-slot:[`item.coachField`]="{ item }">
                <div
                  v-if="item.coach"
                >
                  {{ item.coach.firstname }} {{ item.coach.lastname }}
                </div>
              </template>

              <template v-slot:[`item.statusField`]="{ item }">
                <app-chip
                  v-if="item.status == 10"
                  color="red"
                >
                  Cancelled
                </app-chip>

                <app-chip
                  v-if="item.status == 7"
                  color="green"
                >
                  Completed
                </app-chip>

                <app-chip
                  v-if="item.status < 7"
                  color="blue"
                >
                  Active
                </app-chip>
              </template>

              <template v-slot:[`item.messages`]="{ item }">
                <app-button
                  :label="newMessages(item.id) + ' / ' + allMessages(item.id)"
                  :color="newMessages(item.id) > 0 ? 'purple' : 'grey'"
                  :light="newMessages(item.id) == 0"
                  small
                  @click="onMessages(item.id)"
                />
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>
  </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppInputText from '../components/AppInputText'
  import AppLink from '../components/AppLink'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'BookingsCustomer',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppInputText,
      AppLink,
      AppButton,
      AppTable,
      AppChip,
    },

    data: () => ({
      docsModal: false,
      messagesModal: false,
      currentDialog: {},

      search: '',
      headers: [
        { text: 'Course', value: 'courseField' },
        { text: 'Track Date', value: 'date', align: 'center' },
        { text: 'Road Date 1', value: 'road_date_1', align: 'center' },
        { text: 'Road Date 2', value: 'road_date_2', align: 'center' },
        { text: 'Road Date 3', value: 'road_date_3', align: 'center' },
        { text: 'Road Date 4', value: 'road_date_4', align: 'center' },
        { text: 'Road Date 5', value: 'road_date_5', align: 'center' },
        { text: 'Road Date 6', value: 'road_date_6', align: 'center' },
        { text: 'Coach', value: 'coachField' },
        { text: 'Order Status', value: 'statusField' },
        { text: 'Message History', value: 'messages' },
      ],
    }),

    computed: {
      ...mapState({
        customer: state => state.users.customer,
      }),

      ...mapGetters([
        'dialogByBookId',
      ]),

      customerBooks() {
        if (this.customer.books) {
          return this.customer.books.filter(item => {
            return (this.isCoach && item.coach && item.coach.id == this.me.id) || ! this.isCoach
          }) || []
        }

        return []
      },

      booksModified() {
        let result = []
        for (const key in this.customerBooks) {
          let item = { ...this.customerBooks[key] }

          item.courseField = item.course.title + (item.level.level > 0 ? ('Level ' + item.level.level) : (item.level.crm_id == 'Level_b_fd' ? 'Full Day' : ''))

          item.statusField = ''
          if (item.status == 10) {
            item.statusField = 'Cancelled'
          }

          if (item.status == 7) {
            item.statusField = 'Completed'
          }

          if (item.status < 7) {
            item.statusField = 'Active'
          }
                  
          item.coachField = item.coach ? (item.coach.firstname + ' ' + item.coach.lastname) : ''
          item.date = item.track_date ? (item.track_date + ' ' + item.track_course) : ''

          item.road_date_1 = ''
          if (this.roadDateFormat(item.road_dates, 1)) {
            item.road_date_1 = this.roadDateFormat(item.road_dates, 1)
          }

          item.road_date_2 = ''
          if (this.roadDateFormat(item.road_dates, 1)) {
            item.road_date_2 = this.roadDateFormat(item.road_dates, 2)
          }

          item.road_date_3 = ''
          if (this.roadDateFormat(item.road_dates, 3)) {
            item.road_date_3 = this.roadDateFormat(item.road_dates, 3)
          }

          item.road_date_4 = ''
          if (this.roadDateFormat(item.road_dates, 4)) {
            item.road_date_4 = this.roadDateFormat(item.road_dates, 4)
          }

          item.road_date_5 = ''
          if (this.roadDateFormat(item.road_dates, 5)) {
            item.road_date_5 = this.roadDateFormat(item.road_dates, 5)
          }

          item.road_date_6 = ''
          if (this.roadDateFormat(item.road_dates, 6)) {
            item.road_date_6 = this.roadDateFormat(item.road_dates, 6)
          }

          result.push(item)
        }

        return result
      },

      isDocsMode() {
        return this.customer.documents ? (this.customer.documents.length > 5) : false
      },
    },

    created() {
      if (this.me.role != 0) {
        return this.$router.replace('/panel')
      }

      this.usersCustomersGet({ customerId: this.me.id })
      this.dialogsAll({ customerId: this.me.id })
    },

    methods: {
      ...mapActions([
        'usersCustomersGet',
        'dialogsAll',
      ]),

      limitDocs(docs) {
        if (docs) {
          return docs.slice(0, 3)
        }

        return []
      },

      typeText(type) {
        switch (type) {
          case 'book': return 'Terms and Conditions'
          case 'road1': return 'Rider Declaration Road Date 1'
          case 'road2': return 'Rider Declaration Road Date 2'
          case 'road3': return 'Rider Declaration Road Date 3'
          case 'road4': return 'Rider Declaration Road Date 4'
          case 'road5': return 'Rider Declaration Road Date 5'
          case 'road6': return 'Rider Declaration Road Date 6'
          default: type
        }
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
              return '(Full Day)'
            }

            if (date.slots[road] == 1) {
              return '(AM)'
            }

            if (date.slots[road] == 2) {
              return '(PM)'
            }
          }
        }

        return ''
      },

      allMessages(bookId) {
        const dialog = this.dialogByBookId(bookId)
        
        let count = 0
        if (dialog.messages) {
          for (const message of dialog.messages) {
            if (message.type == 0) {
              count++
            }
          }
        }
        return count
      },

      newMessages(bookId) {
        const dialog = this.dialogByBookId(bookId)

        let count = 0
        if (dialog.messages) {
          for (const message of dialog.messages) {
            if (message.type == 0 && message.author.id != this.me.id && ! message.receiver_read) {
              count++
            }
          }
        }
        return count
      },

      onMessages(bookId) {
        this.currentDialog = this.dialogByBookId(bookId)
        this.$router.push('/panel/dialog/' + this.currentDialog.id)
        
      },

      onMessagesUpdate() {
        this.dialogsAll({ customerId: this.me.id })
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import '../../sass/_variables.scss';

  .customer-info {
    border: solid $text 1px;
    padding: 16px;
    height: 245px;
  }

  .customer-name {
    border-bottom: solid $text 1px;
  }

  .admin-title {
    border-bottom: solid 1px;
    max-width: 150px;
  }
</style>