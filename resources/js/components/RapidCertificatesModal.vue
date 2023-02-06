<template>
  <v-dialog
    v-model="dialog"
    width="700"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Reports/Certificates
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-tabs
          v-model="tabs"
          background-color="transparent"
          show-arrows
        >
          <v-tab>Info</v-tab>
          <v-tab>Attainment</v-tab>
          <v-tab>Texts</v-tab>
        </v-tabs>

        <v-tabs-items
          v-model="tabs"
          dark
        >
          <v-tab-item>
            <v-container fluid class="px-0">
              <v-row>
                <v-col
                  :cols="12"
                  :sm="6"
                  class="py-0"
                >
                  <app-input-text
                    v-model="data.id"
                    :rules="validate('Rider reference no.')"
                    readonly
                    label="Rider reference no."
                    @submit="onSend"
                  />

                  <app-input-text
                    v-model="data.firstname"
                    :rules="validate('First Name')"
                    label="First Name"
                    @submit="onSend"
                  />

                  <app-input-text
                    v-model="data.lastname"
                    :rules="validate('Last Name')"
                    label="Last Name"
                    @submit="onSend"
                  />

                  <app-select
                    v-model="data.prevCourse"
                    :items="coursesList"
                    :rules="validate('Previous course')"
                    label="Previous course"
                    @submit="onSend"
                  />
                </v-col>

                <v-col
                  :cols="12"
                  :sm="6"
                  class="py-0"
                >
                  <app-input-text
                    v-model="data.coach"
                    :rules="validate('Rapid Coach')"
                    label="Rapid Coach"
                    @submit="onSend"
                  />

                  <app-date-picker
                    v-model="isStartDateVisible"
                    :date.sync="data.startDate"
                    @close="isStartDateVisible = false"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <app-input-text
                        v-model="data.startDate"
                        :rules="validate('Course start date')"
                        label="Course start date"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @submit="onSend"
                      />
                    </template>
                  </app-date-picker>

                  <app-date-picker
                    v-model="isEndDateVisible"
                    :date.sync="data.endDate"
                    @close="isEndDateVisible = false"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <app-input-text
                        v-model="data.endDate"
                        :rules="validate('Course completion date')"
                        label="Course completion date"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @submit="onSend"
                      />
                    </template>
                  </app-date-picker>

                  <app-input-text
                    v-model="data.moto"
                    :rules="validate('Motorcycle')"
                    label="Motorcycle"
                    @submit="onSend"
                  />
                </v-col>
              </v-row>

              <app-select
                v-model="data.course"
                :items="coursesList"
                :rules="validate('Course')"
                label="Course"
                @submit="onSend"
              />
            </v-container>
          </v-tab-item>

          <v-tab-item>
            <v-container>
              <v-row>
                <v-col
                  :cols="12"
                  :sm="6"
                  class="py-0"
                >
                  <p class="mb-0">
                    <b>CORE SKILLS</b>
                  </p>

                  <p class="mb-0">
                    Reading the Road
                  </p>

                  <app-checkbox
                    v-model="data.road.forward"
                    label="Forward and rear observation (early, thorough, continuous)"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.road.interpretion"
                    label="Interpretion and anticipation"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.road.perception"
                    label="Hazard perception"
                    hide-details
                    class="mt-0"
                  />

                  <p class="mb-0 mt-3">
                    Planning
                  </p>

                  <app-checkbox
                    v-model="data.planning.position"
                    label="Position"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.planning.speed"
                    label="Speed"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.planning.gear"
                    label="Gear"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.planning.signal"
                    label="Signal"
                    hide-details
                    class="mt-0"
                  />

                  <p class="mb-0 mt-3">
                    Machine Control
                  </p>

                  <app-checkbox
                    v-model="data.control.position"
                    label="Body position"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.technique"
                    label="Steering technique"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.gear"
                    label="Gear selection (timing, smoothness and accuracy)"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.control"
                    label="Throttle control"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.braking"
                    label="Braking"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.moving"
                    label="Moving off/stopping/slow speed handling"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.control.acceleration"
                    label="Acceleration sense"
                    hide-details
                    class="mt-0"
                  />

                  <p class="mb-0 mt-4">
                    <b>GENERAL SKILLS</b>
                  </p>

                  <app-checkbox
                    v-model="data.general.development"
                    label="Attitude to learning and development"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.general.safety"
                    label="Attitude to safety"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.general.courtesy"
                    label="Courtesy"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.general.progress"
                    label="Progress"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.general.flow"
                    label="Smoothness and flow"
                    hide-details
                    class="mt-0"
                  />
                </v-col>

                <v-col
                  :cols="12"
                  :sm="6"
                  class="py-0"
                >
                  <p class="mb-0">
                    <b>CORNERING</b>
                  </p>

                  <app-checkbox
                    v-model="data.cornering.assessment"
                    label="Bend assessment"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.awareness"
                    label="Awareness of other road users"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.position"
                    label="Position through corner"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.speed"
                    label="Assessing entry speed"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.gear"
                    label="Gear selection (control and exit drive)"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.visual"
                    label="Visual skills through bends"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.steering"
                    label="Steering technique (smooth, accurate and effecient)"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.throttle"
                    label="Throttle control through bends"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.cornering.progress"
                    label="Progress (entry, mid-bend and exit speeds)"
                    hide-details
                    class="mt-0"
                  />

                  <p class="mb-0 mt-4">
                    <b>URBAN RIDING</b>
                  </p>

                  <app-checkbox
                    v-model="data.urban.hazards"
                    label="Identifies hazards early"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.urban.mirrors"
                    label="Checks mirrors and blind spots appropriately"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.urban.signals"
                    label="Uses appropriate signals"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.urban.approriate"
                    label="Approriate position, speed and gear"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.urban.acceleration"
                    label="Appropriate acceleration to leave hazards"
                    hide-details
                    class="mt-0"
                  />

                  <p class="mb-0 mt-4">
                    <b>OVERTAKING (3 stage and momentum)</b>
                  </p>

                  <app-checkbox
                    v-model="data.overtalking.opportunity"
                    label="Identifies a safe opportunity as early as possible"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.overtalking.timing"
                    label="Timing of approach and execution"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.overtalking.appropriate"
                    label="Use appropriate position, speed and gear to complete overtake"
                    hide-details
                    class="mt-0"
                  />

                  <app-checkbox
                    v-model="data.overtalking.gap"
                    label="Move to chosen return gap in a timely and smooth manner"
                    hide-details
                    class="mt-0"
                  />
                </v-col>
              </v-row>
            </v-container>
          </v-tab-item>

          <v-tab-item>
            <app-textarea
              v-model="data.summary"
              :rows="5"
              label="Summary Report"
            />

            <app-textarea
              v-model="data.areas"
              :rows="5"
              label="Areas for Development"
            />

            <app-textarea
              v-model="data.comments"
              :rows="5"
              label="Any other comments"
            />

            <app-date-picker
              v-model="isDateVisible"
              :date.sync="data.date"
              @close="isDateVisible = false"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="data.date"
                  :rules="validate('Date')"
                  label="Date"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @submit="onSend"
                />
              </template>
            </app-date-picker>
          </v-tab-item>
        </v-tabs-items>
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
          label="Send"
          color="primary"
          @click="onSend"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppInputText from './AppInputText'
  import AppTextarea from './AppTextarea'
  import AppSelect from './AppSelect'
  import AppButton from './AppButton'
  import AppCheckbox from './AppCheckbox'
  import AppDatePicker from './AppDatePicker'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCertificatesModal',

    components: {
      AppCard,
      AppInputText,
      AppTextarea,
      AppSelect,
      AppButton,
      AppCheckbox,
      AppDatePicker,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      book: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      valid: false,

      tabs: 0,

      data: {
        id: '',
        book_id: '',
        firstname: '',
        lastname: '',
        prevCourse: 'None',
        startDate: '',
        endDate: '',
        moto: '',
        course: 'None',
        summary: '',
        areas: '',
        comments: '',
        date: '',

        road: {
          forward: false,
          interpretion: false,
          perception: false,
        },

        planning: {
          position: false,
          speed: false,
          gear: false,
          signal: false,
        },

        control: {
          position: false,
          technique: false,
          gear: false,
          control: false,
          braking: false,
          moving: false,
          acceleration: false,
        },

        general: {
          development: false,
          safety: false,
          courtesy: false,
          progress: false,
          flow: false,
        },

        cornering: {
          assessment: false,
          awareness: false,
          position: false,
          speed: false,
          gear: false,
          visual: false,
          steering: false,
          throttle: false,
          progress: false,
        },

        urban: {
          hazards: false,
          mirrors: false,
          signals: false,
          approriate: false,
          acceleration: false,
        },

        overtalking: {
          opportunity: false,
          timing: false,
          appropriate: false,
          gap: false,
        },
      },

      isDateVisible: false,
      isStartDateVisible: false,
      isEndDateVisible: false,
      isLoading: false,

      coursesList: [
        { value: 'None', text: 'None' },
        { value: 'Bikemaster 1', text: 'Bikemaster 1' },
        { value: 'Bikemaster 2', text: 'Bikemaster 2' },
        { value: 'Bikemaster 3', text: 'Bikemaster 3' },
        { value: 'Roadmaster 1', text: 'Roadmaster 1' },
        { value: 'Roadmaster 2', text: 'Roadmaster 2' },
        { value: 'Roadmaster 3', text: 'Roadmaster 3' },
        { value: 'Bespoke', text: 'Bespoke' },
      ],
    }),

    computed: {
      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    watch: {
      book(val) {
        if (val.id) {
          this.data.book_id = this.book.id
          this.data.id = this.book.user.id
          this.data.firstname = this.book.user.firstname
          this.data.lastname = this.book.user.lastname
          this.data.coach = this.book.coach.firstname + ' ' + this.book.coach.lastname

          if (this.book.road_date_1 != '') {
            const times = this.book.road_date_1.split('/')
            this.data.startDate = new Date('20' + times[2], (times[1] * 1 - 1), times[0], 12, 0, 0, 0).toISOString().substr(0, 10)
          }

          let check = true
          let counter = 6
          while (check) {
            if (this.book['road_date_' + counter] != '') {
              check = false
              const times = this.book['road_date_' + counter].split('/')
              this.data.endDate = new Date('20' + times[2], (times[1] * 1 - 1), times[0], 12, 0, 0, 0).toISOString().substr(0, 10)
            }

            counter--
            if (counter <= 0) {
              check = false
            }
          }
          
          this.data.date = new Date().toISOString().substr(0, 10)
          this.data.course = ''
          if (this.book.course.id == 1) {
            this.data.course = 'Bikemaster ' + this.book.level.level
          }

          if (this.book.course.id == 2) {
            this.data.course = 'Roadmaster ' + this.book.level.level
          }

          if (this.book.course.id == 3) {
            this.data.course = 'Bespoke'
          }
        }
      },
    },

    methods: {
      ...mapActions([
        'certificatesSend',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Rider reference no.': r = ['require']; break
          case 'First Name': r = ['require']; break
          case 'Last Name': r = ['require']; break
          case 'Rapid Coach': r = ['require']; break
          case 'Course start date': r = ['require']; break
          case 'Course completion date': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSend() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.certificatesSend(this.data).then(data => {
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

<style lang="scss">
  .theme--dark.v-tabs-items {
    background-color: transparent;
  }
</style>