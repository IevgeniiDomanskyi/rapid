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
        Road Dates Confirmed
      </template>
      
      <div
        class="agree-track-dates-body"
      >
        <app-text>
          Have you selected all road dates?
        </app-text>
      </div>

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Cancel"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          :loading="isLoading"
          label="Yes"
          color="primary"
          @click="onSave"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppText from './AppText'
  import AppButton from './AppButton'

  export default {
    name: 'RapidAgreeRoadDatesModal',

    components: {
      AppCard,
      AppText,
      AppButton,
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
    },

    data: () => ({
      isLoading: false,
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

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'bookAgreeRoadDates',
      ]),

      onSave() {
        this.isLoading = true

        this.bookAgreeRoadDates({
          book: this.book,
        }).then(data => {
          this.isLoading = false
          if (data) {
            this.onClose()
          }
        })
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .agree-track-dates-body {
    max-height: 360px;
    overflow: auto;
    margin-bottom: 15px;
  }
</style>