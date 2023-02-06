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
        {{ title }}
      </template>
      
      <slot />

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Cancel"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          label="Yes"
          color="primary"
          @click="onConfirm"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import AppCard from './AppCard'
  import AppButton from './AppButton'

  export default {
    name: 'AppConfirmModal',

    components: {
      AppCard,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      title: {
        type: String,
        default: '',
      },
    },

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
      onConfirm() {
        this.$emit('confirm')
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>