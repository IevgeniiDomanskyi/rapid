<template>
  <v-app-bar
    :color="$colors.panel"
    app
    dark
    class="rapid-header"
  >
    <v-app-bar-nav-icon
      @click.stop="onSidebarToggle"
    />

    <app-link
      v-if="isAdmin"
      :color="$colors.text"
      href="/panel"
      class="ml-4 d-none d-sm-inline-flex"
    >
      <v-icon
        dense
        class="mr-1"
      >
        mdi-view-dashboard
      </v-icon>

      Dashboard
    </app-link>

    <rapid-book-modal
      v-model="bookModal"
      @close="onBookModalClose"
    >
      <template v-slot:activator="{ on, attrs }">
        <app-link
          v-if="isAdmin"
          :color="$colors.text"
          class="ml-4 d-none d-sm-inline-flex"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            dense
          >
            mdi-plus
          </v-icon>

          Add Booking
        </app-link>
      </template>
    </rapid-book-modal>

    <rapid-event-book-modal
      v-model="eventModal"
      @close="onEventModalClose"
    >
      <template v-slot:activator="{ on, attrs }">
        <app-link
          v-if="isAdmin"
          :color="$colors.text"
          class="ml-4 d-none d-sm-inline-flex"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            dense
          >
            mdi-plus
          </v-icon>

          Book Event
        </app-link>
      </template>
    </rapid-event-book-modal>

    <rapid-track-day-book-modal
      v-model="trackDayModal"
      @close="onTrackDayModalClose"
    >
      <template v-slot:activator="{ on, attrs }">
        <app-link
          v-if="isAdmin"
          :color="$colors.text"
          class="ml-4 d-none d-sm-inline-flex"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            dense
          >
            mdi-plus
          </v-icon>

          Track Day Event
        </app-link>
      </template>
    </rapid-track-day-book-modal>

    <rapid-coach-modal
      v-model="coachModal"
      @close="onCoachModalClose"
    >
      <template v-slot:activator="{ on, attrs }">
        <app-link
          v-if="isAdmin"
          :color="$colors.text"
          class="ml-4 d-none d-sm-inline-flex"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            dense
          >
            mdi-plus
          </v-icon>

          Add Coach
        </app-link>
      </template>
    </rapid-coach-modal>

    <rapid-customer-modal
      v-model="customerModal"
      @close="onCustomerModalClose"
    >
      <template v-slot:activator="{ on, attrs }">
        <app-link
          v-if="isAdmin"
          :color="$colors.text"
          class="ml-4 d-none d-sm-inline-flex"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            dense
          >
            mdi-plus
          </v-icon>

          Add Customer
        </app-link>
      </template>
    </rapid-customer-modal>

    <v-spacer />

    <!-- <v-badge
      :content="3"
      :value="3"
      :color="$colors.badge"
      overlap
    >
      <v-icon dense>mdi-bell</v-icon>
    </v-badge> -->

    <v-menu
      bottom
      left
      offset-y
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          icon
          large
          class="mr-0 ml-4"
          v-bind="attrs"
          v-on="on"
        >
          <app-avatar
            :name="me.firstname + ' ' + me.lastname"
          />
        </v-btn>
      </template>

      <v-list>
        <v-list-item
          :to="'/panel' + (me.role != 0 ? '/details' : '')"
          exact
        >
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>

        <v-list-item
          @click="onLogout"
        >
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script>
  import { mapActions } from 'vuex'
  import AppLink from './AppLink'
  import AppAvatar from './AppAvatar'
  import RapidBookModal from './RapidBookModal'
  import RapidEventBookModal from './RapidEventBookModal'
  import RapidTrackDayBookModal from './RapidTrackDayBookModal'
  import RapidCoachModal from './RapidCoachModal'
  import RapidCustomerModal from './RapidCustomerModal'
  import Rapid from '../mixins/rapid'

  export default {
    name: 'RapidHeader',

    mixins: [Rapid],

    components: {
      AppLink,
      AppAvatar,
      RapidBookModal,
      RapidEventBookModal,
      RapidTrackDayBookModal,
      RapidCoachModal,
      RapidCustomerModal,
    },

    data: () => ({
      bookModal: false,
      eventModal: false,
      trackDayModal: false,
      coachModal: false,
      customerModal: false,
    }),

    methods: {
      ...mapActions([
        'authLogout',
      ]),

      onSidebarToggle() {
        this.$emit('sidebarToggle')
      },

      onLogout() {
        this.authLogout()
      },

      onCoachModalClose() {
        this.coachModal = false
      },

      onBookModalClose() {
        this.bookModal = false
      },

      onEventModalClose() {
        this.eventModal = false
      },

      onTrackDayModalClose() {
        this.trackDayModal = false
      },

      onCustomerModalClose() {
        this.customerModal = false
      },
    },
  }
</script>

<style lang="scss" scoped>
  .rapid-header {
    &.v-sheet.v-app-bar.v-toolbar:not(.v-sheet--outlined) {
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
  }
</style>