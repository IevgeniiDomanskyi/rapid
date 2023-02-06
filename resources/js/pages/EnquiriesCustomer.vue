<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Enquiries
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
              :items="customerEnquiries"
              :per-page="10"
            >
              <template v-slot:[`item.subject`]="{ item }">
                {{ item.course.title }}
                {{ item.level.level > 0 ? ('Level ' + item.level.level) : '' }}
              </template>

              <template v-slot:[`item.status`]="{ item }">
                <app-chip
                  v-if=" ! item.status"
                  color="green"
                >
                  Open
                </app-chip>

                <app-chip
                  v-if="item.status"
                  color="red"
                >
                  Closed
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
      search: '',
      headers: [
        { text: 'Date', value: 'created_at' },
        { text: 'Last update', value: 'updated_at' },
        { text: 'Subject', value: 'subject' },
        { text: 'Message', value: 'message' },
        { text: 'Enquiry Status', value: 'status' },
        { text: 'Message History', value: 'messages' },
      ],
    }),

    computed: {
      ...mapState({
        customer: state => state.users.customer,
      }),

      ...mapGetters([
        'dialogByEnquiryId',
      ]),

      customerEnquiries() {
        if (this.customer.enquiries) {
          return this.customer.enquiries.filter(item => {
            return (this.isCoach && item.coach && item.coach.id == this.me.id) || ! this.isCoach
          }) || []
        }

        return []
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

      allMessages(enquiryId) {
        const dialog = this.dialogByEnquiryId(enquiryId)
        
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

      newMessages(enquiryId) {
        const dialog = this.dialogByEnquiryId(enquiryId)

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

      onMessages(enquiryId) {
        this.currentDialog = this.dialogByEnquiryId(enquiryId)
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