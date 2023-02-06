<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        My Events
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
            </div>

            <app-table
              :headers="headers"
              :items="customerEvents"
              :per-page="10"
            >
              <template v-slot:[`item.date`]="{ item }">
                {{ item.from }} - {{ item.to }}
              </template>

              <template v-slot:[`item.price`]="{ item }">
                £{{ item.price }}
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>
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
  import RapidEventModal from '../components/RapidEventModal'
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
      RapidEventModal,
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Date', value: 'date' },
        { text: 'Location', value: 'region.title' },
        { text: 'Price', value: 'price' },
      ],

      isLoading: {},
    }),

    computed: {
      ...mapState({
        customer: state => state.users.customer,
      }),

      customerEvents() {
        if (this.customer.events) {
          return this.customer.events.filter(item => {
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
    },

    methods: {
      ...mapActions([
        'usersCustomersGet',
      ]),
    },
  }
</script>