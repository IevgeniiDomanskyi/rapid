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
              v-model="selected"
              :headers="headers"
              :items="enquiries"
              :search="search"
              :export-options="exportOptions"
              show-select

            >
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

              <template v-slot:[`item.subject`]="{ item }">
                {{ item.course.title }}
                {{ item.level.level > 0 ? ('Level ' + item.level.level) : (item.level.crm_id == 'Level_b_fd' ? ' FD' : '') }}
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

              <template v-slot:[`item.contact`]="{ item }">
                <app-button
                  label="Contact Customer"
                  color="blue"
                  small
                  @click="onMessages(item.dialog_id)"
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
  import { mapState, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppButton from '../components/AppButton'
  import AppText from '../components/AppText'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import AppInputText from '../components/AppInputText'
  import AppLink from '../components/AppLink'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Enquiries',

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
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'First Name', value: 'user.firstname' },
        { text: 'Last Name', value: 'user.lastname' },
        { text: 'Phone', value: 'phone' },
        { text: 'Postcode', value: 'user.postcode' },
        { text: 'Date', value: 'created_at' },
        { text: 'Last Update', value: 'updated_at' },
        { text: 'Subject', value: 'subject' },
        { text: 'Enquiry Status', value: 'status' },
        { text: 'Contact Customer', value: 'contact' },
      ],

      selected: [],
      exportOptions: {
        file: 'enquiries',
        type: 'enquiry',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        enquiries: state => state.book.enquiries,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.enquiryAll()
    },

    methods: {
      ...mapActions([
        'enquiryAll',
      ]),

      onMessages(dialogId) {
        if (dialogId) {
          this.$router.push('/panel/dialog/' + dialogId)
        }
      },
    },
  }
</script>