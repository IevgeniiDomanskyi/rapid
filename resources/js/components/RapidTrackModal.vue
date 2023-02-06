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
        Add Track
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <app-input-text
          v-model="track.name"
          :rules="validate('Track Name')"
          label="Track Name"
          @submit="onSave"
        />

        <app-select
          v-model="track.region"
          :items="regionList"
          :rules="validate('Region')"
          label="Region"
          @submit="onSave"
        />

        <app-input-text
          v-model="track.email"
          :rules="validate('Billing Email Address')"
          label="Billing Email Address"
          @submit="onSave"
        />
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
          label="Save Track"
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
  import AppSelect from './AppSelect'
  import AppText from './AppText'
  import AppButton from './AppButton'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCoachModal',

    components: {
      AppCard,
      AppInputText,
      AppSelect,
      AppText,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },
    },

    data: () => ({
      valid: true,

      track: {
        name: '',
        region: 0,
        email: '',
      },

      isLoading: false,
    }),

    computed: {
      ...mapState({
        regions: state => state.regions.list,
      }),

      regionList() {
        let result = []
        for (const region of this.regions) {
          result.push({
            value: region.id,
            text: region.title,
          })
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

    methods: {
      ...mapActions([
        'tracksSave',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Track Name': r = ['require']; break
          case 'Region': r = ['require']; break
          case 'Billing Email Address': r = ['require', 'email']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.tracksSave(this.track).then(data => {
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