<template>
  <v-chip
    :color="color"
    :small="small"
    :dark="dark"
    label
  >
    <slot />
  </v-chip>
</template>

<script>
  export default {
    name: 'AppChip',

    props: {
      color: {
        type: String,
        default: null,
      },
      
      small: {
        type: Boolean,
        default: null,
      },

      dark: {
        type: Boolean,
        default: false,
      },
    },
  }
</script>