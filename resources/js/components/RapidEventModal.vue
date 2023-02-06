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
        <span v-if="! event.id">Add Event</span>
        <span v-else>Edit Event</span>
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-row>
          <v-col
            :cols="12"
            class="py-0"
          >
            <app-radio-group
              v-model="event.type"
              :list="typeList"
              row
              hide-details
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            class="py-0"
          >
            <app-input-text
              v-model="event.name"
              :disabled="event.id && event.left != event.riders"
              :rules="validate('Name')"
              label="Name"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-date-picker
              v-model="isFromVisible"
              :date.sync="event.from"
              :min="min"
              :disabled="event.id && event.left != event.riders"
              @close="isFromVisible = false"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="computedFrom"
                  :rules="validate('Date From')"
                  label="Date From"
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
            <app-date-picker
              v-model="isToVisible"
              :date.sync="event.to"
              :min="min"
              :disabled="event.id && event.left != event.riders"
              @close="isToVisible = false"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="computedTo"
                  :rules="validate('Date To')"
                  label="Date To"
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
            class="py-0"
          >
            <app-autocomplete
              v-model="event.regions"
              :rules="validate('Location(s)')"
              :items="regions"
              :disabled=" !! event.id"
              item-text="title"
              label="Location(s)"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-input-text
              v-model="event.riders"
              :rules="validate('Number of Riders')"
              label="Number of Riders"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
            class="py-0"
          >
            <app-input-text
              v-model="event.price"
              :rules="validate('Price')"
              :disabled="event.id && event.left != event.riders"
              label="Price"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            class="py-0"
          >
            <app-select
              v-model="event.coach"
              :items="coachesList"
              :rules="validate('Coach')"
              :disabled="event.id && event.left != event.riders"
              label="Coach (optional)"
              @submit="onSave"
            />
          </v-col>
        </v-row>
      </v-form>

      <template v-slot:actions>
        <app-input-text
          v-model="event.fee"
          label="Fee"
          class="fee-text"
        />

        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          :loading="isLoading"
          label="Save Event"
          color="primary"
          @click="onSave"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppInputText from './AppInputText'
  import AppAutocomplete from './AppAutocomplete'
  import AppDatePicker from './AppDatePicker'
  import AppSelect from './AppSelect'
  import AppButton from './AppButton'
  import AppRadioGroup from './AppRadioGroup'
  import rules from '../validation/rules'

  export default {
    name: 'RapidEventModal',

    components: {
      AppCard,
      AppInputText,
      AppAutocomplete,
      AppDatePicker,
      AppSelect,
      AppButton,
      AppRadioGroup,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      existEvent: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      valid: true,
      min: new Date().toISOString().substr(0, 10),

      event: {
        type: 'event',
        name: '',
        from: '',
        to: '',
        riders: '',
        left: '',
        price: '',
        fee: 0,
        coach: '',
        regions: [],
      },

      isFromVisible: false,
      isToVisible: false,
      isLoading: false,

      typeList: [
        { label: 'Event', value: 'event' },
        { label: 'Ride Out', value: 'ride out' },
      ],
    }),

    computed: {
      ...mapState({
        regions: state => state.regions.list,
        coaches: state => state.coaches.list,
      }),

      computedFrom() {
        return this.formatDate(this.event.from)
      },

      computedTo() {
        return this.formatDate(this.event.to)
      },

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      coachesList() {
        let result = []
        for (const coach of this.coaches) {
          result.push({
            value: coach.id,
            text: (coach.firstname + ' ' + coach.lastname),
          })
        }
        return result
      },
    },

    watch: {
      existEvent(val) {
        this.event = {
          id: null,
          type: 'event',
          name: '',
          from: '',
          to: '',
          riders: '',
          left: '',
          price: '',
          fee: 0,
          coach: '',
          regions: [],
        }
        
        if (this.$refs.form) {
          this.$refs.form.resetValidation()
        }

        if (val.id) {
          this.event = {
            id: val.id,
            type: val.type,
            name: val.name,
            from: this.parseDate(val.from),
            to: this.parseDate(val.to),
            riders: val.riders,
            left: val.left,
            price: val.price,
            fee: val.fee,
            coach: (val.coach ? val.coach.id : ''),
            regions: [val.region.id],
          }
        }
      },
    },

    created() {
      this.regionsAll()
      this.coachesAll()
    },

    methods: {
      ...mapActions([
        'regionsAll',
        'coachesAll',
        'eventsSave',
      ]),

      formatDate(date) {
        if ( ! date) return null

        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      },

      parseDate(date) {
        if ( ! date) return null

        const [day, month, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Name': r = ['require']; break
          case 'Date From': r = ['require']; break
          case 'Date To': r = ['require']; break
          case 'Location(s)': r = ['requireArray']; break
          case 'Number of Riders': r = ['require']; break
          case 'Price': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.eventsSave(this.event).then(data => {
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