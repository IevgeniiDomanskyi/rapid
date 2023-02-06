<template>
  <v-dialog
    v-model="dialog"
    width="300"
  >
    <v-date-picker
      v-model="date"
      :color="$colors.primary"
      :allowed-dates="allowedDates"
      scrollable
      @change="onChangeDate"
    >
      <div class="d-flex flex-column" style="width: 100%;">
        <div class="d-flex flex-row align-center">
          <v-spacer />

          <app-radio-group
            v-model="slot"
            :list="slotsList"
            light
            row
          />
        </div>

        <div class="d-flex flex-row align-center">
          <app-button
            label="Clear"
            color="primary"
            outlined
            @click="onClear"
          />

          <v-spacer />
          
          <app-button
            label="Close"
            color="primary"
            outlined
            @click="onClose"
          />

          <app-button
            :loading="isLoading"
            label="Save"
            color="primary"
            @click="onDefine"
          />
        </div>
      </div>
    </v-date-picker>
  </v-dialog>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import AppText from './AppText'
  import AppInputText from './AppInputText'
  import AppButton from './AppButton'
  import AppRadioGroup from './AppRadioGroup'

  export default {
    name: 'RapidDefineRoadDateModal',

    components: {
      AppText,
      AppInputText,
      AppButton,
      AppRadioGroup,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      book: {
        type: Number,
        default: 0,
      },

      road: {
        type: Number,
        default: 0,
      },

      selectedDate: {
        type: Object,
        default: () => {},
      },
    },
    
    data: () => ({
      date: null,
      slot: null,

      slotsList: [
        { value: 1, label: 'AM' },
        { value: 2, label: 'PM' },
        { value: 3, label: 'Full Day' },
      ],

      isLoading: false,
    }),

    computed: {
      ...mapState({
        dates: state => state.dates.list,
      }),

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
      selectedDate(val) {
        this.date = val.date || ''
        if (val.slots && val.slots[this.road]) {
          this.slot = val.slots[this.road]

          this.slotsList = [
            { value: 1, label: 'AM', disabled: val.am && val.slots[this.road] != 1 },
            { value: 2, label: 'PM', disabled: val.pm && val.slots[this.road] != 2 },
            { value: 3, label: 'Full Day', disabled: (val.am && val.slots[this.road] != 1) || (val.pm && val.slots[this.road] != 2) },
          ]
        }
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'bookRoadDateDefine',
      ]),

      onChangeDate() {
        this.slot = null
        for (const date of this.dates) {
          if (this.date == date.date) {
            
            this.slotsList = [
              { value: 1, label: 'AM', disabled: date.am },
              { value: 2, label: 'PM', disabled: date.pm },
              { value: 3, label: 'Full Day', disabled: date.am || date.pm },
            ]

            if (date.id == this.selectedDate.id) {
              this.slot = date.slots[this.road]

              this.slotsList = [
                { value: 1, label: 'AM', disabled: date.am && date.slots[this.road] != 1 },
                { value: 2, label: 'PM', disabled: date.pm && date.slots[this.road] != 2 },
                { value: 3, label: 'Full Day', disabled: (date.am && date.slots[this.road] != 1) || (date.pm && date.slots[this.road] != 2) },
              ]
            }
          }
        }
      },

      allowedDates(val) {
        for (const date of this.dates) {
          if (val == date.date && ( ! date.am || ! date.pm || this.selectedDate.id == date.id)) {
            return true
          }
        }
        return false
      },

      selectedDateId(val) {
        for (const date of this.dates) {
          if (val == date.date) {
            return date.id
          }
        }

        return 0
      },

      onClear() {
        this.date = null
        this.slot = null
      },

      onDefine() {
        if (this.date && ! this.slot) {
          this.messagesSet({
            text: 'Please, select a slot',
            type: 'warning',
          })
        } else {
          this.isLoading = true
          this.bookRoadDateDefine({
            book: this.book,
            road: this.road,
            slot: this.slot,
            date: this.selectedDateId(this.date),
          }).then(data => {
            this.isLoading = false
            if (data) {
              this.onClose()

              this.date = null
              this.slot = null

              this.slotsList = [
                { value: 1, label: 'AM' },
                { value: 2, label: 'PM' },
                { value: 3, label: 'Full Day' },
              ]
            }
          })
        }
      },

      onClose() {
        this.date = null
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/_variables.scss";

  .availability {
    display: flex;
    justify-content: center;
    align-items: stretch;

    &-info {
      flex: 1;
      width: 300px;
      background: $panel;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  }
</style>