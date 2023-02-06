<template>
  <div>
    <rapid-title>
      <app-text
        v-if="isCustomer"
        class="text-h5"
      >
        Message History
      </app-text>

      <app-text
        v-else
      >
        Contact Customer - Conversation History
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col>
          <app-panel
            class="d-flex flex-column justify-space-between"
            style="height: calc(100vh - 192px);"
          >
            <div
              class="dialog-info d-none d-sm-block"
            >
              <app-text
                v-if="dialog.customer && ! isCustomer"
                size="lg"
              >
                Customer Name: {{ dialog.customer.firstname }} {{ dialog.customer.lastname }}
              </app-text>

              <app-text
                v-if="dialog.customer && ! isCustomer"
              >
                Email: {{ dialog.customer.email }}
              </app-text>

              <app-text>
                Conversation opened on {{ dialog.created_at }}
              </app-text>

              <app-text
                v-if="dialog.coach"
              >
                Assigned Instructor: {{ dialog.coach.firstname }} {{ dialog.coach.lastname }}
              </app-text>

              <app-text
                v-if="dialog.customer && ! isCustomer"
              >
                Member Number {{ dialog.customer.id }}
              </app-text>

              <app-text>
                This conversation is
                <app-chip
                  v-if=" ! dialog.status"
                  color="green"
                  small
                  dark
                >
                  Open
                </app-chip>

                <app-chip
                  v-else
                  color="red"
                  small
                  dark
                >
                  Close
                </app-chip>
              </app-text>
            </div>

            <div
              class="dialog-messages"
            >
              <div
                v-for="message in dialog.messages"
                :key="message.id"
                :class="['message-item', { receiver: (message.author.id != me.id && message.type != 1), author: (message.author.id == me.id && message.type != 1), system: message.type == 1 }]"
              >
                <div
                  v-if="(message.type == 2 || message.type == 3) && message.author.id == me.id && me.role == 2 || message.type == 0"
                  class="message-item__user"
                >
                  <div
                    v-if="message.type != 1 && message.author.id == me.id && me.role == 2"
                  >
                    Rapid Training {{ message.type == 2 ? ' (Call)' : (message.type == 3 ? ' (Note)' : '') }}
                  </div>

                  <div
                    v-else
                  >
                    {{ message.author.firstname }} {{ message.author.lastname }}
                  </div>
                </div>

                <div
                  v-if="(message.type == 2 || message.type == 3) && message.author.id == me.id && me.role == 2 || message.type <= 1"
                  class="message-item__message"
                >
                  {{ message.message }}
                </div>

                <div
                  v-if="(message.type == 2 || message.type == 3) && message.author.id == me.id && me.role == 2 || message.type == 0"
                  class="message-item__date"
                >
                  Sent {{ message.created_at }}
                  <span
                    v-if="message.receiver_read"
                  >
                    - Read {{ message.receiver_read }}
                  </span>
                </div>
              </div>
            </div>

            <div
              v-if=" ! dialog.status"
              class="dialog-controls"
            >
              <v-row>
                <v-col
                  :cols="12"
                  :md="7"
                  class="d-flex justify-space-between align-end"
                >
                  <app-textarea
                    v-model="message"
                    :rows="2"
                    label="Enter your message here"
                    inverse
                    class="message-area"
                  />

                  <app-button
                    :loading="isLoading"
                    label="Send"
                    color="primary"
                    small
                    @click="onSendMessage"
                  />
                </v-col>

                <v-col
                  :cols="12"
                  :md="5"
                  class="d-flex justify-end align-end"
                >
                  <app-button
                    v-if="me.role > 0"
                    label="Add Call"
                    color="rose"
                    small
                    @click="onAddCall(dialog.id)"
                  />

                  <app-button
                    v-if="me.role > 0"
                    label="Add Notes"
                    color="grey"
                    class="ml-2"
                    small
                    @click="onAddNotes(dialog.id)"
                  />

                  <app-button
                    v-if="me.role > 0"
                    label="Close Ticket"
                    color="red"
                    class="ml-2"
                    small
                    @click="onCloseTicket"
                  />
                </v-col>
              </v-row>
            </div>
          </app-panel>
        </v-col>
      </v-row>

      <app-confirm-modal
        v-model="confirmModal"
        title="Close confirmation"
        @confirm="onConfirmClose"
        @close="onCloseClose"
      >
        Do you really want to close this ticket?
      </app-confirm-modal>

      <rapid-add-call-modal
        v-model="addCallModal"
        :dialog="dialog"
        @close="onAddCallModalClose"
      />

      <rapid-add-notes-modal
        v-model="addNotesModal"
        :dialog="dialog"
        @close="onAddNotesModalClose"
      />
    </rapid-content>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppPanel from '../components/AppPanel'
  import AppText from '../components/AppText'
  import AppTextarea from '../components/AppTextarea'
  import AppInputText from '../components/AppInputText'
  import AppButton from '../components/AppButton'
  import AppChip from '../components/AppChip'
  import AppConfirmModal from '../components/AppConfirmModal'
  import RapidAddCallModal from '../components/RapidAddCallModal'
  import RapidAddNotesModal from '../components/RapidAddNotesModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Dialog',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppPanel,
      AppText,
      AppTextarea,
      AppInputText,
      AppButton,
      AppChip,
      AppConfirmModal,
      RapidAddCallModal,
      RapidAddNotesModal,
    },

    data: () => ({
      confirmModal: false,
      addCallModal: false,
      addNotesModal: false,

      isLoading: false,
      message: '',
    }),

    computed: {
      ...mapState({
        me: state => state.auth.me,
        dialog: state => state.dialogs.current,
      }),
    },

    created() {
      /* if (this.me.role != 2) {
        return this.$router.replace('/panel')
      } */

      this.dialogsGet({ dialogId: this.$router.history.current.params.dialogId }).then(data => {
        for (const message of data.messages) {
          if (message.author.id != this.me.id && message.author.id > 0 && ! message.receiver_read) {
            this.dialogsMessageRead({ messageId: message.id })
          }
        }
      })
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'dialogsGet',
        'dialogsMessageRead',
        'dialogsAddMessage',
        'dialogsClose',
      ]),

      onAddCall(dialogId) {
        this.addCallModal = true
      },

      onAddCallModalClose() {
        this.addCallModal = false

        this.dialogsGet({ dialogId: this.$router.history.current.params.dialogId }).then(data => {
          for (const message of data.messages) {
            if (message.author.id != this.me.id && message.author.id > 0 && ! message.receiver_read) {
              this.dialogsMessageRead({ messageId: message.id })
            }
          }
        })
      },

      onAddNotes(dialogId) {
        this.addNotesModal = true
      },

      onAddNotesModalClose() {
        this.addNotesModal = false
        
        this.dialogsGet({ dialogId: this.$router.history.current.params.dialogId }).then(data => {
          for (const message of data.messages) {
            if (message.author.id != this.me.id && message.author.id > 0 && ! message.receiver_read) {
              this.dialogsMessageRead({ messageId: message.id })
            }
          }
        })
      },
      
      onSendMessage() {
        if (this.message.trim() == '') {
          this.messagesSet({
            text: 'Please, enter your message',
            type: 'warning',
          })
        } else {
          this.isLoading = true
          this.dialogsAddMessage({
            dialogId: this.$router.history.current.params.dialogId,
            message: this.message,
          }).then(data => {
            this.message = ''
            this.isLoading = false
          })
        }
      },

      onCloseTicket() {
        this.confirmModal = true
      },

      onCloseClose() {
        this.confirmModal = false
      },

      onConfirmClose() {
        this.dialogsClose({
          dialogId: this.$router.history.current.params.dialogId,
        }).then(data => {
          this.confirmModal = false
        })
      },
    },
  }
