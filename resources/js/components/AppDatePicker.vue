<template>
  <v-dialog
    v-model="dialog"
    width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <v-date-picker
      v-model="dateData"
      :color="$colors.primary"
      :min="min"
      :max="max"
      :disabled="disabled"
      full-width
      srollable
    >
      <v-spacer></v-spacer>

      <app-button
        label="Close"
        color="primary"
        outlined
        @click="onClose"
      />

      <app-button
        label="Select"
        color="primary"
        @click="onSelect"
      />
    </v-date-picker>
  </v-dialog>
</template>

<script>
  import AppButton from './AppButton'

  export default {
    name: 'AppDatePicker',

    components: {
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      date: {
        type: String,
        default: null,
      },

      min: {
        type: String,
        default: null,
      },

      max: {
        type: String,
        default: null,
      },

      disabled: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      dateData: {
        get() {
          return this.date
        },

        set(val) {
          this.$emit('update:date', val)
        },
      },
    },

    methods: {
      onClose() {
        this.$emit('update:date', this.date)
        this.$emit('close')
      },

      onSelect() {
        this.$emit('close')
      },
    },
  }
</script>