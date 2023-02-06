<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Track Days
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

              <rapid-track-day-modal
                v-model="trackDayModal"
                :existTrackDay="trackDay"
                @close="onTrackDayModalClose"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-button
                    label="Add Track Day"
                    color="primary"
                    icon="mdi-plus"
                    small
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
              </rapid-track-day-modal>
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="trackDays"
              :search="search"
              show-select
            >
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

    <rapid-track-day-customers-modal
      v-model="customersModal"
      @close="onCustomersModalClose"
    />

    <app-confirm-modal
      v-model="removeConfirmModal"
      title="Confirm Track Day Removing"
      @confirm="onConfirmRemove"
      @close="removeConfirmModal = false"
    >
      Do you really want remove this track day?
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
  import RapidTrackDayModal from '../components/RapidTrackDayModal'
  import RapidTrackDayCustomersModal from '../components/RapidTrackDayCustomersModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'TrackDays',

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
      RapidTrackDayModal,
      RapidTrackDayCustomersModal,
    },

    data: () => ({
      trackDay: {},
      trackDayModal: false,
      customersModal: false,
      removeConfirmModal: false,
      removeId: false,

      search: '',
      headers: [
        { text: 'ID', value: 'track_day_id' },
        { text: 'Name', value: 'name' },
        { text: 'Date', value: 'track_date.date' },
        { text: 'Track', value: 'track_date.track.name' },
        { text: 'Location', value: 'track_date.track.region.title' },
        { text: 'Level 1 Riders', value: 'riders1' },
        { text: 'Level 2 Riders', value: 'riders2' },
        { text: 'Level 3 Riders', value: 'riders3' },
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
        file: 'trackDays',
        type: 'trackDay',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        trackDays: state => state.trackDays.list,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.trackDaysAll().then(data => {
        for (const trackDay of data) {
          this.isLoading[trackDay.id] = false
        }
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'trackDaysAll',
        'trackDaysCustomers',
        'trackDaysRemove',
      ]),

      getUrl(id) {
        return window.location.origin + '/track-day/' + id
      },

      getEnquiryUrl(id) {
        return window.location.origin + '/track-day-enquiry/' + id
      },

      onCopyUrl() {
        this.messagesSet({
          text: 'URL was copied to clipboard',
          type: 'success',
        })
      },

      onTrackDayModalClose() {
        this.trackDayModal = false
        this.trackDay = {}
      },

      onEdit(item) {
        this.trackDay = item
        this.trackDayModal = true
      },

      onCustomersView(trackDay_id) {
        this.trackDaysCustomers({
          id: trackDay_id,
        }).then(data => {
          this.customersModal = true
        })
      },

      onCustomersModalClose() {
        this.customersModal = false
      },

      onRemove(trackDay_id) {
        this.removeConfirmModal = true
        this.removeId = trackDay_id
      },

      onConfirmRemove() {
        this.trackDaysRemove({
          id: this.removeId,
        }).then(data => {
          this.removeConfirmModal = false
          this.removeId = false
        })
      },
    },
  }
</script>