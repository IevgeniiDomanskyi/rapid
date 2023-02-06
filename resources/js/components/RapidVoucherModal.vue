<template>
  <v-dialog
    v-model="dialog"
    width="600"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Add Promotion
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-row>
          <v-col
            :cols="12"
            :sm="8"
          >
            <app-select
              v-model="voucher.type"
              :items="typeList"
              :rules="validate('Discount type')"
              label="Discount type"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
          >
            <app-input-text
              v-model="voucher.amount"
              :rules="validate('Discount offered')"
              label="Discount offered"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="8"
          >
            <app-input-text
              v-if=" ! codes.length"
              v-model="voucher.code"
              :rules="validate('Voucher code')"
              label="Voucher code"
              @submit="onSave"
            />

            <app-text
              v-else
            >
              {{ codes.length }} code(s) uploaded
            </app-text>
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
          >
            <app-button
              v-if=" ! voucher.id"
              :loading="isUploadLoading"
              block
              label="Upload codes"
              color="primary"
            >
              <input
                ref="fileupload"
                type="file"
                accept=".csv"
                @input="onUpload"
              />
            </app-button>
          </v-col>

          <v-col
            :cols="12"
          >
            <app-autocomplete
              v-model="voucher.excludes"
              :rules="validate('Exclude following items')"
              :items="levelsList"
              label="Exclude following items"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
          >
            <v-dialog
              v-model="isExpiredModalVisible"
              :return-value.sync="voucher.expired_at"
              ref="dialog"
              persistent
              width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <app-input-text
                  v-model="computedExpiredAt"
                  :rules="validate('Expiry date')"
                  label="Expiry date"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @submit="onSave"
                />
              </template>

              <v-date-picker
                v-model="voucher.expired_at"
                :min="min"
                :color="$colors.primary"
                scrollable
                @input="onExpiredSelected(voucher.expired_at)"
              />
            </v-dialog>
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
          >
            <app-input-text
              v-model="voucher.coupon_limit"
              :rules="validate('Usage limit per coupon')"
              label="Usage limit per coupon"
              @submit="onSave"
            />
          </v-col>

          <v-col
            :cols="12"
            :sm="4"
          >
            <app-input-text
              v-model="voucher.user_limit"
              :rules="validate('Usage limit per user')"
              label="Usage limit per user"
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
          label="Save Voucher"
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
  import AppText from './AppText'
  import rules from '../validation/rules'

  export default {
    name: 'RapidVoucherModal',

    components: {
      AppCard,
      AppInputText,
      AppAutocomplete,
      AppButton,
      AppSelect,
      AppText,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      existVoucher: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      valid: true,

      min: new Date().toISOString().substr(0, 10),

      voucher: {
        id: null,
        type: '',
        amount: '',
        code: '',
        coupon_limit: '',
        user_limit: '',
        excludes: [],
        expired_at: '',
      },

      typeList: [
        { text: 'Fixed amount (£)', value: 0, },
        { text: 'Percent (%)', value: 1, },
      ],

      isExpiredModalVisible: false,

      isUploadLoading: false,
      isLoading: false,
    }),

    computed: {
      ...mapState({
        courses: state => state.courses.list,
        codes: state => state.vouchers.codes,
      }),

      computedExpiredAt() {
        return this.formatDate(this.voucher.expired_at)
      },

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      levelsList() {
        let result = []
        for (const course of this.courses) {
          for (const level of course.levels) {
            result.push({
              name: (course.title + (level.level > 0 ? (' ' + level.level) : '')),
              id: level.id,
            })
          }
        }

        return result
      },
    },

    watch: {
      existVoucher(val) {
        if (val.id) {
          this.voucher = {
            id: val.id,
            type: val.type,
            amount: val.amount.toString(),
            code: val.code.toString(),
            coupon_limit: val.coupon_limit.toString(),
            user_limit: val.user_limit.toString(),
            excludes: this.getExcludedIds(val.excluded),
            expired_at: this.parseDate(val.expired_at),
          }
        } else {
          this.voucher = {
            id: null,
            type: '',
            amount: '',
            code: '',
            coupon_limit: '',
            user_limit: '',
            excludes: [],
            expired_at: '',
          }
        }
      },
    },

    methods: {
      ...mapActions([
        'vouchersUpload',
        'vouchersSave',
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
          case 'Discount offered': r = ['require']; break
          case 'Voucher code': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      getExcludedIds(excluded) {
        let ids = []

        if (excluded) {
          for (const level of excluded) {
            ids.push(level.id)
          }
        }
        
        return ids
      },

      onExpiredSelected(date) {
        this.$refs.dialog.save(date)
        this.isExpiredModalVisible = false
      },

      onUpload(e) {
        const file = e.target.files[0]
        this.isUploadLoading = true

        let formData = new FormData()
        formData.append('file', file)
        this.vouchersUpload(formData).then(data => {
          if (data) {
            this.voucher.code = data
          }

          this.isUploadLoading = false
          this.$refs.fileupload.value = null
        })
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.vouchersSave(this.voucher).then(data => {
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