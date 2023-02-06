<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Redemptions per vouchers
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
              :items="users"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
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
  import Rapid from '../mixins/rapid'

  export default {
    name: 'ReportsVouchers',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppInputText,
      AppButton,
      AppTable,
    },

    data: () => ({
      search: '',
      headers: [
        { text: 'First name', value: 'firstname' },
        { text: 'Last name', value: 'lastname' },
        { text: 'Email address', value: 'email' },
        { text: 'Redemption Date', value: 'date' },
        { text: 'Redemption Time', value: 'time' },
        { text: 'Voucher code', value: 'code' },
        { text: 'Order Number', value: 'order_number' },
        { text: 'Course', value: 'course' },
      ],

      selected: [],
      exportOptions: {
        file: 'redemptions-per-vouchers',
        type: 'redemption',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        users: state => state.vouchers.users,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.vouchersUsersAll()
    },

    methods: {
      ...mapActions([
        'vouchersUsersAll',
      ]),
    },
  }
</script>