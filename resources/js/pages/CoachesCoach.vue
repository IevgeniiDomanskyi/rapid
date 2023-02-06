<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Coaches
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
              :items="coaches"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.regions`]="{ item }">
                <div v-html="printRegions(item.regions)" />
              </template>

              <template v-slot:[`item.password`]="{ item }">
                <app-button
                  v-if="me.id == item.id"
                  :loading="isLoading[item.id]"
                  label="Reset password"
                  color="brown"
                  small
                  @click="onPasswordReset(item.id)"
                />
              </template>

              <template v-slot:[`item.availability`]="{ item }">
                <app-button
                  v-if="me.id == item.id"
                  label="Check Availability"
                  color="blue"
                  small
                  @click="onAvailability(item)"
                />
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <rapid-availability-modal
      v-model="availabilityModal"
      :coach="currentCoach"
      :dates="dates"
      @close="onAvailabilityModalClose"
    />
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
  import RapidAvailabilityModal from '../components/RapidAvailabilityModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Coaches',

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
      RapidAvailabilityModal,
    },

    data: () => ({
      availabilityModal: false,

      search: '',
      headers: [
        { text: 'First Name', value: 'firstname' },
        { text: 'Last Name', value: 'lastname' },
        { text: 'Email Address', value: 'email' },
        { text: 'Phone Number', value: 'phone' },
        { text: 'Region', value: 'regions' },
        { text: 'Password', value: 'password' },
        { text: 'Availability', value: 'availability' },
      ],

      isLoading: {},

      currentCoach: {},

      selected: [],
      exportOptions: {
        file: 'coaches',
        type: 'coach',
        roles: ['coach'],
      },
    }),

    computed: {
      ...mapState({
        coaches: state => state.coaches.list,
        dates: state => state.dates.list,
      }),
    },

    created() {
      if (this.me.role != 1) {
        return this.$router.replace('/panel')
      }

      this.coachesAll().then(data => {
        for (const coach of data) {
          this.isLoading[coach.id] = false
        }
      })
    },

    methods: {
      ...mapActions([
        'coachesAll',
        'coachesPassword',
        'datesAll',
      ]),

      printRegions(regions) {
        let result = []
        for (const region of regions) {
          result.push(region.title)
        }
        return result.join('<br />')
      },

      onPasswordReset(id) {
        this.isLoading[id] = true
        this.coachesPassword({ userId: id }).then(data => {
          this.isLoading[id] = false
        })
      },

      onAvailability(coach) {
        this.currentCoach = coach
        this.datesAll({ userId: this.currentCoach.id }).then(data => {
          this.availabilityModal = true
        })
      },

      onAvailabilityModalClose() {
        this.availabilityModal = false
      },
    },
  }
</script>