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
        Choose Track Date
      </template>
      
      <div
        class="assign-dates-body"
      >
        <app-text
          v-if=" ! dates.length"
        >
          There are no dates in current county
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
              v-for="(item, index) in dates"
              :value="item.id"
              :key="index"
            >
              <template v-slot:default="{ active }">
                <v-list-item-action>
                  <v-checkbox
                    :input-value="active"
                    :color="$colors.primary"
                  ></v-checkbox>
                </v-list-item-action>

                <v-list-item-content>
                  <v-list-item-title>{{ item.date }} <span v-if="item.course > 0">Bikemaster {{ item.course }}</span></v-list-item-title>
                  <v-list-item-subtitle>{{ item.track.name }}</v-list-item-subtitle>
                </v-list-item-content>
              </template>
            </v-list-item>
          </v-list-item-group>
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

        <app-button
          :loading="isLoading"
          label="Confirm Date"
          color="primary"
          @click="onDefine"
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
    name: 'RapidDefineTrackDateModal',

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

      book: {
        type: Number,
        default: 0,
      },

      trackDate: {
        type: Number,
        default: null,
      },
    },

    data: () => ({
      selected: null,

      isLoading: false,
    }),

    computed: {
      ...mapState({
        dates: state => state.tracks.dates,
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
      trackDate(val) {
        if (this.selected == null) {
          this.selected = val
        }
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'bookTrackDateDefine',
      ]),

      onDefine() {
        if (this.selected != null) {
          this.isLoading = true

          this.bookTrackDateDefine({
            book: this.book,
            date: this.selected,
          }).then(data => {
            this.isLoading = false
            if (data) {
              this.onClose()
            }
          })
        } else {
          this.messagesSet({
            type: 'error',
            text: 'Please, select one of the dates',
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
  .assign-dates-body {
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