<template>
  <div>
    <router-view />

    <app-notifications />
  </div>
</template>

<script>
  import AppNotifications from './components/AppNotifications'

  export default {
    name: 'App',

    components: {
      AppNotifications,
    },
  }
</script>