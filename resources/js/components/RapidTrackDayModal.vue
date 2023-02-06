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
        <span v-if="! trackDay.id">Add Track Day</span>
        <span v-else>Edit Track Day</span>
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
            <app-input-text
              v-model="trackDay.name"
              :disabled="trackDay.id && trackDay.left != trackDay.riders"
              :rules="validate('Name')"
              label="Name"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            class="py-0"
          >
            <app-autocomplete
              v-model="trackDay.trackDate"
              :rules="validate('Track Date')"
              :items="trackDateList"
              :multiple="false"
              :chips="false"
              item-value="value"
              item-text="text"
              label="Track Date"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-input-text
              v-model="trackDay.riders1"
              :rules="validate('Level 1 Riders')"
              label="Level 1 Riders"
              type="number"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-input-text
              v-model="trackDay.riders2"
              :rules="validate('Level 2 Riders')"
              label="Level 2 Riders"
              type="number"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-input-text
              v-model="trackDay.riders3"
              :rules="validate('Level 3 Riders')"
              label="Level 3 Riders"
              type="number"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
            class="py-0"
          >
            <app-input-text
              v-model="trackDay.price"
              :rules="validate('Price')"
              :disabled="trackDay.id && trackDay.left != trackDay.riders"
              label="Price"
              type="number"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="8"
            class="py-0"
          >
            <app-select
              v-model="trackDay.coach"
              :items="coachesList"
              :rules="validate('Coach')"
              :disabled="trackDay.id && trackDay.left != trackDay.riders"
              label="Coach (optional)"
              @submit="onSave"
            />
          </v-col>
        </v-row>
      </v-form>

      <template v-slot:actions>
        <app-input-text
          v-model="trackDay.fee"
          label="Fee"
          type="number"
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
    name: 'RapidTrackDayModal',

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

      existTrackDay: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      valid: true,

      trackDay: {
        name: '',
        trackDate: '',
        riders1: '',
        riders2: '',
        riders3: '',
        price: '',
        fee: 0,
        coach: '',
      },

      isLoading: false,
    }),

    computed: {
      ...mapState({
        trackDates: state => state.tracks.dates,
        coaches: state => state.coaches.list,
      }),

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

      trackDateList() {
        let result = []
        for (const date of this.trackDates) {
          result.push({
            value: date.id,
            text: date.date,
          })
        }
        return result
      },
    },

    watch: {
      existTrackDay(val) {
        this.trackDay = {
          id: null,
          trackDate: '',
          name: '',
          riders1: '',
          riders2: '',
          riders3: '',
          price: '',
          fee: 0,
          coach: '',
        }
        
        if (this.$refs.form) {
          this.$refs.form.resetValidation()
        }

        if (val.id) {
          this.trackDay = {
            id: val.id,
            trackDate: val.track_date.id,
            name: val.name,
            riders1: val.riders1,
            riders2: val.riders2,
            riders3: val.riders3,
            price: val.price,
            fee: val.fee,
            coach: (val.coach ? val.coach.id : ''),
          }
        }
      },
    },

    created() {
      this.tracksDatesAll()
      this.coachesAll()
    },

    methods: {
      ...mapActions([
        'tracksDatesAll',
        'coachesAll',
        'trackDaysSave',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Name': r = ['require']; break
          case 'Track Date': r = ['require']; break
          case 'Price': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.trackDaysSave(this.trackDay).then(data => {
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