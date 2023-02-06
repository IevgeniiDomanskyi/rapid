<template>
  <v-dialog
    v-model="dialog"
    width="500"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Event Customers
      </template>
      
      <div
        class="assign-coaches-body"
      >
        <app-text
          v-if=" ! customers.length"
        >
          There are no customers for this event
        </app-text>

        <v-list
          v-else
          rounded
        >
          <v-list-item
            v-for="(item, index) in customers"
            :key="index"
          >
            <v-list-item-content>
              <v-list-item-title>{{ item.firstname }} {{ item.lastname }}</v-list-item-title>
              <v-list-item-subtitle>{{ item.booked_at }}</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-action>
              <app-button
                :to="'/panel/customers/' + item.id"
                label="View Profile"
                small
                color="blue"
              />
            </v-list-item-action>
          </v-list-item>
        </v-list>
      </div>

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppText from './AppText'
  import AppButton from './AppButton'

  export default {
    name: 'RapidEventCustomersModal',

    components: {
      AppCard,
      AppText,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      ...mapState({
        customers: state => state.events.customers,
      }),

      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    methods: {
      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .assign-coaches-body {
    min-height: 200px;
    max-height: 360px;
    overflow: auto;
    margin-bottom: 15px;
  }
</style>

<style lang="scss">
  .theme--dark.v-list {
    background: transparent;

    &.v-list--rounded {
      padding: 10px 0;
    }
  }
</style>