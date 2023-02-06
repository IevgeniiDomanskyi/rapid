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
              :headers="headers"
              :items="tracksDates"
              :search="search"
              show-select
            >
              <template v-slot:item.track="{ item }">
                {{ item.track.name }}
              </template>

              <template v-slot:item.postcode="{ item }">
                <span class="text-no-wrap">
                  {{ item.track.postcode.code }} ({{ item.track.postcode.area }})
                </span>
              </template>

              <template v-slot:item.status="{ item }">
                <app-chip
                  :color="item.statusColor"
                >
                  {{ item.statusText }}
                </app-chip>
              </template>

              <!--template v-slot:item.course="{ item }">
                {{ item.course.title }}
              </template>

              <template v-slot:item.status="{ item }">
                <app-chip
                  :color="item.statusColor"
                >
                  {{ item.statusText }}
                </app-chip>
              </template>

              <template v-slot:item.postcode="{ item }">
                <span class="text-no-wrap">
                  {{ item.postcode.code }} ({{ item.postcode.area }})
                </span>
              </template>

              <template v-slot:item.coach="{ item }">
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

              <template v-slot:item.date="{ item }">
                <v-icon
                  v-if="item.status == 1 && ! item.track_date"
                  @click="onDefineTrackDate(item.id, item.postcode.id)"
                >
                  mdi-calendar-month
                </v-icon>
              </template>

              <template v-slot:item.action="{ item }">
                <app-button
                  v-if="item.status == 0 && ! item.coach"
                  label="Assign Coach"
                  color="grey"
                  small
                  @click="onAssignCoach(item.id, item.postcode.id)"
                />

                <app-chip
                  v-if="item.status == 1 && ! item.track_date"
                  color="brown"
                >
                  Choose Track Date
                </app-chip>
              </template-->
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <!-- <rapid-track-modal
      v-model="isTrackModalVisible"
      @close="onTrackModalClose"
    /> -->
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
  import RapidTrackDateModal from '../components/RapidTrackDateModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'TracksCoach',

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
      RapidTrackDateModal,
    },

    data: () => ({
      dateModal: false,

      search: '',
      headers: [
        { text: 'Date', value: 'date' },
        { text: 'Time', value: 'time' },
        { text: 'Track', value: 'track' },
        { text: 'Postcode', value: 'postcode' },
        { text: 'Max riders', value: 'riders' },
        { text: 'Left', value: 'left' },
        { text: 'Part 1', value: 'part1' },
        { text: 'Part 2', value: 'part2' },
        { text: 'Status', value: 'status' },
      ],

      isLoading: {},
    }),

    computed: {
      ...mapState({
        tracksDates: state => state.tracks.dates,
      }),
    },

    created() {
      if (this.me.role != 1) {
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
      ]),

      onTrackDateModalClose() {
        this.dateModal = false
      },
    },
  }
</script>