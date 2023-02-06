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
        Add Card
      </template>
      
      <v-form
        v-model="valid"
        ref="form"
        lazy-validation
      >
        <app-input-text
          v-model="form.card"
          :rules="validate('Credit Card Number')"
          label="Credit Card Number"
          type="number"
          maxlength="16"
          @submit="onSave"
        />

        <v-row>
          <v-col :span="12">
            <v-dialog
              v-model="isExpireModalVisible"
              :return-value.sync="form.expire"
              ref="dialog"
              persistent
              width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="dateFormatted"
                  :rules="validate('Expiry Date')"
                  label="Expiry Date"
                  v-bind="attrs"
                  v-on="on"
                  @blur="form.expire = parseDate(dateFormatted)"
                  @submit="onSave"
                />
              </template>

              <v-date-picker
                v-model="form.expire"
                :color="$colors.primary"
                :min="min"
                type="month"
                scrollable
                @input="onExpireSelected(form.expire)"
              />
            </v-dialog>
          </v-col>

          <v-col :span="12">
            <app-input-text
              v-model="form.name"
              :rules="validate('Cardholder Name')"
              label="Cardholder Name"
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
          label="Save Card"
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
  import AppButton from './AppButton'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'RapidCardModal',

    mixins: [Rapid],

    components: {
      AppCard,
      AppInputText,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },
    },

    data: (vm) => ({
      isExpireModalVisible: false,

      valid: true,

      min: new Date().toISOString().substr(0, 10),

      form: {
        card: '',
        expire: new Date().toISOString().substr(0, 10),
        name: '',
      },

      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),

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
      ...mapActions([
        'usersCardAdd',
        'usersCardAll',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Credit Card Number': r = ['require', 'max']; o = {max: 16}; break
          case 'Expiry Date': r = ['require']; break
          case 'Cardholder Name': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onExpireSelected(date) {
        this.$refs.dialog.save(date)
        this.isExpireModalVisible = false
        this.dateFormatted = this.formatDate(date)
      },

      formatDate (date) {
        if (!date) return null

        const [year, month] = date.split('-')
        return `${month}/${year}`
      },

      parseDate (date) {
        if (!date) return null

        const [month, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-01`
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.form.userId = this.me.id
          this.usersCardAdd(this.form).then(data => {
            this.isLoading = false
            if (data) {
              this.$refs.form.resetValidation()

              this.form = {
                card: '',
                expire: new Date().toISOString().substr(0, 10),
                name: '',
              }

              this.usersCardAll({
                userId: this.me.id,
              })

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