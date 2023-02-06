<template>
  <v-dialog
    v-model="dialog"
    width="600"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <div class="availability">
      <div class="availability-info">
        <div>
          <app-text>
            Coach: {{ coach.firstname }} {{ coach.lastname }}
          </app-text>

          <app-text>
            Region: {{ printRegions(coach.regions) }}
          </app-text>
        </div>

        <app-text
          v-if="date && ! editMode"
          class="mt-3"
        >
          {{ slotsText(date) }}
        </app-text>

        <div
          v-if=" ! editMode"
          class="mt-3"
        >
          <app-button
            label="Set Availability"
            color="primary"
            outlined
            small
            @click="onEdit"
          />
        </div>

        <div
          v-else
          class="mt-3"
        >
          <app-button
            label="Save Dates"
            color="primary"
            small
            @click="onSave"
          />

          <app-button
            label="Cancel"
            color="primary"
            outlined
            small
            @click="onEditCancel"
          />
        </div>
      </div>

      <v-date-picker
        v-model="selectedDates"
        :color="$colors.primary"
        :multiple="editMode"
        :events="datesFunction"
        scrollable
      />
    </div>
  </v-dialog>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import AppText from './AppText'
  import AppInputText from './AppInputText'
  import AppButton from './AppButton'

  export default {
    name: 'RapidAvailabilityModal',

    components: {
      AppText,
      AppInputText,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      coach: {
        type: Object,
        default: () => {},
      },

      dates: {
        type: Array,
        default: () => [],
      },
    },
    
    data: () => ({
      date: new Date().toISOString().substr(0, 10),
      availableDates: [],

      editMode: false,
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

      selectedDates: {
        get() {
          return this.editMode ? this.availableDates : this.date
        },

        set(val) {
          if (this.editMode) {
            this.availableDates = val
          } else {
            this.date = val
          }
        },
      },
    },

    methods: {
      ...mapActions([
        'datesSave',
      ]),

      printRegions(regions) {
        let result = []
        if (regions) {
          for (const region of regions) {
            result.push(region.title)
          }
        }
        return result.join(', ')
      },

      slotsText(date) {
        for (const availableDate of this.dates) {
          if (availableDate.date == date) {
            if (availableDate.am && availableDate.pm) {
              return 'No available slots'
            }

            if (availableDate.am && ! availableDate.pm) {
              return 'PM slot is available'
            }

            if (! availableDate.am && availableDate.pm) {
              return 'AM slot is available'
            }

            if (! availableDate.am && ! availableDate.pm) {
              return 'All day is available'
            }
          }
        }

        return 'Date is not available'
      },

      datesFunction(date) {
        if ( ! this.editMode) {
          for (const availableDate of this.dates) {
            if (availableDate.date == date) {
              if (availableDate.am && availableDate.pm) {
                return [this.$colors.red]
              }

              if (availableDate.am && ! availableDate.pm) {
                return [this.$colors.orange]
              }

              if (! availableDate.am && availableDate.pm) {
                return [this.$colors.orange]
              }

              if (! availableDate.am && ! availableDate.pm) {
                return [this.$colors.green]
              }
            }
          }
        }

        return false
      },

      onEdit() {
        if ( ! this.availableDates.length) {
           for (const availableDate of this.dates) {
             this.availableDates.push(availableDate.date)
           }
        }
        
        this.editMode = true
      },

      onEditCancel() {
        this.editMode = false
      },

      onSave() {
        this.isLoading = true
        this.datesSave({
          userId: this.coach.id,
          dates: this.availableDates,
        }).then(data => {
          if (data) {
            this.onEditCancel()
          }

          this.isLoading = false
        })
      },

      onClose() {
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

  @media (max-width: 600px) {
    .availability {
      flex-direction: column;

      &-info {
        width: auto;
      }
    }
  }
</style>