<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Tracks
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

              <rapid-track-date-modal
                v-model="dateModal"
                @close="onTrackDateModalClose"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-button
                    label="Add Track Date"
                    color="primary"
                    icon="mdi-plus"
                    small
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
              </rapid-track-date-modal>
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="tracksDates"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.track`]="{ item }">
                {{ item.track.name }}
              </template>

              <template v-slot:[`item.region`]="{ item }">
                <span class="text-no-wrap">
                  {{ item.track.region ? item.track.region.title : '' }}
                </span>
              </template>

              <template v-slot:[`item.status`]="{ item }">
                <app-chip
                  :color="item.statusColor"
                >
                  {{ item.statusText }}
                </app-chip>
              </template>

              <template v-slot:[`item.remove`]="{ item }">
                <app-button
                  v-if="item.riders == item.left"
                  color="white"
                  isIcon
                  icon="mdi-delete"
                  @click="onRemove(item)"
                />
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <app-confirm-modal
      v-model="isRemoveModalVisible"
      title="Remove Track Date Confirmation"
      @close="isRemoveModalVisible = false"
      @confirm="onTrackDateRemove"
    >
      Are you sure you want do remove this track date?
    </app-confirm-modal>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import AppConfirmModal from '../components/AppConfirmModal'
  import RapidTrackDateModal from '../components/RapidTrackDateModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Tracks',

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
      AppConfirmModal,
      RapidTrackDateModal,
    },

    data: () => ({
      dateModal: false,
      isRemoveModalVisible: false,

      search: '',
      headers: [
        { text: 'Date', value: 'date' },
        { text: 'Track', value: 'track' },
        { text: 'Region', value: 'region' },
        { text: 'Max riders', value: 'riders' },
        { text: 'Left', value: 'left' },
        { text: 'Level 1', value: 'level1' },
        { text: 'Level 2', value: 'level2' },
        { text: 'Status', value: 'status' },
        { text: 'Remove', value: 'remove', align: 'end' },
      ],

      isLoading: {},

      selected: [],
      exportOptions: {
        file: 'track-dates',
        type: 'track',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        tracksDates: state => state.tracks.dates,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.tracksDatesAll().then(data => {
        for (const date of data) {
          this.isLoading[date.id] = false
        }
      })
    },

    methods: {
      ...mapActions([
        'tracksDatesAll',
        'tracksDatesRemove',
      ]),

      onTrackDateModalClose() {
        this.dateModal = false
      },

      onRemove(date) {
        this.removeTrackDate = date.id
        this.isRemoveModalVisible = true
      },

      onTrackDateRemove() {
        this.isRemoveModalVisible = false
        this.tracksDatesRemove({
          date: this.removeTrackDate,
        })
      },
    },
  }
</script>