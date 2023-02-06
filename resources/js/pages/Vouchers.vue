<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        Vouchers
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel>
            <div
              class="d-flex flex-column flex-sm-row justify-space-between align-center mb-4"
            >
              <div
                class="rapid-search-field"
              >
                <app-input-text
                  v-model="search"
                  inverse
                  label="Search..."
                />
              </div>

              <rapid-voucher-modal
                v-model="voucherModal"
                :existVoucher="voucher"
                @close="onVoucherModalClose"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-button
                    label="Add Promotion"
                    color="primary"
                    icon="mdi-plus"
                    small
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
              </rapid-voucher-modal>
            </div>

            <app-table
              v-model="selected"
              :headers="headers"
              :items="vouchers"
              :search="search"
              :export-options="exportOptions"
              show-select
            >
              <template v-slot:[`item.type`]="{ item }">
                <app-text
                  v-if="item.type == 0"
                >
                  Fixed amount
                </app-text>

                <app-text
                  v-else
                >
                  Percentage
                </app-text>
              </template>

              <template v-slot:[`item.excluded`]="{ item }">
                <app-text
                  v-for="level in item.excluded"
                  :key="level.id"
                  class="text-no-wrap"
                >
                  {{ level.course.title }} {{ level.level > 0 ? level.level : '' }}
                </app-text>
              </template>

              <template v-slot:[`item.actions`]="{ item }">
                <div class="text-no-wrap">
                  <app-button
                    label="Edit"
                    color="green"
                    small
                    @click="onEdit(item)"
                  />

                  <app-button
                    label="Delete"
                    color="red"
                    small
                    @click="onRemove(item)"
                  />
                </div>
              </template>
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <app-confirm-modal
      v-model="confirmRemoveModal"
      title="Remove voucher"
      @confirm="onVoucherRemove"
      @close="onConfirmRemoveModalClose"
    >
      <app-text>
        Are you sure you want to delete this voucher?
      </app-text>
    </app-confirm-modal>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import AppTable from '../components/AppTable'
  import AppConfirmModal from '../components/AppConfirmModal'
  import RapidVoucherModal from '../components/RapidVoucherModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Vouchers',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppInputText,
      AppButton,
      AppTable,
      AppConfirmModal,
      RapidVoucherModal,
    },

    data: () => ({
      voucher: {},
      voucherModal: false,
      confirmRemoveModal: false,
      removeId: 0,

      search: '',
      headers: [
        { text: 'Discount type', value: 'type' },
        { text: 'Discount offered', value: 'amount' },
        { text: 'Voucher code', value: 'code' },
        { text: 'Expiry date', value: 'expired_at' },
        { text: 'Excluded items', value: 'excluded' },
        { text: 'Usage limit per coupon', value: 'coupon_limit' },
        { text: 'Usage limit per user', value: 'user_limit' },
        { text: 'Redemptions', value: 'redemptions' },
        { text: 'Actions', value: 'actions' },
      ],

      selected: [],
      exportOptions: {
        file: 'vouchers',
        type: 'voucher',
        roles: ['admin'],
      },
    }),

    computed: {
      ...mapState({
        vouchers: state => state.vouchers.list,
      }),
    },

    created() {
      if (this.me.role != 2) {
        return this.$router.replace('/panel')
      }

      this.vouchersAll()
    },

    methods: {
      ...mapActions([
        'vouchersAll',
        'vouchersRemove',
      ]),

      onVoucherModalClose() {
        this.voucherModal = false
        this.voucher = {}
      },

      onVoucherRemove() {
        this.vouchersRemove({ voucherId: this.removeId }).then(data => {
          if (data) {
            this.onConfirmRemoveModalClose()
          }
        })
      },

      onConfirmRemoveModalClose() {
        this.confirmRemoveModal = false
        this.removeId = 0
      },

      onEdit(item) {
        this.voucher = item
        this.voucherModal = true
      },

      onRemove(item) {
        this.removeId = item.id
        this.confirmRemoveModal = true
      },
    },
  }
</script>