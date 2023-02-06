<template>
  <div class="rapid-panel">
    <rapid-sidebar
      :drawer="drawer"
      @change="onSidebarChange"
    />

    <rapid-header
      @sidebarToggle="onSidebarToggle"
    />

    <v-main>
      <router-view />
    </v-main>
  </div>
</template>

<script>
  import RapidSidebar from '../components/RapidSidebar'
  import RapidHeader from '../components/RapidHeader'

  export default {
    name: 'Panel',

    components: {
      RapidSidebar,
      RapidHeader,
    },

    data: () => ({
      drawer: null,
    }),

    methods: {
      onSidebarChange(val) {
        this.drawer = val
      },

      onSidebarToggle() {
        this.drawer = ! this.drawer
      },
    },
  }
</script>

<style lang="scss">
  
</style>