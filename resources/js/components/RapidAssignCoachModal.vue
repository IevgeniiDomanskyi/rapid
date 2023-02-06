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
        Assign Coach
      </template>
      
      <div
        class="assign-coaches-body"
      >
        <app-text
          v-if=" ! coaches.length"
        >
          There are no coaches in current county
        </app-text>

        <v-list
          v-else
          rounded
        >
          <v-list-item-group
            v-model="selected"
            :color="$colors.primary"
          >
            <v-list-item
              v-for="(item, index) in coaches"
              :key="index"
              :value="item.id"
            >
              <template v-slot:default="{ active, toggle }">
                <v-list-item-action>
                  <v-checkbox
                    :input-value="active"
                    :value="active"
                    :color="$colors.primary"
                    @click="toggle"
                  ></v-checkbox>
                </v-list-item-action>

                <v-list-item-content>
                  <v-list-item-title>{{ item.firstname }} {{ item.lastname }}</v-list-item-title>
                  <v-list-item-subtitle>{{ item.email }}</v-list-item-subtitle>
                </v-list-item-content>
              </template>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </div>

      <template v-slot:actions>
        <app-input-text
          v-model="feeVal"
          label="Fee"
          class="fee-text"
        />

        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />

        <app-button
          :loading="isLoading"
          label="Assign Selected"
          color="primary"
          @click="onAssign"
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
  import AppInputText from './AppInputText.vue'

  export default {
    name: 'RapidAssignCoachModal',

    components: {
      AppCard,
      AppText,
      AppButton,
      AppInputText,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      book: {
        type: Number,
        default: 0,
      },

      fee: {
        type: [String, Number],
        default: '',
      },

      coach: {
        type: Object,
        default: () => {},
      },
    },

    data: () => ({
      selected: null,

      isLoading: false,

      feeVal: '',
    }),

    computed: {
      ...mapState({
        coaches: state => state.coaches.list,
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

    watch: {
      fee(val) {
        this.feeVal = val
      },

      coach(val) {
        if (val) {
          this.selected = val.id
        }
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'bookAssign',
      ]),

      onAssign() {
        if (this.selected != null) {
          this.isLoading = true

          this.bookAssign({
            book: this.book,
            coach: this.selected,
            fee: this.feeVal,
          }).then(data => {
            this.isLoading = false
            if (data) {
              this.onClose()
            }
          })
        } else {
          this.messagesSet({
            type: 'error',
            text: 'Please, select one of the coaches',
          })
        }
      },

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

  .fee-text {
    width: 70px;
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