<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        My Payment Details
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel
            v-if="canAddCard"
            class="mb-6"
          >
            <div
              class="d-flex justify-space-between align-center mb-4"
            >
              <app-text
                class="text-h6"
              >
                Available Cards
              </app-text>

              <app-button
                id="addCardLight"
                label="Add Card"
                color="primary"
                icon="mdi-plus"
                small
              />
            </div>

            <app-table
              :headers="cardHeaders"
              :items="cards"
              :perPage="10"
            >
              <template v-slot:[`item.card`]="{ item }">
                XXXX-XXXX-XXXX-{{ item.end }}
              </template>

              <template v-slot:[`item.remove`]="{ item }">
                <app-button
                  color="white"
                  isIcon
                  icon="mdi-delete"
                  @click="onRemove(item)"
                />
              </template>
            </app-table>
          </app-panel>

          <app-panel
            v-else
            class="mb-6"
          >
            <app-text
              class="text-center text-h6"
            >
              To add cards you should complete your profile. Please, make sure you have email, phone number and billing address at your profile.
              
              <br /><br />

              <app-button
                label="Fill My Personal Details"
                color="primary"
                @click="onDetails"
              />
            </app-text>
          </app-panel>

          <app-panel>
            <div
              class="d-flex justify-space-between align-center mb-4"
            >
              <app-text
                class="text-h6"
              >
                Payment History
              </app-text>
            </div>

            <app-table
              :headers="headers"
              :items="history"
            >
              <template v-slot:[`item.amount`]="{ item }">
                £{{ item.amount }}
              </template>

              <template v-slot:[`item.order`]="{ item }">
                <div v-if="item.book">
                  <app-text>
                    {{ item.book.course.title }}
                  </app-text>

                  <app-text>
                    <span
                      v-if="item.book.level.level > 0"
                    >
                      Level {{ item.book.level.level }}: 
                    </span>
                    {{ item.book.level.title }}
                  </app-text>
                </div>

                <div v-if="item.event">
                  <app-text>
                    EVENT
                  </app-text>

                  <app-text>
                    {{ item.event.name }}
                  </app-text>
                </div>
              </template>

              <!-- <template v-slot:[`item.instalment`]="{ item }">
                <app-text
                  v-if="item.instalment > 1"
                >
                  {{ item.instalment }} instalments of &pound;{{ Math.round(item.amount / item.instalment) }}
                </app-text>

                <app-text
                  v-else
                >
                  ---
                </app-text>
              </template> -->

              <template v-slot:[`item.status`]="{ item }">
                <app-chip
                  v-if="item.status"
                  :color="$colors.green"
                >
                  <app-text
                    :color="$colors.white"
                  >
                    Paid Successfully
                  </app-text>
                </app-chip>

                <app-chip
                  v-else
                >
                  <app-text
                    :color="$colors.white"
                  >
                    Require Payment
                  </app-text>
                </app-chip>
              </template>

              <!-- <template v-slot:[`item.action`]="{ item }">
                <app-button
                  v-if=" ! item.status"
                  color="blue"
                  label="Make a Payment"
                  small
                  @click="onPayment(item.id)"
                />

                <app-text
                  v-else
                >
                  ---
                </app-text>
              </template> -->
            </app-table>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>

    <app-confirm-modal
      v-model="isRemoveModalVisible"
      title="Remove Card Confirmation"
      @close="isRemoveModalVisible = false"
      @confirm="onCardRemove"
    >
      Are you sure you want do remove this card?
    </app-confirm-modal>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppTable from '../components/AppTable'
  import AppText from '../components/AppText'
  import AppChip from '../components/AppChip'
  import AppButton from '../components/AppButton'
  import AppConfirmModal from '../components/AppConfirmModal'
  import RapidCardModal from '../components/RapidCardModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Payment',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppTable,
      AppText,
      AppChip,
      AppButton,
      AppConfirmModal,
      RapidCardModal,
    },

    data: () => ({
      cardHeaders: [
        { text: 'Card', value: 'card' },
        { text: 'Remove', value: 'remove', align: 'end' },
      ],

      headers: [
        { text: 'Created', value: 'created_at' },
        { text: 'Order', value: 'order' },
        { text: 'Amount', value: 'amount' },
        // { text: 'Instalments', value: 'instalment' },
        { text: 'Due Date', value: 'paid_at' },
        { text: 'Status', value: 'status' },
        // { text: 'Action', value: 'action' },
      ],

      isRemoveModalVisible: false,
      removeCard: null,

      cardModal: false,
    }),

    computed: {
      ...mapState({
        cards: state => state.users.cards,
        payments: state => state.payment.list,
        history: state => state.payment.history,
      }),

      canAddCard() {
        const user = this.me
        return user.address && user.phone != '' && user.phone.length == 11 && user.email != '' && user.firstname != '' && user.lastname != ''
      },
    },

    created() {
      if (this.me.role != 0) {
        return this.$router.replace('/panel')
      }

      this.usersCardAll({
        userId: this.me.id,
      })

      this.paymentHistory({
        userId: this.me.id,
      })

      this.requestCardDetails()
    },

    methods: {
      ...mapActions([
        'usersCardDetails',
        'usersCardAll',
        'usersCardRemove',
        'paymentAll',
        'paymentHistory',
      ]),

      requestCardDetails() {
        if (this.canAddCard) {
          this.usersCardDetails({ userId: this.me.id }).then(data => {
            if (data) {
              const response = JSON.parse(data)
              const responseEndpoint = `${window.location.protocol}//${window.location.host}/panel/payment`
              if (process.env.MIX_GP_MODE == 'dev') {
                RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay")
              } else {
                RealexHpp.setHppUrl("https://pay.realexpayments.com/pay")
              }
              RealexHpp.lightbox.init("addCardLight", responseEndpoint, response)
            }
          })
        }
      },

      onPayment(id) {
        this.$router.push('/panel/payment/' + id);
      },

      onDetails() {
        this.$router.push('/panel');
      },

      onRemove(card) {
        this.removeCard = card.id
        this.isRemoveModalVisible = true
      },

      onCardRemove() {
        this.isRemoveModalVisible = false
        this.usersCardRemove({
          userId: this.me.id,
          card: this.removeCard,
        })
      },

      onCardModalClose() {
        this.cardModal = false
      },
    },
  }
</script>