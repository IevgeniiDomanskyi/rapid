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
        Add Coach
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <app-input-text
          v-model="coach.firstname"
          :rules="validate('First Name')"
          label="First Name"
          @submit="onSave"
        />

        <app-input-text
          v-model="coach.lastname"
          :rules="validate('Last Name')"
          label="Last Name"
          @submit="onSave"
        />

        <app-input-text
          v-model="coach.email"
          :rules="validate('Email Address')"
          label="Email Address"
          type="email"
          @submit="onSave"
        />

        <app-input-text
          v-model="coach.phone"
          :rules="validate('Phone Number')"
          label="Phone Number"
          @submit="onSave"
        />

        <app-autocomplete
          v-model="coach.regions"
          :rules="validate('Region')"
          :items="regions"
          item-text="title"
          label="Region"
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
          label="Save Coach"
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
  import AppButton from './AppButton'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCoachModal',

    components: {
      AppCard,
      AppInputText,
      AppAutocomplete,
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

      coach: {
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        regions: [],
      },

      isLoading: false,
    }),

    computed: {
      ...mapState({
        regions: state => state.regions.list,
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

    created() {
      this.regionsAll()
    },

    methods: {
      ...mapActions([
        'regionsAll',
        'coachesSave',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First Name': r = ['require']; break
          case 'Last Name': r = ['require']; break
          case 'Email Address': r = ['require', 'email']; break
          case 'Phone Number': r = ['require']; break
          case 'Region': r = ['requireArray']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.coachesSave(this.coach).then(data => {
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