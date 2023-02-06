<template>
  <v-dialog
    v-model="modal"
    width="500"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Add Call
      </template>
      
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <app-textarea
          v-model="message"
          :rules="validate('Message')"
          label="Message"
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
          label="Save Call"
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
  import AppTextarea from './AppTextarea'
  import AppButton from './AppButton'
  import rules from '../validation/rules'

  export default {
    name: 'RapidAddCallModal',

    components: {
      AppCard,
      AppTextarea,
      AppButton,
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
      valid: true,

      message: '',

      isLoading: false,
    }),

    computed: {
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
        'dialogsAddCall',
      ]),

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'Message': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onSave() {
        if (this.$refs.form.validate()) {
          this.isLoading = true

          this.dialogsAddCall({
            dialogId: this.dialog.id,
            message: this.message,
          }).then(data => {
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