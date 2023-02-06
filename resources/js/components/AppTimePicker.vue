<template>
  <v-dialog
    v-model="dialog"
    width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <v-time-picker
      v-model="timeData"
      :color="$colors.primary"
      full-width
      format="24hr"
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
    </v-time-picker>
  </v-dialog>
</template>

<script>
  import AppButton from './AppButton'

  export default {
    name: 'AppTimePicker',

    components: {
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      time: {
        type: String,
        default: null,
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

      timeData: {
        get() {
          return this.time
        },

        set(val) {
          this.$emit('update:time', val)
        },
      },
    },

    methods: {
      onClose() {
        this.$emit('update:time', this.time)
        this.$emit('close')
      },

      onSelect() {
        this.$emit('close')
      },
    },
  }
</script>