<template>
  <v-list-item
    :to="href"
    exact
    link
    class="rapid-sidebar-item"
  >
    <v-list-item-action
      v-if="icon"
    >
      <v-icon>{{ icon }}</v-icon>
    </v-list-item-action>

    <v-list-item-content>
      <v-list-item-title>
        {{ label }}
      </v-list-item-title>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
  export default {
    name: 'RapidSidebarItem',

    props: {
      icon: {
        type: String,
        default: null,
      },

      label: {
        type: String,
        default: '',
      },

      href: {
        type: String,
        default: null,
      },
    },
  }
</script>

<style lang="scss">
  .rapid-sidebar-item {
    &.v-list-item {
      .v-list-item__action {
        margin-right: 16px;
      }

      .v-list-item__title {
        font-size: 16px;
        font-weight: 500;
      }
    }
  }
</style>