</script>

<style lang="scss" scoped>
  .message-item {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    font-size: .875rem;
    color: #fff;

    &__user {
      width: 60%;
      opacity: 0.7;
    }

    &__message {
      width: 60%;
      padding: 12px 16px;
      border-radius: 4px;
      margin-bottom: 4px;
    }

    &__date {
      width: 60%;
      opacity: 0.7;
      text-align: right;
    }

    &.receiver {
      align-items: flex-start;
      .message-item__message {
        background: #4B4F72;
      }
    }

    &.author {
      align-items: flex-end;
      .message-item__message {
        background: #B5B5B5;
        color: rgba(0, 0, 0, 0.87);
      }
    }

    &.system {
      align-items: center;
      text-align: center;
      .message-item__message {
        background: #9185BF;
      }
    }
  }

  .dialog-info {
    padding-bottom: 16px;
    border-bottom: solid rgba(255, 255, 255, 0.2) 1px;
  }

  .dialog-messages {
    flex: 1;
    overflow: auto;
    padding: 16px;
  }

  .dialog-controls {
    border-top: solid rgba(255, 255, 255, 0.2) 1px;
    height: 96px;
  }

  @media (max-width: 600px) {
    .dialog-controls {
      height: auto;
    } 
  }
</style>

<style lang="scss">
  .message-area {
    margin-right: 16px;

    .v-text-field__details {
      display: none;
    }

    .v-input__slot {
      margin-bottom: 0;
    }
  }
</style>