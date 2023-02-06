<template>
  <v-navigation-drawer
    v-model="sidebar"
    :color="$colors.sidebar"
    :width="250"
    dark
    app
  >
    <div class="py-4 px-8 mb-3">
      <app-logo />
    </div>

    <v-list
      v-if="isCustomer"
      dense
    >
      <rapid-sidebar-item
        icon="mdi-account"
        href="/panel"
        label="My Personal Details"
      />

      <rapid-sidebar-item
        icon="mdi-calendar-month"
        href="/panel/bookings-customer"
        label="My Bookings"
      />

      <rapid-sidebar-item
        icon="mdi-email-open"
        href="/panel/enquiries-customer"
        label="My Enquiries"
      />

      <rapid-sidebar-item
        icon="mdi-ticket-confirmation-outline"
        href="/panel/events-customer"
        label="My Events"
      />

      <rapid-sidebar-item
        icon="mdi-credit-card-clock-outline"
        href="/panel/payment"
        label="My Payment Details"
      />

      <rapid-sidebar-item
        icon="mdi-text-box-multiple-outline"
        href="/panel/documents"
        label="My Digital Documents"
      />
    </v-list>

    <v-list
      v-if="isCoach"
      dense
    >
      <!-- <rapid-sidebar-item
        icon="mdi-view-dashboard"
        href="/panel"
        label="Dashboard"
      /> -->

      <rapid-sidebar-item
        icon="mdi-calendar-month"
        href="/panel"
        label="Bookings"
      />

      <!-- <rapid-sidebar-item
        icon="mdi-email-open"
        href="/panel/coach/enquiries"
        label="Enquiries"
      /> -->

      <rapid-sidebar-item
        icon="mdi-account-multiple"
        href="/panel/coach/customers"
        label="Customers"
      />

      <rapid-sidebar-item
        icon="mdi-bulletin-board"
        href="/panel/coach/coaches"
        label="My Diary"
      />

      <!-- <rapid-sidebar-item
        icon="mdi-lightbulb"
        href="/panel/coach/tracks"
        label="Tracks"
      /> -->

      <!-- <rapid-sidebar-item
        icon="mdi-cogs"
        href="/panel/coach/settings"
        label="Settings"
      /> -->

      <!-- <rapid-sidebar-item
        icon="mdi-chart-bar"
        href="/panel/coach/reports"
        label="Report/Certificates"
      /> -->
    </v-list>

    <v-list
      v-if="isAdmin"
      dense
    >
      <rapid-sidebar-item
        icon="mdi-view-dashboard"
        href="/panel"
        label="Dashboard"
      />

      <rapid-sidebar-item
        icon="mdi-calendar-month"
        href="/panel/bookings"
        label="Bookings"
      />

      <rapid-sidebar-item
        icon="mdi-email-open"
        href="/panel/enquiries"
        label="Enquiries"
      />

      <rapid-sidebar-item
        icon="mdi-account-multiple"
        href="/panel/customers"
        label="Customers"
      />

      <rapid-sidebar-item
        icon="mdi-bulletin-board"
        href="/panel/coaches"
        label="Coaches"
      />

      <rapid-sidebar-item
        icon="mdi-lightbulb"
        href="/panel/tracks"
        label="Tracks"
      />

      <!-- <rapid-sidebar-item
        icon="mdi-cogs"
        href="/panel/settings"
        label="Settings"
      /> -->

      <rapid-sidebar-item
        icon="mdi-chart-bar"
        href="/panel/reports/certificates"
        label="Reports/Certificates"
      />

      <v-list-group
        group="/panel/vouchers"
        prepend-icon="mdi-currency-gbp"
        class="sidebar-item-sub"
      >
        <template v-slot:activator>
          <v-list-item-title>Vouchers</v-list-item-title>
        </template>

        <rapid-sidebar-item
          href="/panel/vouchers"
          label="Vouchers"
        />

        <rapid-sidebar-item
          href="/panel/reports/vouchers"
          label="Voucher report"
        />
      </v-list-group>

      <v-list-group
        group="/panel/events"
        prepend-icon="mdi-ticket-confirmation-outline"
        class="sidebar-item-sub"
      >
        <template v-slot:activator>
          <v-list-item-title>Events/Ride Outs</v-list-item-title>
        </template>

        <rapid-sidebar-item
          href="/panel/events"
          label="List"
        />

        <rapid-sidebar-item
          href="/panel/events/bookings"
          label="Bookings"
        />

        <rapid-sidebar-item
          href="/panel/events/enquiries"
          label="Enquiries"
        />
      </v-list-group>

      <v-list-group
        group="/panel/track-days"
        prepend-icon="mdi-calendar-today"
        class="sidebar-item-sub"
      >
        <template v-slot:activator>
          <v-list-item-title>Track Days</v-list-item-title>
        </template>

        <rapid-sidebar-item
          href="/panel/track-days"
          label="List"
        />

        <rapid-sidebar-item
          href="/panel/track-days/bookings"
          label="Bookings"
        />

        <rapid-sidebar-item
          href="/panel/track-days/enquiries"
          label="Enquiries"
        />
      </v-list-group>

      <!-- <rapid-sidebar-item
        icon="mdi-help-circle-outline"
        href="/panel/help"
        label="Help"
      /> -->
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  import AppLogo from './AppLogo'
  import RapidSidebarItem from './RapidSidebarItem'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'RapidSidebar',

    mixins: [Rapid],

    components: {
      AppLogo,
      RapidSidebarItem,
    },

    props: {
      drawer: {
        type: Boolean,
        default: null,
      }
    },

    computed: {
      sidebar: {
        get() {
          return this.drawer
        },
        set(val) {
          this.$emit('change', val)
        },
      }
    },
  }
</script>

<style lang="scss">
  .v-application--is-ltr {
    .sidebar-item-sub {
      .v-list-item__icon:first-child {
        margin-right: 16px;
      }

      .v-list-item {
        .v-list-item__title {
          font-size: 16px;
        }
      }

      &.v-list-group {
        .v-list-group__header {
          .v-list-item__icon.v-list-group__header__append-icon {
            min-width: 30px;
          }
        }
      }

      &.primary--text {
        color: #fff !important;
      }

      .v-list-group__items {
        .v-list-item__title {
          padding-left: 40px;
        }
      }
    }
  }
</style>