<template>
  <div class="rapid-panel">
    <v-main>
      <v-container
        class="fill-height"
        fluid
      >
        <app-text
          size="lg"
        >
          Terms and Policy
        </app-text>
      </v-container>
    </v-main>
  </div>
</template>

<script>
  import AppText from '../components/AppText'

  export default {
    name: 'Terms',

    components: {
      AppText,
    },
  }
</script>