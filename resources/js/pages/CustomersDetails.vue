<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Customers > Profile
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <v-row>
              <v-col
                :lg="4"
                :md="6"
                :sm="6"
                :xs="12"
              >
                <div class="customer-info">
                  <app-text
                    size="md"
                    class="customer-name mb-4"
                  >
                    {{ customer.firstname }} {{ customer.lastname }}
                  </app-text>

                  <app-text
                    class="mb-2"
                  >
                    First Name: {{ customer.firstname }}
                  </app-text>

                  <app-text
                    class="mb-2"
                  >
                    Last Name: {{ customer.lastname }}
                  </app-text>

                  <app-text
                    class="mb-2"
                  >
                    Email: {{ customer.email }}
                  </app-text>

                  <app-text
                    v-if="customer.phone"
                    class="mb-2"
                  >
                    Phone number: {{ customer.phone }}
                  </app-text>

                  <app-text
                    v-if="customer.dob"
                    class="mb-2"
                  >
                    Date of Birth: {{ customer.dob }}
                  </app-text>

                  <app-text>
                    Member Number: {{ customer.id }}
                  </app-text>
                </div>
              </v-col>

              <v-col
                :lg="4"
                :md="6"
                :sm="6"
                :xs="12"
              >
                <div class="customer-info">
                  <app-text
                    size="md"
                    class="customer-name mb-4"
                  >
                    &nbsp;
                  </app-text>

                  <div
                    v-for="doc in limitDocs(customer.documents)"
                    :key="doc.id"
                    class="d-flex justify-space-between align-center mb-2"
                  >
                    <app-text>
                      {{ typeText(doc.type, doc.course) }}
                    </app-text>

                    <app-button
                      :href="(doc.file != '' ? doc.file : '/pdf/RAPID-TRAINING-LIMITED-T&CDeclaration.pdf')"
                      label="Download"
                      small
                      height="25"
                      color="blue"
                      target="_blank"
                    />
                  </div>

                  <div
                    v-if="isDocsMode"
                    class="text-center"
                  >
                    <app-link
                      @click="docsModal = true"
                    >
                      Show more
                    </app-link>
                  </div>
                </div>
              </v-col>
            </v-row>

            <app-text
              class="admin-title mt-4"
            >
              Bookings
            </app-text>

            <app-table
              :headers="headers"
              :items="customerBooks"
              :per-page="10"
            >
              <template v-slot:[`item.course`]="{ item }">
                <app-text>
                  {{ item.course.title }}
                </app-text>

                <app-text
                  v-if="item.level.level > 0"
                >
                  Level {{ item.level.level }}
                </app-text>
              </template>

              <template v-slot:[`item.road_date_1`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 1)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 1) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_2`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 2)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 2) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_3`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 3)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 3) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_4`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 4)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 4) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_5`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 5)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 5) }}
                </app-chip>
              </template>

              <template v-slot:[`item.road_date_6`]="{ item }">
                <app-chip
                  v-if="roadDateFormat(item.road_dates, 6)"
                  color="green"
                >
                  {{ roadDateFormat(item.road_dates, 6) }}
                </app-chip>
              </template>

              <template v-slot:[`item.date`]="{ item }">
                <div
                  v-if="item.track_date"
                >
                  {{ item.track_date }}<br />
                  {{ item.track_course }}
                </div>
              </template>

              <template v-slot:[`item.coach`]="{ item }">
                <div
                  v-if="item.coach"
                >
                  {{ item.coach.firstname }} {{ item.coach.lastname }}
                </div>
              </template>

              <template v-slot:[`item.status`]="{ item }">
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

            <app-text
              class="admin-title mt-4"
            >
              Events
            </app-text>

            <app-table
              :headers="headersEvents"
              :items="customerEvents"
              :per-page="10"
              item-key="subId"
            >
              <template v-slot:[`item.date`]="{ item }">
                {{ item.from }} - {{ item.to }}
              </template>

              <template v-slot:[`item.price`]="{ item }">
                £{{ item.price }}
              </template>

              <template v-slot:[`item.coach`]="{ item }">
                <div
                  v-if="item.coach"
                >
                  {{ item.coach.firstname }} {{ item.coach.lastname }}
                </div>
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>

      <rapid-docs-modal
        v-model="docsModal"
        :docs="customer.documents"
        @close="onDocsModalClose"
      />

      <rapid-messages-modal
        v-model="messagesModal"
        :dialog="currentDialog"
        @update="onMessagesUpdate"
        @close="onMessagesModalClose"
      />
    </rapid-content>
  </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppLink from '../components/AppLink'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import RapidDocsModal from '../components/RapidDocsModal'
  import RapidMessagesModal from '../components/RapidMessagesModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'CustomersDetails',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppLink,
      AppButton,
      AppTable,
      AppChip,
      RapidDocsModal,
      RapidMessagesModal,
    },

    data: () => ({
      docsModal: false,
      messagesModal: false,
      currentDialog: {},

      search: '',
      headers: [
        { text: 'Course', value: 'course' },
        { text: 'Road Date 1', value: 'road_date_1', align: 'center' },
        { text: 'Road Date 2', value: 'road_date_2', align: 'center' },
        { text: 'Road Date 3', value: 'road_date_3', align: 'center' },
        { text: 'Road Date 4', value: 'road_date_4', align: 'center' },
        { text: 'Road Date 5', value: 'road_date_5', align: 'center' },
        { text: 'Road Date 6', value: 'road_date_6', align: 'center' },
        { text: 'Track Date', value: 'date', align: 'center' },
        { text: 'Coach', value: 'coach' },
        { text: 'Order Status', value: 'status' },
        { text: 'Message History', value: 'messages' },
      ],

      headersEvents: [
        { text: 'ID', value: 'event_id' },
        { text: 'Name', value: 'name' },
        { text: 'Date', value: 'date' },
        { text: 'Location', value: 'region.title' },
        { text: 'Coach', value: 'coach' },
        { text: 'Price', value: 'price' },
        { text: 'Booked At', value: 'booked_at' },
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

      customerEvents() {
        if (this.customer.events) {
          let items = this.customer.events
          items.forEach((item, i) => {
            item.subId = 'e' + i;
          })

          return items
        }

        return []
      },

      isDocsMode() {
        return this.customer.documents ? (this.customer.documents.length > 5) : false
      },
    },

    created() {
      if (this.me.role != 1 && this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.usersCustomersGet({ customerId: this.$router.history.current.params.customerId })
      this.dialogsAll({ customerId: this.$router.history.current.params.customerId })
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

      typeText(type, course) {
        switch (type) {
          case 'book': return 'Terms and Conditions'
          case 'road1': return 'Rider Declaration Road Date 1'
          case 'road2': return 'Rider Declaration Road Date 2'
          case 'road3': return 'Rider Declaration Road Date 3'
          case 'road4': return 'Rider Declaration Road Date 4'
          case 'road5': return 'Rider Declaration Road Date 5'
          case 'road6': return 'Rider Declaration Road Date 6'
          case 'certificate': return 'Certificate ' + course
          case 'congratulation': return 'Congratulation ' + course
          case 'report': return 'Report ' + course
          default: type
        }
      },

      onDocsModalClose() {
        this.docsModal = false
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

      allMessages(bookId) {
        const dialog = this.dialogByBookId(bookId)
        return dialog.messages ? dialog.messages.length : 0
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
        this.messagesModal = true
      },

      onMessagesModalClose() {
        this.messagesModal = false
      },

      onMessagesUpdate() {
        this.dialogsAll({ customerId: this.$router.history.current.params.customerId })
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