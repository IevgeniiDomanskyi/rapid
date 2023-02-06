<template>
  <component :is="page" />
</template>

<script>
  import Dashboard from './Dashboard'
  import BookingsCoach from './BookingsCoach'
  import Details from './Details'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'Home',

    mixins: [Rapid],

    components: {
      Dashboard,
      BookingsCoach,
      Details,
    },

    computed: {
      page() {
        if (this.isCustomer) {
          return 'Details'
        }

        if (this.isCoach) {
          return 'BookingsCoach'
        }

        if (this.isAdmin) {
          return 'Dashboard'
        }
      },
    },
  }
</script>