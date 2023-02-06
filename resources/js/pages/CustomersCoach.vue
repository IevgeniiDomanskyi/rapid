<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Customers
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
              :items="customers"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.is_active`]="{ item }">
                <app-chip
                  v-if="item.is_active"
                  color="green"
                >
                  Email is verified
                </app-chip>

                <app-chip
                  v-else
                  color="orange"
                >
                  Email is not verified
                </app-chip>
              </template>

              <template v-slot:[`item.details`]="{ item }">
                <app-button
                  label="Details"
                  color="blue"
                  small
                  @click="onDetails(item.id)"
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
  import AppText from '../components/AppText'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppChip from '../components/AppChip'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'CustomersCoach',

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
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'Member Number', value: 'id' },
        { text: 'First Name', value: 'firstname' },
        { text: 'Last Name', value: 'lastname' },
        { text: 'Email Address', value: 'email' },
        { text: 'Phone Number', value: 'phone' },
        { text: 'DOB', value: 'dob' },
        { text: 'Verified', value: 'is_active' },
        { text: 'Details', value: 'details' },
      ],

      selected: [],
      exportOptions: {
        file: 'customers',
        type: 'user',
        roles: ['coach'],
      },
    }),

    computed: {
      ...mapState({
        customers: state => state.coaches.customers,
      }),
    },

    created() {
      if (this.me.role != 1) {
        return this.$router.replace('/panel')
      }

      this.coachesCustomers()
    },

    methods: {
      ...mapActions([
        'coachesCustomers',
      ]),

      onDetails(id) {
        this.$router.push('/panel/customers/' + id)
      },
    },
  }
</script>