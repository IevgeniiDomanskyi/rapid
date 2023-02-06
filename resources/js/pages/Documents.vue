<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        My Digital Documents
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <app-table
              :headers="headers"
              :items="documents"
            >
              <template v-slot:[`item.date`]="{ item }">
                {{ item.date }}
              </template>

              <template v-slot:[`item.type`]="{ item }">
                {{ typeText(item.type, item.course) }}
              </template>

              <template v-slot:[`item.signed`]="{ item }">
                <span
                  v-if="item.is_signed && item.type != 'certificate' && item.type != 'congratulation' && item.type != 'report'"
                >
                  {{ item.updated_at }}
                </span>

                <span
                  v-else
                >
                  ---
                </span>
              </template>

              <template v-slot:[`item.action`]="{ item }">
                <app-button
                  v-if=" ! item.is_signed"
                  label="Sign Declaration"
                  :color="item.same ? 'green' : 'grey'"
                  small
                  @click="onSignOn(item)"
                />

                <span
                  v-else
                >
                  ---
                </span>
              </template>

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
  import { mapState, mapMutations, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppTable from '../components/AppTable'
  import AppButton from '../components/AppButton'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Documents',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppTable,
      AppButton,
    },

    data: () => ({
      headers: [
        { text: 'Due Date', value: 'date' },
        { text: 'Type', value: 'type' },
        { text: 'Signed', value: 'signed' },
        { text: 'Action', value: 'action' },
        { text: 'View', value: 'view', align: 'end' },
      ],
    }),

    computed: {
      ...mapState({
        documents: state => state.documents.list,
      }),
    },

    created() {
      if (this.me.role != 0) {
        return this.$router.replace('/panel')
      }

      this.documentsAll({
        userId: this.me.id,
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'documentsAll',
        'documentsSign',
      ]),

      typeText(type, course) {
        switch (type) {
          case 'book': return 'Terms and Conditions'
          case 'event': return 'Event Rider Declaration'
          case 'tour': return 'Tour Rider Declaration'
          case 'road1': return 'Rider Declaration [Road Date 1]'
          case 'road2': return 'Rider Declaration [Road Date 2]'
          case 'road3': return 'Rider Declaration [Road Date 3]'
          case 'road4': return 'Rider Declaration [Road Date 4]'
          case 'road5': return 'Rider Declaration [Road Date 5]'
          case 'road6': return 'Rider Declaration [Road Date 6]'
          case 'certificate': return 'Certificate [' + course + ']'
          case 'congratulation': return 'Congratulation [' + course + ']'
          case 'report': return 'Report [' + course + ']'
          default: type
        }
      },

      onSignOn(document) {
        if (document.same || ( ! document.same && document.type == 'road1')) {
          this.documentsSign({
            userId: this.me.id,
            documentId: document.id,
          })
        } else {
          this.messagesSet({
            text: 'Rider declarations should be signed 2 days before the ride date',
            type: 'warning',
          })
        }
      },
    },
  }
</script>