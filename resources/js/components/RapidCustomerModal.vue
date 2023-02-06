<template>
  <v-dialog
    v-model="dialog"
    width="600"
    @click:outside="onClose"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        {{ isEdit ? 'Edit Customer' : 'Add Customer' }}
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
          >
            <app-input-text
              v-model="customer.firstname"
              :rules="validate('First Name')"
              label="First Name"
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.lastname"
              :rules="validate('Last Name')"
              label="Last Name"
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.email"
              :rules="validate('Email Address')"
              :readonly="!! isEdit"
              label="Email Address"
              type="email"
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.phone"
              :rules="validate('Phone Number')"
              label="Phone Number"
              type="number"
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.postcode"
              :rules="validate('Postcode')"
              label="Postcode"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="6"
          >
            <app-input-text
              v-model="customer.line1"
              :rules="validate('Address Line 1')"
              label="Address Line 1"
              inverse
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.line2"
              label="Address Line 2"
              inverse
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.town"
              :rules="validate('Town')"
              label="Town/City"
              inverse
              @submit="onSave"
            />

            <app-input-text
              v-model="customer.county"
              :rules="validate('County')"
              label="County"
              inverse
              @submit="onSave"
            />

            <app-select
              v-model="customer.country"
              :items="countryList"
              :rules="validate('Country')"
              label="Country"
              inverse
              @submit="onSave"
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
          label="Save Customer"
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
  import AppSelect from './AppSelect'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCustomerModal',

    components: {
      AppCard,
      AppInputText,
      AppAutocomplete,
      AppButton,
      AppSelect,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      existCustomer: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      valid: true,
      validAddress: true,

      customer: {
        id: null,
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        postcode: '',
        line1: '',
        line2: '',
        town: '',
        county: '',
        country: '',
      },

      countryList: [
        { value: 'England', text: 'England' },
        { value: 'Scotland', text: 'Scotland' },
        { value: 'Wales', text: 'Wales' },
      ],

      isLoading: false,
    }),

    computed: {
      ...mapState({
        postcodes: state => state.postcodes.list,
      }),

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      isEdit() {
        return (this.existCustomer && this.existCustomer.id)
      },

      postcodesList() {
        let result = []
        for (const postcode of this.postcodes) {
          result.push({
            value: postcode.id,
            text: (postcode.code + ' (' + postcode.area + ')'),
          })
        }
        return result
      },
    },

    watch: {
      existCustomer(val) {
        this.customer = {
          id: val.id,
          firstname: val.firstname,
          lastname: val.lastname,
          email: val.email,
          phone: val.phone,
          postcode: val.postcode,
          line1: '',
          line2: '',
          town: '',
          county: '',
          country: '',
        }

        if (val.address) {
          this.customer.line1 = val.address.line1
          this.customer.line2 = val.address.line2
          this.customer.town = val.address.town
          this.customer.county = val.address.county
          this.customer.country = val.address.country
        }
      },
    },

    methods: {
      ...mapActions([
        'usersCustomersSave',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First Name': r = ['require']; break
          case 'Last Name': r = ['require']; break
          case 'Email Address': r = ['require', 'email']; break
          case 'Phone Number': r = ['require', 'phone']; break
          case 'Address Line 1': r = ['require']; break
          case 'Town': r = ['require']; break
          case 'City': r = ['require']; break
          // case 'County': r = ['require']; break
          case 'Country': r = ['require']; break
          case 'Postcode': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.usersCustomersSave(this.customer).then(data => {
            this.isLoading = false
            if (data) {
              this.customer = {
                id: null,
                firstname: '',
                lastname: '',
                email: '',
                phone: '',
                postcode: '',
                line1: '',
                line2: '',
                town: '',
                county: '',
                country: '',
              }

              this.onClose()
            }
          })
        }
      },

      onClose() {
        this.$refs.form.resetValidation()
        this.$emit('close')
      },
    },
  }
</script>