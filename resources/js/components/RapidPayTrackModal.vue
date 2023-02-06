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
        Confirm Track
      </template>
      
      <div
        class="pay-track-body"
      >
        <app-checkbox
          v-model="selected"
          label="Track was confirmed"
        />
      </div>

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
          label="Save"
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
  import AppCheckbox from './AppCheckbox'
  import AppButton from './AppButton'

  export default {
    name: 'RapidPayTrackModal',

    components: {
      AppCard,
      AppText,
      AppCheckbox,
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
      selected: null,

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
        'bookTrackPay',
      ]),

      onSave() {
        if (this.selected) {
          this.isLoading = true

          this.bookTrackPay({
            book: this.book,
            pay: this.selected,
          }).then(data => {
            this.isLoading = false
            if (data) {
              this.onClose()
            }
          })
        } else {
          this.onClose()
        }
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .pay-track-body {
    max-height: 360px;
    overflow: auto;
    margin-bottom: 15px;
  }
</style>