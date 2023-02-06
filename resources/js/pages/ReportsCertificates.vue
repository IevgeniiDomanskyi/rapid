<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Reports/Certificates
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
              :items="documents"
              :search="search"
            >
              <template v-slot:[`item.view`]="{ item }">
                <a
                  :href="(item.file != '' ? item.file : '/pdf/RAPID-TRAINING-LIMITED-T&CDeclaration.pdf')"
                  target="_blank"
                >
                  <v-icon>mdi-eye</v-icon>
                </a>
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
        { text: 'Member ID', value: 'user.id' },
        { text: 'First name', value: 'user.firstname' },
        { text: 'Last name', value: 'user.lastname' },
        { text: 'Document', value: 'type' },
        { text: 'Date', value: 'date' },
        { text: 'View document', value: 'view', align: 'end' },
      ],

      selected: [],
      exportOptions: {
        file: 'certificates',
        type: 'certificate',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        documents: state => state.certificates.list,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.certificatesAll()
    },

    methods: {
      ...mapActions([
        'certificatesAll',
      ]),
    },
  }
</script>