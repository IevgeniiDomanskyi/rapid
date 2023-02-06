<template>
  <v-dialog
    v-model="dialog"
    width="500"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Add Track Date
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-row>
          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-date-picker
              v-model="isDateVisible"
              :date.sync="date.date"
              @close="isDateVisible = false"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="computedDateFormatted"
                  :rules="validate('Date')"
                  label="Date"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @submit="onSave"
                />
              </template>
            </app-date-picker>
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-select
              v-model="date.riders"
              :items="riders"
              :rules="validate('Number of Riders')"
              label="Number of Riders"
              @submit="onSave"
            />

            <!-- <app-select
              v-model="date.course"
              :items="coursesList"
              :rules="validate('Courses')"
              label="Courses"
              @submit="onSave"
            /> -->

            <!-- <app-time-picker
              v-model="isTimeVisible"
              :time.sync="date.time"
              @close="isTimeVisible = false"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="date.time"
                  :rules="validate('Time')"
                  label="Time"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @submit="onSave"
                />
              </template>
            </app-time-picker> -->
          </v-col>
        </v-row>

        <v-row>
          <v-col
            :cols="7"
            :sm="9"
            class="py-0"
          >
            <app-select
              v-model="date.track"
              :items="tracksList"
              :rules="validate('Track Name')"
              label="Track Name"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="5"
            :sm="3"
            class="py-0"
          >
            <app-text
              v-if="isAdmin"
              align="center"
              size="xs"
            >
              Not on the list?
            </app-text>
            
            <app-button
              v-if="isAdmin"
              label="Add New"
              color="primary"
              outlined
              small
              block
              @click="onTrackModal"
            />
          </v-col>
        </v-row>
      </v-form>

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          :loading="isLoading"
          label="Save Date"
          color="primary"
          @click="onSave"
        />
      </template>

      <rapid-track-modal
        v-model="tracksModal"
        @close="onTrackModalClose"
      />
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppInputText from './AppInputText'
  import AppSelect from './AppSelect'
  import AppText from './AppText'
  import AppButton from './AppButton'
  import AppDatePicker from './AppDatePicker'
  import AppTimePicker from './AppTimePicker'
  import RapidTrackModal from './RapidTrackModal'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCoachModal',

    mixins: [Rapid],

    components: {
      AppCard,
      AppInputText,
      AppSelect,
      AppText,
      AppButton,
      AppDatePicker,
      AppTimePicker,
      RapidTrackModal,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },
    },

    data: () => ({
      valid: true,

      date: {
        date: null,
        track: 0,
        riders: 0,
      },

      isLoading: false,

      tracksModal: false,
      isTimeVisible: false,
      isDateVisible: false,
    }),

    computed: {
      ...mapState({
        tracks: state => state.tracks.list,
        postcodes: state => state.postcodes.list,
      }),

      computedDateFormatted() {
        return this.formatDate(this.date.date)
      },

      tracksList() {
        let result = []
        for (const track of this.tracks) {
          result.push({
            value: track.id,
            text: track.name,
          })
        }
        return result
      },

      riders() {
        let result = []
        for (let i = 1; i <= 50; i++) {
          result.push(i)
        }
        return result
      },

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    created() {
      this.tracksAll()
    },

    methods: {
      ...mapActions([
        'tracksDatesSave',
        'tracksAll',
        'postcodesAll',
      ]),

      formatDate(date) {
        if ( ! date) return null

        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      },

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Date': r = ['require']; break
          case 'Track Name': r = ['require']; break
          case 'Number of Riders': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onTrackModal() {
        this.postcodesAll().then(data => {
          this.tracksModal = true
        })
      },

      onTrackModalClose() {
        this.tracksModal = false
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.tracksDatesSave(this.date).then(data => {
            this.isLoading = false
            if (data) {
              this.onClose()
            }
          })
        }
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>