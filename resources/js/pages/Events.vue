<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Events/Ride Outs List
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

              <rapid-event-modal
                v-model="eventModal"
                :existEvent="event"
                @close="onEventModalClose"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-button
                    label="Add Event"
                    color="primary"
                    icon="mdi-plus"
                    small
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
              </rapid-event-modal>
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="events"
              :search="search"
              show-select
            >
              <template v-slot:[`item.date`]="{ item }">
                {{ (item.from.indexOf(item.to) + 1) ? item.from : (item.from + ' - ' + item.to) }}
              </template>

              <template v-slot:[`item.view`]="{ item }">
                <app-link
                  v-if="item.customers > 0"
                  color="white"
                  @click="onCustomersView(item.id)"
                >
                  {{ item.customers }}&nbsp;<v-icon>mdi-eye</v-icon>
                </app-link>
              </template>

              <template v-slot:[`item.price`]="{ item }">
                £{{ item.price }}
              </template>

              <template v-slot:[`item.edit`]="{ item }">
                <app-button
                  :loading="isLoading[item.id]"
                  :key="item.id"
                  label="Edit"
                  color="brown"
                  small
                  @click="onEdit(item)"
                />
              </template>

              <template v-slot:[`item.url`]="{ item }">
                <app-button
                  v-clipboard="getUrl(item.id)"
                  v-clipboard:success="onCopyUrl"
                  label="Checkout URL"
                  color="blue"
                  small
                />
              </template>

              <template v-slot:[`item.enquiry`]="{ item }">
                <app-button
                  v-clipboard="getEnquiryUrl(item.id)"
                  v-clipboard:success="onCopyUrl"
                  label="Enquiry URL"
                  color="blue"
                  small
                />
              </template>

              <template v-slot:[`item.remove`]="{ item }">
                <app-button
                  v-if="item.left == item.riders"
                  :key="item.id"
                  label="Remove"
                  color="red"
                  small
                  @click="onRemove(item.id)"
                />
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <rapid-event-customers-modal
      v-model="customersModal"
      @close="onCustomersModalClose"
    />

    <app-confirm-modal
      v-model="removeConfirmModal"
      title="Confirm Event Removing"
      @confirm="onConfirmRemove"
      @close="removeConfirmModal = false"
    >
      Do you really want remove this event?
      <br /><br />
    </app-confirm-modal>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import AppLink from '../components/AppLink'
  import AppConfirmModal from '../components/AppConfirmModal'
  import RapidEventModal from '../components/RapidEventModal'
  import RapidEventCustomersModal from '../components/RapidEventCustomersModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Events',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppInputText,
      AppButton,
      AppTable,
      AppChip,
      AppLink,
      AppConfirmModal,
      RapidEventModal,
      RapidEventCustomersModal,
    },

    data: () => ({
      event: {},
      eventModal: false,
      customersModal: false,
      removeConfirmModal: false,
      removeId: false,

      search: '',
      headers: [
        { text: 'ID', value: 'event_id' },
        { text: 'Name', value: 'name' },
        { text: 'Type', value: 'typeText' },
        { text: 'Date', value: 'date' },
        { text: 'Location', value: 'region.title' },
        { text: 'Riders', value: 'riders' },
        { text: 'Customers', value: 'view' },
        { text: 'Left', value: 'left' },
        { text: 'Price', value: 'price' },
        { text: '', value: 'edit' },
        { text: '', value: 'url' },
        { text: '', value: 'enquiry' },
        { text: '', value: 'remove' },
      ],

      isLoading: {},

      selected: [],
      exportOptions: {
        file: 'events',
        type: 'event',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        events: state => state.events.list,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.eventsAll().then(data => {
        for (const event of data) {
          this.isLoading[event.id] = false
        }
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'eventsAll',
        'eventsCustomers',
        'eventsRemove',
      ]),

      getUrl(id) {
        return window.location.origin + '/event/' + id
      },

      onCopyUrl() {
        this.messagesSet({
          text: 'URL was copied to clipboard',
          type: 'success',
        })
      },

      getEnquiryUrl(id) {
        return window.location.origin + '/event-enquiry/' + id
      },

      onEventModalClose() {
        this.eventModal = false
        this.event = {}
      },

      onEdit(item) {
        this.event = item
        this.eventModal = true
      },

      onCustomersView(event_id) {
        this.eventsCustomers({
          id: event_id,
        }).then(data => {
          this.customersModal = true
        })
      },

      onCustomersModalClose() {
        this.customersModal = false
      },

      onRemove(event_id) {
        this.removeConfirmModal = true
        this.removeId = event_id
      },

      onConfirmRemove() {
        this.eventsRemove({
          id: this.removeId,
        }).then(data => {
          this.removeConfirmModal = false
          this.removeId = false
        })
      },
    },
  }
</script>