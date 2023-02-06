<template>
  <v-dialog
    v-model="modal"
    width="700"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Message History
      </template>
      
      <div
        class="messages-body"
      >
        <app-text
          v-if=" ! dialog.id || ! dialog.messages.length"
        >
          There are no messages for this booking
        </app-text>

        <v-list
          v-else
          rounded
        >
          <div
            v-for="message in lastMessages(dialog.messages)"
            :key="message.id"
            :class="['message-item', { receiver: (message.author.id != me.id && message.type != 1), author: (message.author.id == me.id && message.type != 1), system: message.type == 1 }]"
          >
            <div
              v-if="message.type != 1"
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
              class="message-item__message"
            >
              {{ message.message }}
            </div>

            <div
              v-if="message.type != 1"
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
        </v-list>
      </div>

      <template v-slot:actions>
        <app-button
          :href="'/panel/dialog/' + dialog.id"
          label="See More"
          color="purple"
        />

        <app-button
          label="Add Call"
          color="rose"
          class="ml-2 d-none d-sm-inline-flex"
          @click="onAddCall(dialog.id)"
        />

        <app-button
          label="Add Notes"
          color="grey"
          class="ml-2 d-none d-sm-inline-flex"
          @click="onAddNotes(dialog.id)"
        />
        
        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />
      </template>

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
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppText from './AppText'
  import AppButton from './AppButton'
  import RapidAddCallModal from './RapidAddCallModal'
  import RapidAddNotesModal from './RapidAddNotesModal'

  export default {
    name: 'RapidMessagesModal',

    components: {
      AppCard,
      AppText,
      AppButton,
      RapidAddCallModal,
      RapidAddNotesModal,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      dialog: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      isLoading: false,
      addCallModal: false,
      addNotesModal: false,
    }),

    computed: {
      ...mapState({
        me: state => state.auth.me,
      }),

      modal: {
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
        'dialogsMessageRead',
      ]),

      lastMessages(messages) {
        let list = messages.slice(0, 3)
        for (const message of list) {
          if (message.type == 0 && message.author.id != this.me.id && ! message.receiver_read) {
            this.dialogsMessageRead({ messageId: message.id }).then(data => {
              this.$emit('update')
            })
          }
        }
        return list
      },

      onAddCall(dialogId) {
        this.addCallModal = true
      },

      onAddCallModalClose() {
        this.addCallModal = false
        this.$emit('update')
      },

      onAddNotes(dialogId) {
        this.addNotesModal = true
      },

      onAddNotesModalClose() {
        this.addNotesModal = false
        this.$emit('update')
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .messages-body {
    min-height: 200px;
    max-height: 360px;
    overflow: auto;
    margin-bottom: 15px;
  }

  .message-item {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;

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
</style>